<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;

$client = new GoogleClient();
$client->setAuthConfig(base_path('storage/app/drive-api-504815-0e9468fed933.json'));
$client->setScopes([GoogleDrive::DRIVE_READONLY]);

$drive = new GoogleDrive($client);

echo "Searching for shared folders/files..." . PHP_EOL;

// 1. Check shared folders
try {
    $results = $drive->files->listFiles([
        'q' => "mimeType = 'application/vnd.google-apps.folder' and trashed = false",
        'fields' => 'files(id, name, sharedWithMeTime, parents)',
        'pageSize' => 50,
    ]);

    foreach ($results->getFiles() as $f) {
        echo "FOLDER: Name={$f->getName()} | ID={$f->getId()}" . PHP_EOL;
    }
} catch (\Exception $e) {
    echo "Folder query error: " . $e->getMessage() . PHP_EOL;
}

// 2. Check all items accessible by service account
try {
    $results2 = $drive->files->listFiles([
        'fields' => 'files(id, name, mimeType)',
        'pageSize' => 50,
    ]);

    echo "Total files/folders found: " . count($results2->getFiles()) . PHP_EOL;
    foreach ($results2->getFiles() as $f) {
        echo "ITEM: Name={$f->getName()} | Type={$f->getMimeType()} | ID={$f->getId()}" . PHP_EOL;
    }
} catch (\Exception $e) {
    echo "Item query error: " . $e->getMessage() . PHP_EOL;
}
