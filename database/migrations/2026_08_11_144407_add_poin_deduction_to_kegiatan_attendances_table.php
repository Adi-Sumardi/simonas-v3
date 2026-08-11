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
            // Poin yang dipotong dari warga untuk record ini (biasanya diisi saat status = alpa
            // pada kegiatan wajib_absen yang sudah selesai berlangsung). 0 = tidak ada potongan.
            $table->unsignedInteger('poin_deduction')->default(0)->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatan_attendances', function (Blueprint $table) {
            $table->dropColumn('poin_deduction');
        });
    }
};
