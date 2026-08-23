<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Media;
use App\Models\Setting;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class GoogleDriveController extends Controller
{
    public function index(GoogleDriveService $driveService)
    {
        $folderId = $driveService->getFolderId() ?? 'Belum diatur';
        $isConfigured = $driveService->isConfigured();

        $lastSyncAt = Setting::get('last_sync_at', 'Belum pernah');
        $lastSyncStatus = Setting::get('last_sync_status', 'idle');
        $lastSyncMessage = Setting::get('last_sync_message', '-');

        $albumsCount = Album::count();
        $mediaCount = Media::count();

        return view('admin.google_drive', compact(
            'folderId',
            'isConfigured',
            'lastSyncAt',
            'lastSyncStatus',
            'lastSyncMessage',
            'albumsCount',
            'mediaCount'
        ));
    }

    public function sync(Request $request)
    {
        try {
            \App\Jobs\SyncGalleryJob::dispatchSync();

            return back()->with('success', 'Proses sinkronisasi Google Drive berhasil dijalankan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memulai sinkronisasi: ' . $e->getMessage());
        }
    }
}
