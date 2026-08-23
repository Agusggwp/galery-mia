<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Cyber Dark Neo-Brutalist Dashboard</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0b0f19] font-sans antialiased text-white flex min-h-screen border-t-8 border-[#8b5cf6]">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-[#111827] text-white flex-shrink-0 flex flex-col justify-between hidden md:flex min-h-screen border-r-5 border-black shadow-[6px_0px_0px_0px_rgba(0,0,0,1)]">
        <div>
            <!-- Sidebar Header -->
            <div class="h-24 px-6 flex items-center gap-3 border-b-4 border-black bg-[#1e1b4b] text-white">
                <div class="w-11 h-11 bg-[#8b5cf6] text-white border-3 border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] flex items-center justify-center font-black text-xl">
                    MI
                </div>
                <div>
                    <span class="font-black text-lg uppercase tracking-tight block">Admin Panel</span>
                    <span class="text-[10px] font-black uppercase tracking-wider text-[#f59e0b]">D3 MI PNB</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-2 font-black text-xs uppercase tracking-wider">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#8b5cf6] text-white' : 'bg-[#1f2937] text-white hover:bg-[#374151]' }}">
                    <span>📊</span> Dashboard
                </a>

                <a href="{{ route('admin.albums.index') }}" class="flex items-center gap-3 px-4 py-3 border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all {{ request()->routeIs('admin.albums.*') ? 'bg-[#8b5cf6] text-white' : 'bg-[#1f2937] text-white hover:bg-[#374151]' }}">
                    <span>📁</span> Kelola Album
                </a>

                <a href="{{ route('admin.media.index') }}" class="flex items-center gap-3 px-4 py-3 border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all {{ request()->routeIs('admin.media.*') ? 'bg-[#8b5cf6] text-white' : 'bg-[#1f2937] text-white hover:bg-[#374151]' }}">
                    <span>🖼️</span> Kelola Media
                </a>

                <a href="{{ route('admin.members.index') }}" class="flex items-center gap-3 px-4 py-3 border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all {{ request()->routeIs('admin.members.*') ? 'bg-[#8b5cf6] text-white' : 'bg-[#1f2937] text-white hover:bg-[#374151]' }}">
                    <span>👥</span> Anggota Kelas
                </a>

                <a href="{{ route('admin.member-invitations.index') }}" class="flex items-center gap-3 px-4 py-3 border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all {{ request()->routeIs('admin.member-invitations.*') ? 'bg-[#8b5cf6] text-white' : 'bg-[#1f2937] text-white hover:bg-[#374151]' }}">
                    <span>🔗</span> Link Undangan
                </a>

                <a href="{{ route('admin.google-drive') }}" class="flex items-center gap-3 px-4 py-3 border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all {{ request()->routeIs('admin.google-drive') ? 'bg-[#8b5cf6] text-white' : 'bg-[#1f2937] text-white hover:bg-[#374151]' }}">
                    <span>⚡</span> Drive API Sync
                </a>

                <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-3 border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all {{ request()->routeIs('admin.settings') ? 'bg-[#8b5cf6] text-white' : 'bg-[#1f2937] text-white hover:bg-[#374151]' }}">
                    <span>⚙️</span> Pengaturan Web
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t-4 border-black bg-[#0b0f19]">
            <a href="{{ route('home') }}" target="_blank" class="brutal-btn brutal-btn-primary w-full py-2 text-xs">
                Situs Publik &rarr;
            </a>
        </div>
    </aside>

    <!-- Main Admin Content Area -->
    <div class="flex-grow flex flex-col min-h-screen">
        <!-- Admin Top Header Bar -->
        <header class="bg-[#111827] border-b-4 border-black h-20 px-6 flex items-center justify-between shadow-[0px_4px_0px_0px_rgba(0,0,0,1)] sticky top-0 z-30">
            <h2 class="text-xl font-black uppercase text-white">@yield('header_title', 'Dashboard')</h2>

            <div class="flex items-center gap-4">
                <span class="text-xs font-black uppercase text-gray-300 hidden sm:inline">
                    ADMIN: <strong class="bg-[#8b5cf6] text-white px-2 py-0.5 border-2 border-black">{{ auth()->user()->email ?? 'Admin' }}</strong>
                </span>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="brutal-btn brutal-btn-crimson px-3 py-1.5 text-xs">
                        LOGOUT
                    </button>
                </form>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-6 pt-6">
            @if(session('success'))
                <div class="p-4 bg-[#8b5cf6] text-white border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] font-black text-xs uppercase flex items-center gap-3">
                    <span>⚡</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-[#f43f5e] text-white border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] font-black text-xs uppercase flex items-center gap-3">
                    <span>⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <!-- Main Content View -->
        <main class="p-6 flex-grow">
            @yield('content')
        </main>
    </div>
</body>
</html>
