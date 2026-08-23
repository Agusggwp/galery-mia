<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AlbumController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Album::withCount('media');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $albums = $query->latest()->paginate(10)->withQueryString();

        return view('admin.albums.index', compact('albums', 'search'));
    }

    public function update(Request $request, Album $album)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_visible' => 'nullable|boolean',
        ]);

        $album->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'is_visible' => $request->has('is_visible'),
        ]);

        return back()->with('success', 'Album berhasil diperbarui!');
    }

    public function toggleVisibility(Album $album)
    {
        $album->update([
            'is_visible' => !$album->is_visible
        ]);

        return back()->with('success', 'Status visibilitas album berhasil diubah!');
    }
}
