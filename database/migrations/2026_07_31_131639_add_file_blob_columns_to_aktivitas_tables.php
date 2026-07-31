<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    private const TABLES = ['akademiks', 'leaderships', 'karakters', 'kreatifs'];

    public function up(): void
    {
        foreach (self::TABLES as $table) {
            Schema::table($table, function (Blueprint $t) {
                // `file` tetap ada — sekarang isinya nama file asli (bukan path disk).
                $t->binary('file_data')->nullable()->after('file');
                $t->string('file_mime', 100)->nullable()->after('file_data');
                $t->unsignedInteger('file_size')->nullable()->after('file_mime');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (self::TABLES as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn(['file_data', 'file_mime', 'file_size']);
            });
        }
    }
};
