@extends('layouts.app')

@section('title', 'Beranda - ' . $siteName)

@section('content')
<!-- Hero Section (Cyber Dark Neo-Brutalist) -->
<section class="relative bg-[#1e1b4b] text-white py-20 lg:py-28 border-b-5 border-black overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-4xl mx-auto text-center space-y-6">
            <!-- Badge Tag -->
            <div class="inline-flex items-center gap-2 brutal-badge bg-[#8b5cf6] text-white px-4 py-2 text-xs font-black">
                <span>⚡</span>  • POLITEKNIK NEGERI BALI
            </div>

            <!-- Main Heading -->
            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black uppercase tracking-tight text-white leading-none">
                GALLERY KELAS <br class="hidden sm:inline">
                <span class="bg-[#f59e0b] text-black px-4 py-1 border-4 border-black inline-block shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] mt-2">
                    {{ $siteName }}
                </span>
            </h1>

            <p class="max-w-2xl mx-auto text-base sm:text-lg font-bold text-gray-200 bg-[#0f172a] p-6 border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] leading-relaxed">
                {{ $siteDesc }}
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
                <a href="{{ route('gallery') }}" class="brutal-btn brutal-btn-primary px-8 py-4 text-base">
                    🚀 JELAJAHI GALERI MEDIA
                </a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="brutal-btn brutal-btn-amber px-8 py-4 text-base">
                        ⚡ DASHBOARD ADMIN
                    </a>
                @endauth
            </div>
        </div>

        <!-- Counter Statistics Grid -->
        <div class="mt-16 grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-4xl mx-auto">
            <div class="brutal-card brutal-card-slate p-6 text-center">
                <div class="text-5xl font-black text-[#8b5cf6]">
                    {{ number_format($albumsCount) }}
                </div>
                <div class="text-xs uppercase tracking-wider font-black text-white mt-2">ALBUM DOKUMENTASI</div>
            </div>

            <div class="brutal-card brutal-card-slate p-6 text-center">
                <div class="text-5xl font-black text-[#f59e0b]">
                    {{ number_format($photosCount) }}
                </div>
                <div class="text-xs uppercase tracking-wider font-black text-white mt-2">FOTO KENANGAN</div>
            </div>

            <div class="brutal-card brutal-card-slate p-6 text-center">
                <div class="text-5xl font-black text-[#3b82f6]">
                    {{ number_format($videosCount) }}
                </div>
                <div class="text-xs uppercase tracking-wider font-black text-white mt-2">VIDEO ACTIVITIES</div>
            </div>
        </div>
    </div>
</section>

<!-- Recent Albums Section -->
<section class="py-20 bg-[#0b0f19]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-12 gap-4 pb-6 border-b-4 border-black">
            <div>
                <span class="brutal-badge bg-[#8b5cf6] text-white">KOLEKSI TERBARU</span>
                <h2 class="text-3xl sm:text-4xl font-black uppercase text-white tracking-tight mt-2">ALBUM KEGIATAN</h2>
            </div>
            <a href="{{ route('gallery') }}" class="brutal-btn brutal-btn-slate px-5 py-2.5 text-xs">
                LIHAT SEMUA ALBUM &rarr;
            </a>
        </div>

        @if($recentAlbums->isEmpty())
            <div class="brutal-card p-12 text-center bg-[#111827]">
                <h3 class="text-xl font-black uppercase text-white">Belum Ada Album</h3>
                <p class="text-xs font-bold text-gray-400 mt-2">Lakukan sinkronisasi di Admin Panel untuk memuat album.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($recentAlbums as $album)
                    <a href="{{ route('album.show', $album->slug) }}" class="brutal-card block group overflow-hidden bg-[#111827]">
                        <div class="relative h-60 border-b-4 border-black bg-[#0b0f19] overflow-hidden">
                            <img src="{{ $album->cover_url }}" alt="{{ $album->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            
                            <!-- Counter Badge -->
                            <div class="absolute bottom-3 left-3 brutal-badge bg-[#f59e0b] text-black text-xs font-black">
                                {{ $album->photos_count }} FOTO • {{ $album->videos_count }} VIDEO
                            </div>
                        </div>

                        <div class="p-6 bg-[#111827]">
                            <h3 class="font-black text-xl text-white uppercase group-hover:text-[#8b5cf6] transition-colors line-clamp-1">
                                {{ $album->name }}
                            </h3>
                            <p class="text-xs font-bold text-gray-400 mt-2 line-clamp-2 leading-relaxed">
                                {{ $album->description ?: 'Dokumentasi foto dan video kegiatan kelas.' }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- Recent Photos Highlights Grid -->
<section class="py-20 bg-[#111827] border-t-5 border-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-12 gap-4 pb-6 border-b-4 border-black">
            <div>
                <span class="brutal-badge bg-[#f59e0b] text-black">SOROTAN TERKINI</span>
                <h2 class="text-3xl sm:text-4xl font-black uppercase text-white tracking-tight mt-2">MEDIA TERBARU</h2>
            </div>
            <a href="{{ route('gallery') }}" class="brutal-btn brutal-btn-primary px-5 py-2.5 text-xs">
                BUKA GALERI LENGKAP &rarr;
            </a>
        </div>

        @if($recentMedia->isEmpty())
            <div class="brutal-card p-12 text-center bg-[#111827] font-black text-white">
                Belum ada media tersimpan.
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach($recentMedia as $index => $item)
                    <div onclick='openLightbox({{ json_encode($recentMedia->map(fn($m) => [
                        "id" => $m->id,
                        "name" => $m->name,
                        "type" => $m->type,
                        "mime_type" => $m->mime_type,
                        "thumbnail_url" => route("media.thumbnail", $m->id),
                        "drive_url" => route("media.stream", $m->id),
                        "google_drive_id" => $m->google_drive_id,
                        "album_name" => $m->album->name ?? "Umum"
                    ])) }}, {{ $index }})'
                    class="brutal-card relative h-56 sm:h-64 cursor-pointer overflow-hidden group bg-black">
                        
                        <img src="{{ route('media.thumbnail', $item->id) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 opacity-90 group-hover:opacity-100">



                        <!-- Type Indicator Badge -->
                        <div class="absolute top-3 left-3 z-10">
                            @if($item->type === 'video')
                                <span class="brutal-badge bg-[#f43f5e] text-white text-[10px]">
                                    🎬 VIDEO
                                </span>
                            @else
                                <span class="brutal-badge bg-[#8b5cf6] text-white text-[10px]">
                                    📷 FOTO
                                </span>
                            @endif
                        </div>

                        @if($item->type === 'video')
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-12 h-12 bg-[#f59e0b] text-black border-3 border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] flex items-center justify-center font-black group-hover:scale-110 transition-transform">
                                    ▶
                                </div>
                            </div>
                        @endif

                        <div class="absolute inset-x-0 bottom-0 bg-[#111827] border-t-3 border-black p-3 text-white">
                            <span class="text-[10px] font-black uppercase text-[#8b5cf6] block truncate">{{ $item->album->name ?? 'Album' }}</span>
                            <h4 class="font-black text-xs uppercase truncate">{{ $item->name }}</h4>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
