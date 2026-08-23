<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Services\GoogleDriveService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MediaStreamController extends Controller
{
    protected GoogleDriveService $driveService;

    public function __construct(GoogleDriveService $driveService)
    {
        $this->driveService = $driveService;
    }

    public function thumbnail($id)
    {
        $media = Media::findOrFail($id);

        $cacheKey = 'media_thumb_v3_' . $media->id;

        $imageData = Cache::get($cacheKey);

        if (!$imageData) {
            try {
                $driveService = $this->driveService->getDriveService();
                if ($driveService) {
                    $file = $driveService->files->get($media->google_drive_id, ['fields' => 'id, thumbnailLink']);
                    $thumbLink = $file->getThumbnailLink();

                    if ($thumbLink) {
                        $thumbLink = preg_replace('/=s\d+$/', '=s800', $thumbLink);

                        $client = new Client(['timeout' => 15, 'verify' => false]);
                        $res = $client->get($thumbLink);

                        if ($res->getStatusCode() === 200) {
                            $imageData = [
                                'content' => base64_encode((string) $res->getBody()),
                                'mime_type' => $res->getHeaderLine('Content-Type') ?: 'image/jpeg'
                            ];
                            Cache::put($cacheKey, $imageData, 86400);
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('MediaStreamController thumbnail failed for media ' . $media->id . ': ' . $e->getMessage());
            }
        }

        if ($imageData && !empty($imageData['content'])) {
            return response(base64_decode($imageData['content']), 200)
                ->header('Content-Type', $imageData['mime_type'])
                ->header('Cache-Control', 'public, max-age=604800, immutable');
        }

        // Fallback SVG placeholder
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="800" height="600" viewBox="0 0 800 600" fill="#111827">
            <rect width="800" height="600" fill="#111827"/>
            <rect x="20" y="20" width="760" height="560" fill="none" stroke="#8b5cf6" stroke-width="6"/>
            <text x="50%" y="45%" dominant-baseline="middle" text-anchor="middle" fill="#8b5cf6" font-family="sans-serif" font-size="28" font-weight="900">' . htmlspecialchars(strtoupper($media->name)) . '</text>
            <text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" fill="#f59e0b" font-family="sans-serif" font-size="18" font-weight="700">GOOGLE DRIVE MEDIA</text>
        </svg>';

        return response($svg, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'public, max-age=86400');
    }

    public function stream($id)
    {
        $media = Media::findOrFail($id);

        $cacheKey = 'media_highres_v3_' . $media->id;

        $imageData = Cache::get($cacheKey);

        if (!$imageData) {
            try {
                $driveService = $this->driveService->getDriveService();
                if ($driveService) {
                    $file = $driveService->files->get($media->google_drive_id, ['fields' => 'id, thumbnailLink']);
                    $thumbLink = $file->getThumbnailLink();

                    if ($thumbLink) {
                        $thumbLink = preg_replace('/=s\d+$/', '=s1600', $thumbLink);

                        $client = new Client(['timeout' => 20, 'verify' => false]);
                        $res = $client->get($thumbLink);

                        if ($res->getStatusCode() === 200) {
                            $imageData = [
                                'content' => base64_encode((string) $res->getBody()),
                                'mime_type' => $res->getHeaderLine('Content-Type') ?: 'image/jpeg'
                            ];
                            Cache::put($cacheKey, $imageData, 86400);
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('MediaStreamController stream failed for media ' . $media->id . ': ' . $e->getMessage());
            }
        }

        if ($imageData && !empty($imageData['content'])) {
            return response(base64_decode($imageData['content']), 200)
                ->header('Content-Type', $imageData['mime_type'])
                ->header('Cache-Control', 'public, max-age=604800, immutable');
        }

        return redirect($media->drive_url);
    }
}
