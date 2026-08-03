<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KomponenPenilaianSubAspek extends Model
{
    protected $table = 'komponen_penilaian_sub_aspek';

    protected $fillable = ['aspek_id', 'nama_sub_aspek', 'urutan'];

    protected $casts = [
        'aspek_id' => 'integer',
        'urutan'   => 'integer',
    ];

    public function aspek(): BelongsTo
    {
        return $this->belongsTo(KomponenPenilaianAspek::class, 'aspek_id');
    }

    public function jenisKegiatans(): HasMany
    {
        return $this->hasMany(KomponenPenilaianJenis::class, 'sub_aspek_id')
                    ->orderBy('urutan');
    }
}
