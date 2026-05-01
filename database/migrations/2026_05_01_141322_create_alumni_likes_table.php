<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alumni_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unique(['alumni_post_id', 'user_id']);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('alumni_likes'); }
};
