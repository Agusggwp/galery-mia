<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Media;
use App\Models\Setting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $albumsCount = Album::count();
        $photosCount = Media::where('type', 'image')->count();
        $videosCount = Media::where('type', 'video')->count();

        $recentMedia = Media::with('album')->latest()->take(6)->get();

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
