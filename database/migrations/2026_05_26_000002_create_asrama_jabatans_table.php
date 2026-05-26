<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asrama_jabatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asrama_id')->constrained('asramas')->onDelete('cascade');
            $table->year('tahun');
            $table->string('direktur')->nullable();
            $table->string('ketua')->nullable();
            $table->timestamps();
            $table->unique(['asrama_id', 'tahun']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('asrama_jabatans');
    }
};
