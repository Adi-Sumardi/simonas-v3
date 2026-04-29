<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlumniPrestasi extends Model
{
    protected $table = 'alumni_achievement';
	protected $primaryKey = 'id';

    protected $fillable = [
        'id_alumni',
        'nama_penghargaan'
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
