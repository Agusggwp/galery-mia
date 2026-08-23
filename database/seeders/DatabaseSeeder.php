<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        User::updateOrCreate(
            ['email' => 'admin@gallery.com'],
            [
                'name' => 'Admin Gallery',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Default Settings
        Setting::set('site_name', 'D3 Manajemen Informatika PNB');
        Setting::set('site_description', 'Dokumentasi Resmi Kelas Program Studi D3 Manajemen Informatika (MI) Politeknik Negeri Bali (Akreditasi Unggul). Berfokus pada keahlian praktis teknologi informasi, pemrograman, pengelolaan basis data, dan analisis sistem.');
        Setting::set('footer_info', '© 2026 D3 Manajemen Informatika - Politeknik Negeri Bali (PNB). Kampus Bukit Jimbaran, Badung, Bali.');
        Setting::set('google_drive_folder_id', '1wRexDYWkh5sU6dXFaMip9SqCLkQ-Wr44');


        // 3. Default Member Invitation Link
        $invitation = \App\Models\MemberInvitation::updateOrCreate(
            ['token' => 'pnb-mi-2026-join'],
            [
                'name' => 'Form Pendaftaran Anggota D3 MI PNB 2026',
                'description' => 'Formulir pendaftaran resmi mahasiswa kelas D3 Manajemen Informatika Politeknik Negeri Bali.',
                'is_active' => true,
                'created_by' => 1,
            ]
        );

        // 4. Sample Approved Members
        \App\Models\Member::updateOrCreate(
            ['slug' => 'i-made-agus-wirawan'],
            [
                'name' => 'I Made Agus Wirawan',
                'nickname' => 'Agus',
                'student_number' => '2315323001',
                'class_name' => 'MI A',
                'major' => 'Manajemen Informatika',
                'generation' => '2024',
                'bio' => 'Mahasiswa D3 MI PNB berminat pada Web Development, Laravel, dan Cloud Architecture.',
                'is_visible' => true,
                'status' => 'approved',
                'invitation_id' => $invitation->id,
                'approved_at' => now(),
            ]
        );

        \App\Models\Member::updateOrCreate(
            ['slug' => 'ni-putu-diah-saraswati'],
            [
                'name' => 'Ni Putu Diah Saraswati',
                'nickname' => 'Diah',
                'student_number' => '2315323002',
                'class_name' => 'MI A',
                'major' => 'Manajemen Informatika',
                'generation' => '2024',
                'bio' => 'Passionate in UI/UX Design, Data Analysis, and Software Documentation.',
                'is_visible' => true,
                'status' => 'approved',
                'invitation_id' => $invitation->id,
                'approved_at' => now(),
            ]
        );

        // 5. Trigger initial gallery sync
        Artisan::call('gallery:sync');
    }
}


