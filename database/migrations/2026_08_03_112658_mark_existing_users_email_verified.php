<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Email verification baru diaktifkan sekarang — tandai semua user yang
     * sudah ada (hasil migrasi data lama + yang sudah dipakai di production)
     * sebagai sudah terverifikasi, supaya tidak ada yang tiba-tiba terkunci
     * dari aplikasinya sendiri. Hanya user BARU yang daftar setelah ini yang
     * wajib verifikasi email.
     */
    public function up(): void
    {
        DB::table('users')->whereNull('email_verified_at')->update([
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Sengaja tidak di-unverify balik — tidak ada cara aman untuk tau
        // mana yang "asli" belum verified vs yang di-backfill migrasi ini.
    }
};
