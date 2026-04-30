<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword;

    protected $fillable = [
        'captcha',
        'avatar',
        'name',
        'email',
        'google_id',
        'password',
        'role',
        'asrama',
        'status_warga',
        'no_induk',
        'tgl_masuk',
        'tgl_keluar',
        'alamat_sekarang',
        'pekerjaan',
        'universitas',
        'fakultas',
        'prodi',
        'angkatan',
        'tgl_seminar',
        'tgl_skripsi',
        'tgl_wisuda',
        'nik',
        'alamat',
        'provinsi',
        'kota',
        'kecamatan',
        'kode_pos',
        'no_telp',
        'asal_sekolah',
        'tgl_lahir',
        'prestasi',
        'organisasi',
        'nama_ayah',
        'nama_ibu',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopeAlumni($query)
    {
        return $query->where('role', 'alumni');
    }

    public function ipks()
    {
        return $this->hasMany(Ipk::class);
    }

    public function akademiks()
    {
        return $this->hasMany(Akademik::class);
    }

    public function leaderships()
    {
        return $this->hasMany(Leadership::class);
    }

    public function karakters()
    {
        return $this->hasMany(Karakter::class);
    }

    public function kreatifs()
    {
        return $this->hasMany(Kreatif::class);
    }
}
