<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class DailyTarget extends Model
{
    protected $fillable = ['label', 'key', 'value', 'unit', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'value' => 'integer'];

    public static function val(string $key, mixed $default = null): mixed
    {
        $targets = Cache::remember('daily_targets', 300, function () {
            return self::where('is_active', true)->pluck('value', 'key')->toArray();
        });

        return $targets[$key] ?? $default;
    }

    public static function clearCache(): void
    {
        Cache::forget('daily_targets');
    }
}
