<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilRiwayat extends Model
{
    protected $fillable = [
        'user_id', 'tipe', 'judul', 'posisi',
        'mulai', 'selesai', 'masih_berlangsung',
        'deskripsi', 'lokasi',
    ];

    protected $casts = [
        'mulai'              => 'date',
        'selesai'            => 'date',
        'masih_berlangsung'  => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
