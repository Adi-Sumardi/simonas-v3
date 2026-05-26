<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword, HasRoles;

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
        'mentor_id',
        'bio',
        'no_hp',
        'nim',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * Always return full URL for avatar
     */
    public function getAvatarAttribute($value)
    {
        if (!$value) return null;
        if (filter_var($value, FILTER_VALIDATE_URL)) return $value;
        return asset('storage/' . $value);
    }

    public function calculatePoints(): int
    {
        $pointShalat   = (int) \App\Models\AppSetting::val('point_shalat', 10);
        $pointHafalan  = (int) \App\Models\AppSetting::val('point_hafalan', 25);
        $pointAkademik = (int) \App\Models\AppSetting::val('point_akademik', 15);
        $pointKegiatan = (int) \App\Models\AppSetting::val('point_kegiatan', 20);

        // 1. Shalat points (all-time completed dates)
        $shalatPoints = 0;
        $shalatEvents = \App\Models\UserEvent::where('user_id', $this->id)
            ->where('type', 'shalat')
            ->get();
        foreach ($shalatEvents as $se) {
            $shalatPoints += count($se->completed_at_dates ?? []) * $pointShalat;
        }

        // 2. Hafalan points (all-time approved)
        $hafalanPoints = \App\Models\HafalanLog::where('user_id', $this->id)
            ->where('score', 'memtas')
            ->count() * $pointHafalan;

        // 3. Activity points (all-time)
        $activityCount = 0;
        $models = [
            \App\Models\Akademik::class,
            \App\Models\Leadership::class,
            \App\Models\Karakter::class,
            \App\Models\Kreatif::class,
        ];
        foreach ($models as $m) {
            $activityCount += $m::where('user_id', $this->id)->count();
        }
        $akademikPoints = $activityCount * $pointAkademik;

        // 4. Kegiatan points (all-time completed dates)
        $kegiatanPoints = 0;
        $kegiatanEvents = \App\Models\UserEvent::where('user_id', $this->id)
            ->where('type', 'kegiatan')
            ->get();
        foreach ($kegiatanEvents as $ke) {
            $kegiatanPoints += count($ke->completed_at_dates ?? []) * $pointKegiatan;
        }

        return $shalatPoints + $hafalanPoints + $akademikPoints + $kegiatanPoints;
    }

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

    /**
     * Unified Profil relationships
     */
    public function alumni()
    {
        // Link by email as currently used in controllers
        return $this->hasOne(Alumni::class, 'email', 'email');
    }

    public function profilRiwayats()
    {
        return $this->hasMany(ProfilRiwayat::class, 'user_id', 'id');
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function mentees()
    {
        return $this->hasMany(User::class, 'mentor_id');
    }
}
