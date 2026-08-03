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
            $table->string('tingkat_keanggotaan', 20)->default('percobaan')->after('status_warga');
        });

        // Warga lama dianggap "tetap" secara default (bukan percobaan) supaya tidak
        // salah kategori — admin bisa sesuaikan manual lewat menu Warga.
        \Illuminate\Support\Facades\DB::table('users')
            ->where('role', 'mahasiswa')
            ->update(['tingkat_keanggotaan' => 'tetap']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tingkat_keanggotaan');
        });
    }
};
