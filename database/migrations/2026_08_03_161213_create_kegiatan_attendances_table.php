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
        Schema::create('kegiatan_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('asrama')->nullable();
            $table->timestamp('waktu_absen');
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();

            // Lokasi saat absen (dipakai buat watermark & validasi, tidak bisa diedit user)
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('alamat')->nullable();

            // Foto selfie (kamera depan) — watermark jam+lokasi sudah dibakar ke gambar sebelum upload
            $table->string('file_selfie')->nullable();
            $table->binary('file_selfie_data')->nullable();
            $table->string('file_selfie_mime', 100)->nullable();
            $table->unsignedInteger('file_selfie_size')->nullable();

            // Foto lokasi/kegiatan (kamera belakang)
            $table->string('file_lokasi')->nullable();
            $table->binary('file_lokasi_data')->nullable();
            $table->string('file_lokasi_mime', 100)->nullable();
            $table->unsignedInteger('file_lokasi_size')->nullable();

            $table->timestamps();

            $table->unique(['kegiatan_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_attendances');
    }
};
