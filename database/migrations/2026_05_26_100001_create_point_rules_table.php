<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_rules', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('activity_type'); // shalat|hafalan|kegiatan|akademik|leadership|karakter|kreatif
            $table->integer('poin')->default(0);
            $table->string('unit')->nullable()->default('per aktivitas');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('point_rules')->insert([
            ['label' => 'Shalat Wajib',        'activity_type' => 'shalat',     'poin' => 10, 'unit' => 'per waktu shalat',       'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['label' => 'Setoran Hafalan',      'activity_type' => 'hafalan',    'poin' => 25, 'unit' => 'per setoran disetujui',   'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['label' => 'Aktivitas Akademik',   'activity_type' => 'akademik',   'poin' => 15, 'unit' => 'per aktivitas',           'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['label' => 'Kehadiran Kegiatan',   'activity_type' => 'kegiatan',   'poin' => 20, 'unit' => 'per kegiatan dihadiri',   'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('point_rules');
    }
};
