<header class="bg-[#111827] border-b-4 border-black sticky top-0 z-40 shadow-[0px_4px_0px_0px_rgba(0,0,0,1)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Brand Logo & Title -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                @if(!empty($logoUrl))
                    <img src="{{ $logoUrl }}" alt="Logo" class="w-11 h-11 border-3 border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] object-contain bg-white">
                @else
                    <div class="w-12 h-12 bg-[#8b5cf6] text-white border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] flex items-center justify-center font-black text-2xl group-hover:bg-[#f59e0b] group-hover:text-black transition-all">
                        MI
                    </div>
                @endif
                <div>
                    <span class="text-xl sm:text-2xl font-black uppercase tracking-tight text-white group-hover:text-[#8b5cf6] transition-colors">
                        {{ \App\Models\Setting::get('site_name', 'D3 MI PNB') }}
                    </span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-[#f59e0b] block -mt-1">
                        Politeknik Negeri Bali
                    </span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-4 font-black text-xs uppercase tracking-wider">
                <a href="{{ route('home') }}" class="px-3.5 py-2 border-3 border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] transition-all {{ request()->routeIs('home') ? 'bg-[#8b5cf6] text-white' : 'bg-[#1f2937] text-white hover:bg-[#374151]' }}">
                    BERANDA
                </a>
                <a href="{{ route('gallery') }}" class="px-3.5 py-2 border-3 border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] transition-all {{ request()->routeIs('gallery') ? 'bg-[#8b5cf6] text-white' : 'bg-[#1f2937] text-white hover:bg-[#374151]' }}">
                    GALERI MEDIA
                </a>
                <a href="{{ route('members.index') }}" class="px-3.5 py-2 border-3 border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] transition-all {{ request()->routeIs('members.*') ? 'bg-[#8b5cf6] text-white' : 'bg-[#1f2937] text-white hover:bg-[#374151]' }}">
                    ANGGOTA KELAS
                </a>
            </nav>

            <!-- Mobile Menu Toggle Button -->
            <button id="mobile-menu-btn" class="md:hidden brutal-btn brutal-btn-slate p-2">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    <!-- Mobile Dropdown -->
    <div id="mobile-menu" class="hidden md:hidden border-t-4 border-black bg-[#111827] px-6 py-5 space-y-3 font-black text-xs uppercase text-white">
        <a href="{{ route('home') }}" class="block py-2 hover:text-[#8b5cf6]">BERANDA</a>
        <a href="{{ route('gallery') }}" class="block py-2 hover:text-[#8b5cf6]">GALERI MEDIA</a>
        <a href="{{ route('members.index') }}" class="block py-2 hover:text-[#8b5cf6]">ANGGOTA KELAS</a>
    </div>
</header>

<script>
    document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
        document.getElementById('mobile-menu')?.classList.toggle('hidden');
    });
</script>
