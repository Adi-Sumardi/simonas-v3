<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PointRule extends Model
{
    protected $fillable = ['label', 'activity_type', 'poin', 'unit', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'poin' => 'integer'];

    public const TYPES = [
        'shalat'     => 'Shalat Wajib',
        'hafalan'    => 'Setoran Hafalan',
        'kegiatan'   => 'Kehadiran Kegiatan',
        'akademik'   => 'Aktivitas Akademik',
        'leadership' => 'Aktivitas Leadership',
        'karakter'   => 'Aktivitas Karakter',
        'kreatif'    => 'Aktivitas Kreativitas',
    ];

    public static function activeRules()
    {
        return Cache::remember('point_rules_active', 300, function () {
            return self::where('is_active', true)->get();
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('point_rules_active');
    }
}
