<?php

namespace App\Console\Commands;

use App\Models\Album;
use App\Models\Media;
use App\Models\Setting;
use App\Services\GoogleDriveService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SyncGalleryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gallery:sync {--force : Force full resync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronkan album dan media dari Google Drive ke database MySQL';

    /**
     * Execute the console command.
     */
    public function handle(GoogleDriveService $driveService): int
    {
        $this->info('Starting Google Drive synchronization...');
        Setting::set('last_sync_status', 'in_progress');
        Setting::set('last_sync_started_at', now()->toDateTimeString());

        try {
            $driveAlbums = $driveService->getAlbums();
            $this->info(sprintf('Discovered %d albums in Google Drive.', count($driveAlbums)));

            $processedDriveAlbumIds = [];
            $totalMediaAdded = 0;
            $totalMediaUpdated = 0;

            foreach ($driveAlbums as $dAlbum) {
                $processedDriveAlbumIds[] = $dAlbum['google_drive_id'];

                $album = Album::updateOrCreate(
                    ['google_drive_id' => $dAlbum['google_drive_id']],
                    [
                        'name' => $dAlbum['name'],
                        'slug' => Str::slug($dAlbum['name']),
                        'description' => $dAlbum['description'] ?? null,
                        'is_visible' => true,
                    ]
                );

                $driveMediaFiles = $driveService->getMediaFilesInFolder($dAlbum['google_drive_id']);
                $this->info(sprintf('Album "%s": found %d media items.', $album->name, count($driveMediaFiles)));

                $processedDriveMediaIds = [];

                foreach ($driveMediaFiles as $dMedia) {
                    $processedDriveMediaIds[] = $dMedia['google_drive_id'];

                    $existing = Media::where('google_drive_id', $dMedia['google_drive_id'])->first();
                    if ($existing) {
                        $totalMediaUpdated++;
                    } else {
                        $totalMediaAdded++;
                    }

                    Media::updateOrCreate(
                        ['google_drive_id' => $dMedia['google_drive_id']],
                        [
                            'album_id' => $album->id,
                            'name' => $dMedia['name'],
                            'slug' => Str::slug(pathinfo($dMedia['name'], PATHINFO_FILENAME)),
                            'mime_type' => $dMedia['mime_type'],
                            'type' => $dMedia['type'],
                            'thumbnail_url' => $dMedia['thumbnail_url'],
                            'drive_url' => $dMedia['drive_url'],
                            'file_size' => $dMedia['file_size'],
                            'captured_at' => !empty($dMedia['captured_at']) ? CarbonParse($dMedia['captured_at']) : now(),
                            'is_visible' => true,
                        ]
                    );
                }

                // Mark media no longer in Google Drive folder as hidden
                if (!empty($processedDriveMediaIds)) {
                    Media::where('album_id', $album->id)
                        ->whereNotIn('google_drive_id', $processedDriveMediaIds)
                        ->update(['is_visible' => false]);
                }

                // Set cover image if null
                if (!$album->cover_media_id) {
                    $firstImg = Media::where('album_id', $album->id)->where('type', 'image')->first();
                    if ($firstImg) {
                        $album->update(['cover_media_id' => $firstImg->id]);
                    }
                }
            }

            // Mark albums no longer in main Google Drive folder as hidden
            if (!empty($processedDriveAlbumIds)) {
                Album::whereNotIn('google_drive_id', $processedDriveAlbumIds)
                    ->update(['is_visible' => false]);
            }

            $summaryMsg = sprintf(
                'Sync completed successfully! Processed %d albums, %d media added, %d media updated.',
                count($driveAlbums),
                $totalMediaAdded,
                $totalMediaUpdated
            );

            $this->info($summaryMsg);
            Setting::set('last_sync_at', now()->toDateTimeString());
            Setting::set('last_sync_status', 'success');
            Setting::set('last_sync_message', $summaryMsg);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $errorMsg = 'Sync failed: ' . $e->getMessage();
            $this->error($errorMsg);
            Log::error($errorMsg);
            Setting::set('last_sync_status', 'failed');
            Setting::set('last_sync_message', $errorMsg);

            return Command::FAILURE;
        }
    }
}

function CarbonParse($dateStr) {
    try {
        return \Carbon\Carbon::parse($dateStr);
    } catch (\Exception $e) {
        return now();
    }
}
