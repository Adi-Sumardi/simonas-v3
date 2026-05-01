<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add reviewer_notes from mentor and reviewed_at timestamp
        Schema::table('hafalan_logs', function (Blueprint $table) {
            $table->text('mentor_notes')->nullable()->after('notes');
            $table->timestamp('reviewed_at')->nullable()->after('tested_at');
        });
    }

    public function down(): void
    {
        Schema::table('hafalan_logs', function (Blueprint $table) {
            $table->dropColumn(['mentor_notes', 'reviewed_at']);
        });
    }
};
