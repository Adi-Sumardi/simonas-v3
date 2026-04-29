<?php

namespace App;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use Notifiable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'captcha', 
        'avatar', 
        'name', 
        'email', 
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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
