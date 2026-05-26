<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default values
        $defaults = [
            'shalat_target'     => '5',
            'hafalan_target'    => '30',
            'study_hour_target' => '4',
            'point_shalat'      => '10',
            'point_hafalan'     => '25',
            'point_akademik'    => '15',
            'point_kegiatan'    => '20',
        ];

        foreach ($defaults as $key => $value) {
            DB::table('app_settings')->insert([
                'key'        => $key,
                'value'      => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
