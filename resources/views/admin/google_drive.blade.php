@extends('layouts.admin')

@section('title', 'Integrasi Google Drive API - Admin')
@section('header_title', 'Status Integrasi Google Drive API')

@section('content')
<div class="space-y-8">
    
    <!-- Connection Status Banner -->
    <div class="brutal-card bg-[#1e1b4b] p-6 sm:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2">
            <div class="flex items-center gap-3">
                <span class="w-4 h-4 bg-[#8b5cf6] border-2 border-white animate-ping"></span>
                <h3 class="text-2xl font-black uppercase text-white">
                    STATUS API: {{ $isConfigured ? 'TERHUBUNG & SIAP' : 'MODE FALLBACK DEMO' }}
                </h3>
            </div>
            <p class="text-xs sm:text-sm font-bold text-gray-300 max-w-xl bg-[#0b0f19] p-3 border-3 border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]">
                {{ $isConfigured 
                    ? 'Koneksi Google Drive API aktif dan siap melakukan pemindaian folder & media secara real-time.' 
                    : 'Google Drive API belum memiliki credential resmi di .env, sistem saat ini berjalan menggunakan data simulasi demo.' }}
            </p>
        </div>

        <form action="{{ route('admin.google-drive.sync') }}" method="POST">
            @csrf
            <button type="submit" class="brutal-btn brutal-btn-amber px-6 py-4 text-sm font-black">
                ⚡ SINKRONKAN SEKARANG
            </button>
        </form>
    </div>

    <!-- Sync Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="brutal-card bg-[#111827] p-6">
            <span class="text-xs font-black uppercase text-gray-300">MAIN FOLDER ID</span>
            <div class="mt-2 font-mono text-xs font-black text-black bg-[#f59e0b] p-3 border-3 border-black shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] select-all truncate">
                {{ $folderId }}
            </div>
            <span class="text-[10px] font-bold text-gray-400 mt-2 block">Dapat diubah di halaman Pengaturan Website</span>
        </div>

        <div class="brutal-card bg-[#111827] p-6">
            <span class="text-xs font-black uppercase text-gray-300">WAKTU SYNC TERAKHIR</span>
            <div class="mt-2 text-base font-black text-white">
                {{ $lastSyncAt }}
            </div>
            <span class="text-[10px] font-bold text-gray-400 mt-2 block">Jadwal Otomatis: Hourly (Tiap jam)</span>
        </div>

        <div class="brutal-card bg-[#111827] p-6">
            <span class="text-xs font-black uppercase text-gray-300">TOTAL TERINDEKS</span>
            <div class="mt-2 text-lg font-black text-[#8b5cf6]">
                {{ number_format($albumsCount) }} ALBUM • {{ number_format($mediaCount) }} MEDIA
            </div>
            <span class="brutal-badge bg-[#8b5cf6] text-white text-[10px] mt-2 inline-block">
                MySQL Database Active
            </span>
        </div>
    </div>

    <!-- Sync Log & Status Details -->
    <div class="brutal-card bg-[#111827] p-6 sm:p-8 space-y-4">
        <h4 class="font-black text-base uppercase text-white">LAPORAN HASIL SINKRONISASI TERAKHIR</h4>

        <div class="p-4 bg-[#0b0f19] text-[#8b5cf6] font-mono text-xs space-y-2 border-3 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
            <div><span class="text-[#f59e0b]">[STATUS]</span>: {{ strtoupper($lastSyncStatus) }}</div>
            <div><span class="text-[#f59e0b]">[PESAN]</span>: {{ $lastSyncMessage }}</div>
        </div>
    </div>
</div>
@endsection
