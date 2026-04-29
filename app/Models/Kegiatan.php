<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatans';
    
    protected $fillable = [
        'nama_kegiatan',
        'tujuan',
        'penyelenggara',
        'jenis_kegiatan',
        'waktu',
        'tempat',
        'keterangan',
        'file'
    ];
} 