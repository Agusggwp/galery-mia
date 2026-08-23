@extends('layouts.app')

@section('title', 'Anggota Kelas - ' . ($siteName ?? 'D3 MI PNB'))

@section('content')
<!-- Header Banner (Cyber Dark Neo-Brutalist) -->
<section class="bg-[#1e1b4b] text-white py-16 border-b-5 border-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="brutal-badge bg-[#8b5cf6] text-white mb-3">CLASS DIRECTORY</span>
        <h1 class="text-4xl sm:text-6xl font-black uppercase tracking-tight text-white">ANGGOTA KELAS</h1>
        <p class="text-white font-bold mt-2 text-base sm:text-lg max-w-2xl bg-[#0f172a] p-4 border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
            Daftar profil resmi mahasiswa dan anggota kelas D3 Manajemen Informatika Politeknik Negeri Bali.
        </p>
    </div>
</section>

<!-- Filter & Members Grid Container -->
<section class="py-12 bg-[#0b0f19] min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter Toolbar -->
        <div class="brutal-card p-6 mb-10 bg-[#111827] space-y-6">
            <form action="{{ route('members.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Search Input -->
                <div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="CARI NAMA / NIM / NICK..." class="brutal-input w-full px-4 py-3 text-xs uppercase font-black bg-[#0b0f19] text-white">
                </div>

                <!-- Class Filter -->
                <div>
                    <select name="class" onchange="this.form.submit()" class="brutal-input w-full py-3 px-4 text-xs font-black uppercase bg-[#0b0f19] text-white">
                        <option value="">-- SEMUA KELAS --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c }}" {{ $className == $c ? 'selected' : '' }}>
                                KELAS {{ strtoupper($c) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Generation Filter -->
                <div>
                    <select name="generation" onchange="this.form.submit()" class="brutal-input w-full py-3 px-4 text-xs font-black uppercase bg-[#0b0f19] text-white">
                        <option value="">-- SEMUA ANGKATAN --</option>
                        @foreach($generations as $gen)
                            <option value="{{ $gen }}" {{ $generation == $gen ? 'selected' : '' }}>
                                ANGKATAN {{ $gen }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="brutal-btn brutal-btn-primary flex-1 py-3 text-xs">
                        🔍 CARI ANGGOTA
                    </button>
                    @if($search || $className || $generation)
                        <a href="{{ route('members.index') }}" class="brutal-btn brutal-btn-amber py-3 px-4 text-xs">
                            RESET
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Members Grid -->
        @if($members->isEmpty())
            <div class="brutal-card p-16 text-center bg-[#111827]">
                <h3 class="text-2xl font-black uppercase text-white">BELUM ADA ANGGOTA TERDAFTAR</h3>
                <p class="text-xs font-bold text-gray-400 mt-2">Coba sesuaikan kata kunci pencarian atau hubungi Admin untuk mendaftar.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 items-stretch">
                @foreach($members as $member)
                    <x-member-card :member="$member" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $members->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
