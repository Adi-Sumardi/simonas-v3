<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komponen_penilaian_jenis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_aspek_id')
                  ->constrained('komponen_penilaian_sub_aspek')
                  ->cascadeOnDelete();
            $table->string('nama_kegiatan', 200);
            $table->unsignedSmallInteger('urutan')->default(0);

            // Internal — nullable: null berarti level ini tidak relevan (tampil "–")
            $table->unsignedTinyInteger('poin_a')->nullable(); // Asrama
            $table->unsignedTinyInteger('poin_p')->nullable(); // Prodi
            $table->unsignedTinyInteger('poin_f')->nullable(); // Fakultas
            $table->unsignedTinyInteger('poin_u')->nullable(); // Universitas

            // Eksternal — nullable
            $table->unsignedTinyInteger('poin_w')->nullable(); // Wilayah (JABODETABEK)
            $table->unsignedTinyInteger('poin_n')->nullable(); // Nasional
            $table->unsignedTinyInteger('poin_i')->nullable(); // Internasional

            $table->text('keterangan_bukti')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komponen_penilaian_jenis');
    }
};
