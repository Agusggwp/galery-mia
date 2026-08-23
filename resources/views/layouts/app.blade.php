<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $siteName ?? 'D3 Manajemen Informatika PNB') - Cyber Dark Neo-Brutalist Archive</title>
    <meta name="description" content="@yield('meta_description', $siteDesc ?? 'Gallery D3 Manajemen Informatika PNB.')">

    <!-- Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind & Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0b0f19] text-white font-sans antialiased selection:bg-[#f59e0b] selection:text-black flex flex-col min-h-screen border-t-8 border-[#8b5cf6]">

    <!-- Navbar -->
    @include('components.navbar')

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <div class="p-4 bg-[#8b5cf6] text-white border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] flex items-center justify-between font-black uppercase text-sm">
                <div class="flex items-center gap-3">
                    <span class="text-xl">⚡</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.footer')

    <!-- Global Lightbox Preview Modal -->
    @include('components.lightbox')

    @stack('scripts')
</body>
</html>
