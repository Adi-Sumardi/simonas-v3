<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('live_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('users')->onDelete('cascade');
            $table->string('room_name');
            $table->boolean('is_active')->default(true);
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_meetings');
    }
};
