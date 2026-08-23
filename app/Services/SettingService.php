<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    protected const CACHE_KEY = 'site_settings_all';
    protected const CACHE_TTL = 86400; // 24 hours

    public function all(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            try {
                return Setting::pluck('value', 'key')->toArray();
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    public function get(string $key, $default = null)
    {
        $settings = $this->all();
        return $settings[$key] ?? $default;
    }

    public function set(string $key, $value): void
    {
        Setting::set($key, $value);
        $this->clearCache();
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
