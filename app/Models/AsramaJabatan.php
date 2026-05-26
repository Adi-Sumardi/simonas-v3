<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsramaJabatan extends Model
{
    protected $fillable = ['asrama_id', 'tahun', 'direktur', 'ketua'];

    public function asrama()
    {
        return $this->belongsTo(Asrama::class);
    }
}
