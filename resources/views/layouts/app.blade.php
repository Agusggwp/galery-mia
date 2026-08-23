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
<body class="bg-[#0b0f19] text-white font-sans antialiased selection:bg-[#f59e0b] selection:text-black flex flex-col min-h-screen border-t-8 border-[#8b5cf6] relative">

    <!-- Global Page Loading Top Indicator -->
    <div id="global-page-loader" class="hidden fixed top-4 right-4 z-50 brutal-card bg-[#8b5cf6] text-white px-5 py-3 border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] flex items-center gap-3 font-black text-xs uppercase animate-bounce">
        <svg class="w-5 h-5 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        <span>⚡ MEMUAT HALAMAN...</span>
    </div>

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('global-page-loader');

            // Show loading indicator when clicking links
            document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="javascript:"])').forEach(link => {
                link.addEventListener('click', function(e) {
                    if (loader && !e.ctrlKey && !e.metaKey) {
                        loader.classList.remove('hidden');
                    }
                });
            });

            // Show loading indicator when submitting forms
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    if (loader) {
                        loader.classList.remove('hidden');
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>

