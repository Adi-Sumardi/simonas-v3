<?php

namespace App\Imports;

use App\Alumni;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportAlumni implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Alumni([
            'nama'=> $row[1],
            'email'=> $row[2],
            'gelar_depan'=> $row[3],
            'gelar_belakang'=> $row[4],
            'provinsi_asal'=> $row[5],
            'tanggal_lahir'=> $row[6],
            'alamat_domisili'=> $row[7],
            'alamat_jalan'=> $row[8],
            'kota'=> $row[9],
            'provinsi'=> $row[10],
            'kode_pos'=> $row[11],
            'no_whatsapp'=> $row[12],
            'jumlah_anak'=> $row[13],
            'asal_asrama'=> $row[14],
            'tahun_masuk_asrama'=> $row[15],
            'tahun_keluar_asrama'=> $row[16],
            'pengalaman_organisasi'=> $row[17],
            'pendidikan_terakhir'=> $row[18],
            'jurusan_s1'=> $row[19],
            'kampus_s1'=> $row[20],
            'jurusan_s2'=> $row[21],
            'kampus_s2'=> $row[22],
            'jurusan_s3'=> $row[23],
            'kampus_s3'=> $row[24],
            'pekerjaan_sekarang'=> $row[25],
            'bidang_pekerjaan'=> $row[26],
            'bidang_keahlian'=> $row[27],
            'pengalaman_pekerjaan'=> $row[28],
        ]);
    }
}
