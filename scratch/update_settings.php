<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;

Setting::set('site_name', 'D3 Manajemen Informatika PNB');
Setting::set('site_description', 'Dokumentasi Resmi Kelas Program Studi D3 Manajemen Informatika (MI) Politeknik Negeri Bali (Akreditasi Unggul). Berfokus pada keahlian praktis teknologi informasi, pemrograman, pengelolaan basis data, dan analisis sistem.');
Setting::set('footer_info', '© 2026 D3 Manajemen Informatika - Politeknik Negeri Bali (PNB). Kampus Bukit Jimbaran, Badung, Bali.');

echo "Settings updated successfully for D3 Manajemen Informatika PNB!" . PHP_EOL;
