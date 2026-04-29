<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlumniOrganisasi extends Model
{
    protected $table = 'alumni_organization';
	protected $primaryKey = 'id';

    protected $fillable = [
        'id_alumni',
        'tipe',
        'nama',
        'organisasi_jabatan',
        'tahun_organisasi'
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
