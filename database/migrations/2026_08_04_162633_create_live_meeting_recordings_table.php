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
        Schema::create('live_meeting_recordings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('live_meeting_rooms')->cascadeOnDelete();
            $table->string('egress_id')->nullable()->unique();
            $table->string('file_path')->nullable();
            $table->string('status')->default('recording'); // recording, processing, ready, failed
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamp('downloaded_at')->nullable();
            $table->foreignId('downloaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_meeting_recordings');
    }
};
