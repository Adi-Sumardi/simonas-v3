<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlumniPendidikan extends Model
{
    protected $table = 'alumni_academic';
	protected $primaryKey = 'id';

    protected $fillable = [
        'id_alumni',
        'gelar',
        'nama_kampus',
        'fakultas_jurusan',
        'tahun_ajaran'
    ];
    protected $attributes = [
        'id_alumni' => 0,
        'gelar'=> '',
        'nama_kampus'=> '',
        'fakultas_jurusan'=> '',
        'tahun_ajaran' => ''
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
