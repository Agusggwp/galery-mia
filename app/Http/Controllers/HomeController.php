<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Media;
use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $siteName = Setting::get('site_name', 'Gallery Kelas - Web Documentation');
        $siteDesc = Setting::get('site_description', 'Dokumentasi kenangan, foto, dan video kegiatan kelas tersimpan rapi dan aman di Google Drive.');
        $logoUrl = Setting::get('logo_url', null);

        $albumsCount = Album::where('is_visible', true)->count();
        $photosCount = Media::where('is_visible', true)->where('type', 'image')->count();
        $videosCount = Media::where('is_visible', true)->where('type', 'video')->count();

        $recentAlbums = Album::where('is_visible', true)
            ->withCount(['visibleMedia as photos_count' => fn($q) => $q->where('type', 'image')])
            ->withCount(['visibleMedia as videos_count' => fn($q) => $q->where('type', 'video')])
            ->latest()
            ->take(6)
            ->get();

        $recentMedia = Media::where('is_visible', true)
            ->with('album')
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact(
            'siteName',
            'siteDesc',
            'logoUrl',
            'albumsCount',
            'photosCount',
            'videosCount',
            'recentAlbums',
            'recentMedia'
        ));
    }
}
