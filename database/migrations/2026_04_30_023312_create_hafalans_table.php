<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hafalans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('target_juz')->default(30);
            $table->unsignedTinyInteger('current_juz')->default(0);
            $table->unsignedSmallInteger('current_ayah')->default(0);
            $table->unsignedInteger('total_ayah_completed')->default(0);
            $table->unsignedInteger('streak_days')->default(0);
            $table->timestamp('last_tasmi_at')->nullable();
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hafalans');
    }
};
