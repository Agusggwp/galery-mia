@extends('layouts.app')

@section('title', 'Galeri Foto & Video - ' . $siteName)

@section('content')
<!-- Header Banner (Cyber Dark Neo-Brutalist) -->
<section class="bg-[#1e1b4b] text-white py-16 border-b-5 border-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="brutal-badge bg-[#8b5cf6] text-white mb-3">ARCHIVE CATALOG</span>
        <h1 class="text-4xl sm:text-6xl font-black uppercase tracking-tight text-white">GALERI MEDIA KELAS</h1>
        <p class="text-white font-bold mt-2 text-base sm:text-lg max-w-2xl bg-[#0f172a] p-4 border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
            Cari dan jelajahi kearsipan foto serta video momen terbaik kelas dari Google Drive.
        </p>
    </div>
</section>

<!-- Filter & Gallery Container -->
<section class="py-12 bg-[#0b0f19] min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter Toolbar -->
        <div class="brutal-card p-6 mb-10 bg-[#111827] space-y-6">
            <form action="{{ route('gallery') }}" method="GET" class="space-y-4">
                
                <!-- Search & Dropdowns Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search Input -->
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" placeholder="CARI NAMA MEDIA..." class="brutal-input w-full px-4 py-3 text-xs uppercase font-black bg-[#0b0f19] text-white">
                    </div>

                    <!-- Album Filter -->
                    <div>
                        <select name="album" onchange="this.form.submit()" class="brutal-input w-full py-3 px-4 text-xs font-black uppercase bg-[#0b0f19] text-white">
                            <option value="">-- SEMUA ALBUM --</option>
                            @foreach($albums as $alb)
                                <option value="{{ $alb->slug }}" {{ $albumSlug == $alb->slug ? 'selected' : '' }}>
                                    {{ strtoupper($alb->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year Filter -->
                    <div>
                        <select name="year" onchange="this.form.submit()" class="brutal-input w-full py-3 px-4 text-xs font-black uppercase bg-[#0b0f19] text-white">
                            <option value="">-- SEMUA TAHUN --</option>
                            @foreach($years as $yr)
                                <option value="{{ $yr }}" {{ $year == $yr ? 'selected' : '' }}>
                                    TAHUN {{ $yr }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        <button type="submit" class="brutal-btn brutal-btn-primary flex-1 py-3 text-xs">
                            🔍 FILTER
                        </button>
                        @if($search || $albumSlug || $type || $year)
                            <a href="{{ route('gallery') }}" class="brutal-btn brutal-btn-amber py-3 px-4 text-xs">
                                RESET
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Media Type Tabs -->
                <div class="flex flex-wrap items-center gap-2 pt-4 border-t-3 border-black">
                    <span class="text-xs font-black uppercase tracking-wider text-gray-300 mr-2">Tipe Media:</span>
                    <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="brutal-btn text-xs px-4 py-2 {{ empty($type) ? 'brutal-btn-primary' : 'brutal-btn-slate' }}">
                        SEMUA MEDIA
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['type' => 'image']) }}" class="brutal-btn text-xs px-4 py-2 {{ $type === 'image' ? 'brutal-btn-primary' : 'brutal-btn-slate' }}">
                        📷 TOMBOL FOTO
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['type' => 'video']) }}" class="brutal-btn text-xs px-4 py-2 {{ $type === 'video' ? 'brutal-btn-crimson' : 'brutal-btn-slate' }}">
                        🎬 TOMBOL VIDEO
                    </a>
                </div>
            </form>
        </div>

        <!-- Media Grid -->
        @if($mediaList->isEmpty())
            <div class="brutal-card p-16 text-center bg-[#111827]">
                <h3 class="text-2xl font-black uppercase text-white">TIDAK ADA MEDIA DITEMUKAN</h3>
                <p class="text-xs font-bold text-gray-400 mt-2">Coba sesuaikan kata kunci pencarian atau filter yang Anda pilih.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-5">
                @foreach($mediaList as $index => $item)
                    <div onclick='openLightbox({{ json_encode($mediaList->map(fn($m) => [
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
                        
                        <img src="{{ route('media.thumbnail', $item->id) }}" alt="{{ $item->name }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 opacity-90 group-hover:opacity-100">



                        <!-- Badge -->
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

            <!-- Pagination -->
            <div class="mt-12">
                {{ $mediaList->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
