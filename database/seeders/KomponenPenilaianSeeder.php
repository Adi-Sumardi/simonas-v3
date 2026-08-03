<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KomponenPenilaianAspek;
use App\Models\KomponenPenilaianSubAspek;
use App\Models\KomponenPenilaianJenis;

/**
 * Data poin diambil dari dokumen resmi "Komponen Simonas" (4 PDF: Akademik,
 * Leadership, Keislaman, Kreativitas & Kewirausahaan) di
 * database/migration-data/data aspek/. Kolom poin: A=Asrama, P=Prodi,
 * F=Fakultas, U=Universitas (Internal), W=Wilayah/JABODETABEK, N=Nasional,
 * I=Internasional (Eksternal). Untuk aspek Keislaman, dokumen sumber memakai
 * kolom "Lokal (Asrama/Prodi)" gabungan — nilainya diisi sama ke poin_a & poin_p.
 */
class KomponenPenilaianSeeder extends Seeder
{
    public function run(): void
    {
        KomponenPenilaianJenis::query()->delete();
        KomponenPenilaianSubAspek::query()->delete();
        KomponenPenilaianAspek::query()->delete();

        $data = [

            // ═══════════════════════════════════════════════════════════════
            // [1] AKADEMIK
            // ═══════════════════════════════════════════════════════════════
            [
                'kode' => 'akademik', 'nama_aspek' => 'Akademik', 'urutan' => 1,
                'sub_aspeks' => [
                    [
                        'nama_sub_aspek' => 'Mendapatkan Nilai (Prestasi) Akademik', 'urutan' => 1,
                        'jenis' => [
                            ['nama_kegiatan' => 'Memperoleh IP Semester ≥ 3,00', 'poin_u'=>4, 'keterangan_bukti'=>'KHS/transkrip semester yang dilegalisasi/diunduh dari sistem akademik.'],
                            ['nama_kegiatan' => 'Memperoleh IP Semester ≥ 3,50', 'poin_u'=>6, 'keterangan_bukti'=>'KHS/transkrip semester yang dilegalisasi/diunduh dari sistem akademik.'],
                            ['nama_kegiatan' => 'Menjadi Mahasiswa Berprestasi Tingkat Prodi', 'poin_p'=>6, 'keterangan_bukti'=>'SK/sertifikat/piagam penetapan mahasiswa berprestasi sesuai tingkatnya.'],
                            ['nama_kegiatan' => 'Menjadi Mahasiswa Berprestasi Tingkat Fakultas', 'poin_f'=>7, 'keterangan_bukti'=>'SK/sertifikat/piagam penetapan mahasiswa berprestasi sesuai tingkatnya.'],
                            ['nama_kegiatan' => 'Menjadi Mahasiswa Berprestasi Tingkat Universitas', 'poin_u'=>8, 'keterangan_bukti'=>'SK/sertifikat/piagam penetapan mahasiswa berprestasi sesuai tingkatnya.'],
                            ['nama_kegiatan' => 'Juara Umum, Favorit, Harapan Kompetisi Akademik', 'poin_a'=>4,'poin_p'=>4,'poin_f'=>5,'poin_u'=>5,'poin_w'=>6,'poin_n'=>7,'poin_i'=>8, 'keterangan_bukti'=>'Sertifikat/piagam, surat tugas, dokumentasi lomba, dan bukti peringkat/hasil resmi.'],
                            ['nama_kegiatan' => 'Juara III Kompetisi Akademik', 'poin_a'=>5,'poin_p'=>5,'poin_f'=>6,'poin_u'=>6,'poin_w'=>7,'poin_n'=>8,'poin_i'=>9, 'keterangan_bukti'=>'Sertifikat/piagam, surat tugas, dokumentasi lomba, dan bukti peringkat/hasil resmi.'],
                            ['nama_kegiatan' => 'Juara II Kompetisi Akademik', 'poin_a'=>6,'poin_p'=>6,'poin_f'=>7,'poin_u'=>7,'poin_w'=>8,'poin_n'=>9,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat/piagam, surat tugas, dokumentasi lomba, dan bukti peringkat/hasil resmi.'],
                            ['nama_kegiatan' => 'Juara I Kompetisi Akademik', 'poin_a'=>7,'poin_p'=>7,'poin_f'=>8,'poin_u'=>8,'poin_w'=>9,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat/piagam, surat tugas, dokumentasi lomba, dan bukti peringkat/hasil resmi.'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Mengikuti Kegiatan Mentoring', 'urutan' => 2,
                        'jenis' => [
                            ['nama_kegiatan' => 'Mengikuti Mentoring Akademik', 'poin_a'=>2,'poin_p'=>3,'poin_f'=>4,'poin_u'=>5, 'keterangan_bukti'=>'Kartu/logbook mentoring, daftar hadir, jadwal, dan paraf/validasi mentor/dosen.'],
                            ['nama_kegiatan' => 'Mengikuti Kelompok Belajar Terstruktur', 'poin_a'=>3,'poin_p'=>4,'poin_f'=>4,'poin_u'=>5, 'keterangan_bukti'=>'Kartu/logbook mentoring, daftar hadir, jadwal, dan paraf/validasi mentor/dosen.'],
                            ['nama_kegiatan' => 'Mengikuti Program Pendampingan Skripsi', 'poin_p'=>4,'poin_f'=>5,'poin_u'=>6, 'keterangan_bukti'=>'Dokumen karya, lembar pengesahan/penilaian dosen, atau bukti unggah pada LMS/repository.'],
                            ['nama_kegiatan' => 'Mengikuti Bimbingan Akademik Dosen', 'poin_p'=>2,'poin_f'=>3,'poin_u'=>4, 'keterangan_bukti'=>'Kartu/logbook mentoring, daftar hadir, jadwal, dan paraf/validasi mentor/dosen.'],
                            ['nama_kegiatan' => 'Menjadi Narasumber Mentoring Akademik', 'poin_p'=>6,'poin_f'=>6,'poin_u'=>7,'poin_w'=>7,'poin_n'=>8,'poin_i'=>9, 'keterangan_bukti'=>'Surat undangan/tugas, sertifikat, materi presentasi, dokumentasi, dan daftar hadir.'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Mengikuti Forum Akademik', 'urutan' => 3,
                        'jenis' => [
                            ['nama_kegiatan' => 'Mengikuti Kuliah Umum', 'poin_p'=>2,'poin_f'=>3,'poin_u'=>3,'poin_w'=>4,'poin_n'=>5,'poin_i'=>6, 'keterangan_bukti'=>'Sertifikat/daftar hadir, surat tugas bila ada, dokumentasi, dan ringkasan materi.'],
                            ['nama_kegiatan' => 'Mengikuti Bedah Buku Akademik', 'poin_a'=>3,'poin_p'=>3,'poin_f'=>4,'poin_u'=>4,'poin_w'=>5,'poin_n'=>6,'poin_i'=>7],
                            ['nama_kegiatan' => 'Mengikuti Forum Diskusi Ilmiah', 'poin_a'=>3,'poin_p'=>3,'poin_f'=>3,'poin_u'=>3,'poin_w'=>4,'poin_n'=>5,'poin_i'=>6],
                            ['nama_kegiatan' => 'Mengikuti Pelatihan, Seminar, Webinar, Workshop Akademik', 'poin_p'=>3,'poin_f'=>3,'poin_u'=>4,'poin_w'=>5,'poin_n'=>6,'poin_i'=>7],
                            ['nama_kegiatan' => 'Mengikuti Kompetisi Karya Tulis', 'poin_p'=>4,'poin_f'=>4,'poin_u'=>5,'poin_w'=>6,'poin_n'=>7,'poin_i'=>8],
                            ['nama_kegiatan' => 'Mengikuti Olimpiade Akademik', 'poin_p'=>4,'poin_f'=>5,'poin_u'=>6,'poin_w'=>7,'poin_n'=>8,'poin_i'=>9],
                            ['nama_kegiatan' => 'Menjadi Panitia Forum Akademik', 'poin_a'=>4,'poin_p'=>5,'poin_f'=>5,'poin_u'=>6,'poin_w'=>7,'poin_n'=>8,'poin_i'=>9],
                            ['nama_kegiatan' => 'Menjadi Moderator Forum Akademik', 'poin_p'=>6,'poin_f'=>7,'poin_u'=>7,'poin_w'=>8,'poin_n'=>9,'poin_i'=>10],
                            ['nama_kegiatan' => 'Menjadi Pemateri Forum Akademik', 'poin_a'=>4,'poin_p'=>4,'poin_f'=>5,'poin_u'=>5,'poin_w'=>5,'poin_n'=>8,'poin_i'=>10],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Membaca Buku atau Artikel', 'urutan' => 4,
                        'jenis' => [
                            ['nama_kegiatan' => 'Membaca Buku Referensi Akademik', 'poin_a'=>2,'poin_p'=>2,'poin_f'=>3,'poin_u'=>4,'poin_w'=>5,'poin_n'=>6,'poin_i'=>7],
                            ['nama_kegiatan' => 'Membaca Artikel Ilmiah, Jurnal', 'poin_a'=>3,'poin_p'=>3,'poin_f'=>4,'poin_u'=>5,'poin_w'=>5,'poin_n'=>6,'poin_i'=>7],
                            ['nama_kegiatan' => 'Membuat Resume Buku, Artikel/Jurnal', 'poin_a'=>3,'poin_p'=>3,'poin_f'=>3,'poin_u'=>3,'poin_w'=>4,'poin_n'=>5,'poin_i'=>6],
                            ['nama_kegiatan' => 'Mengikuti Bedah Buku Akademik', 'poin_a'=>3,'poin_p'=>3,'poin_f'=>3,'poin_u'=>3,'poin_w'=>4,'poin_n'=>5,'poin_i'=>6],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Memanfaatkan TIK untuk Pengembangan Diri', 'urutan' => 5,
                        'jenis' => [
                            ['nama_kegiatan' => 'Mengikuti Kursus Online', 'poin_p'=>4,'poin_f'=>4,'poin_u'=>4,'poin_w'=>5,'poin_n'=>6,'poin_i'=>7],
                            ['nama_kegiatan' => 'Menyelesaikan Kelas Online Bersertifikat', 'poin_a'=>3,'poin_p'=>3,'poin_f'=>4,'poin_u'=>5,'poin_w'=>6,'poin_n'=>7],
                            ['nama_kegiatan' => 'Mengikuti Pelatihan Software/AI, Pemrograman, dll', 'poin_a'=>4,'poin_p'=>4,'poin_f'=>4,'poin_u'=>5,'poin_w'=>6,'poin_n'=>7,'poin_i'=>8],
                            ['nama_kegiatan' => 'Membuat Presentasi Akademik', 'poin_a'=>3,'poin_p'=>4,'poin_f'=>5,'poin_u'=>6],
                            ['nama_kegiatan' => 'Mengikuti Sertifikasi Digital', 'poin_p'=>5,'poin_f'=>5,'poin_u'=>5,'poin_w'=>6,'poin_n'=>7,'poin_i'=>8],
                            ['nama_kegiatan' => 'Membuat Portofolio Digital Akademik', 'poin_a'=>5,'poin_p'=>5,'poin_f'=>5,'poin_u'=>5,'poin_w'=>5],
                            ['nama_kegiatan' => 'Mengembangkan Media Pembelajaran Digital', 'poin_a'=>6,'poin_p'=>6,'poin_f'=>6,'poin_u'=>7,'poin_w'=>7,'poin_n'=>8,'poin_i'=>10],
                            ['nama_kegiatan' => 'Menjadi Trainer Teknologi Akademik', 'poin_p'=>7,'poin_f'=>7,'poin_u'=>7,'poin_w'=>8,'poin_n'=>9,'poin_i'=>10],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Menulis Makalah, Artikel, dan Karya Ilmiah', 'urutan' => 6,
                        'jenis' => [
                            ['nama_kegiatan' => 'Menulis Ringkasan Akademik', 'poin_a'=>3,'poin_p'=>3,'poin_f'=>4],
                            ['nama_kegiatan' => 'Menulis Makalah/Laporan', 'poin_a'=>4,'poin_p'=>4,'poin_f'=>5,'poin_u'=>6,'poin_w'=>7,'poin_n'=>9,'poin_i'=>10],
                            ['nama_kegiatan' => 'Menulis Artikel Ilmiah', 'poin_p'=>5,'poin_f'=>5,'poin_u'=>5,'poin_w'=>6,'poin_n'=>7,'poin_i'=>8],
                            ['nama_kegiatan' => 'Menulis Proposal Penelitian', 'poin_u'=>8],
                            ['nama_kegiatan' => 'Menulis Skripsi/Tugas Akhir', 'poin_f'=>10,'poin_u'=>10],
                            ['nama_kegiatan' => 'Menulis Buku atau Modul', 'poin_f'=>8,'poin_u'=>8,'poin_w'=>8,'poin_n'=>9],
                            ['nama_kegiatan' => 'Artikel Dipublikasikan pada Media Kampus', 'poin_f'=>6,'poin_u'=>7,'poin_w'=>8,'poin_n'=>9,'poin_i'=>10],
                            ['nama_kegiatan' => 'Artikel Dipublikasikan pada Media Nasional', 'poin_f'=>6,'poin_u'=>6,'poin_w'=>6,'poin_n'=>7,'poin_i'=>8],
                            ['nama_kegiatan' => 'Artikel Terbit pada Jurnal Nasional', 'poin_n'=>9],
                            ['nama_kegiatan' => 'Artikel Terbit pada Jurnal Internasional', 'poin_i'=>10],
                            ['nama_kegiatan' => 'Menjadi Editor atau Reviewer Karya Ilmiah', 'poin_f'=>6,'poin_u'=>7,'poin_w'=>8,'poin_n'=>9,'poin_i'=>10],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Menyampaikan Gagasan, Presentasi, Moderator', 'urutan' => 7,
                        'jenis' => [
                            ['nama_kegiatan' => 'Menjadi Peserta Presentasi Kelas', 'poin_p'=>2,'poin_f'=>2,'poin_u'=>3,'poin_w'=>4,'poin_n'=>5,'poin_i'=>6],
                            ['nama_kegiatan' => 'Menjadi Presenter Seminar', 'poin_p'=>5,'poin_f'=>5,'poin_u'=>6,'poin_w'=>7,'poin_n'=>8,'poin_i'=>9],
                            ['nama_kegiatan' => 'Menjadi Presenter Konferensi', 'poin_p'=>4,'poin_f'=>4,'poin_u'=>4,'poin_w'=>6,'poin_n'=>7,'poin_i'=>8],
                            ['nama_kegiatan' => 'Menjadi Moderator Diskusi Akademik', 'poin_p'=>6,'poin_f'=>6,'poin_u'=>7,'poin_w'=>8,'poin_n'=>9,'poin_i'=>10],
                            ['nama_kegiatan' => 'Menjadi Moderator Seminar', 'poin_p'=>7,'poin_f'=>7,'poin_u'=>7,'poin_w'=>8,'poin_n'=>9,'poin_i'=>10],
                            ['nama_kegiatan' => 'Menjadi MC Kegiatan Akademik', 'poin_p'=>5,'poin_f'=>6,'poin_u'=>7,'poin_w'=>8],
                            ['nama_kegiatan' => 'Menjadi Pemateri Workshop', 'poin_p'=>5,'poin_f'=>6,'poin_u'=>7],
                            ['nama_kegiatan' => 'Menjadi Narasumber Webinar', 'poin_p'=>6,'poin_f'=>7,'poin_u'=>8],
                            ['nama_kegiatan' => 'Menjadi Juri Kompetisi Akademik', 'poin_p'=>6,'poin_f'=>7,'poin_u'=>8, 'keterangan_bukti'=>'Sertifikat/piagam, surat tugas, dokumentasi lomba, dan bukti peringkat/hasil resmi.'],
                            ['nama_kegiatan' => 'Menjadi Duta Akademik', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9, 'keterangan_bukti'=>'Bukti resmi kegiatan, dokumentasi, daftar hadir, sertifikat, atau validasi dosen/unit terkait.'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Memberikan Kontribusi (Mengajar, Melatih, Membimbing)', 'urutan' => 8,
                        'jenis' => [
                            ['nama_kegiatan' => 'Menjadi Tutor Sebaya', 'poin_p'=>6,'poin_f'=>7,'poin_u'=>8,'poin_w'=>9, 'keterangan_bukti'=>'SK/surat tugas, jadwal kegiatan, daftar peserta, logbook, laporan, dan dokumentasi.'],
                            ['nama_kegiatan' => 'Membimbing Adik Tingkat', 'poin_a'=>7, 'keterangan_bukti'=>'SK/surat tugas, jadwal kegiatan, daftar peserta, logbook, laporan, dan dokumentasi.'],
                            ['nama_kegiatan' => 'Menjadi Asisten Praktikum', 'poin_p'=>7,'poin_f'=>7,'poin_u'=>8,'poin_w'=>9, 'keterangan_bukti'=>'SK/surat tugas, jadwal kegiatan, daftar peserta, logbook, laporan, dan dokumentasi.'],
                            ['nama_kegiatan' => 'Menjadi Asisten Dosen', 'poin_f'=>7,'poin_u'=>7,'poin_w'=>7,'poin_n'=>8,'poin_i'=>9, 'keterangan_bukti'=>'SK/surat tugas, jadwal kegiatan, daftar peserta, logbook, laporan, dan dokumentasi.'],
                            ['nama_kegiatan' => 'Menjadi Pengajar Kelas Pendamping', 'poin_a'=>8,'poin_p'=>8,'poin_f'=>8,'poin_u'=>8,'poin_w'=>9, 'keterangan_bukti'=>'SK/surat tugas, jadwal kegiatan, daftar peserta, logbook, laporan, dan dokumentasi.'],
                            ['nama_kegiatan' => 'Menjadi Pelatih Kompetisi Akademik', 'poin_a'=>8,'poin_p'=>8,'poin_f'=>8,'poin_u'=>8,'poin_w'=>8,'poin_n'=>9,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat/piagam, surat tugas, dokumentasi lomba, dan bukti peringkat/hasil resmi.'],
                            ['nama_kegiatan' => 'Menjadi Pembimbing Kelompok Belajar', 'poin_a'=>8,'poin_p'=>8,'poin_f'=>8,'poin_u'=>8,'poin_w'=>9, 'keterangan_bukti'=>'SK/surat tugas, jadwal kegiatan, daftar peserta, logbook, laporan, dan dokumentasi.'],
                            ['nama_kegiatan' => 'Menjadi Pembimbing Karya Tulis Ilmiah', 'poin_a'=>9,'poin_p'=>9,'poin_f'=>9,'poin_u'=>9,'poin_w'=>9,'poin_n'=>9,'poin_i'=>10, 'keterangan_bukti'=>'SK/surat tugas, jadwal kegiatan, daftar peserta, logbook, laporan, dan dokumentasi.'],
                            ['nama_kegiatan' => 'Menjadi Relawan Pendidikan', 'poin_a'=>5,'poin_p'=>5,'poin_f'=>6,'poin_u'=>6,'poin_w'=>7,'poin_n'=>8,'poin_i'=>9, 'keterangan_bukti'=>'Bukti resmi kegiatan, dokumentasi, daftar hadir, sertifikat, atau validasi dosen/unit terkait.'],
                            ['nama_kegiatan' => 'Menjadi Pengurus Bidang Akademik', 'poin_f'=>8,'poin_u'=>9,'poin_w'=>9,'poin_n'=>10, 'keterangan_bukti'=>'SK kepanitiaan/pengurus, job description, laporan kegiatan, dokumentasi, dan daftar hadir.'],
                        ],
                    ],
                ],
            ],

            // ═══════════════════════════════════════════════════════════════
            // [2] LEADERSHIP
            // ═══════════════════════════════════════════════════════════════
            [
                'kode' => 'leadership', 'nama_aspek' => 'Leadership', 'urutan' => 2,
                'sub_aspeks' => [
                    [
                        'nama_sub_aspek' => 'Mengikuti Pelatihan Kepemimpinan', 'urutan' => 1,
                        'jenis' => [
                            ['nama_kegiatan' => 'Mengikuti Pelatihan Kepemimpinan', 'poin_a'=>5,'poin_p'=>6,'poin_f'=>7,'poin_u'=>8,'poin_w'=>9,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat / Bukti Kehadiran'],
                            ['nama_kegiatan' => 'Mengikuti Latihan Kepemimpinan Mahasiswa', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat Pelatihan'],
                            ['nama_kegiatan' => 'Mengikuti Sekolah Kepemimpinan', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat / Laporan'],
                            ['nama_kegiatan' => 'Mengikuti Youth Leadership Program', 'poin_a'=>7,'poin_p'=>8,'poin_f'=>9,'poin_u'=>10,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat Program'],
                            ['nama_kegiatan' => 'Mengikuti Pertukaran Pemuda/Kepemudaan', 'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat / Surat Tugas'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Mengikuti Kegiatan Mentoring', 'urutan' => 2,
                        'jenis' => [
                            ['nama_kegiatan' => 'Mengikuti Mentoring', 'poin_a'=>5,'poin_p'=>5,'poin_f'=>6,'poin_u'=>6,'poin_w'=>7,'poin_n'=>8,'poin_i'=>8, 'keterangan_bukti'=>'Daftar Hadir / Logbook'],
                            ['nama_kegiatan' => 'Menjadi Mentor', 'poin_a'=>7,'poin_p'=>8,'poin_f'=>9,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'SK Mentor / Surat Tugas'],
                            ['nama_kegiatan' => 'Menjadi Fasilitator Kegiatan', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat Fasilitator'],
                            ['nama_kegiatan' => 'Menjadi Pendamping atau Pembina Organisasi Mahasiswa', 'poin_a'=>8,'poin_p'=>9,'poin_f'=>9,'poin_u'=>10,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'SK Pembina'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Melaksanakan Tugas Kepanitiaan (Mandat)', 'urutan' => 3,
                        'jenis' => [
                            ['nama_kegiatan' => 'Melaksanakan Tugas Kepanitiaan', 'poin_a'=>5,'poin_p'=>6,'poin_f'=>7,'poin_u'=>8,'poin_w'=>9,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'SK Kepanitiaan'],
                            ['nama_kegiatan' => 'Menjadi Koordinator Divisi Kepanitiaan', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'SK Kepanitiaan'],
                            ['nama_kegiatan' => 'Menjadi Ketua Panitia', 'poin_a'=>7,'poin_p'=>8,'poin_f'=>9,'poin_u'=>10,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'SK / Laporan Kegiatan'],
                            ['nama_kegiatan' => 'Menjadi Liaison Officer (LO)', 'poin_a'=>5,'poin_p'=>6,'poin_f'=>7,'poin_u'=>8,'poin_w'=>9,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat / Surat Tugas'],
                            ['nama_kegiatan' => 'Menjadi Master of Ceremony (MC)', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat / Dokumentasi'],
                            ['nama_kegiatan' => 'Menjadi Volunteer Kegiatan Sosial', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat Volunteer'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Melakukan Tugas sebagai Pengurus Organisasi', 'urutan' => 4,
                        'jenis' => [
                            ['nama_kegiatan' => 'Menjadi Pengurus Organisasi', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'SK Kepengurusan'],
                            ['nama_kegiatan' => 'Menjadi Koordinator Bidang Organisasi', 'poin_a'=>7,'poin_p'=>8,'poin_f'=>9,'poin_u'=>10,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'SK Kepengurusan'],
                            ['nama_kegiatan' => 'Menjadi Ketua Organisasi', 'poin_a'=>8,'poin_p'=>9,'poin_f'=>10,'poin_u'=>10,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'SK Ketua'],
                            ['nama_kegiatan' => 'Menjadi Delegasi Organisasi', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Surat Tugas Delegasi'],
                            ['nama_kegiatan' => 'Menjadi Perwakilan Asrama dalam Forum', 'poin_a'=>7,'poin_p'=>8,'poin_f'=>9,'poin_u'=>10,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Surat Tugas / Undangan'],
                            ['nama_kegiatan' => 'Menjadi Penggerak Program Pengembangan Mahasiswa', 'poin_a'=>8,'poin_p'=>9,'poin_f'=>10,'poin_u'=>10,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Proposal / Laporan Program'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Menjadi Peserta atau Memimpin Rapat', 'urutan' => 5,
                        'jenis' => [
                            ['nama_kegiatan' => 'Menjadi Peserta Rapat', 'poin_a'=>4,'poin_p'=>5,'poin_f'=>6,'poin_u'=>7,'poin_w'=>8,'poin_n'=>9,'poin_i'=>10, 'keterangan_bukti'=>'Notulensi / Daftar Hadir'],
                            ['nama_kegiatan' => 'Memimpin Rapat', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Notulensi / Daftar Hadir'],
                            ['nama_kegiatan' => 'Mengikuti Forum Musyawarah Organisasi', 'poin_a'=>5,'poin_p'=>6,'poin_f'=>7,'poin_u'=>8,'poin_w'=>9,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Daftar Hadir / Foto'],
                            ['nama_kegiatan' => 'Menjadi Pimpinan Sidang', 'poin_a'=>7,'poin_p'=>8,'poin_f'=>9,'poin_u'=>10,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Berita Acara / Palu Sidang'],
                            ['nama_kegiatan' => 'Menjadi Notulis atau Sekretaris Sidang', 'poin_a'=>5,'poin_p'=>6,'poin_f'=>7,'poin_u'=>8,'poin_w'=>9,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Notulensi / Berita Acara'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Mengikuti Diskusi atau Debat Penyelesaian Masalah', 'urutan' => 6,
                        'jenis' => [
                            ['nama_kegiatan' => 'Mengikuti Diskusi Penyelesaian Masalah', 'poin_a'=>5,'poin_p'=>6,'poin_f'=>7,'poin_u'=>8,'poin_w'=>9,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Daftar Hadir / Notulensi'],
                            ['nama_kegiatan' => 'Mengikuti Debat atau Forum Argumentasi', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat Peserta'],
                            ['nama_kegiatan' => 'Menjadi Moderator Diskusi', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat / Surat Tugas'],
                            ['nama_kegiatan' => 'Mengikuti Model United Nations (MUN)', 'poin_u'=>8,'poin_w'=>9,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat / Penghargaan'],
                            ['nama_kegiatan' => 'Mengikuti Parlemen Mahasiswa', 'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'SK / KTM Parlemen'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Menulis Surat, Proposal Kegiatan, Laporan, dll', 'urutan' => 7,
                        'jenis' => [
                            ['nama_kegiatan' => 'Menulis Proposal Kegiatan', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Dokumen Proposal (Disahkan)'],
                            ['nama_kegiatan' => 'Menyusun Laporan Pertanggungjawaban (LPJ)', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Dokumen LPJ'],
                            ['nama_kegiatan' => 'Menyusun Surat Resmi Organisasi', 'poin_a'=>5,'poin_p'=>6,'poin_f'=>7,'poin_u'=>8,'poin_w'=>9,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Arsip Surat Keluar'],
                            ['nama_kegiatan' => 'Menyusun Program Kerja Organisasi', 'poin_a'=>7,'poin_p'=>8,'poin_f'=>9,'poin_u'=>10,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Dokumen Proker'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Memberikan Kontribusi Baik Harta, Tenaga, Waktu', 'urutan' => 8,
                        'jenis' => [
                            ['nama_kegiatan' => 'Memberikan Kontribusi Dana untuk Kegiatan Sosial', 'poin_a'=>5,'poin_p'=>6,'poin_f'=>7,'poin_u'=>8,'poin_w'=>9,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Bukti Transfer / Kuitansi'],
                            ['nama_kegiatan' => 'Memberikan Kontribusi Tenaga dalam Kegiatan Sosial', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat / Foto Kegiatan'],
                            ['nama_kegiatan' => 'Memberikan Kontribusi Waktu dalam Kegiatan Sosial', 'poin_a'=>5,'poin_p'=>6,'poin_f'=>7,'poin_u'=>8,'poin_w'=>9,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Logbook / Foto'],
                            ['nama_kegiatan' => 'Menjadi Volunteer Kegiatan Sosial', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat Volunteer'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Menyampaikan Gagasan Baik Lisan atau Tulisan', 'urutan' => 9,
                        'jenis' => [
                            ['nama_kegiatan' => 'Menulis Gagasan atau Opini Kepemimpinan', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Link Publikasi / Dokumen'],
                            ['nama_kegiatan' => 'Menyampaikan Gagasan Secara Lisan', 'poin_a'=>5,'poin_p'=>6,'poin_f'=>7,'poin_u'=>8,'poin_w'=>9,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Notulensi / Rekaman Video'],
                            ['nama_kegiatan' => 'Menjadi Pemateri Pelatihan Kepemimpinan', 'poin_a'=>8,'poin_p'=>9,'poin_f'=>10,'poin_u'=>10,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat Pemateri'],
                            ['nama_kegiatan' => 'Menjadi Narasumber Seminar Kepemimpinan', 'poin_a'=>8,'poin_p'=>9,'poin_f'=>10,'poin_u'=>10,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Surat Undangan / Sertifikat'],
                            ['nama_kegiatan' => 'Menjadi Inisiator Program atau Kegiatan', 'poin_a'=>8,'poin_p'=>9,'poin_f'=>10,'poin_u'=>10,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Proposal / Laporan Kegiatan'],
                        ],
                    ],
                ],
            ],

            // ═══════════════════════════════════════════════════════════════
            // [3] KARAKTER ISLAMI (Keislaman)
            // Sumber pakai kolom "Lokal (Asrama/Prodi)" gabungan → diisi ke poin_a & poin_p sekaligus.
            // ═══════════════════════════════════════════════════════════════
            [
                'kode' => 'karakter_islami', 'nama_aspek' => 'Karakter Islami', 'urutan' => 3,
                'sub_aspeks' => [
                    [
                        'nama_sub_aspek' => "Membaca Al-Qur'an, Hafalan, & Hadits Pilihan", 'urutan' => 1,
                        'jenis' => [
                            ['nama_kegiatan' => 'Tilawah Mandiri / Murojaah', 'poin_a'=>4,'poin_p'=>4, 'keterangan_bukti'=>"Kartu Kontrol Mutaba'ah Harian / Dokumentasi"],
                            ['nama_kegiatan' => 'Setoran Hafalan Baru (Tasmi 1 Juz / 10 Hadits Arbain)', 'poin_f'=>5, 'keterangan_bukti'=>'Kartu Setoran Hafalan / Dokumentasi'],
                            ['nama_kegiatan' => 'Setoran Hafalan Baru (Tasmi 2-5 Juz / 20 Hadits Pilihan)', 'poin_u'=>6,'poin_n'=>7, 'keterangan_bukti'=>'Sertifikat'],
                            ['nama_kegiatan' => 'Setoran Hafalan Baru (Tasmi >5 Juz)', 'poin_u'=>8,'poin_w'=>9,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat'],
                            ['nama_kegiatan' => 'Lulus Sertifikasi Tahsin Asrama', 'poin_a'=>8,'poin_p'=>8],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Mengikuti Kegiatan Mentoring', 'urutan' => 2,
                        'jenis' => [
                            ['nama_kegiatan' => 'Peserta Mentoring / Halaqah', 'poin_f'=>3,'poin_u'=>4, 'keterangan_bukti'=>'Dokumentasi'],
                            ['nama_kegiatan' => 'Ketua Kelompok Mentoring', 'poin_u'=>5,'poin_w'=>6, 'keterangan_bukti'=>'Surat Keterangan'],
                            ['nama_kegiatan' => 'Mentor / Fasilitator Keagamaan', 'poin_a'=>7,'poin_p'=>7,'poin_w'=>8,'poin_n'=>9, 'keterangan_bukti'=>'SK Mentor / Sertifikat Fasilitator'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Mengikuti Kajian, Membaca Buku, atau Ceramah Agama', 'urutan' => 3,
                        'jenis' => [
                            ['nama_kegiatan' => 'Peserta Kajian / Majelis Taklim', 'poin_f'=>3,'poin_u'=>4, 'keterangan_bukti'=>'Sertifikat / Absensi / Dokumentasi'],
                            ['nama_kegiatan' => 'Mengisi Kajian', 'poin_u'=>5,'poin_w'=>6,'poin_n'=>7,'poin_i'=>8, 'keterangan_bukti'=>'Sertifikat Kegiatan / Resume Kajian'],
                            ['nama_kegiatan' => 'Membaca & Meresume Buku Agama Mandiri', 'poin_a'=>4,'poin_p'=>4, 'keterangan_bukti'=>'Berkas Resume Buku'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Menjadi Imam Shalat Jamaah atau Memimpin Doa', 'urutan' => 4,
                        'jenis' => [
                            ['nama_kegiatan' => 'Memimpin Doa / Dzikir / Kultum', 'poin_a'=>4,'poin_p'=>4, 'keterangan_bukti'=>'Presensi atau Surat Tugas Asrama / Dokumentasi'],
                            ['nama_kegiatan' => 'Imam Shalat Wajib', 'poin_a'=>4,'poin_p'=>4, 'keterangan_bukti'=>'SK Jadwal Imam Rawatib Asrama / Dokumentasi'],
                            ['nama_kegiatan' => 'Imam Shalat Rawatib / Shalat Jumat / Tarawih', 'poin_u'=>6,'poin_w'=>7,'poin_n'=>7,'poin_i'=>8, 'keterangan_bukti'=>'Surat Tugas / SK Jadwal Resmi Masjid / Dokumentasi'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Mengamalkan Ibadah Harian (Shalat, Puasa, Zakat, dll)', 'urutan' => 5,
                        'jenis' => [
                            ['nama_kegiatan' => 'Shalat Berjamaah Tepat Waktu Pada 5 Shalat Fardhu', 'poin_a'=>5,'poin_p'=>5, 'keterangan_bukti'=>"Kartu Mutaba'ah"],
                            ['nama_kegiatan' => 'Ibadah Sunnah (Tahajjud, Dhuha, Rawatib)', 'poin_a'=>6,'poin_p'=>6, 'keterangan_bukti'=>"Kartu Mutaba'ah"],
                            ['nama_kegiatan' => 'Amalan Puasa Sunnah (Senin-Kamis/Daud/Ayyamul Bidh)', 'poin_a'=>7,'poin_p'=>7, 'keterangan_bukti'=>"Kartu Mutaba'ah"],
                            ['nama_kegiatan' => 'Menyalurkan Zakat, Infaq, Sedekah', 'poin_u'=>5,'poin_w'=>6,'poin_n'=>6,'poin_i'=>7, 'keterangan_bukti'=>'SK Kepanitiaan Ziswaf / Dokumentasi'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Menyampaikan Dakwah/Kultum (Lisan maupun Tulisan)', 'urutan' => 6,
                        'jenis' => [
                            ['nama_kegiatan' => 'Dakwah Online (Artikel / Konten Kreatif Medsos)', 'poin_a'=>5,'poin_p'=>5, 'keterangan_bukti'=>'Bukti Pemuatan / Tangkapan Layar Link'],
                            ['nama_kegiatan' => 'Penceramah / Khutbah / Narasumber Keagamaan', 'poin_u'=>6,'poin_w'=>7,'poin_n'=>8,'poin_i'=>8, 'keterangan_bukti'=>'Surat Undangan / Sertifikat'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Memelihara Kebersihan (Kamar, Lingkungan Asrama, dll)', 'urutan' => 7,
                        'jenis' => [
                            ['nama_kegiatan' => 'Kebersihan Mandiri (Piket Kamar)', 'poin_a'=>3,'poin_p'=>3, 'keterangan_bukti'=>'Lembar Kontrol Kebersihan Kamar / Dokumentasi'],
                            ['nama_kegiatan' => 'Kerja Bakti Bersama / Gotong Royong Lingkungan', 'poin_a'=>4,'poin_p'=>4, 'keterangan_bukti'=>'Absensi Kerja Bakti Asrama'],
                            ['nama_kegiatan' => 'Penanggung Jawab (PJ) / Koordinator Kebersihan', 'poin_u'=>5,'poin_n'=>7,'poin_i'=>8, 'keterangan_bukti'=>'SK Kepengurusan / Surat Tugas'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Memelihara Silaturahmi dan Menolong Sesama', 'urutan' => 8,
                        'jenis' => [
                            ['nama_kegiatan' => 'Solidaritas Internal (Menjenguk / Membantu Teman Sakit)', 'poin_a'=>5,'poin_p'=>5, 'keterangan_bukti'=>'Surat Keterangan / Laporan Kegiatan'],
                            ['nama_kegiatan' => 'Aksi Sosial / Bakti Sosial Kemasyarakatan', 'poin_u'=>6,'poin_i'=>8, 'keterangan_bukti'=>'Surat Tugas Pengabdian / Sertifikat / Dokumentasi'],
                            ['nama_kegiatan' => 'Relawan / Panitia Tanggap Bencana & Kemanusiaan', 'poin_w'=>7,'poin_n'=>8,'poin_i'=>9, 'keterangan_bukti'=>'SK Relawan dari Lembaga Resmi (BAZNAS/LAZ)'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Mengajar Pengajian, TPA, TPQ, dll', 'urutan' => 9,
                        'jenis' => [
                            ['nama_kegiatan' => 'Pengajar Pengganti / Badal (Insidental)', 'poin_a'=>5,'poin_p'=>5,'poin_w'=>7, 'keterangan_bukti'=>'Surat Keterangan Mengajar / Dokumentasi'],
                            ['nama_kegiatan' => 'Pengajar Tetap / Guru Mengaji', 'poin_a'=>6,'poin_p'=>6,'poin_w'=>8, 'keterangan_bukti'=>'SK Mengajar / Kartu Presensi Mengajar'],
                            ['nama_kegiatan' => 'Kepala / Direktur Pengelola TPA-TPQ', 'poin_a'=>7,'poin_p'=>7,'poin_w'=>9, 'keterangan_bukti'=>'SK Pengangkatan Jabatan Struktur TPA'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Menjuarai Lomba Keislaman (MTQ, MHQ, Dai, dll)', 'urutan' => 10,
                        'jenis' => [
                            ['nama_kegiatan' => 'Peserta Lomba Keislaman', 'poin_a'=>4,'poin_p'=>4,'poin_f'=>5,'poin_u'=>6,'poin_w'=>6,'poin_n'=>7,'poin_i'=>8, 'keterangan_bukti'=>'Sertifikat Keikutsertaan / Pendaftaran'],
                            ['nama_kegiatan' => 'Finalis Lomba Keislaman', 'poin_a'=>5,'poin_p'=>5,'poin_f'=>6,'poin_u'=>7,'poin_w'=>7,'poin_n'=>8,'poin_i'=>9, 'keterangan_bukti'=>'Sertifikat Finalis Lomba'],
                            ['nama_kegiatan' => 'Juara III Lomba Keislaman', 'poin_a'=>5,'poin_p'=>5,'poin_f'=>6,'poin_u'=>7,'poin_w'=>7,'poin_n'=>8,'poin_i'=>9, 'keterangan_bukti'=>'Sertifikat Juara III / Tropi'],
                            ['nama_kegiatan' => 'Juara II Lomba Keislaman', 'poin_a'=>6,'poin_p'=>6,'poin_f'=>7,'poin_u'=>8,'poin_w'=>8,'poin_n'=>9,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat Juara II / Tropi'],
                            ['nama_kegiatan' => 'Juara I Lomba Keislaman', 'poin_a'=>7,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>9,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat Juara I / Tropi'],
                        ],
                    ],
                ],
            ],

            // ═══════════════════════════════════════════════════════════════
            // [4] KREATIFITAS (& Kewirausahaan)
            // ═══════════════════════════════════════════════════════════════
            [
                'kode' => 'kreatifitas', 'nama_aspek' => 'Kreatifitas', 'urutan' => 4,
                'sub_aspeks' => [
                    [
                        'nama_sub_aspek' => 'Mengikuti Pelatihan Kreativitas dan Kewirausahaan', 'urutan' => 1,
                        'jenis' => [
                            ['nama_kegiatan' => 'Mengikuti Pelatihan Kewirausahaan yang Diadakan Asrama/Yayasan', 'poin_a'=>4, 'keterangan_bukti'=>'Sertifikat / Daftar Hadir'],
                            ['nama_kegiatan' => 'Mengikuti Workshop Digital Marketing/UMKM', 'poin_a'=>4,'poin_p'=>5,'poin_f'=>6,'poin_u'=>7,'poin_w'=>8,'poin_n'=>9,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat / Bukti Kehadiran'],
                            ['nama_kegiatan' => 'Mengikuti Pelatihan Public Speaking dan Presentasi Bisnis', 'poin_a'=>4,'poin_p'=>5,'poin_f'=>6,'poin_u'=>7,'poin_w'=>8,'poin_n'=>9,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat / Bukti Kehadiran'],
                            ['nama_kegiatan' => 'Mengikuti Pelatihan Pembuatan Konten Kreatif (Foto/Video Produk)', 'poin_a'=>4,'poin_p'=>5,'poin_f'=>6,'poin_u'=>7,'poin_w'=>8,'poin_n'=>9,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat / Bukti Kehadiran'],
                            ['nama_kegiatan' => 'Mengikuti Pelatihan Keuangan dan Pembukuan Usaha Sederhana', 'poin_a'=>4,'poin_p'=>5,'poin_f'=>6,'poin_u'=>7,'poin_w'=>8,'poin_n'=>9,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat / Bukti Kehadiran'],
                            ['nama_kegiatan' => 'Mengikuti Pelatihan Desain Grafis/Branding Produk', 'poin_a'=>4,'poin_p'=>5,'poin_f'=>6,'poin_u'=>7,'poin_w'=>8,'poin_n'=>9,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat / Bukti Kehadiran'],
                            ['nama_kegiatan' => 'Mengikuti Pelatihan dari LAZ YAPI/IRRES terkait Pemberdayaan Ekonomi', 'poin_a'=>5, 'keterangan_bukti'=>'Sertifikat / Bukti Kehadiran'],
                            ['nama_kegiatan' => 'Mengikuti Sertifikasi Keterampilan (Barista, Tata Boga, Menjahit, dll)', 'poin_w'=>8,'poin_n'=>9,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat Kompetensi'],
                            ['nama_kegiatan' => 'Mengikuti Seminar Nasional/Webinar Kewirausahaan dari Luar Asrama', 'poin_n'=>9,'poin_i'=>10, 'keterangan_bukti'=>'E-Sertifikat / Tiket Seminar'],
                            ['nama_kegiatan' => 'Membuat Catatan/Rangkuman Hasil Pelatihan sebagai Bahan Evaluasi Diri', 'poin_a'=>4, 'keterangan_bukti'=>'Dokumen Catatan / Rangkuman'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Mengikuti Kegiatan Mentoring', 'urutan' => 2,
                        'jenis' => [
                            ['nama_kegiatan' => 'Hadir Rutin dalam Sesi Mentoring Kewirausahaan Mingguan/Bulanan', 'poin_a'=>4, 'keterangan_bukti'=>'Logbook Mentoring / Daftar Hadir'],
                            ['nama_kegiatan' => 'Aktif Berdiskusi dan Bertanya saat Sesi Mentoring', 'poin_a'=>5, 'keterangan_bukti'=>'Catatan Mentoring / Feedback Mentor'],
                            ['nama_kegiatan' => 'Mengikuti Mentoring Individual (One-on-One) dengan Pembina/Senior', 'poin_a'=>5, 'keterangan_bukti'=>'Logbook Mentoring 1-on-1'],
                            ['nama_kegiatan' => 'Menjadi Mentee dalam Program Magang Internal Usaha Asrama', 'poin_a'=>6, 'keterangan_bukti'=>'Surat Keterangan Magang / Laporan'],
                            ['nama_kegiatan' => 'Mengikuti Leadership Talk dan Growth Mindset Session', 'poin_a'=>4,'poin_p'=>5,'poin_f'=>6,'poin_u'=>7,'poin_w'=>8,'poin_n'=>9,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat / Catatan Sesi'],
                            ['nama_kegiatan' => 'Menyusun Rencana Aksi (Action Plan) Hasil Mentoring', 'poin_a'=>5, 'keterangan_bukti'=>'Dokumen Action Plan'],
                            ['nama_kegiatan' => 'Melaporkan Progres Usaha/Ide secara Berkala ke Mentor', 'poin_a'=>5, 'keterangan_bukti'=>'Laporan Progres'],
                            ['nama_kegiatan' => 'Menjalin Komunikasi dengan Alumni/Praktisi Wirausaha sebagai Mentor Eksternal', 'poin_w'=>7,'poin_n'=>8,'poin_i'=>9, 'keterangan_bukti'=>'Bukti Komunikasi (Screenshot) / Catatan'],
                            ['nama_kegiatan' => 'Mengikuti Evaluasi/Refleksi Bersama Kelompok Mentoring', 'poin_a'=>4, 'keterangan_bukti'=>'Catatan Evaluasi'],
                            ['nama_kegiatan' => 'Menjadi Mentor bagi Adik Kelas/Junior dalam Kegiatan Kewirausahaan', 'poin_a'=>7, 'keterangan_bukti'=>'Surat Tugas / Bukti Mentoring'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Membaca Buku, Majalah, Internet dll Terkait Kewirausahaan', 'urutan' => 3,
                        'jenis' => [
                            ['nama_kegiatan' => 'Membaca Minimal 1 Buku Kewirausahaan per Bulan dan Membuat Resensi', 'poin_a'=>5, 'keterangan_bukti'=>'Dokumen Resensi Buku'],
                            ['nama_kegiatan' => 'Mengikuti Program Literasi/Pojok Baca Asrama terkait Ekonomi-Bisnis', 'poin_a'=>4, 'keterangan_bukti'=>'Daftar Hadir Pojok Baca'],
                            ['nama_kegiatan' => 'Membaca Artikel/Jurnal Ekonomi Syariah dan Bisnis Islami', 'poin_a'=>4, 'keterangan_bukti'=>'Resume Artikel / Jurnal'],
                            ['nama_kegiatan' => 'Mengikuti Akun/Media Sosial Edukasi Kewirausahaan dan Mencatat Insight', 'poin_a'=>4, 'keterangan_bukti'=>'Screenshot Follow / Catatan Insight'],
                            ['nama_kegiatan' => 'Menonton Video Edukatif (YouTube, Podcast) tentang Bisnis dan Startup', 'poin_a'=>4, 'keterangan_bukti'=>'Screenshot Riwayat Tontonan / Catatan'],
                            ['nama_kegiatan' => 'Mengikuti Perkembangan Berita Ekonomi dan UMKM Terkini', 'poin_a'=>4, 'keterangan_bukti'=>'Catatan Berita / Kliping'],
                            ['nama_kegiatan' => 'Berdiskusi Kelompok Membahas Buku/Bacaan Kewirausahaan (Book Club)', 'poin_a'=>5, 'keterangan_bukti'=>'Notulensi Book Club'],
                            ['nama_kegiatan' => 'Membuat Ringkasan Tertulis dari Bacaan dan Dipresentasikan', 'poin_a'=>6, 'keterangan_bukti'=>'Ringkasan Tertulis / Materi Presentasi'],
                            ['nama_kegiatan' => 'Mempelajari Studi Kasus (Success Story) Wirausahawan Muslim', 'poin_a'=>5, 'keterangan_bukti'=>'Catatan Studi Kasus'],
                            ['nama_kegiatan' => 'Mengakses Platform Pembelajaran Online (E-Course) terkait Bisnis', 'poin_a'=>5, 'keterangan_bukti'=>'Sertifikat E-Course / Screenshot Progress'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Mengikuti Forum Ceramah atau Diskusi Kewirausahaan', 'urutan' => 4,
                        'jenis' => [
                            ['nama_kegiatan' => 'Hadir Aktif dalam Forum Diskusi/Seminar Kewirausahaan Asrama', 'poin_a'=>4, 'keterangan_bukti'=>'Daftar Hadir / Foto Kegiatan'],
                            ['nama_kegiatan' => 'Mengajukan Pertanyaan atau Pendapat dalam Forum Diskusi', 'poin_a'=>5, 'keterangan_bukti'=>'Catatan / Notulensi'],
                            ['nama_kegiatan' => 'Menjadi Moderator/Notulen dalam Forum Diskusi Kewirausahaan', 'poin_a'=>6, 'keterangan_bukti'=>'Bukti Peran (Notulensi/Foto)'],
                            ['nama_kegiatan' => 'Mengikuti Talkshow dengan Pengusaha/Alumni Sukses', 'poin_a'=>4,'poin_p'=>5,'poin_f'=>6,'poin_u'=>7,'poin_w'=>8,'poin_n'=>9,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat / Bukti Kehadiran'],
                            ['nama_kegiatan' => 'Berpartisipasi dalam Forum Lintas Asrama (Sunan Giri, Putri, Wali Songo, Sunan Gunung Jati)', 'poin_w'=>7, 'keterangan_bukti'=>'Sertifikat / Foto Kegiatan Lintas Asrama'],
                            ['nama_kegiatan' => 'Mengikuti Kajian yang Mengaitkan Ekonomi Syariah dengan Praktik Usaha', 'poin_a'=>5, 'keterangan_bukti'=>'Catatan Kajian / Daftar Hadir'],
                            ['nama_kegiatan' => 'Menjadi Pembicara/Sharing Pengalaman Usaha dalam Forum', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat Pembicara / Materi Presentasi'],
                            ['nama_kegiatan' => 'Mengikuti Diskusi Evaluasi Unit Usaha Asrama (Koperasi, Kantin, dll)', 'poin_a'=>5, 'keterangan_bukti'=>'Notulensi Evaluasi'],
                            ['nama_kegiatan' => 'Mengikuti Forum Daring (Webinar/Zoom) tentang Kewirausahaan', 'poin_a'=>4,'poin_p'=>5,'poin_f'=>6,'poin_u'=>7,'poin_w'=>8,'poin_n'=>9,'poin_i'=>10, 'keterangan_bukti'=>'E-Sertifikat / Screenshot Zoom'],
                            ['nama_kegiatan' => 'Menyusun Resume Hasil Forum dan Membagikannya ke Warga Asrama Lain', 'poin_a'=>5, 'keterangan_bukti'=>'Dokumen Resume / Bukti Share'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Melakukan Tugas dalam Kegiatan Usaha Asrama', 'urutan' => 5,
                        'jenis' => [
                            ['nama_kegiatan' => 'Terlibat Aktif dalam Operasional Koperasi/Kantin Asrama', 'poin_a'=>6, 'keterangan_bukti'=>'Daftar Hadir / Logbook Operasional'],
                            ['nama_kegiatan' => 'Membantu Pengelolaan Unit Usaha PT YAPI Investa Amanah/Koperasi', 'poin_a'=>7, 'keterangan_bukti'=>'Surat Tugas / Laporan Kinerja'],
                            ['nama_kegiatan' => 'Mengelola Stok dan Inventaris Barang Usaha Asrama', 'poin_a'=>6, 'keterangan_bukti'=>'Laporan Stok Barang'],
                            ['nama_kegiatan' => 'Menjadi Petugas Piket/Penjaga dalam Kegiatan Bazar atau Jualan', 'poin_a'=>5, 'keterangan_bukti'=>'Jadwal Piket / Foto Penjagaan'],
                            ['nama_kegiatan' => 'Membantu Pencatatan Keuangan Harian Unit Usaha', 'poin_a'=>6, 'keterangan_bukti'=>'Laporan Keuangan Harian'],
                            ['nama_kegiatan' => 'Ikut Serta dalam Kepanitiaan Event Ekonomi Kreatif Asrama', 'poin_a'=>6, 'keterangan_bukti'=>'SK Kepanitiaan / Sertifikat Panitia'],
                            ['nama_kegiatan' => 'Membantu Promosi dan Pemasaran Produk Usaha Asrama', 'poin_a'=>5, 'keterangan_bukti'=>'Bukti Promosi (Screenshot/Brosur)'],
                            ['nama_kegiatan' => 'Melayani Konsumen/Pembeli dengan Baik dalam Kegiatan Usaha', 'poin_a'=>5, 'keterangan_bukti'=>'Testimoni Konsumen / Laporan Penjualan'],
                            ['nama_kegiatan' => 'Memberikan Ide Perbaikan/Inovasi untuk Usaha Asrama', 'poin_a'=>6, 'keterangan_bukti'=>'Dokumen Usulan Ide'],
                            ['nama_kegiatan' => 'Melaksanakan Tugas Piket Sesuai Jadwal Tanpa Diingatkan', 'poin_a'=>5, 'keterangan_bukti'=>'Logbook Piket / Ceklis Tugas'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Menulis Proposal Usaha', 'urutan' => 6,
                        'jenis' => [
                            ['nama_kegiatan' => 'Menyusun Ide Bisnis dan Deskripsi Produk/Jasa secara Tertulis', 'poin_a'=>6, 'keterangan_bukti'=>'Dokumen Ide Bisnis'],
                            ['nama_kegiatan' => 'Membuat Analisis Pasar dan Target Konsumen Sederhana', 'poin_a'=>6, 'keterangan_bukti'=>'Dokumen Analisis Pasar'],
                            ['nama_kegiatan' => 'Menyusun Rencana Anggaran Biaya (RAB) Usaha', 'poin_a'=>6, 'keterangan_bukti'=>'Dokumen RAB'],
                            ['nama_kegiatan' => 'Membuat Proyeksi Keuntungan dan Strategi Penjualan', 'poin_a'=>6, 'keterangan_bukti'=>'Dokumen Proyeksi Keuangan'],
                            ['nama_kegiatan' => 'Menyusun Proposal Kerja Sama dengan Pihak Sponsor/Donatur', 'poin_w'=>8,'poin_n'=>9,'poin_i'=>10, 'keterangan_bukti'=>'Proposal Kerja Sama / Sponsorship'],
                            ['nama_kegiatan' => 'Mengikuti Kompetisi Business Plan Internal/Eksternal Asrama', 'poin_a'=>5,'poin_p'=>6,'poin_f'=>7,'poin_u'=>8,'poin_w'=>9,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat Peserta Lomba / Proposal'],
                            ['nama_kegiatan' => 'Merevisi Proposal Berdasarkan Masukan Mentor/Pembina', 'poin_a'=>5, 'keterangan_bukti'=>'Draft Revisi / Catatan Mentor'],
                            ['nama_kegiatan' => 'Mempresentasikan Proposal Usaha di Depan Tim Penilai', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Materi Presentasi (PPT) / Foto'],
                            ['nama_kegiatan' => 'Mengajukan Proposal Usaha untuk Pendanaan Internal (LAZ/Koperasi)', 'poin_a'=>7, 'keterangan_bukti'=>'Bukti Pengajuan Proposal / Tanda Terima'],
                            ['nama_kegiatan' => 'Mendokumentasikan Proposal sebagai Portofolio Pribadi', 'poin_a'=>5, 'keterangan_bukti'=>'Link Portofolio Proposal'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Menghasilkan Karya Kreatif (Video, Grafis, dll)', 'urutan' => 7,
                        'jenis' => [
                            ['nama_kegiatan' => 'Membuat Konten Promosi Produk/Usaha (Foto, Video, Reels)', 'poin_a'=>6, 'keterangan_bukti'=>'Link Konten / File Video/Foto'],
                            ['nama_kegiatan' => 'Mendesain Logo, Label, atau Kemasan Produk', 'poin_a'=>6, 'keterangan_bukti'=>'File Desain (JPG/PNG/PDF)'],
                            ['nama_kegiatan' => 'Membuat Video Dokumentasi Kegiatan Asrama untuk Media Sosial', 'poin_a'=>6, 'keterangan_bukti'=>'Link Video / File Video'],
                            ['nama_kegiatan' => 'Membuat Poster/Banner Digital untuk Event Kewirausahaan', 'poin_a'=>5, 'keterangan_bukti'=>'File Poster / Banner Digital'],
                            ['nama_kegiatan' => 'Mengelola Akun Media Sosial Usaha/Asrama (Admin Konten)', 'poin_a'=>7, 'keterangan_bukti'=>'Screenshot Akun / Laporan Insight Medsos'],
                            ['nama_kegiatan' => 'Membuat Karya Tulis (Artikel/Blog) Bertema Kewirausahaan', 'poin_a'=>5,'poin_p'=>6,'poin_f'=>7,'poin_u'=>8,'poin_w'=>9,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Link Artikel / File Tulisan'],
                            ['nama_kegiatan' => 'Berpartisipasi dalam Lomba Kreativitas (Desain, Fotografi, Videografi)', 'poin_a'=>5,'poin_p'=>6,'poin_f'=>7,'poin_u'=>8,'poin_w'=>9,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat Lomba / Karya'],
                            ['nama_kegiatan' => 'Membuat Jingle/Konten Audio Promosi Sederhana', 'poin_a'=>6, 'keterangan_bukti'=>'File Audio / Link Jingle'],
                            ['nama_kegiatan' => 'Mengembangkan Ide Produk Baru yang Inovatif dan Bernilai Jual', 'poin_a'=>8, 'keterangan_bukti'=>'Dokumen Deskripsi Produk / Prototipe'],
                            ['nama_kegiatan' => 'Mengunggah Karya Kreatif Pribadi yang Mendukung Citra Asrama', 'poin_a'=>5, 'keterangan_bukti'=>'Link Postingan / Screenshot'],
                        ],
                    ],
                    [
                        'nama_sub_aspek' => 'Memiliki Keberanian untuk Memulai Usaha', 'urutan' => 8,
                        'jenis' => [
                            ['nama_kegiatan' => 'Memiliki Usaha Pribadi/Kelompok yang Berjalan Aktif', 'poin_a'=>8, 'keterangan_bukti'=>'Foto Usaha / Laporan Usaha Aktif'],
                            ['nama_kegiatan' => 'Berani Menawarkan Produk/Jasa kepada Warga Asrama atau Luar', 'poin_a'=>6, 'keterangan_bukti'=>'Bukti Penawaran / Chat / Brosur'],
                            ['nama_kegiatan' => 'Mengambil Peran sebagai Ketua/Koordinator dalam Unit Usaha Bersama', 'poin_a'=>8, 'keterangan_bukti'=>'SK / Bukti Peran Ketua'],
                            ['nama_kegiatan' => 'Berani Mengambil Risiko Mencoba Ide Bisnis Baru Meski Berpotensi Rugi', 'poin_a'=>7, 'keterangan_bukti'=>'Laporan Usaha / Catatan Evaluasi'],
                            ['nama_kegiatan' => 'Mengikuti Kompetisi/Expo Kewirausahaan dengan Produk Sendiri', 'poin_a'=>6,'poin_p'=>7,'poin_f'=>8,'poin_u'=>9,'poin_w'=>10,'poin_n'=>10,'poin_i'=>10, 'keterangan_bukti'=>'Sertifikat Expo / Foto Booth'],
                            ['nama_kegiatan' => 'Mencari Modal Usaha secara Mandiri (Tabungan, Patungan, Sponsor)', 'poin_a'=>7, 'keterangan_bukti'=>'Bukti Modal / Buku Tabungan Usaha'],
                            ['nama_kegiatan' => 'Bangkit Kembali dan Evaluasi Setelah Mengalami Kegagalan Usaha', 'poin_a'=>9, 'keterangan_bukti'=>'Laporan Evaluasi / Rencana Perbaikan'],
                            ['nama_kegiatan' => 'Mengajak Warga Lain Bergabung/Berkolaborasi dalam Usaha', 'poin_a'=>6, 'keterangan_bukti'=>'Daftar Anggota Tim / Bukti Kolaborasi'],
                            ['nama_kegiatan' => 'Konsisten Menjalankan Usaha dalam Jangka Waktu Tertentu (≥1 Bulan)', 'poin_a'=>9, 'keterangan_bukti'=>'Laporan Usaha Bulanan'],
                            ['nama_kegiatan' => 'Melaporkan Perkembangan Usaha Pribadi secara Berkala ke Pembina', 'poin_a'=>5, 'keterangan_bukti'=>'Laporan Perkembangan (Progres Report)'],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($data as $aspekData) {
            $subAspeks = $aspekData['sub_aspeks'];
            unset($aspekData['sub_aspeks']);

            $aspek = KomponenPenilaianAspek::create($aspekData);

            foreach ($subAspeks as $subData) {
                $jenisData = $subData['jenis'];
                unset($subData['jenis']);

                $sub = $aspek->subAspeks()->create($subData);

                foreach ($jenisData as $urutan => $jenis) {
                    $sub->jenisKegiatans()->create(array_merge(
                        $jenis,
                        ['urutan' => $urutan + 1]
                    ));
                }
            }
        }

        $this->command->info('✅ KomponenPenilaian seeder selesai: 4 aspek, ' .
            KomponenPenilaianSubAspek::count() . ' sub-aspek, ' .
            KomponenPenilaianJenis::count() . ' jenis kegiatan.');
    }
}
