<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Media;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $album = Album::where('slug', $slug)
            ->where('is_visible', true)
            ->firstOrFail();

        $type = $request->input('type');
        $search = $request->input('search');

        $query = Media::where('album_id', $album->id)
            ->where('is_visible', true);

        if ($type && in_array($type, ['image', 'video'])) {
            $query->where('type', $type);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $mediaList = $query->latest('captured_at')->paginate(16)->withQueryString();

        $photosCount = Media::where('album_id', $album->id)->where('is_visible', true)->where('type', 'image')->count();
        $videosCount = Media::where('album_id', $album->id)->where('is_visible', true)->where('type', 'video')->count();

        return view('album', compact(
            'album',
            'mediaList',
            'photosCount',
            'videosCount',
            'type',
            'search'
        ));
    }
}
