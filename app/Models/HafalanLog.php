<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HafalanLog extends Model
{
    use HasFactory;

    public const SCORE_MEMTAS = 'memtas';
    public const SCORE_LAYAK_ULANG = 'layak_ulang';
    public const SCORE_PERLU_PERBAIKAN = 'perlu_perbaikan';
    public const SCORE_PENDING = 'pending';

    protected $fillable = [
        'user_id',
        'mentor_id',
        'surah',
        'ayat_start',
        'ayat_end',
        'halaman_start',
        'halaman_end',
        'score',
        'notes',
        'tested_at',
        'mentor_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'tested_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'ayat_start' => 'integer',
        'ayat_end' => 'integer',
        'halaman_start' => 'integer',
        'halaman_end' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function scopePending($query)
    {
        return $query->where('score', self::SCORE_PENDING);
    }
}
