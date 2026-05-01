<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alumni_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['story', 'achievement', 'event', 'question'])->default('story');
            $table->string('title')->nullable();
            $table->text('content');
            $table->string('image_url')->nullable();
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('comments_count')->default(0);
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('alumni_posts'); }
};
