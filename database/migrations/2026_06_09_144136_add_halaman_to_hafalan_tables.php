<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('hafalan_logs', function (Blueprint $table) {
            $table->unsignedSmallInteger('halaman_start')->nullable()->after('ayat_end');
            $table->unsignedSmallInteger('halaman_end')->nullable()->after('halaman_start');
        });

        Schema::table('hafalans', function (Blueprint $table) {
            $table->unsignedSmallInteger('current_page')->nullable()->after('current_ayat');
        });
    }

    public function down(): void
    {
        Schema::table('hafalan_logs', function (Blueprint $table) {
            $table->dropColumn(['halaman_start', 'halaman_end']);
        });

        Schema::table('hafalans', function (Blueprint $table) {
            $table->dropColumn('current_page');
        });
    }
};
