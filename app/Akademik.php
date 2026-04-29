<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Akademik extends Model
{
    protected $fillable = [
        'user_id', 
        'komponen_id', 
        'nama_warga', 
        'komponen', 
        'asrama', 
        'kegiatan', 
        'waktu', 
        'tempat', 
        'keterangan', 
        'file', 
        'nama_penilai', 
        'nilai',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function komponen()
    {
        return $this->belongsTo(Komponen::class, 'komponen_id');
    }
}
