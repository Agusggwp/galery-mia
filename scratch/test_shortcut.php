<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Media;
use App\Services\GoogleDriveService;

$service = app(GoogleDriveService::class);
$googleDrive = $service->getDriveService();

$file = $googleDrive->files->get('16Q0yiA3avu7-J7BjJx4Y2-AJ3fUOA-HA', ['fields' => 'id, name, mimeType, shortcutDetails']);

echo "Shortcut Name: " . $file->getName() . PHP_EOL;
echo "Shortcut Mime: " . $file->getMimeType() . PHP_EOL;

$details = $file->getShortcutDetails();
if ($details) {
    echo "Target ID: " . $details->getTargetId() . PHP_EOL;
    echo "Target Mime: " . $details->getTargetMimeType() . PHP_EOL;

    // Fetch target file
    $targetFile = $googleDrive->files->get($details->getTargetId(), ['fields' => 'id, name, thumbnailLink']);
    echo "Target Thumbnail: " . ($targetFile->getThumbnailLink() ?: 'NULL') . PHP_EOL;
}
