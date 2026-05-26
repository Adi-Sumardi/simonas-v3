<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asrama extends Model
{
    protected $fillable = [
        'nama_asrama', 'kapasitas', 'tahun_jabatan', 'direktur', 'ketua',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function jabatans()
    {
        return $this->hasMany(AsramaJabatan::class)->orderBy('tahun', 'desc');
    }

    public function jabatanTahunIni()
    {
        return $this->hasOne(AsramaJabatan::class)->where('tahun', now()->year);
    }
}
