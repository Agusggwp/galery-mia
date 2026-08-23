@extends('layouts.app')

@section('title', $album->name . ' - Album Gallery')

@section('content')
<!-- Album Header Banner (Cyber Dark Neo-Brutalist) -->
<section class="bg-[#1e1b4b] text-white py-16 border-b-5 border-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('gallery') }}" class="brutal-btn brutal-btn-slate text-xs px-3 py-1.5 mb-4">
            &larr; KEMBALI KE GALERI
        </a>
        <h1 class="text-4xl sm:text-6xl font-black uppercase tracking-tight text-white">{{ $album->name }}</h1>
        <p class="text-white font-bold mt-3 text-base sm:text-lg max-w-3xl bg-[#0f172a] p-4 border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
            {{ $album->description ?: 'Dokumentasi foto dan video album.' }}
        </p>

        <div class="flex items-center gap-3 mt-6">
            <span class="brutal-badge bg-[#8b5cf6] text-white text-xs font-black">
                📷 {{ number_format($photosCount) }} FOTO
            </span>
            <span class="brutal-badge bg-[#f43f5e] text-white text-xs font-black">
                🎬 {{ number_format($videosCount) }} VIDEO
            </span>
        </div>
    </div>
</section>

<!-- Album Media Grid Section -->
<section class="py-12 bg-[#0b0f19] min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter Toolbar -->
        <div class="brutal-card p-4 mb-8 bg-[#111827] flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Media Type Buttons -->
            <div class="flex items-center gap-2">
                <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="brutal-btn text-xs px-4 py-2 {{ empty($type) ? 'brutal-btn-primary' : 'brutal-btn-slate' }}">
                    SEMUA ({{ $photosCount + $videosCount }})
                </a>
                <a href="{{ request()->fullUrlWithQuery(['type' => 'image']) }}" class="brutal-btn text-xs px-4 py-2 {{ $type === 'image' ? 'brutal-btn-primary' : 'brutal-btn-slate' }}">
                    FOTO ({{ $photosCount }})
                </a>
                <a href="{{ request()->fullUrlWithQuery(['type' => 'video']) }}" class="brutal-btn text-xs px-4 py-2 {{ $type === 'video' ? 'brutal-btn-crimson' : 'brutal-btn-slate' }}">
                    VIDEO ({{ $videosCount }})
                </a>
            </div>

            <!-- Search inside album -->
            <form action="{{ route('album.show', $album->slug) }}" method="GET" class="w-full sm:w-72">
                @if($type)<input type="hidden" name="type" value="{{ $type }}">@endif
                <input type="text" name="search" value="{{ $search }}" placeholder="CARI DALAM ALBUM..." class="brutal-input w-full px-3 py-2 text-xs uppercase font-black bg-[#0b0f19] text-white">
            </form>
        </div>

        <!-- Grid Media -->
        @if($mediaList->isEmpty())
            <div class="brutal-card p-16 text-center bg-[#111827]">
                <h3 class="text-xl font-black uppercase text-white">BELUM ADA MEDIA DI ALBUM INI</h3>
                <p class="text-xs font-bold text-gray-400 mt-1">Lakukan sinkronisasi di Admin Panel jika Google Drive baru saja diupdate.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-5">
                @foreach($mediaList as $index => $item)
                    <div onclick='openLightbox({{ json_encode($mediaList->map(fn($m) => [
                        "id" => $m->id,
                        "name" => $m->name,
                        "type" => $m->type,
                        "mime_type" => $m->mime_type,
                        "thumbnail_url" => route("media.thumbnail", $m->id),
                        "drive_url" => route("media.stream", $m->id),
                        "google_drive_id" => $m->google_drive_id,
                        "album_name" => $album->name
                    ])) }}, {{ $index }})'
                    class="brutal-card relative h-56 sm:h-64 cursor-pointer overflow-hidden group bg-black">
                        
                        <img src="{{ route('media.thumbnail', $item->id) }}" alt="{{ $item->name }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 opacity-90 group-hover:opacity-100">



                        @if($item->type === 'video')
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-12 h-12 bg-[#f59e0b] text-black border-3 border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] flex items-center justify-center font-black group-hover:scale-110 transition-transform">
                                    ▶
                                </div>
                            </div>
                        @endif

                        <div class="absolute inset-x-0 bottom-0 bg-[#111827] border-t-3 border-black p-3 text-white">
                            <h4 class="font-black text-xs uppercase truncate">{{ $item->name }}</h4>
                            <span class="text-[10px] font-bold text-gray-400 block mt-0.5">{{ $item->formatted_size }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $mediaList->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
