<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kegiatan extends Model
{
    protected $fillable = [
        'nama_kegiatan',
        'tujuan',
        'penyelenggara',
        'asrama',
        'jenis_kegiatan',
        'wajib_absen',
        'waktu',
        'tempat',
        'keterangan',
        'file',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'wajib_absen' => 'boolean',
    ];

    public function attendances(): HasMany
    {
        return $this->hasMany(KegiatanAttendance::class);
    }
}
