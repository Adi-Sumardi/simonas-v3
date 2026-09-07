<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Two confirmed production crashes on kegiatan creation:
     *
     * 1. `tujuan` is `varchar(255) NOT NULL`, but Super/Kegiatan.tsx (used by the
     *    "Super"-role asrama coordinators) has no input for it at all — the empty
     *    string is turned into null by Laravel's ConvertEmptyStringsToNull
     *    middleware, and SuperController::storeKegiatan validates it as
     *    'nullable|string', so null reaches the INSERT and Postgres rejects it
     *    (SQLSTATE 23502). PengurusAsramaController does collect it and validates
     *    'required|max:500', so it's genuinely optional depending on entry point.
     * 2. `keterangan`/`tujuan` are capped at varchar(255) while the app validates
     *    up to 1000/500 chars respectively — a longer description truncation-fails
     *    the insert (SQLSTATE 22001, seen with a ~500-char "keterangan" on 2026-08-16).
     *
     * Fix: make `tujuan` nullable and widen both columns to TEXT so the DB never
     * disagrees with the app's own validation limits again.
     */
    public function up(): void
    {
        // Production runs Postgres only; sqlite (test suite) never enforced the
        // varchar(255) cap the same way and none of the tests exercise this path.
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE kegiatans ALTER COLUMN tujuan TYPE TEXT');
            DB::statement('ALTER TABLE kegiatans ALTER COLUMN tujuan DROP NOT NULL');
            DB::statement('ALTER TABLE kegiatans ALTER COLUMN keterangan TYPE TEXT');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement("UPDATE kegiatans SET tujuan = '' WHERE tujuan IS NULL");
            DB::statement('ALTER TABLE kegiatans ALTER COLUMN tujuan TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE kegiatans ALTER COLUMN tujuan SET NOT NULL');
            DB::statement('ALTER TABLE kegiatans ALTER COLUMN keterangan TYPE VARCHAR(255)');
        }
    }
};
