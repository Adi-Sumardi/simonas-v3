<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Komponen extends Model
{
    protected $fillable = [
        'kode', 
        'nama_komponen', 
        'aspek', 
    ];

    public function akademik(){
        return $this->hasMany(Akademik::class);
    }
}
