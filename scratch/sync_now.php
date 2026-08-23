<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;

Setting::set('google_drive_folder_id', '1wRexDYWkh5sU6dXFaMip9SqCLkQ-Wr44');
echo "Updated setting google_drive_folder_id to: " . Setting::get('google_drive_folder_id') . PHP_EOL;

Artisan::call('gallery:sync');
echo Artisan::output();
