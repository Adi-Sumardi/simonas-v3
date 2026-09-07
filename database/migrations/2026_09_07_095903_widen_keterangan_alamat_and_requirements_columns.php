<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Same class of bug as the kegiatans.tujuan/keterangan fix (see migration
     * 2026_09_07_093158): these columns are still varchar(255) while the app
     * validates longer input, so a normal-length submission truncation-fails
     * the insert with SQLSTATE 22001. Found by auditing every `max:` validation
     * rule above 255 against its actual column width.
     *
     * - akademiks/leaderships/karakters/kreatifs.keterangan: validated up to 1000
     *   chars in Mahasiswa\AktivitasController::store/update — this is the core
     *   "Log Aktivitas" flow every warga uses daily, so any keterangan over 255
     *   chars (a couple of sentences) currently fails to save.
     * - users.alamat: validated up to 500 chars and REQUIRED in
     *   OnboardingController::store — a verbose Indonesian address (RT/RW/
     *   kelurahan/kecamatan) easily exceeds 255 chars, which would permanently
     *   strand a brand-new user at onboarding since the field can't be skipped.
     * - alumni_jobs.requirements: validated up to 1000 chars in
     *   AlumniHubController::storeJob.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            foreach (['akademiks', 'leaderships', 'karakters', 'kreatifs'] as $table) {
                DB::statement("ALTER TABLE {$table} ALTER COLUMN keterangan TYPE TEXT");
            }
            DB::statement('ALTER TABLE users ALTER COLUMN alamat TYPE TEXT');
            DB::statement('ALTER TABLE alumni_jobs ALTER COLUMN requirements TYPE TEXT');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            foreach (['akademiks', 'leaderships', 'karakters', 'kreatifs'] as $table) {
                DB::statement("ALTER TABLE {$table} ALTER COLUMN keterangan TYPE VARCHAR(255)");
            }
            DB::statement('ALTER TABLE users ALTER COLUMN alamat TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE alumni_jobs ALTER COLUMN requirements TYPE VARCHAR(255)');
        }
    }
};
