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
        Schema::table('kegiatan_attendances', function (Blueprint $table) {
            // hadir, izin, sakit, alpa, haid (haid hanya berlaku untuk asrama putri)
            $table->string('status')->default('hadir')->after('user_id');
            $table->string('keterangan')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatan_attendances', function (Blueprint $table) {
            $table->dropColumn(['status', 'keterangan']);
        });
    }
};
