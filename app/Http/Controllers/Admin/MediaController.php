<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $albumId = $request->input('album_id');
        $type = $request->input('type');

        $query = Media::with('album');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($albumId) {
            $query->where('album_id', $albumId);
        }

        if ($type && in_array($type, ['image', 'video'])) {
            $query->where('type', $type);
        }

        $mediaList = $query->latest()->paginate(15)->withQueryString();

        $albums = Album::orderBy('name')->get();

        return view('admin.media.index', compact(
            'mediaList',
            'albums',
            'search',
            'albumId',
            'type'
        ));
    }

    public function toggleVisibility(Media $media)
    {
        $media->update([
            'is_visible' => !$media->is_visible
        ]);

        return back()->with('success', 'Visibilitas media berhasil diperbarui!');
    }
}
