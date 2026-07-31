<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komponen extends Model
{
    protected $fillable = [
        'kode',
        'nama_komponen',
        'bobot',
        'aspek',
    ];

    protected $casts = [
        'bobot' => 'integer',
    ];

    public const ASPEK = [
        'Akademik',
        'Leadership',
        'Karakter Islami',
        'Kreativitas & Kewirausahaan',
    ];

    public function akademik(){
        return $this->hasMany(Akademik::class);
    }
}
