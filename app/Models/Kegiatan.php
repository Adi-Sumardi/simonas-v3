<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $fillable = [
        'nama_kegiatan',
        'tujuan',
        'penyelenggara',
        'asrama',
        'jenis_kegiatan',
        'waktu',
        'tempat',
        'keterangan',
        'file',
    ];

    protected $hidden = ['created_at', 'updated_at'];
}
