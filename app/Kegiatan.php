<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $fillable = [
        'nama_kegiatan', 
        'tujuan', 
        'penyelenggara', 
        'jenis_kegiatan', 
        'waktu', 'tempat', 
        'keterangan', 
        'file'
    ];

    protected $hidden = ['created_at', 'updated_at'];
}
