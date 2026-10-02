<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mentor_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('mentee_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('spiritual');
            $table->unsignedTinyInteger('community');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['mentee_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentor_evaluations');
    }
};
