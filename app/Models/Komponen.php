<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komponen extends Model
{
    protected $table = 'komponens';
    
    protected $fillable = [
        'kode',
        'nama_komponen',
        'aspek'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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
} 