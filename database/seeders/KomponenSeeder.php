<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KomponenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['aspek' => 'Akademik', 'nama_komponen' => 'Indeks Prestasi Kumulatif', 'kode' => 'IPK'],
            ['aspek' => 'Akademik', 'nama_komponen' => 'Seminar / Workshop', 'kode' => 'SEM'],
            ['aspek' => 'Leadership', 'nama_komponen' => 'Ketua Organisasi', 'kode' => 'ORG1'],
            ['aspek' => 'Leadership', 'nama_komponen' => 'Panitia Kegiatan', 'kode' => 'PAN'],
            ['aspek' => 'Karakter Islami', 'nama_komponen' => 'Tahfidz Quran', 'kode' => 'TFZ'],
            ['aspek' => 'Karakter Islami', 'nama_komponen' => 'Kajian Rutin', 'kode' => 'KJN'],
            ['aspek' => 'Kreativitas', 'nama_komponen' => 'Lomba Karya Tulis', 'kode' => 'LKT'],
            ['aspek' => 'Kewirausahaan', 'nama_komponen' => 'Bisnis Mandiri', 'kode' => 'BSN'],
        ];

        foreach ($data as $item) {
            \App\Models\Komponen::updateOrCreate(
                ['kode' => $item['kode']],
                ['aspek' => $item['aspek'], 'nama_komponen' => $item['nama_komponen']]
            );
        }
    }
}
