<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    protected $fillable = [
        'file',
        'name',
        'email',
        'password',
        'role',
        'asrama',
        'tgl_masuk',
        'tgl_keluar',
        'universitas',
        'fakultas',
        'prodi',
        'angkatan',
        'tgl_seminar',
        'tgl_skripsi',
        'tgl_wisuda',
        'nik',
        'alamat',
        'no_telp',
        'asal_sekolah',
        'tgl_lahir',
        'prestasi',
        'organisasi',
        'nama_ayah',
        'nama_ibu',
    ];
}
