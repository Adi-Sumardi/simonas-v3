<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asrama extends Model
{
    protected $fillable = [
        'nama_asrama', 'kapasitas', 'tahun_jabatan', 'direktur', 'ketua',
    ];

    protected $hidden = ['created_at', 'updated_at'];
}
