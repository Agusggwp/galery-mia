<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Media;
use App\Services\GoogleDriveService;

$media = Media::find(522) ?? Media::where('google_drive_id', 'not like', 'mock_%')->first();
echo "Media ID: " . $media->id . " Name: " . $media->name . " DriveID: " . $media->google_drive_id . " Mime: " . $media->mime_type . PHP_EOL;

$service = app(GoogleDriveService::class);
$googleDrive = $service->getDriveService();

try {
    $file = $googleDrive->files->get($media->google_drive_id, ['fields' => 'id, name, mimeType, thumbnailLink, webViewLink, parents']);
    echo "Drive Name: " . $file->getName() . PHP_EOL;
    echo "Drive MimeType: " . $file->getMimeType() . PHP_EOL;
    echo "Drive thumbnailLink: " . ($file->getThumbnailLink() ?: 'NULL') . PHP_EOL;
} catch (\Throwable $e) {
    echo "Google Drive API Error: " . $e->getMessage() . PHP_EOL;
}
