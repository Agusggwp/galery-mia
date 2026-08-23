@extends('layouts.admin')

@section('title', 'Kelola Album - Admin')
@section('header_title', 'Kelola Album Documentation')

@section('content')
<div class="brutal-card bg-[#111827] p-6 sm:p-8 space-y-6">
    
    <!-- Top Toolbar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pb-4 border-b-3 border-black">
        <form action="{{ route('admin.albums.index') }}" method="GET" class="w-full sm:w-80">
            <input type="text" name="search" value="{{ $search }}" placeholder="CARI NAMA ALBUM..." class="brutal-input w-full px-3 py-2 text-xs uppercase font-black bg-[#0b0f19] text-white">
        </form>

        <span class="brutal-badge bg-[#8b5cf6] text-white text-xs">TOTAL ALBUM: {{ $albums->total() }}</span>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
        <table class="w-full text-left text-xs font-bold text-white">
            <thead class="bg-[#6d28d9] uppercase text-[11px] font-black text-white border-b-3 border-black">
                <tr>
                    <th class="py-4 px-4">Album</th>
                    <th class="py-4 px-4">Google Drive Folder ID</th>
                    <th class="py-4 px-4">Jumlah Media</th>
                    <th class="py-4 px-4">Status Visibilitas</th>
                    <th class="py-4 px-4 text-right">Aksi Edit</th>
                </tr>
            </thead>
            <tbody class="divide-y-3 divide-black bg-[#111827]">
                @forelse($albums as $album)
                    <tr class="hover:bg-[#1f2937] transition-colors">
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $album->cover_url }}" alt="{{ $album->name }}" class="w-12 h-12 border-2 border-black object-cover bg-gray-900">
                                <div>
                                    <h4 class="font-black text-sm text-white uppercase">{{ $album->name }}</h4>
                                    <p class="text-xs text-gray-400 line-clamp-1 max-w-xs font-medium">{{ $album->description ?: 'Tidak ada deskripsi' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4 font-mono text-xs font-black text-[#f59e0b]">
                            {{ $album->google_drive_id ?: 'MANUAL' }}
                        </td>
                        <td class="py-4 px-4 font-black">
                            {{ $album->media_count }} FILE
                        </td>
                        <td class="py-4 px-4">
                            <form action="{{ route('admin.albums.toggle', $album->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="brutal-btn text-[10px] px-3 py-1 {{ $album->is_visible ? 'brutal-btn-primary' : 'brutal-btn-crimson' }}">
                                    {{ $album->is_visible ? '✓ TAMPIL' : '✕ SEMBUNYI' }}
                                </button>
                            </form>
                        </td>
                        <td class="py-4 px-4 text-right">
                            <details class="group relative inline-block text-left">
                                <summary class="brutal-btn brutal-btn-slate px-3 py-1 text-[10px]">
                                    EDIT ALBUM
                                </summary>
                                <div class="absolute right-0 mt-2 w-80 bg-[#1e1b4b] border-4 border-black p-5 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] z-50 text-left">
                                    <h4 class="font-black text-sm uppercase text-white mb-3">EDIT DETAIL ALBUM</h4>
                                    <form action="{{ route('admin.albums.update', $album->id) }}" method="POST" class="space-y-3">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="block text-[10px] font-black uppercase text-white mb-1">NAMA ALBUM</label>
                                            <input type="text" name="name" value="{{ $album->name }}" required class="brutal-input w-full px-3 py-2 text-xs uppercase font-black bg-[#0b0f19] text-white">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black uppercase text-white mb-1">DESKRIPSI</label>
                                            <textarea name="description" rows="3" class="brutal-input w-full px-3 py-2 text-xs font-bold bg-[#0b0f19] text-white">{{ $album->description }}</textarea>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" name="is_visible" value="1" id="vis_{{ $album->id }}" {{ $album->is_visible ? 'checked' : '' }} class="w-4 h-4 border-2 border-black text-[#8b5cf6]">
                                            <label for="vis_{{ $album->id }}" class="text-xs font-black uppercase text-white">TAMPILKAN KE PUBLIK</label>
                                        </div>
                                        <button type="submit" class="brutal-btn brutal-btn-amber w-full py-2 text-xs">
                                            SIMPAN PERUBAHAN
                                        </button>
                                    </form>
                                </div>
                            </details>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center font-black text-gray-400">BELUM ADA ALBUM TERSIMPAN.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        {{ $albums->links() }}
    </div>
</div>
@endsection
