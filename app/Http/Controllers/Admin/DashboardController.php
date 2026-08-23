<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Media;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('admin_stats', 300, function () {
            return [
                'albumsCount' => Album::count(),
                'photosCount' => Media::where('type', 'image')->count(),
                'videosCount' => Media::where('type', 'video')->count(),
            ];
        });

        $albumsCount = $stats['albumsCount'];
        $photosCount = $stats['photosCount'];
        $videosCount = $stats['videosCount'];

        $recentMedia = Media::select(['id', 'album_id', 'name', 'mime_type', 'type', 'thumbnail_url', 'drive_url'])
            ->with('album:id,name')
            ->latest()
            ->take(6)
            ->get();

        $lastSyncAt = Setting::get('last_sync_at', 'Belum pernah disinkronkan');
        $lastSyncStatus = Setting::get('last_sync_status', 'idle');
        $lastSyncMessage = Setting::get('last_sync_message', 'Sistem siap');

        return view('admin.dashboard', compact(
            'albumsCount',
            'photosCount',
            'videosCount',
            'recentMedia',
            'lastSyncAt',
            'lastSyncStatus',
            'lastSyncMessage'
        ));
    }
}
