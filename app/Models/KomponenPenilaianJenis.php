<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KomponenPenilaianJenis extends Model
{
    protected $table = 'komponen_penilaian_jenis';

    protected $fillable = [
        'sub_aspek_id',
        'nama_kegiatan',
        'urutan',
        // Internal
        'poin_a', 'poin_p', 'poin_f', 'poin_u',
        // Eksternal
        'poin_w', 'poin_n', 'poin_i',
        'keterangan_bukti',
    ];

    protected $casts = [
        'sub_aspek_id'  => 'integer',
        'urutan'        => 'integer',
        'poin_a'        => 'integer',
        'poin_p'        => 'integer',
        'poin_f'        => 'integer',
        'poin_u'        => 'integer',
        'poin_w'        => 'integer',
        'poin_n'        => 'integer',
        'poin_i'        => 'integer',
    ];

    // Level codes untuk dropdown Cakupan
    public const LEVEL_LABELS = [
        'a' => 'Asrama (A)',
        'p' => 'Prodi (P)',
        'f' => 'Fakultas (F)',
        'u' => 'Universitas (U)',
        'w' => 'Wilayah/JABODETABEK (W)',
        'n' => 'Nasional (N)',
        'i' => 'Internasional (I)',
    ];

    public function subAspek(): BelongsTo
    {
        return $this->belongsTo(KomponenPenilaianSubAspek::class, 'sub_aspek_id');
    }

    /**
     * Ambil poin untuk level tertentu (a/p/f/u/w/n/i).
     * Return null jika level tidak relevan untuk jenis kegiatan ini.
     */
    public function getPoinByLevel(string $level): ?int
    {
        $col = 'poin_' . strtolower($level);
        return $this->$col ?? null;
    }

    /**
     * Daftar level yang tersedia (non-null) untuk dropdown Cakupan/Level.
     * Return array: ['a' => 4, 'u' => 8, ...]
     */
    public function getAvailableLevels(): array
    {
        $result = [];
        foreach (array_keys(self::LEVEL_LABELS) as $lvl) {
            $val = $this->getPoinByLevel($lvl);
            if ($val !== null) {
                $result[$lvl] = $val;
            }
        }
        return $result;
    }
}
