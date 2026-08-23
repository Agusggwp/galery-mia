<?php

namespace App\Services;

use App\Models\Setting;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    protected ?GoogleClient $client = null;
    protected ?GoogleDrive $driveService = null;
    protected ?string $folderId = null;

    public function __construct()
    {
        $this->folderId = Setting::get('google_drive_folder_id', config('services.google_drive.folder_id', env('GOOGLE_DRIVE_FOLDER_ID')));
        $this->initClient();
    }

    protected function initClient(): void
    {
        try {
            $client = new GoogleClient();
            $client->setApplicationName(config('app.name', 'Gallery Kelas'));
            $client->setScopes([GoogleDrive::DRIVE_READONLY]);

            // 1. Service Account JSON file path
            $serviceAccountPath = env('GOOGLE_SERVICE_ACCOUNT_JSON') ?? env('GOOGLE_APPLICATION_CREDENTIALS');
            if ($serviceAccountPath) {
                if (!file_exists($serviceAccountPath) && file_exists(base_path($serviceAccountPath))) {
                    $serviceAccountPath = base_path($serviceAccountPath);
                }
                if (file_exists($serviceAccountPath)) {
                    $client->setAuthConfig($serviceAccountPath);
                    $this->client = $client;
                    $this->driveService = new GoogleDrive($client);
                    return;
                }
            }


            // 2. Service Account inline JSON string
            $serviceAccountJson = env('GOOGLE_SERVICE_ACCOUNT_JSON_CONTENT');
            if ($serviceAccountJson) {
                $jsonArray = json_decode($serviceAccountJson, true);
                if ($jsonArray) {
                    $client->setAuthConfig($jsonArray);
                    $this->client = $client;
                    $this->driveService = new GoogleDrive($client);
                    return;
                }
            }

            // 3. OAuth Client ID & Secret / Refresh token
            $clientId = config('services.google_drive.client_id', env('GOOGLE_CLIENT_ID'));
            $clientSecret = config('services.google_drive.client_secret', env('GOOGLE_CLIENT_SECRET'));
            $refreshToken = env('GOOGLE_REFRESH_TOKEN');

            if ($clientId && $clientSecret) {
                $client->setClientId($clientId);
                $client->setClientSecret($clientSecret);
                if ($refreshToken) {
                    $client->refreshToken($refreshToken);
                }
                $this->client = $client;
                $this->driveService = new GoogleDrive($client);
                return;
            }

            // 4. API Key (for public folders)
            $apiKey = env('GOOGLE_DRIVE_API_KEY');
            if ($apiKey) {
                $client->setDeveloperKey($apiKey);
                $this->client = $client;
                $this->driveService = new GoogleDrive($client);
                return;
            }
        } catch (\Exception $e) {
            Log::error('GoogleDriveService init failed: ' . $e->getMessage());
        }
    }

    public function isConfigured(): bool
    {
        return !empty($this->folderId) 
            && $this->folderId !== 'your_google_drive_folder_id_here' 
            && $this->driveService !== null;
    }

    public function getFolderId(): ?string
    {
        return $this->folderId;
    }

    public function getDriveService(): ?GoogleDrive
    {
        return $this->driveService;
    }


    public function getFileContent(string $fileId): ?array
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $response = $this->driveService->files->get($fileId, ['alt' => 'media']);
            return [
                'content' => (string) $response->getBody(),
                'mime_type' => $response->getHeaderLine('Content-Type')
            ];
        } catch (\Exception $e) {
            Log::warning('GoogleDriveService getFileContent failed for ' . $fileId . ': ' . $e->getMessage());
            return null;
        }
    }


    /**
     * Get list of subfolders inside main Google Drive folder (Albums)
     * Also includes the main folder itself if it contains direct media files.
     */
    public function getAlbums(): array
    {
        if (!$this->isConfigured()) {
            return $this->getMockAlbums();
        }

        try {
            // 1. Fetch subfolders
            $query = sprintf(
                "'%s' in parents and mimeType = 'application/vnd.google-apps.folder' and trashed = false",
                $this->folderId
            );

            $results = $this->driveService->files->listFiles([
                'q' => $query,
                'fields' => 'files(id, name, description, createdTime, modifiedTime)',
                'pageSize' => 100,
            ]);

            $albums = [];
            foreach ($results->getFiles() as $folder) {
                $albums[] = [
                    'google_drive_id' => $folder->getId(),
                    'name' => $folder->getName(),
                    'description' => $folder->getDescription() ?? '',
                    'created_at' => $folder->getCreatedTime(),
                    'updated_at' => $folder->getModifiedTime(),
                ];
            }

            // 2. Check if main folder itself contains direct media files
            $directMediaQuery = sprintf(
                "'%s' in parents and mimeType != 'application/vnd.google-apps.folder' and trashed = false",
                $this->folderId
            );
            $directMedia = $this->driveService->files->listFiles([
                'q' => $directMediaQuery,
                'fields' => 'files(id)',
                'pageSize' => 1,
            ]);

            if (count($directMedia->getFiles()) > 0) {
                // Prepend main folder as an Album so direct files are synced!
                array_unshift($albums, [
                    'google_drive_id' => $this->folderId,
                    'name' => 'Dokumentasi Utama',
                    'description' => 'Foto & Video yang diunggah langsung di folder utama',
                    'created_at' => now()->toIso8601String(),
                    'updated_at' => now()->toIso8601String(),
                ]);
            }

            return $albums;
        } catch (\Exception $e) {
            Log::warning('GoogleDrive API fetch albums failed: ' . $e->getMessage());
            return [];
        }
    }


    /**
     * Get all media files inside a specific album subfolder
     */
    public function getMediaFilesInFolder(string $folderId): array
    {
        if (!$this->isConfigured()) {
            return $this->getMockMediaForFolder($folderId);
        }

        try {
            $query = sprintf(
                "'%s' in parents and mimeType != 'application/vnd.google-apps.folder' and trashed = false",
                $folderId
            );

            $results = $this->driveService->files->listFiles([
                'q' => $query,
                'fields' => 'files(id, name, mimeType, size, thumbnailLink, webContentLink, webViewLink, shortcutDetails, createdTime, modifiedTime)',
                'pageSize' => 500,
            ]);

            $media = [];
            foreach ($results->getFiles() as $file) {
                $mime = strtolower($file->getMimeType());
                $fileId = $file->getId();

                // Resolve Google Drive shortcuts to their actual target file ID
                if ($mime === 'application/vnd.google-apps.shortcut' && $file->getShortcutDetails()) {
                    $shortcutDetails = $file->getShortcutDetails();
                    $fileId = $shortcutDetails->getTargetId();
                    if ($shortcutDetails->getTargetMimeType()) {
                        $mime = strtolower($shortcutDetails->getTargetMimeType());
                    }
                }

                $type = $this->determineMediaType($mime, $file->getName());

                if (!$type || !$fileId) {
                    continue; // Skip non-media files
                }

                $rawThumbnail = $file->getThumbnailLink();
                if ($rawThumbnail) {
                    $thumbnailUrl = preg_replace('/=s\d+$/', '=s800', $rawThumbnail);
                    $highResUrl = preg_replace('/=s\d+$/', '=s1600', $rawThumbnail);
                } else {
                    $thumbnailUrl = $this->generateThumbnailUrl($fileId, $type);
                    $highResUrl = sprintf('https://lh3.googleusercontent.com/d/%s=s1600', $fileId);
                }

                $media[] = [
                    'google_drive_id' => $fileId,
                    'name' => $file->getName(),
                    'mime_type' => $mime,
                    'type' => $type,
                    'thumbnail_url' => $thumbnailUrl,
                    'drive_url' => $type === 'image' ? $highResUrl : ($file->getWebViewLink() ?? $this->generateEmbedUrl($fileId)),
                    'file_size' => (int) $file->getSize(),
                    'captured_at' => $file->getCreatedTime(),
                ];
            }


            return $media;
        } catch (\Exception $e) {
            Log::warning('GoogleDrive API fetch media failed: ' . $e->getMessage());
            return [];
        }
    }


    public function determineMediaType(string $mimeType, string $filename): ?string
    {
        $imageMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/bmp', 'image/svg+xml', 'image/heif', 'image/heic'];
        $videoMimes = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm', 'video/mpeg', 'video/3gpp', 'video/ogg'];

        if (in_array($mimeType, $imageMimes)) {
            return 'image';
        }

        if (in_array($mimeType, $videoMimes)) {
            return 'video';
        }

        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'heic', 'heif'])) {
            return 'image';
        }
        if (in_array($ext, ['mp4', 'mov', 'avi', 'mkv', 'webm', '3gp'])) {
            return 'video';
        }

        return null;
    }


    public function generateThumbnailUrl(string $driveId, string $type = 'image'): string
    {
        return sprintf('https://lh3.googleusercontent.com/d/%s=w800-h600', $driveId);
    }

    public function generateEmbedUrl(string $driveId): string
    {
        return sprintf('https://drive.google.com/file/d/%s/preview', $driveId);
    }

    /**
     * Demo Mock Albums when Google Drive API credentials are not set
     */
    protected function getMockAlbums(): array
    {
        return [
            [
                'google_drive_id' => 'mock_folder_1',
                'name' => 'Kunjungan Industri & Field Trip 2024',
                'description' => 'Dokumentasi kegiatan kunjungan industri ke tech startup dan perusahaan IT.',
                'created_at' => now()->subMonths(2)->toIso8601String(),
                'updated_at' => now()->subMonths(2)->toIso8601String(),
            ],
            [
                'google_drive_id' => 'mock_folder_2',
                'name' => 'Pentas Seni & Classmeet 2024',
                'description' => 'Keseruan penampilan musik, tari, dan perlombaan antarkelas.',
                'created_at' => now()->subMonths(4)->toIso8601String(),
                'updated_at' => now()->subMonths(4)->toIso8601String(),
            ],
            [
                'google_drive_id' => 'mock_folder_3',
                'name' => 'Masa Bimbingan & Makrab',
                'description' => 'Momen keakraban kelas dan kegiatan outbound awal semester.',
                'created_at' => now()->subMonths(8)->toIso8601String(),
                'updated_at' => now()->subMonths(8)->toIso8601String(),
            ],
            [
                'google_drive_id' => 'mock_folder_4',
                'name' => 'Proyek Kelompok & Workshop',
                'description' => 'Foto dan video pengerjaan tugas akhir serta hackathon kelas.',
                'created_at' => now()->subMonths(10)->toIso8601String(),
                'updated_at' => now()->subMonths(10)->toIso8601String(),
            ]
        ];
    }

    /**
     * Demo Mock Media when Google Drive API credentials are not set
     */
    protected function getMockMediaForFolder(string $folderId): array
    {
        $mockSampleImages = [
            'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&w=1200&q=80',
        ];

        $mockSampleVideos = [
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
        ];

        $mediaList = [];
        $imgIndex = 0;

        for ($i = 1; $i <= 5; $i++) {
            $imgUrl = $mockSampleImages[($i + strlen($folderId)) % count($mockSampleImages)];
            $mediaList[] = [
                'google_drive_id' => 'mock_file_' . $folderId . '_img_' . $i,
                'name' => 'Foto_Kegiatan_' . $i . '.jpg',
                'mime_type' => 'image/jpeg',
                'type' => 'image',
                'thumbnail_url' => $imgUrl,
                'drive_url' => $imgUrl,
                'file_size' => rand(1500000, 4500000),
                'captured_at' => now()->subDays($i * 3)->toIso8601String(),
            ];
        }

        // Add 1 Video per folder
        $videoUrl = $mockSampleVideos[strlen($folderId) % count($mockSampleVideos)];
        $mediaList[] = [
            'google_drive_id' => 'mock_file_' . $folderId . '_vid_1',
            'name' => 'Highlight_Video_Seru.mp4',
            'mime_type' => 'video/mp4',
            'type' => 'video',
            'thumbnail_url' => 'https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?auto=format&fit=crop&w=1200&q=80',
            'drive_url' => $videoUrl,
            'file_size' => rand(15000000, 45000000),
            'captured_at' => now()->subDays(2)->toIso8601String(),
        ];

        return $mediaList;
    }
}
