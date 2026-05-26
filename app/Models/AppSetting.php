<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key with optional default.
     */
    public static function val(string $key, mixed $default = null): mixed
    {
        $settings = Cache::remember('app_settings', 300, function () {
            return self::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Get all settings as associative array.
     */
    public static function allValues(): array
    {
        return Cache::remember('app_settings', 300, function () {
            return self::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Set a setting and clear cache.
     */
    public static function set(string $key, mixed $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        Cache::forget('app_settings');
    }

    /**
     * Bulk set settings and clear cache.
     */
    public static function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            self::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        }
        Cache::forget('app_settings');
    }
}
