<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KomponenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Data diambil dari tabel `komponens` sistem lama (database/migration-data/komponens.sql).
     * ID dipertahankan sama persis supaya `komponen_id` pada data akademiks/leaderships/
     * karakters/kreatifs hasil migrasi dari sistem lama langsung nyambung tanpa remapping.
     */
    public function run(): void
    {
        $data = [
            ['id' => 1, 'kode' => '1001', 'nama_komponen' => 'Mendapatkan nilai (prestasi) akademik', 'aspek' => 'Akademik'],
            ['id' => 2, 'kode' => '1002', 'nama_komponen' => 'Mengikuti kegiatan mentoring', 'aspek' => 'Akademik'],
            ['id' => 3, 'kode' => '1003', 'nama_komponen' => 'Mengikuti forum akademik', 'aspek' => 'Akademik'],
            ['id' => 4, 'kode' => '1004', 'nama_komponen' => 'Membaca buku atau artikel dll', 'aspek' => 'Akademik'],
            ['id' => 5, 'kode' => '1005', 'nama_komponen' => 'Memanfaatkan TIK untuk pengembangan diri', 'aspek' => 'Akademik'],
            ['id' => 6, 'kode' => '1006', 'nama_komponen' => 'Menulis makalah, artikel dll', 'aspek' => 'Akademik'],
            ['id' => 7, 'kode' => '1007', 'nama_komponen' => 'Menyampaikan gagasan, presentasi, moderator', 'aspek' => 'Akademik'],
            ['id' => 8, 'kode' => '1008', 'nama_komponen' => 'Memberikan kontribusi (mengajar, melatih,membimbing)', 'aspek' => 'Akademik'],
            ['id' => 9, 'kode' => '2001', 'nama_komponen' => 'Mengikuti pelatihan kepemimpinan', 'aspek' => 'Leadership'],
            ['id' => 10, 'kode' => '2002', 'nama_komponen' => 'Mengikuti kegiatan mentoring', 'aspek' => 'Leadership'],
            ['id' => 11, 'kode' => '2003', 'nama_komponen' => 'Melaksanakan tugas kepanitiaan (mandat)', 'aspek' => 'Leadership'],
            ['id' => 12, 'kode' => '2004', 'nama_komponen' => 'Melakukan tugas sebagai pengurus organisasi', 'aspek' => 'Leadership'],
            ['id' => 13, 'kode' => '2005', 'nama_komponen' => 'Menjadi peserta atau memimpin rapat', 'aspek' => 'Leadership'],
            ['id' => 14, 'kode' => '2006', 'nama_komponen' => 'Mengikuti diskusi atau debat penyelesaian masalah', 'aspek' => 'Leadership'],
            ['id' => 15, 'kode' => '2007', 'nama_komponen' => 'Menulis surat, proposal kegiatan, laporan dll', 'aspek' => 'Leadership'],
            ['id' => 16, 'kode' => '2008', 'nama_komponen' => 'Memberikan kontribusi baik harta, tenaga, waktu', 'aspek' => 'Leadership'],
            ['id' => 17, 'kode' => '2009', 'nama_komponen' => 'Menyampaikan gagasan baik lisan atau tulisan', 'aspek' => 'Leadership'],
            ['id' => 18, 'kode' => '3001', 'nama_komponen' => 'Membaca Al Quran, hafalan, hadits pilihan', 'aspek' => 'Karakter Islami'],
            ['id' => 19, 'kode' => '3002', 'nama_komponen' => 'Mengikuti kegiatan mentoring', 'aspek' => 'Karakter Islami'],
            ['id' => 20, 'kode' => '3003', 'nama_komponen' => 'Mengikuti kajian, membaca buku atau ceramah agama', 'aspek' => 'Karakter Islami'],
            ['id' => 21, 'kode' => '3004', 'nama_komponen' => 'Menjadi imam shalat jamaah atau memimpin doa', 'aspek' => 'Karakter Islami'],
            ['id' => 22, 'kode' => '3005', 'nama_komponen' => 'Mengamalkan ibadah harian; shalat, puasa, zakat, dll', 'aspek' => 'Karakter Islami'],
            ['id' => 23, 'kode' => '3006', 'nama_komponen' => 'Menyampaikan dakwah, kultum, baik lisan, tulisan', 'aspek' => 'Karakter Islami'],
            ['id' => 24, 'kode' => '3007', 'nama_komponen' => 'Memelihara kebersihan (kamar, lingkungan, dll)', 'aspek' => 'Karakter Islami'],
            ['id' => 25, 'kode' => '3008', 'nama_komponen' => 'Mengajar pengajian, TPA, TPQ, dll', 'aspek' => 'Karakter Islami'],
            ['id' => 26, 'kode' => '3009', 'nama_komponen' => 'Memelihara silaturahmi dan menolong sesama', 'aspek' => 'Karakter Islami'],
            ['id' => 27, 'kode' => '4001', 'nama_komponen' => 'Mengikuti pelatihan kreativitas dan kewirausahaan', 'aspek' => 'Kreativitas & Kewirausahaan'],
            ['id' => 28, 'kode' => '4002', 'nama_komponen' => 'Mengikuti kegiatan mentoring', 'aspek' => 'Kreativitas & Kewirausahaan'],
            ['id' => 29, 'kode' => '4003', 'nama_komponen' => 'Membaca buku, majalah, internet dll terkait kewirausahaan', 'aspek' => 'Kreativitas & Kewirausahaan'],
            ['id' => 30, 'kode' => '4004', 'nama_komponen' => 'Mengikuti forum ceramah atau diskusi kewirausahaan', 'aspek' => 'Kreativitas & Kewirausahaan'],
            ['id' => 31, 'kode' => '4005', 'nama_komponen' => 'Melakukan tugas dalam kegiatan usaha asrama', 'aspek' => 'Kreativitas & Kewirausahaan'],
            ['id' => 32, 'kode' => '4006', 'nama_komponen' => 'Menulis proposal usaha', 'aspek' => 'Kreativitas & Kewirausahaan'],
            ['id' => 33, 'kode' => '4007', 'nama_komponen' => 'Menghasilkan karya kreatif (video, grafis, dll)', 'aspek' => 'Kreativitas & Kewirausahaan'],
            ['id' => 34, 'kode' => '4008', 'nama_komponen' => 'Memiliki keberanian untuk memulai usaha', 'aspek' => 'Kreativitas & Kewirausahaan'],
        ];

        $now = now();
        foreach ($data as &$item) {
            $item['created_at'] = $now;
            $item['updated_at'] = $now;
        }
        unset($item);

        DB::table('komponens')->upsert($data, ['id'], ['kode', 'nama_komponen', 'aspek', 'updated_at']);
        DB::statement("SELECT setval(pg_get_serial_sequence('komponens','id'), COALESCE(MAX(id),0)+1, false) FROM komponens");
    }
}
