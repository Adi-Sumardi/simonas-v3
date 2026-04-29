<?php
namespace App\Exports;
use App\Alumni;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Load data alumni dengan relasi pendidikan, organisasi, dan pekerjaan
        return Alumni::with(['pendidikan', 'organisasi', 'pekerjaan'])->get();
    }

    public function headings(): array
    {
        // Tentukan heading untuk setiap kolom
        return [
            'Nama',
            'Asrama',
            'Tahun Masuk',
            'Tahun Keluar',
            'Jenjang Pendidikan',
            'Perguruan Tinggi',
            'Bidang Pekerjaan',

        ];
    }

    public function map($alumni): array
    {
        $gelarPendidikan = $alumni->pendidikan->pluck('gelar')->implode(', ');
        $gelarNamaKampus = $alumni->pendidikan->pluck('nama_kampus')->implode(', ');
        $namaOrganisasi = $alumni->organisasi->pluck('nama_organisasi')->implode(', ');
        $namaPekerjaan = $alumni->pekerjaan->pluck('bidang_pekerjaan')->implode(', ');

        return [
            $alumni->nama,
            $alumni->id_asrama,
            $alumni->tahun_masuk_asrama,
            $alumni->tahun_keluar_asrama,
            $gelarPendidikan,
            $gelarNamaKampus,
            $namaPekerjaan,
            // optional($alumni->pendidikan->first())->gelar,
            // optional($alumni->organisasi->first())->nama,
            // optional($alumni->organisasi->skip(1)->first())->nama,
            // optional($alumni->pekerjaan->first())->tempat_pekerjaan,
            // optional($alumni->pekerjaan->skip(1)->first())->tempat_pekerjaan,
        ];
    }
}
