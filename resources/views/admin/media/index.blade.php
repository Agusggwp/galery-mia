@extends('layouts.admin')

@section('title', 'Kelola Media - Admin')
@section('header_title', 'Kelola Foto & Video Media')

@section('content')
<div class="brutal-card bg-[#111827] p-6 sm:p-8 space-y-6">
    
    <!-- Filter Toolbar -->
    <form action="{{ route('admin.media.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        <!-- Search -->
        <div>
            <input type="text" name="search" value="{{ $search }}" placeholder="CARI NAMA MEDIA..." class="brutal-input w-full px-3 py-2 text-xs font-black uppercase bg-[#0b0f19] text-white">
        </div>

        <!-- Album Filter -->
        <div>
            <select name="album_id" onchange="this.form.submit()" class="brutal-input w-full py-2 px-3 text-xs font-black uppercase bg-[#0b0f19] text-white">
                <option value="">-- SEMUA ALBUM --</option>
                @foreach($albums as $alb)
                    <option value="{{ $alb->id }}" {{ $albumId == $alb->id ? 'selected' : '' }}>
                        {{ strtoupper($alb->name) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Type Filter -->
        <div>
            <select name="type" onchange="this.form.submit()" class="brutal-input w-full py-2 px-3 text-xs font-black uppercase bg-[#0b0f19] text-white">
                <option value="">-- SEMUA TIPE --</option>
                <option value="image" {{ $type === 'image' ? 'selected' : '' }}>📷 FOTO (GAMBAR)</option>
                <option value="video" {{ $type === 'video' ? 'selected' : '' }}>🎬 VIDEO</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-2">
            <button type="submit" class="brutal-btn brutal-btn-primary flex-1 py-2 text-xs">
                FILTER
            </button>
            @if($search || $albumId || $type)
                <a href="{{ route('admin.media.index') }}" class="brutal-btn brutal-btn-amber py-2 px-4 text-xs">
                    RESET
                </a>
            @endif
        </div>
    </form>

    <!-- Media Table -->
    <div class="overflow-x-auto border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
        <table class="w-full text-left text-xs font-bold text-white">
            <thead class="bg-[#6d28d9] uppercase text-[11px] font-black text-white border-b-3 border-black">
                <tr>
                    <th class="py-4 px-4">Thumbnail & Nama</th>
                    <th class="py-4 px-4">Album</th>
                    <th class="py-4 px-4">Tipe & Size</th>
                    <th class="py-4 px-4">Google Drive ID</th>
                    <th class="py-4 px-4">Status Visibilitas</th>
                </tr>
            </thead>
            <tbody class="divide-y-3 divide-black bg-[#111827]">
                @forelse($mediaList as $item)
                    <tr class="hover:bg-[#1f2937] transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->name }}" class="w-14 h-14 border-2 border-black object-cover bg-gray-900">
                                <div>
                                    <h4 class="font-black text-xs uppercase text-white line-clamp-1 max-w-xs">{{ $item->name }}</h4>
                                    <span class="text-[10px] font-mono text-gray-400">{{ $item->mime_type }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 font-black uppercase text-[#8b5cf6]">
                            {{ $item->album->name ?? 'Umum' }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="brutal-badge text-[9px] {{ $item->type === 'video' ? 'bg-[#f43f5e] text-white' : 'bg-[#8b5cf6] text-white' }}">
                                {{ strtoupper($item->type) }}
                            </span>
                            <span class="text-[10px] font-black text-gray-400 block mt-1">{{ $item->formatted_size }}</span>
                        </td>
                        <td class="py-3 px-4 font-mono text-[10px] font-black text-[#f59e0b]">
                            {{ $item->google_drive_id }}
                        </td>
                        <td class="py-3 px-4">
                            <form action="{{ route('admin.media.toggle', $item->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="brutal-btn text-[10px] px-3 py-1 {{ $item->is_visible ? 'brutal-btn-primary' : 'brutal-btn-crimson' }}">
                                    {{ $item->is_visible ? '✓ TAMPIL' : '✕ SEMBUNYI' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center font-black text-gray-400">TIDAK ADA MEDIA DITEMUKAN.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        {{ $mediaList->links() }}
    </div>
</div>
@endsection
