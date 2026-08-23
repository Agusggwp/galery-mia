@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('header_title', 'Ringkasan Dashboard')

@section('content')
<!-- Overview Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <div class="brutal-card brutal-card-slate p-6 flex items-center justify-between">
        <div>
            <span class="text-xs font-black uppercase text-gray-300">TOTAL ALBUM</span>
            <div class="text-4xl font-black text-[#8b5cf6] mt-1">{{ number_format($albumsCount) }}</div>
        </div>
        <div class="text-3xl">📁</div>
    </div>

    <div class="brutal-card brutal-card-slate p-6 flex items-center justify-between">
        <div>
            <span class="text-xs font-black uppercase text-gray-300">TOTAL FOTO</span>
            <div class="text-4xl font-black text-[#f59e0b] mt-1">{{ number_format($photosCount) }}</div>
        </div>
        <div class="text-3xl">📷</div>
    </div>

    <div class="brutal-card brutal-card-slate p-6 flex items-center justify-between">
        <div>
            <span class="text-xs font-black uppercase text-gray-300">TOTAL VIDEO</span>
            <div class="text-4xl font-black text-[#3b82f6] mt-1">{{ number_format($videosCount) }}</div>
        </div>
        <div class="text-3xl">🎬</div>
    </div>

    <div class="brutal-card brutal-card-slate p-6 flex items-center justify-between">
        <div>
            <span class="text-xs font-black uppercase text-gray-300">STATUS SYNC</span>
            <div class="text-base font-black mt-1 uppercase tracking-wider
                {{ $lastSyncStatus === 'success' ? 'bg-[#8b5cf6] text-white px-2 py-0.5 border-2 border-black inline-block' : 'bg-[#f43f5e] text-white px-2 py-0.5 border-2 border-black inline-block' }}">
                {{ $lastSyncStatus }}
            </div>
        </div>
        <div class="text-3xl">⚡</div>
    </div>
</div>

<!-- Sync Status Banner Card & Actions -->
<div class="brutal-card bg-[#1e1b4b] p-6 sm:p-8 mb-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
    <div class="space-y-2">
        <span class="brutal-badge bg-[#8b5cf6] text-white text-xs">GOOGLE DRIVE SYNCHRONIZER</span>
        <h3 class="text-2xl font-black text-white uppercase">SINKRONISASI TERAKHIR: {{ $lastSyncAt }}</h3>
        <p class="text-xs font-bold text-gray-300 max-w-xl border-l-4 border-[#f59e0b] pl-3 py-1 bg-[#0b0f19] p-2 border-2 border-black">
            CATATAN: {{ $lastSyncMessage }}
        </p>
    </div>

    <div class="flex items-center gap-3 w-full md:w-auto">
        <a href="{{ route('admin.google-drive') }}" class="brutal-btn brutal-btn-amber px-6 py-3.5 text-xs font-black w-full md:w-auto text-center">
            ⚙️ PENGATURAN DRIVE &rarr;
        </a>
    </div>
</div>

<!-- Recent Media Grid -->
<div class="brutal-card bg-[#111827] p-6 sm:p-8">
    <div class="flex items-center justify-between mb-6 pb-4 border-b-3 border-black">
        <h3 class="text-lg font-black uppercase text-white">MEDIA TERBARU DISIMPAN</h3>
        <a href="{{ route('admin.media.index') }}" class="brutal-btn brutal-btn-slate px-3 py-1 text-[10px]">
            LIHAT SEMUA &rarr;
        </a>
    </div>

    @if($recentMedia->isEmpty())
        <p class="text-xs font-bold text-gray-400 text-center py-8">Belum ada media tersimpan.</p>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
            @foreach($recentMedia as $m)
                <div class="brutal-card overflow-hidden bg-black p-0">
                    <img src="{{ $m->thumbnail_url }}" alt="{{ $m->name }}" class="w-full h-28 object-cover">
                    <div class="p-2 bg-[#111827] border-t-3 border-black">
                        <span class="text-[9px] font-black text-[#8b5cf6] uppercase block truncate">{{ $m->album->name ?? 'Album' }}</span>
                        <h5 class="text-xs font-black text-white truncate" title="{{ $m->name }}">{{ $m->name }}</h5>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
