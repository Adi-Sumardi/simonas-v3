<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KomponenPenilaianAspek extends Model
{
    protected $table = 'komponen_penilaian_aspek';

    protected $fillable = ['kode', 'nama_aspek', 'urutan'];

    protected $casts = ['urutan' => 'integer'];

    // 4 Aspek tetap — di-seed, tidak bisa ditambah/hapus dari UI
    public const ASPEK_LIST = [
        'akademik'        => 'Akademik',
        'leadership'      => 'Leadership',
        'karakter_islami' => 'Karakter Islami',
        'kreatifitas'     => 'Kreatifitas',
    ];

    public const ASPEK_ICON = [
        'akademik'        => '📚',
        'leadership'      => '👑',
        'karakter_islami' => '🕌',
        'kreatifitas'     => '🎨',
    ];

    public function subAspeks(): HasMany
    {
        return $this->hasMany(KomponenPenilaianSubAspek::class, 'aspek_id')
                    ->orderBy('urutan');
    }
}
