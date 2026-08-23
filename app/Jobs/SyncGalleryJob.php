<?php

namespace App\Jobs;

use App\Services\GoogleDriveService;
use App\Models\Album;
use App\Models\Media;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncGalleryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600; // 10 minutes max execution time

    public function handle(GoogleDriveService $driveService): void
    {
        Log::info('[SyncGalleryJob] Starting background Google Drive sync...');
        
        Setting::set('last_sync_status', 'in_progress');
        Setting::set('last_sync_message', 'Sinkronisasi latar belakang (background queue) sedang berjalan...');

        try {
            $driveAlbums = $driveService->getAlbums();
            if (empty($driveAlbums)) {
                Setting::set('last_sync_status', 'warning');
                Setting::set('last_sync_message', 'Tidak ada album atau folder ditemukan di Google Drive.');
                return;
            }

            $addedMedia = 0;
            $updatedMedia = 0;

            foreach ($driveAlbums as $albumData) {
                $album = Album::updateOrCreate(
                    ['google_drive_id' => $albumData['google_drive_id']],
                    [
                        'name' => $albumData['name'],
                        'slug' => Album::generateUniqueSlug($albumData['name'], $albumData['google_drive_id']),
                        'description' => $albumData['description'] ?? null,
                        'is_visible' => true,
                    ]
                );

                $driveFiles = $driveService->getMediaFilesInFolder($albumData['google_drive_id']);

                foreach ($driveFiles as $fileData) {
                    $media = Media::where('google_drive_id', $fileData['google_drive_id'])->first();

                    if ($media) {
                        $media->update([
                            'album_id' => $album->id,
                            'name' => $fileData['name'],
                            'mime_type' => $fileData['mime_type'],
                            'type' => $fileData['type'],
                            'thumbnail_url' => $fileData['thumbnail_url'],
                            'drive_url' => $fileData['drive_url'],
                            'file_size' => $fileData['file_size'],
                        ]);
                        $updatedMedia++;
                    } else {
                        Media::create([
                            'album_id' => $album->id,
                            'google_drive_id' => $fileData['google_drive_id'],
                            'name' => $fileData['name'],
                            'mime_type' => $fileData['mime_type'],
                            'type' => $fileData['type'],
                            'thumbnail_url' => $fileData['thumbnail_url'],
                            'drive_url' => $fileData['drive_url'],
                            'file_size' => $fileData['file_size'],
                            'is_visible' => true,
                            'captured_at' => $fileData['captured_at'] ?? now(),
                        ]);
                        $addedMedia++;
                    }
                }
            }

            // Clear cache after sync
            \Illuminate\Support\Facades\Cache::forget('home_stats');
            \Illuminate\Support\Facades\Cache::forget('admin_stats');

            $msg = sprintf('Sinkronisasi sukses! Diproses %d album, %d media baru, %d media diperbarui.', count($driveAlbums), $addedMedia, $updatedMedia);
            Setting::set('last_sync_at', now()->toDateTimeString());
            Setting::set('last_sync_status', 'success');
            Setting::set('last_sync_message', $msg);

            Log::info('[SyncGalleryJob] ' . $msg);
        } catch (\Exception $e) {
            Setting::set('last_sync_status', 'error');
            Setting::set('last_sync_message', 'Gagal: ' . $e->getMessage());
            Log::error('[SyncGalleryJob] Error: ' . $e->getMessage());
        }
    }
}
