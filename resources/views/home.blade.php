@extends('layouts.app')

@section('title', 'Beranda - ' . $siteName)

@section('content')
<!-- Hero Section (Cyber Dark Neo-Brutalist Animated) -->
<section class="relative bg-[#1e1b4b] text-white py-20 lg:py-28 border-b-5 border-black overflow-hidden select-none">
    
    <!-- Cyber Grid Background Overlay -->
    <div class="absolute inset-0 cyber-grid-bg opacity-30 pointer-events-none"></div>

    <!-- Ambient Glow Orbs -->
    <div class="absolute -top-20 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-[#8b5cf6]/20 rounded-full blur-3xl pointer-events-none animate-pulse"></div>
    <div class="absolute -bottom-20 right-10 w-72 h-72 bg-[#f59e0b]/15 rounded-full blur-2xl pointer-events-none animate-float-slow"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-4xl mx-auto text-center space-y-6">
            <!-- Badge Tag with Bounce Icon -->
            <div class="inline-flex items-center gap-2 brutal-badge bg-[#8b5cf6] text-white px-4 py-2 text-xs font-black animate-hero-pop hover:scale-105 transition-transform duration-200">
                <span class="animate-bounce inline-block text-yellow-300">⚡</span> • POLITEKNIK NEGERI BALI
            </div>

            <!-- Main Heading with Typewriter Animation & Floating Box -->
            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black uppercase tracking-tight text-white leading-tight animate-hero-pop">
                <span id="typewriter-text" class="typewriter-cursor pr-2 inline-block text-white">GALLERY KELAS</span> <br class="hidden sm:inline">
                <span class="bg-[#f59e0b] text-black px-4 py-2 border-4 border-black inline-block shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:shadow-[10px_10px_0px_0px_rgba(139,92,246,1)] mt-3 animate-float-slow hover:scale-105 hover:-rotate-1 transition-all duration-300 cursor-pointer">
                    {{ $siteName }}
                </span>
            </h1>

            <!-- Animated Description Box -->
            <p class="max-w-2xl mx-auto text-base sm:text-lg font-bold text-gray-200 bg-[#0f172a] p-6 border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:border-[#8b5cf6] hover:shadow-[8px_8px_0px_0px_rgba(139,92,246,1)] transition-all duration-300 leading-relaxed animate-hero-pop">
                {{ $siteDesc }}
            </p>

            <!-- Animated Action Button -->
            <div class="flex flex-wrap items-center justify-center gap-4 pt-4 animate-hero-pop">
                <a href="{{ route('gallery') }}" class="brutal-btn brutal-btn-primary px-8 py-4 text-base group hover:scale-105 active:scale-95 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                    <span class="animate-rocket group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform mr-2">🚀</span> JELAJAHI GALERI MEDIA
                </a>
            </div>

        <!-- Random Alternating Class Members Highlight Section -->
        @if(isset($randomMembers) && $randomMembers->isNotEmpty())
            <div class="mt-16 max-w-5xl mx-auto space-y-6">
                <!-- Section Header -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pb-4 border-b-3 border-black">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 bg-[#f59e0b] rounded-full animate-ping"></span>
                        <span class="brutal-badge bg-[#8b5cf6] text-white text-xs font-black">
                            👥 ANGGOTA KELAS (ACAK & BERGANTIAN)
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <button id="member-prev-btn" class="brutal-btn brutal-btn-slate px-3 py-1.5 text-xs font-black">
                            &larr; PREV
                        </button>
                        <button id="member-next-btn" class="brutal-btn brutal-btn-amber px-3 py-1.5 text-xs font-black">
                            NEXT &rarr;
                        </button>
                        <a href="{{ route('members.index') }}" class="brutal-btn brutal-btn-primary px-4 py-1.5 text-xs font-black">
                            SEMUA ANGGOTA &rarr;
                        </a>
                    </div>
                </div>

                <!-- Member Cards Slider Container -->
                <div class="relative overflow-hidden p-2">
                    <div id="member-slider" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 items-stretch transition-all duration-500 transform">
                        @foreach($randomMembers as $member)
                            <div class="member-slide-item h-full">
                                <x-member-card :member="$member" />
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const slider = document.getElementById('member-slider');
                    const items = document.querySelectorAll('.member-slide-item');
                    if (!slider || items.length === 0) return;

                    let currentIndex = 0;
                    
                    function getItemsPerPage() {
                        if (window.innerWidth < 640) return 1;
                        if (window.innerWidth < 768) return 2;
                        return 4;
                    }

                    function updateSlider() {
                        const itemsPerPage = getItemsPerPage();
                        const totalPages = Math.ceil(items.length / itemsPerPage);
                        if (currentIndex >= totalPages) currentIndex = 0;

                        items.forEach((item, idx) => {
                            if (idx >= currentIndex * itemsPerPage && idx < (currentIndex + 1) * itemsPerPage) {
                                item.style.display = 'block';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    }

                    document.getElementById('member-next-btn')?.addEventListener('click', function() {
                        const totalPages = Math.ceil(items.length / getItemsPerPage());
                        currentIndex = (currentIndex + 1) % totalPages;
                        updateSlider();
                    });

                    document.getElementById('member-prev-btn')?.addEventListener('click', function() {
                        const totalPages = Math.ceil(items.length / getItemsPerPage());
                        currentIndex = (currentIndex - 1 + totalPages) % totalPages;
                        updateSlider();
                    });

                    // Auto rotate every 4 seconds
                    setInterval(function() {
                        const totalPages = Math.ceil(items.length / getItemsPerPage());
                        if (totalPages > 1) {
                            currentIndex = (currentIndex + 1) % totalPages;
                            updateSlider();
                        }
                    }, 4000);

                    updateSlider();
                    window.addEventListener('resize', updateSlider);
                });
            </script>
        @endif
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
                    class="brutal-card relative h-52 sm:h-64 cursor-pointer overflow-hidden group bg-black active:scale-98 transition-all">
                        
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const target = document.getElementById('typewriter-text');
        if (!target) return;

        const phrases = ["GALLERY KELAS", "DOKUMENTASI KELAS", "MEMORI KELAS", "ARSIP KELAS"];
        let phraseIdx = 0;
        let charIdx = phrases[0].length;
        let isDeleting = false;
        let speed = 100;

        function typeLoop() {
            const current = phrases[phraseIdx];

            if (isDeleting) {
                target.innerText = current.substring(0, charIdx - 1);
                charIdx--;
                speed = 45;
            } else {
                target.innerText = current.substring(0, charIdx + 1);
                charIdx++;
                speed = 90;
            }

            if (!isDeleting && charIdx === current.length) {
                speed = 2400; // Pause when word is completely typed out
                isDeleting = true;
            } else if (isDeleting && charIdx === 0) {
                isDeleting = false;
                phraseIdx = (phraseIdx + 1) % phrases.length;
                speed = 300;
            }

            setTimeout(typeLoop, speed);
        }

        // Wait initial 2.5 seconds before starting typing cycle
        setTimeout(() => {
            isDeleting = true;
            typeLoop();
        }, 2500);
    });
</script>
@endsection

