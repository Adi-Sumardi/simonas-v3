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
        $tables = ['akademiks', 'leaderships', 'karakters', 'kreatifs'];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('tipe_kegiatan')->nullable()->after('kegiatan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['akademiks', 'leaderships', 'karakters', 'kreatifs'];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('tipe_kegiatan');
            });
        }
    }
};
