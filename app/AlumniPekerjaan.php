<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlumniPekerjaan extends Model
{
    protected $table = 'alumni_job_history';
	protected $primaryKey = 'id';

    protected $fillable = [
        'id_alumni',
        'tempat_pekerjaan',
        'tahun_kerjaan',
        'bidang_pekerjaan',
    ];

    protected $hidden = ['created_at'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = Date("Y-m-d H:i:s");
            return true;
        });
    }
}
