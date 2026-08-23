<footer class="bg-[#111827] text-white mt-24 border-t-5 border-black pt-16 pb-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 pb-12 border-b-4 border-black">
            
            <!-- Brand Info -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-[#8b5cf6] text-white border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] flex items-center justify-center font-black text-2xl">
                        MI
                    </div>
                    <span class="text-2xl font-black uppercase tracking-tight text-white">
                        {{ \App\Models\Setting::get('site_name', 'D3 MI PNB') }}
                    </span>
                </div>
                <p class="text-gray-300 font-bold text-xs leading-relaxed border-l-4 border-[#8b5cf6] pl-3 py-1 bg-[#0b0f19] p-3 border-2 border-black">
                    {{ \App\Models\Setting::get('site_description', 'Dokumentasi Resmi Kelas D3 Manajemen Informatika Politeknik Negeri Bali.') }}
                </p>
            </div>

            <!-- Quick Links -->
            <div class="space-y-4">
                <h4 class="brutal-badge bg-[#6d28d9] text-white text-xs">Navigasi Cepat</h4>
                <ul class="space-y-3 font-black text-xs uppercase">
                    <li><a href="{{ route('home') }}" class="hover:text-[#8b5cf6] transition-colors flex items-center gap-2"><span>&rarr;</span> Beranda Utama</a></li>
                    <li><a href="{{ route('gallery') }}" class="hover:text-[#8b5cf6] transition-colors flex items-center gap-2"><span>&rarr;</span> Galeri Media</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-[#8b5cf6] transition-colors flex items-center gap-2"><span>&rarr;</span> Panel Admin</a></li>
                </ul>
            </div>

            <!-- Cloud Integration Notice -->
            <div class="space-y-4">
                <h4 class="brutal-badge bg-[#f59e0b] text-black text-xs">Cloud Storage</h4>
                <p class="text-gray-400 font-medium text-xs leading-relaxed">
                    Seluruh media tersimpan aman di Google Drive cloud storage dan terintegrasi otomatis dengan website tanpa membebankan server.
                </p>
                <div class="brutal-badge bg-[#8b5cf6] text-white text-xs">
                    ⚡ Google Drive API v3 Integrated
                </div>
            </div>
        </div>

        <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 font-bold text-xs uppercase tracking-wider text-gray-400">
            <p>{{ \App\Models\Setting::get('footer_info', '© 2026 D3 Manajemen Informatika PNB. Kampus Bukit Jimbaran, Bali.') }}</p>
            <p class="bg-black text-[#8b5cf6] px-3 py-1 border-2 border-white">Laravel • Cyber Dark Neo-Brutalism</p>
        </div>
    </div>
</footer>
