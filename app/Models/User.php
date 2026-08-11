<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword, HasRoles;

    protected $fillable = [
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
        'semester',
        'onboarding_completed_at',
        'tgl_mulai_percobaan',
        'tgl_akhir_percobaan',
        'tingkat_keanggotaan',
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
        $rules = \App\Models\PointRule::activeRules();

        // Fallback to AppSetting if table not yet migrated
        if ($rules->isEmpty()) {
            return $this->calculatePointsLegacy();
        }

        $total = 0;
        foreach ($rules as $rule) {
            $total += $this->countByActivityType($rule->activity_type) * $rule->poin;
        }

        $total -= $this->kegiatanPoinDeduction();

        return max(0, $total);
    }

    /**
     * Total poin yang dipotong dari alpa pada kegiatan asrama wajib_absen (lihat
     * PengurusAsramaController::storeKegiatanAttendance, di mana poin_deduction diisi).
     */
    public function kegiatanPoinDeduction(?string $from = null, ?string $to = null): int
    {
        $query = \App\Models\KegiatanAttendance::where('user_id', $this->id)
            ->where('status', 'alpa');

        if ($from && $to) {
            $query->whereBetween('waktu_absen', [$from, $to]);
        }

        return (int) $query->sum('poin_deduction');
    }

    private function countByActivityType(string $type, ?string $from = null, ?string $to = null): int
    {
        $range = fn ($query) => ($from && $to) ? $query->whereBetween('created_at', [$from, $to]) : $query;

        return match ($type) {
            'shalat' => (int) $range(\App\Models\UserEvent::where('user_id', $this->id)->where('type', 'shalat'))
                ->get()
                ->sum(fn ($e) => count($e->completed_at_dates ?? [])),

            'hafalan' => $range(\App\Models\HafalanLog::where('user_id', $this->id)->where('score', 'memtas'))
                ->count(),

            'kegiatan' => (int) $range(\App\Models\UserEvent::where('user_id', $this->id)->where('type', 'kegiatan'))
                ->get()
                ->sum(fn ($e) => count($e->completed_at_dates ?? [])),

            'akademik'   => $this->sumKomponenBobot('akademiks', $from, $to),
            'leadership' => $this->sumKomponenBobot('leaderships', $from, $to),
            'karakter'   => $this->sumKomponenBobot('karakters', $from, $to),
            'kreatif'    => $this->sumKomponenBobot('kreatifs', $from, $to),

            default => 0,
        };
    }

    /**
     * Jumlah bobot komponen (dari tabel `komponens`) untuk semua baris user di
     * $table (akademiks/leaderships/karakters/kreatifs). Baris tanpa komponen_id
     * (atau yang komponennya sudah dihapus) dihitung bobot 1 supaya konsisten
     * dengan hitungan lama (count per aktivitas).
     */
    private function sumKomponenBobot(string $table, ?string $from, ?string $to): int
    {
        $query = DB::table($table)
            ->leftJoin('komponens', 'komponens.id', '=', "{$table}.komponen_id")
            ->where("{$table}.user_id", $this->id);

        if ($from && $to) {
            $query->whereBetween("{$table}.created_at", [$from, $to]);
        }

        return (int) $query->sum(DB::raw('COALESCE(komponens.bobot, 1)'));
    }

    /**
     * 6 dimensi penilaian dinormalisasi ke skala 0-100 (20 aktivitas = 100),
     * dipakai di radar chart Dashboard super-admin dan halaman detail warga
     * supaya kedua tempat selalu menampilkan angka yang identik. $from/$to
     * (format Y-m-d) membatasi rentang tanggal aktivitas yang dihitung.
     */
    public function radarScores(?string $from = null, ?string $to = null): array
    {
        $norm = fn (int $v) => min(100, (int) round($v * 5));

        return [
            ['subject' => 'Shalat',       'value' => $norm($this->countByActivityType('shalat', $from, $to)),     'fullMark' => 100],
            ['subject' => 'Akademik',     'value' => $norm($this->countByActivityType('akademik', $from, $to)),   'fullMark' => 100],
            ['subject' => 'Hafalan',      'value' => $norm($this->countByActivityType('hafalan', $from, $to)),    'fullMark' => 100],
            ['subject' => 'Kepemimpinan', 'value' => $norm($this->countByActivityType('leadership', $from, $to)), 'fullMark' => 100],
            ['subject' => 'Karakter',     'value' => $norm($this->countByActivityType('karakter', $from, $to)),   'fullMark' => 100],
            ['subject' => 'Kreativitas',  'value' => $norm($this->countByActivityType('kreatif', $from, $to)),    'fullMark' => 100],
        ];
    }

    private function calculatePointsLegacy(): int
    {
        $pS = (int) \App\Models\AppSetting::val('point_shalat', 10);
        $pH = (int) \App\Models\AppSetting::val('point_hafalan', 25);
        $pA = (int) \App\Models\AppSetting::val('point_akademik', 15);
        $pK = (int) \App\Models\AppSetting::val('point_kegiatan', 20);

        $shalatPts = (int) \App\Models\UserEvent::where('user_id', $this->id)->where('type', 'shalat')
            ->get()->sum(fn ($e) => count($e->completed_at_dates ?? [])) * $pS;

        $hafalanPts = \App\Models\HafalanLog::where('user_id', $this->id)->where('score', 'memtas')->count() * $pH;

        $actCount = array_sum(array_map(
            fn ($m) => $m::where('user_id', $this->id)->count(),
            [\App\Models\Akademik::class, \App\Models\Leadership::class, \App\Models\Karakter::class, \App\Models\Kreatif::class]
        ));

        $kegiatanPts = (int) \App\Models\UserEvent::where('user_id', $this->id)->where('type', 'kegiatan')
            ->get()->sum(fn ($e) => count($e->completed_at_dates ?? [])) * $pK;

        return $shalatPts + $hafalanPts + ($actCount * $pA) + $kegiatanPts;
    }

    public function scopeAlumni($query)
    {
        return $query->where('role', 'alumni');
    }

    public function ipks()
    {
        return $this->hasMany(Ipk::class);
    }

    public function hafalan()
    {
        return $this->hasOne(Hafalan::class);
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
