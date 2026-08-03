<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('semester')->nullable()->after('angkatan');
            $table->timestamp('onboarding_completed_at')->nullable()->after('last_login_at');
            $table->date('tgl_mulai_percobaan')->nullable()->after('onboarding_completed_at');
        });

        // Aplikasi ini baru — semua akun (mahasiswa, mentor, super, alumni) tetap harus
        // lewat wizard onboarding, tidak ada yang di-backfill selesai.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['semester', 'onboarding_completed_at', 'tgl_mulai_percobaan']);
        });
    }
};
