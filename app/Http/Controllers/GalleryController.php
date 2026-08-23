<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Media;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $albumSlug = $request->input('album');
        $type = $request->input('type'); // 'image', 'video' or null
        $year = $request->input('year');

        $query = Media::where('is_visible', true)
            ->whereHas('album', function ($q) {
                $q->where('is_visible', true);
            })
            ->with('album');

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

        $mediaList = $query->latest('captured_at')->paginate(16)->withQueryString();

        $albums = Album::where('is_visible', true)->orderBy('name')->get();

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


        $siteName = \App\Models\Setting::get('site_name', 'Gallery Kelas');

        return view('gallery', compact(
            'siteName',
            'mediaList',
            'albums',
            'years',
            'search',
            'albumSlug',
            'type',
            'year'
        ));
    }
}

