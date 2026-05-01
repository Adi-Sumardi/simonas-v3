<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hafalans', function (Blueprint $table) {
            if (!Schema::hasColumn('hafalans', 'current_surah_nomor')) {
                $table->unsignedSmallInteger('current_surah_nomor')->default(1)->after('current_juz');
            }
            if (!Schema::hasColumn('hafalans', 'current_surah_nama')) {
                $table->string('current_surah_nama', 100)->default('Al-Fatihah')->after('current_surah_nomor');
            }
            if (!Schema::hasColumn('hafalans', 'current_ayat')) {
                $table->unsignedSmallInteger('current_ayat')->default(1)->after('current_surah_nama');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hafalans', function (Blueprint $table) {
            $table->dropColumn(['current_surah_nomor', 'current_surah_nama', 'current_ayat']);
        });
    }
};
