<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE kegiatans ALTER COLUMN keterangan DROP NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE kegiatans SET keterangan = '' WHERE keterangan IS NULL");
        DB::statement('ALTER TABLE kegiatans ALTER COLUMN keterangan SET NOT NULL');
    }
};
