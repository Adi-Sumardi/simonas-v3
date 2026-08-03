<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = ['akademiks', 'leaderships', 'karakters', 'kreatifs'];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('sub_aspek_id')->nullable()->constrained('komponen_penilaian_sub_aspek')->nullOnDelete();
                $table->foreignId('jenis_kegiatan_id')->nullable()->constrained('komponen_penilaian_jenis')->nullOnDelete();
                $table->string('level_kegiatan', 10)->nullable(); // a, p, f, u, w, n, i
                $table->integer('poin')->default(0);
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign([$tableName . '_sub_aspek_id_foreign']);
                $table->dropForeign([$tableName . '_jenis_kegiatan_id_foreign']);
                $table->dropColumn(['sub_aspek_id', 'jenis_kegiatan_id', 'level_kegiatan', 'poin']);
            });
        }
    }
};
