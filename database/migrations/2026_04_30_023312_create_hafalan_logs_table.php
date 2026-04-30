<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hafalan_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mentor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('surah');
            $table->unsignedSmallInteger('ayat_start');
            $table->unsignedSmallInteger('ayat_end');
            $table->enum('score', ['memtas', 'layak_ulang', 'perlu_perbaikan', 'pending'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('tested_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'tested_at']);
            $table->index(['mentor_id', 'score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hafalan_logs');
    }
};
