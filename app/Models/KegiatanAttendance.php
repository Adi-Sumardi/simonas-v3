<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KegiatanAttendance extends Model
{
    protected $fillable = [
        'kegiatan_id',
        'user_id',
        'asrama',
        'waktu_absen',
        'dicatat_oleh',
        'latitude',
        'longitude',
        'alamat',
        'file_selfie',
        'file_selfie_data',
        'file_selfie_mime',
        'file_selfie_size',
        'file_lokasi',
        'file_lokasi_data',
        'file_lokasi_mime',
        'file_lokasi_size',
    ];

    protected $hidden = [
        'file_selfie_data',
        'file_lokasi_data',
    ];

    protected $casts = [
        'waktu_absen' => 'datetime',
        'latitude'    => 'float',
        'longitude'   => 'float',
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
