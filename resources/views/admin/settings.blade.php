@extends('layouts.admin')

@section('title', 'Pengaturan Website - Admin')
@section('header_title', 'Pengaturan Profil Website & Folder Drive')

@section('content')
<div class="max-w-4xl brutal-card bg-[#111827] p-6 sm:p-10 space-y-8">
    <div class="pb-4 border-b-4 border-black">
        <h3 class="text-2xl font-black uppercase text-white">IDENTITAS WEBSITE & GOOGLE DRIVE</h3>
        <p class="text-xs font-bold text-gray-400 mt-1 uppercase">Sesuaikan informasi publik dan Folder ID Google Drive utama.</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Site Name -->
        <div>
            <label for="site_name" class="block text-xs font-black uppercase text-white mb-2">NAMA KELAS / JUDUL WEBSITE</label>
            <input type="text" name="site_name" id="site_name" value="{{ old('site_name', $siteName) }}" required class="brutal-input w-full px-4 py-3 text-xs uppercase font-black bg-[#0b0f19] text-white">
        </div>

        <!-- Site Description -->
        <div>
            <label for="site_description" class="block text-xs font-black uppercase text-white mb-2">DESKRIPSI SINGKAT WEBSITE</label>
            <textarea name="site_description" id="site_description" rows="3" required class="brutal-input w-full px-4 py-3 text-xs font-bold bg-[#0b0f19] text-white">{{ old('site_description', $siteDescription) }}</textarea>
        </div>

        <!-- Google Drive Folder ID -->
        <div>
            <label for="google_drive_folder_id" class="block text-xs font-black uppercase text-white mb-2">GOOGLE DRIVE FOLDER ID UTAMA</label>
            <input type="text" name="google_drive_folder_id" id="google_drive_folder_id" value="{{ old('google_drive_folder_id', $googleDriveFolderId) }}" placeholder="1a2b3c4d5e6f7g8h9i0j..." class="brutal-input w-full px-4 py-3 text-xs font-mono font-black bg-[#0b0f19] text-white">
            <span class="text-[11px] font-bold text-gray-400 mt-1 block">Folder ID didapatkan dari akhiran URL Google Drive (contoh: drive.google.com/drive/folders/<strong>1ABC...XYZ</strong>)</span>
        </div>

        <!-- Logo URL -->
        <div>
            <label for="logo_url" class="block text-xs font-black uppercase text-white mb-2">URL LOGO (OPSIONAL)</label>
            <input type="url" name="logo_url" id="logo_url" value="{{ old('logo_url', $logoUrl) }}" placeholder="https://example.com/logo.png" class="brutal-input w-full px-4 py-3 text-xs font-bold bg-[#0b0f19] text-white">
        </div>

        <!-- Footer Info -->
        <div>
            <label for="footer_info" class="block text-xs font-black uppercase text-white mb-2">INFORMASI HAK CIPTA FOOTER</label>
            <input type="text" name="footer_info" id="footer_info" value="{{ old('footer_info', $footerInfo) }}" class="brutal-input w-full px-4 py-3 text-xs font-bold bg-[#0b0f19] text-white">
        </div>

        <div class="pt-4 border-t-3 border-black flex items-center justify-end">
            <button type="submit" class="brutal-btn brutal-btn-primary px-8 py-3.5 text-xs font-black">
                💾 SIMPAN PERUBAHAN
            </button>
        </div>
    </form>
</div>
@endsection
