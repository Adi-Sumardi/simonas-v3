<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class BaseAktivitas extends Model
{
    protected $fillable = [
        'user_id',
        'komponen_id',
        'nama_warga',
        'komponen',
        'asrama',
        'kegiatan',
        'waktu',
        'tempat',
        'keterangan',
        'file',
        'nama_penilai',
        'nilai'
    ];

    protected $casts = [
        'waktu' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'nilai' => 'float'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function komponen(): BelongsTo
    {
        return $this->belongsTo(Komponen::class);
    }
}
