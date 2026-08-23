<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Media;
use App\Services\GoogleDriveService;
use GuzzleHttp\Client;

$media = Media::where('google_drive_id', 'not like', 'mock_%')->first();
$service = app(GoogleDriveService::class);
$googleDrive = $service->getDriveService();

$file = $googleDrive->files->get($media->google_drive_id, ['fields' => 'id, thumbnailLink']);
$rawLink = $file->getThumbnailLink();
echo "Raw link: " . $rawLink . PHP_EOL;

$client = new Client(['http_errors' => false]);

// Test 1: Raw link
$res1 = $client->get($rawLink);
echo "Raw link status: " . $res1->getStatusCode() . PHP_EOL;

// Test 2: =s800
$link2 = preg_replace('/=s\d+$/', '=s800', $rawLink);
$res2 = $client->get($link2);
echo "=s800 link status: " . $res2->getStatusCode() . PHP_EOL;

// Test 3: =w800-h600
$link3 = preg_replace('/=s\d+$/', '=w800-h600', $rawLink);
$res3 = $client->get($link3);
echo "=w800-h600 link status: " . $res3->getStatusCode() . PHP_EOL;
