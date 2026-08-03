<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentoringLog extends Model
{
    protected $fillable = [
        'mentor_id',
        'mentee_id',
        'tanggal',
        'topik',
        'tujuan',
        'hasil_diskusi',
        'kendala',
        'solusi',
        'tindak_lanjut',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function mentee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }
}
