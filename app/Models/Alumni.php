<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    protected $table = 'users';
    
    protected $fillable = [
        'name', 'email', 'avatar', 'asrama', 'no_induk',
        'tgl_masuk', 'tgl_keluar', 'alamat_sekarang', 'pekerjaan',
        'universitas', 'fakultas', 'prodi', 'angkatan',
        'tgl_seminar', 'tgl_skripsi', 'tgl_wisuda',
        'nik', 'alamat', 'provinsi', 'kota', 'kecamatan',
        'kode_pos', 'no_telp', 'asal_sekolah', 'tgl_lahir',
        'prestasi', 'organisasi', 'nama_ayah', 'nama_ibu'
    ];

    protected $hidden = [
        'password', 'remember_token', 'captcha', 'email_verified_at'
    ];
} 