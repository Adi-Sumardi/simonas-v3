<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komponen_penilaian_sub_aspek', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aspek_id')
                  ->constrained('komponen_penilaian_aspek')
                  ->cascadeOnDelete();
            $table->string('nama_sub_aspek', 150);
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komponen_penilaian_sub_aspek');
    }
};
