<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * `kegiatans.asrama` was added after the legacy import and never backfilled,
     * so ~3.1k imported kegiatan have asrama = NULL even though the organizing
     * asrama is recorded verbatim in `penyelenggara`. Effects before this fix:
     * - Mahasiswa\KegiatanController shows NULL-asrama rows to everyone, so each
     *   warga saw every other asrama's old kegiatan.
     * - PengurusAsramaController filters `asrama = <own>`, so pengurus could not
     *   see any of their own asrama's history.
     *
     * Only rows whose penyelenggara exactly matches an asrama name are touched;
     * genuinely global organizers (YAPI, Direktorat Keasramaan, ...) stay NULL,
     * which is the intended "visible to all asrama" meaning.
     */
    public function up(): void
    {
        DB::table('kegiatans')
            ->whereNull('asrama')
            ->whereIn('penyelenggara', DB::table('asramas')->select('nama_asrama'))
            ->update(['asrama' => DB::raw('penyelenggara')]);
    }

    /**
     * Not reversible: after up() there's no way to tell backfilled rows from
     * rows that legitimately had asrama = penyelenggara. Intentionally a no-op.
     */
    public function down(): void
    {
    }
};
