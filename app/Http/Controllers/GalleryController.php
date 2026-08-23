<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Media;
use App\Models\Setting;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $albumSlug = $request->input('album');
        $type = $request->input('type'); // 'image', 'video' or null
        $year = $request->input('year');

        // Limit per_page parameter between 10 and 50 to prevent database overload
        $perPage = min(max((int) $request->input('per_page', 20), 10), 50);

        $query = Media::where('is_visible', true)
            ->whereHas('album', function ($q) {
                $q->where('is_visible', true);
            })
            ->select(['id', 'album_id', 'google_drive_id', 'name', 'mime_type', 'type', 'thumbnail_url', 'drive_url', 'captured_at'])
            ->with('album:id,name,slug');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($albumSlug) {
            $query->whereHas('album', function ($q) use ($albumSlug) {
                $q->where('slug', $albumSlug);
            });
        }

        if ($type && in_array($type, ['image', 'video'])) {
            $query->where('type', $type);
        }

        if ($year) {
            $query->whereYear('captured_at', $year);
        }

        $mediaList = $query->latest('captured_at')->paginate($perPage)->withQueryString();

        $albums = Album::where('is_visible', true)->select(['id', 'name', 'slug'])->orderBy('name')->get();

        // Get available years for filter (driver compatible)
        $driver = config('database.connections.' . config('database.default') . '.driver', 'mysql');
        $yearSql = $driver === 'sqlite' ? "strftime('%Y', captured_at) as year" : "YEAR(captured_at) as year";

        $years = Media::where('is_visible', true)
            ->selectRaw($yearSql)
            ->distinct()
            ->whereNotNull('captured_at')
            ->pluck('year')
            ->filter()
            ->sortDesc();

        $siteName = Setting::get('site_name', 'D3 MI PNB');

        return view('gallery', compact(
            'mediaList',
            'albums',
            'years',
            'search',
            'albumSlug',
            'type',
            'year',
            'siteName'
        ));
    }
}
