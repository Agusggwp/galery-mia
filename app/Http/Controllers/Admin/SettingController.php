<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $siteName = Setting::get('site_name', 'Gallery Kelas XI RPL 1');
        $siteDescription = Setting::get('site_description', 'Dokumentasi momen indah, kegiatan belajar, kunjungan industri, dan perayaan bersama kelas.');
        $logoUrl = Setting::get('logo_url', '');
        $footerInfo = Setting::get('footer_info', '© 2026 Gallery Kelas. Powered by Laravel & Google Drive API.');
        $googleDriveFolderId = Setting::get('google_drive_folder_id', env('GOOGLE_DRIVE_FOLDER_ID', ''));

        return view('admin.settings', compact(
            'siteName',
            'siteDescription',
            'logoUrl',
            'footerInfo',
            'googleDriveFolderId'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string',
            'logo_url' => 'nullable|string',
            'footer_info' => 'nullable|string',
            'google_drive_folder_id' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Pengaturan website berhasil disimpan!');
    }
}
