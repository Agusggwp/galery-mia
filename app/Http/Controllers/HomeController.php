<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Media;
use App\Models\Member;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $siteName = Setting::get('site_name', 'D3 Manajemen Informatika PNB');
        $siteDesc = Setting::get('site_description', 'Dokumentasi Resmi Kelas D3 Manajemen Informatika (MI) Politeknik Negeri Bali.');
        $logoUrl = Setting::get('logo_url', null);

        // Cache statistics counts for 15 minutes
        $stats = Cache::remember('home_stats', 900, function () {
            return [
                'albumsCount' => Album::where('is_visible', true)->count(),
                'photosCount' => Media::where('is_visible', true)->where('type', 'image')->count(),
                'videosCount' => Media::where('is_visible', true)->where('type', 'video')->count(),
            ];
        });

        $albumsCount = $stats['albumsCount'];
        $photosCount = $stats['photosCount'];
        $videosCount = $stats['videosCount'];

        $recentAlbums = Album::where('is_visible', true)
            ->withCount(['visibleMedia as photos_count' => fn($q) => $q->where('type', 'image')])
            ->withCount(['visibleMedia as videos_count' => fn($q) => $q->where('type', 'video')])
            ->latest()
            ->take(6)
            ->get();

        $recentMedia = Media::where('is_visible', true)
            ->select(['id', 'album_id', 'google_drive_id', 'name', 'mime_type', 'type', 'thumbnail_url', 'drive_url'])
            ->with('album:id,name,slug')
            ->latest()
            ->take(8)
            ->get();

        // Fetch random approved members for homepage rotating highlight
        $randomMembers = Member::approved()->visible()->inRandomOrder()->take(8)->get();

        return view('home', compact(
            'siteName',
            'siteDesc',
            'logoUrl',
            'albumsCount',
            'photosCount',
            'videosCount',
            'recentAlbums',
            'recentMedia',
            'randomMembers'
        ));
    }
}
