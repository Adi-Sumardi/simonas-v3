<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_targets', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('key')->unique();
            $table->integer('value')->default(0);
            $table->string('unit')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('daily_targets')->insert([
            ['label' => 'Target Shalat',    'key' => 'shalat_target',      'value' => 5,  'unit' => 'waktu / hari',       'description' => 'Jumlah waktu shalat wajib yang harus dicapai per hari',    'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['label' => 'Target Hafalan',   'key' => 'hafalan_target',     'value' => 30, 'unit' => 'juz',                 'description' => 'Target total juz hafalan hingga lulus',                     'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['label' => 'Target Aktivitas', 'key' => 'study_hour_target',  'value' => 23, 'unit' => 'aktivitas / bulan',   'description' => 'Jumlah log aktivitas yang harus dicapai per bulan',         'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_targets');
    }
};
