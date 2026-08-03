<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\Regency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    // Provinsi Papua baru (pemekaran 2022) belum ada di dataset sumber (masih
    // gabung ke Papua/Papua Barat lama) — ditambahkan manual, tanpa rincian
    // kabupaten/kota dulu (bisa ditambah belakangan kalau ada warga dari sana).
    private const PROVINSI_BARU = [
        'PAPUA SELATAN',
        'PAPUA TENGAH',
        'PAPUA PEGUNUNGAN',
        'PAPUA BARAT DAYA',
    ];

    public function run(): void
    {
        if (Province::count() > 0) {
            $this->command->info('Wilayah sudah ter-seed, skip.');
            return;
        }

        $provinceIdMap = []; // csv id => db id

        $provincesCsv = database_path('seeders/data/wilayah_provinces.csv');
        foreach (array_filter(explode("\n", trim(file_get_contents($provincesCsv)))) as $line) {
            [$csvId, $name] = explode(',', $line, 2);
            $province = Province::create(['name' => trim($name)]);
            $provinceIdMap[$csvId] = $province->id;
        }

        foreach (self::PROVINSI_BARU as $name) {
            Province::create(['name' => $name]);
        }

        $regenciesCsv = database_path('seeders/data/wilayah_regencies.csv');
        $rows = [];
        foreach (array_filter(explode("\n", trim(file_get_contents($regenciesCsv)))) as $line) {
            [$csvId, $csvProvinceId, $name] = explode(',', $line, 3);
            if (!isset($provinceIdMap[$csvProvinceId])) {
                continue;
            }
            $rows[] = [
                'province_id' => $provinceIdMap[$csvProvinceId],
                'name'        => trim($name),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('regencies')->insert($chunk);
        }

        $this->command->info('Wilayah: ' . Province::count() . ' provinsi, ' . Regency::count() . ' kabupaten/kota.');
    }
}
