<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hafalan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'target_juz',
        'current_juz',
        'current_ayah',
        'total_ayah_completed',
        'streak_days',
        'last_tasmi_at',
        // Bookmark
        'current_surah_nomor',
        'current_surah_nama',
        'current_ayat',
        'current_page',
    ];

    protected $casts = [
        'last_tasmi_at' => 'datetime',
        'target_juz' => 'integer',
        'current_juz' => 'integer',
        'current_ayah' => 'integer',
        'total_ayah_completed' => 'integer',
        'streak_days' => 'integer',
        'current_page' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(HafalanLog::class, 'user_id', 'user_id');
    }

    public function getProgressPercentAttribute(): float
    {
        return $this->target_juz > 0
            ? round(($this->current_juz / $this->target_juz) * 100, 1)
            : 0;
    }
}
