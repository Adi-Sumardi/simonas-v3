<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komponen_penilaian_aspek', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->unique(); // akademik|leadership|karakter_islami|kreatifitas
            $table->string('nama_aspek', 100);
            $table->unsignedTinyInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komponen_penilaian_aspek');
    }
};
