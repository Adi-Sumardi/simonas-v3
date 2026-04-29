<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    protected $table = 'alumnis';
	protected $primaryKey = 'id';

    protected $fillable = [
        'id_province',
        'id_regency',
        'id_home_province',
        'id_asrama',
        'nama',
        'nia',
        'email',
        'provinsi_asal',
        'tanggal_lahir',
        'alamat_domisili',
        'kode_pos',
        'no_whatsapp',
        'asal_asrama',
        'tahun_masuk_asrama',
        'tahun_keluar_asrama',
        'jabatan_asrama',
        'foto',
        'teman_angkatan',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function pendidikan() {
        return $this->hasMany('App\AlumniPendidikan', 'id_alumni');
    }
    public function organisasi() {
        return $this->hasMany('App\AlumniOrganisasi', 'id_alumni');
    }
    public function pekerjaan() {
        return $this->hasMany('App\AlumniPekerjaan', 'id_alumni');
    }
    public function prestasi() {
        return $this->hasMany('App\AlumniPrestasi', 'id_alumni');
    }
    public function asrama()
    {
        return $this->belongsTo(Asrama::class, 'id_asrama', 'id');
    }
    public function province()
    {
        return $this->belongsTo(Province::class, 'id_province', 'id');
    }
    public function materJob()
    {
        return $this->belongsTo(MasterJob::class, 'materjob_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = Date("Y-m-d H:i:s");
            return true;
        });
        static::updating(function ($model) {
            $model->updated_at = Date("Y-m-d H:i:s");
            return true;
        });

    }
}
