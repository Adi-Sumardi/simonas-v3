<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// Aktivitas lama diinput lewat sistem Komponen flat (sebelum Komponen Penilaian
// Baru) tidak pernah punya nilai `poin` — supaya tetap terhitung di leaderboard
// setelah metrik pindah ke SUM(poin), isi `poin` dari bobot komponen lamanya.
return new class extends Migration
{
    private const TABLES = ['akademiks', 'leaderships', 'karakters', 'kreatifs'];

    public function up(): void
    {
        // Postgres tidak dukung UPDATE...JOIN ala MySQL — pakai UPDATE...FROM.
        foreach (self::TABLES as $table) {
            DB::statement("
                UPDATE {$table}
                SET poin = komponens.bobot
                FROM komponens
                WHERE {$table}.komponen_id = komponens.id
                  AND {$table}.jenis_kegiatan_id IS NULL
                  AND (poin IS NULL OR poin = 0)
            ");
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $table) {
            DB::table($table)->whereNull('jenis_kegiatan_id')->update(['poin' => 0]);
        }
    }
};
