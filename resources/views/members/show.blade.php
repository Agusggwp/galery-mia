@extends('layouts.app')

@section('title', $member->name . ' - Profil Anggota Kelas')

@section('content')
<!-- Member Detail Hero Section -->
<section class="bg-[#1e1b4b] text-white py-16 border-b-5 border-black">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <a href="{{ route('members.index') }}" class="brutal-btn brutal-btn-slate text-xs px-4 py-2 mb-8">
            &larr; KEMBALI KE DAFTAR ANGGOTA
        </a>

        <div class="brutal-card bg-[#111827] p-8 sm:p-10 flex flex-col md:flex-row items-center md:items-start gap-8">
            <!-- Large Profile Photo -->
            <div class="w-48 h-48 sm:w-56 sm:h-56 flex-shrink-0 border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] overflow-hidden bg-[#0b0f19]">
                <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
            </div>

            <!-- Profile Info -->
            <div class="space-y-4 text-center md:text-left flex-grow">
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                    <span class="brutal-badge bg-[#8b5cf6] text-white text-xs">KELAS {{ strtoupper($member->class_name) }}</span>
                    <span class="brutal-badge bg-[#f59e0b] text-black text-xs">ANGKATAN {{ $member->generation }}</span>
                    <span class="brutal-badge bg-[#3b82f6] text-white text-xs">NIM/ABSEN: {{ $member->student_number }}</span>
                </div>

                <h1 class="text-3xl sm:text-5xl font-black uppercase text-white tracking-tight">
                    {{ $member->name }}
                </h1>
                
                <p class="text-sm font-black uppercase text-[#f59e0b]">
                    PANGGILAN: "{{ $member->nickname }}" • JURUSAN: {{ strtoupper($member->major) }}
                </p>

                <!-- Bio Box -->
                <div class="bg-[#0b0f19] p-5 border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-xs sm:text-sm font-bold text-gray-200 leading-relaxed">
                    {{ $member->bio ?: 'Mahasiswa aktif Program Studi D3 Manajemen Informatika Politeknik Negeri Bali.' }}
                </div>

                <!-- Social Links (If Public) -->
                @if(($member->instagram && $member->is_instagram_public) || ($member->whatsapp && $member->is_whatsapp_public))
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 pt-2">
                        @if($member->instagram && $member->is_instagram_public)
                            <a href="https://instagram.com/{{ ltrim($member->instagram, '@') }}" target="_blank" class="brutal-btn brutal-btn-crimson px-4 py-2 text-xs">
                                📸 INSTAGRAM: @{{ ltrim($member->instagram, '@') }}
                            </a>
                        @endif

                        @if($member->whatsapp && $member->is_whatsapp_public)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $member->whatsapp) }}" target="_blank" class="brutal-btn brutal-btn-amber px-4 py-2 text-xs">
                                💬 WHATSAPP
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

    </div>
</section>

<!-- Class Gallery Highlights Section -->
@if(isset($relatedMedia) && $relatedMedia->isNotEmpty())
    <section class="py-16 bg-[#0b0f19]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 pb-4 border-b-4 border-black">
                <span class="brutal-badge bg-[#8b5cf6] text-white">SOROTAN FOTO KELAS</span>
                <h2 class="text-2xl sm:text-3xl font-black uppercase text-white mt-2">DOKUMENTASI KEGIATAN</h2>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach($relatedMedia as $media)
                    <div class="brutal-card overflow-hidden bg-black h-48 border-3 border-black">
                        <img src="{{ route('media.thumbnail', $media->id) }}" alt="{{ $media->name }}" class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection
