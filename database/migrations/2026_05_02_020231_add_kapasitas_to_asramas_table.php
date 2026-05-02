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
        Schema::table('asramas', function (Blueprint $table) {
            $table->integer('kapasitas')->default(40)->after('nama_asrama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asramas', function (Blueprint $table) {
            $table->dropColumn('kapasitas');
        });
    }
};
