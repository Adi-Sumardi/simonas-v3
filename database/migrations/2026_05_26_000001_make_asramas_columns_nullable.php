<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('asramas', function (Blueprint $table) {
            $table->string('direktur')->nullable()->change();
            $table->string('ketua')->nullable()->change();
            $table->string('tahun_jabatan')->nullable()->default(null)->change();
        });
    }
    public function down(): void {}
};
