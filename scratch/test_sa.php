<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = app(\App\Services\GoogleDriveService::class);
echo "Drive Service Class: " . get_class($service) . PHP_EOL;
echo "SA Path: " . env('GOOGLE_SERVICE_ACCOUNT_JSON') . PHP_EOL;
echo "File Exists: " . (file_exists(base_path(env('GOOGLE_SERVICE_ACCOUNT_JSON'))) ? 'YES' : 'NO') . PHP_EOL;
