<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asrama extends Model
{
    protected $fillable = [
        'nama_asrama', 'tahun_jabatan', 'direktur', 'ketua',
    ];

    protected $hidden = ['created_at', 'updated_at'];
}
