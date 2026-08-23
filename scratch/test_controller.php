<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Media;
use App\Http\Controllers\MediaStreamController;
use App\Services\GoogleDriveService;

$media = Media::where('google_drive_id', 'not like', 'mock_%')->first();
echo "Media ID: " . $media->id . " DriveID: " . $media->google_drive_id . PHP_EOL;

$controller = app(MediaStreamController::class);
$response = $controller->thumbnail($media->id);

echo "Status Code: " . $response->getStatusCode() . PHP_EOL;
echo "Content-Type: " . $response->headers->get('Content-Type') . PHP_EOL;
echo "Body snippet: " . substr((string)$response->getContent(), 0, 200) . PHP_EOL;
