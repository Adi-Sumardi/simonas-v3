# Rencana Update SIMONAS

Backlog fitur. Status per item ditandai di checklist masing-masing.

---

## 1. Onboarding Wizard — ✅ Selesai & live

Wizard 4 langkah muncul untuk semua role (mahasiswa/mentor/super/alumni) yang belum onboarding, wajib dilewati sebelum akses aplikasi (karena app ini baru — warga lama tetap kena, tidak di-backfill).

- [x] Desain alur wizard (langkah-langkah, data yang dikumpulkan)
- [x] Review desain bareng sebelum coding
- [x] Implementasi — form wizard (Kontak & Foto, Data Akademik, Alamat & Kontak Darurat) + migration kolom `semester`, `onboarding_completed_at`, `tgl_mulai_percobaan`
- [x] Revisi: "Kenalan Fitur" dipindah dari step wizard jadi **modal popup carousel** yang muncul otomatis di Dashboard sekali setelah onboarding selesai, isinya beda per role

---

## 2. Filter Tambahan di Menu Warga — 🔧 Sedang dikerjakan

Tambahkan filter di halaman manajemen Warga:

- [x] Klarifikasi skema: status keanggotaan (percobaan/tetap/senior) jadi kolom baru `tingkat_keanggotaan`, terpisah dari `status_warga` (aktif/nonaktif) — migration & backend filter sudah jalan
- [x] Filter berdasarkan **semester kuliah**
- [x] Filter berdasarkan **status warga** (sudah ada sebelumnya)
- [x] Filter berdasarkan **tingkat keanggotaan** (percobaan/tetap/senior)
- [x] Filter berdasarkan **lama menjadi warga percobaan** (berdasarkan `tgl_mulai_percobaan`, opsi ≥1/3/6/12 bulan)
- [ ] Final check UI (kolom tabel, badge) & deploy ke production

---

## 3. Log Book Mentoring — ✅ Selesai & live

Tabel baru `mentoring_logs` (tanggal, mentor, mentee, topik, tujuan, hasil diskusi, kendala, solusi, tindak lanjut).

- [x] Desain struktur data log book
- [x] Menu Log Book Mentoring — mentor: CRUD penuh (`/mentor/logbook`, filter per mentee); mahasiswa: read-only (`/mahasiswa/logbook`)
- [x] Section ringkasan (3 log terbaru) di Dashboard mahasiswa & mentor

---

## 4. Statistik Penerimaan Beasiswa Per Bulan (Laporan) — 🔧 Sebagian selesai

Tabel `beasiswas` (user, mentor, sumber yayasan/eksternal, nominal, tanggal diajukan/diterima, status pending/approved/rejected).

- [x] Section statistik + tabel di halaman Laporan (chart bulanan + list)
- [x] **Tidak ikut di-export** (`print:hidden`, dikecualikan dari export PDF Laporan)
- [x] Beasiswa eksternal (KIP-K, dsb) — admin input langsung, status approved
- [x] Beasiswa Yayasan — approval oleh mentor mentee bersangkutan (`/mentor/beasiswa/pending`), sesuai aturan: **yang approve mahasiswa dapat Beasiswa Yayasan adalah mentornya**
- [ ] **Auto-generate proposal bulanan** (belum dikerjakan, tunggu item #6): sistem cek tiap akhir bulan per mentee — total aktivitas ≥120, total kehadiran Kajian Ahad Pagi, total Sholat Subuh. Kalau 3 kriteria terpenuhi → sistem buat pengajuan Beasiswa Yayasan (status pending) otomatis untuk diapprove mentor. **Depends on**: item #6 Attendance Kegiatan (buat data Kajian Ahad Pagi) + pemisahan data Sholat Subuh dari `UserEvent` tipe shalat.

---

## 5. Data Master (Mahasiswa + Alumni Gabungan)

Menu baru **Data Master** — gabungan data warga mahasiswa & alumni, dengan filter:

- [ ] Filter berdasarkan **wilayah/daerah**
- [ ] Filter berdasarkan **asal kampus**
- [ ] Filter berdasarkan **jurusan/program studi**

---

## 6. Attendance Kegiatan

Fitur absensi untuk kegiatan (contoh: Kajian Ahad). Pendekatan **dinamis**: setiap admin/pengurus asrama membuat kegiatan bisa mencentang opsi "wajib absen" atau tidak.

- [ ] Toggle "wajib absen" saat admin/pengurus asrama membuat kegiatan
- [ ] Kalau dicentang → mahasiswa wajib melakukan absensi ke kegiatan tersebut
- [ ] Rekap kehadiran per kegiatan: siapa saja yang absen, waktu absen, asrama mana
- [ ] Upload/lampiran **foto kegiatan** pada rekap absensi

---

## 7. Komponen Penilaian — Menu Pengaturan (Desain)

Upgrade section **Komponen Penilaian** di menu Pengaturan menjadi lebih kompleks dan fleksibel. Mendukung sistem poin berlevel (Internal & Eksternal) dengan hierarki **3 level**: Aspek Utama → Sub-Aspek → Jenis Kegiatan.

### Konsep Sistem Penilaian

Setiap poin kegiatan mahasiswa memiliki **Aspek Utama** (tetap 4), dibreakdown ke **Sub-Aspek**, lalu ke **Jenis Kegiatan** spesifik. Tiap Jenis Kegiatan punya poin berbeda-beda per **level cakupan**:

#### Level Internal (warna biru muda)
| Kode | Keterangan |
|------|------------|
| **A** | Asrama |
| **P** | Prodi |
| **F** | Fakultas |
| **U** | Universitas |

#### Level Eksternal (warna merah)
| Kode | Keterangan |
|------|--------------|
| **W** | Wilayah (JABODETABEK) |
| **N** | Nasional |
| **I** | Internasional |

> Jika sebuah kegiatan tidak relevan pada level tertentu, nilai dikosongkan (`–`).

### Hierarki 3 Level

```
Aspek Utama (4, TETAP — di-seed, tidak bisa ditambah/hapus dari UI)
  └─ Sub-Aspek (dinamis — bisa tambah/edit/hapus/reorder per Aspek Utama)
       └─ Jenis Kegiatan (dinamis — tiap baris punya 7 nilai poin A/P/F/U/W/N/I)
```

**4 Aspek Utama yang ditetapkan:**
1. Akademik
2. Leadership
3. Karakter Islami
4. Kreatifitas

### Contoh Struktur Data Lengkap

```
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
[1] AKADEMIK
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  ▸ Sub-Aspek: Prestasi Nilai
    ├─ IP Semester ≥ 3,00                 → U:4
    ├─ IP Semester ≥ 3,50                 → U:6
    ├─ Berprestasi Tingkat Prodi          → P:6
    ├─ Berprestasi Tingkat Fakultas       → F:7
    └─ Berprestasi Tingkat Universitas    → U:8

  ▸ Sub-Aspek: Kompetisi Akademik
    ├─ Umum/Favorit/Harapan              → A:4, P:4, F:5, U:6, W:5, N:6, I:7
    ├─ Juara III                          → A:5, P:5, F:6, U:7, W:6, N:7, I:8
    ├─ Juara II                           → A:6, P:6, F:7, U:8, W:7, N:8, I:9
    └─ Juara I                            → A:7, P:7, F:8, U:9, W:8, N:9, I:10

  ▸ Sub-Aspek: Mentoring Akademik
    ├─ Mengikuti Mentoring Akademik       → A:2
    ├─ Mengikuti KBT (Kelompok Belajar)  → A:3, P:4, F:4, U:5
    ├─ Bimbingan Akademik Dosen          → A:2, P:3, F:4
    ├─ Program Pendampingan Skripsi      → A:5, P:6
    └─ Narasumber Mentoring Akademik     → A:4, P:5, F:6, U:7, W:6, N:7, I:8

  ▸ Sub-Aspek: Karya Ilmiah
    ├─ Menulis Artikel/Jurnal Kampus      → A:3, P:4
    ├─ Publikasi Jurnal Nasional          → N:8
    └─ Publikasi Jurnal Internasional     → I:10

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
[2] LEADERSHIP
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  ▸ Sub-Aspek: Organisasi Internal
    ├─ Anggota Aktif Organisasi           → A:3, P:3, F:4, U:5
    ├─ Pengurus Organisasi                → A:5, P:5, F:6, U:7
    └─ Ketua/Presiden Organisasi         → A:7, P:7, F:8, U:9

  ▸ Sub-Aspek: Organisasi Eksternal
    ├─ Anggota Aktif                      → W:4, N:6, I:8
    ├─ Pengurus                           → W:6, N:8, I:10
    └─ Ketua/Pimpinan                    → W:8, N:10, I:12

  ▸ Sub-Aspek: Kepanitiaan & Event
    ├─ Anggota Panitia                    → A:2, P:3, F:4, U:5, W:4, N:5, I:6
    ├─ Koordinator/Divisi                 → A:3, P:4, F:5, U:6, W:5, N:6, I:7
    └─ Ketua Pelaksana                   → A:5, P:6, F:7, U:8, W:7, N:8, I:9

  ▸ Sub-Aspek: Pelatihan Kepemimpinan
    ├─ Peserta Pelatihan Leadership       → A:3, P:3, F:4, U:5, W:4, N:5, I:6
    └─ Trainer/Fasilitator               → A:5, P:5, F:6, U:7, W:6, N:8, I:10

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
[3] KARAKTER ISLAMI
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  ▸ Sub-Aspek: Hafalan Al-Qur'an
    ├─ Menghafal 1 Juz (baru)             → A:5
    ├─ Setoran Hafalan (per kali)         → A:2
    └─ Khatam Hafalan Target Semester    → A:8, P:8

  ▸ Sub-Aspek: Kajian & Kegiatan Keislaman
    ├─ Mengikuti Kajian Rutin             → A:2
    ├─ Narasumber/Pemateri Kajian         → A:4, P:5, F:6, U:7, W:6, N:7, I:8
    └─ Mengikuti Pesantren/Daurah         → A:5, W:6, N:8, I:10

  ▸ Sub-Aspek: Kompetisi Keislaman
    ├─ Peserta MTQ/MSQ                   → A:3, P:4, F:5, U:6, W:5, N:6, I:7
    ├─ Juara III MTQ/MSQ                 → A:5, P:5, F:6, U:7, W:6, N:7, I:8
    ├─ Juara II MTQ/MSQ                  → A:6, P:6, F:7, U:8, W:7, N:8, I:9
    └─ Juara I MTQ/MSQ                   → A:7, P:7, F:8, U:9, W:8, N:9, I:10

  ▸ Sub-Aspek: Dakwah & Sosial
    ├─ Relawan Kegiatan Sosial Keislaman  → A:3, P:3, F:4, U:5
    └─ Penyelenggara Program Dakwah      → A:5, P:5, F:6, U:7, W:6, N:8

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
[4] KREATIFITAS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  ▸ Sub-Aspek: Seni & Budaya
    ├─ Peserta Pentas Seni                → A:3, P:3, F:4, U:5
    ├─ Juara Lomba Seni                  → A:5, P:5, F:6, U:7, W:6, N:7, I:8
    └─ Penyelenggara Event Seni          → A:5, P:5, F:6, U:7

  ▸ Sub-Aspek: Kompetisi Kreatif
    ├─ Peserta Lomba Desain/Foto/Film    → A:3, P:4, F:5, U:6, W:5, N:6, I:7
    ├─ Juara III                          → A:5, P:5, F:6, U:7, W:6, N:7, I:8
    ├─ Juara II                           → A:6, P:6, F:7, U:8, W:7, N:8, I:9
    └─ Juara I                            → A:7, P:7, F:8, U:9, W:8, N:9, I:10

  ▸ Sub-Aspek: Kewirausahaan
    ├─ Memiliki Usaha Aktif               → A:4
    ├─ Omzet/Skala Kampus                 → P:5, F:6, U:7
    └─ Peserta/Juara Kompetisi Bisnis    → A:5, P:6, F:7, U:8, W:7, N:8, I:10

  ▸ Sub-Aspek: Teknologi & Inovasi
    ├─ Membuat Karya/Produk Digital       → A:4, P:5, F:6
    ├─ Peserta Hackathon/Inovasi          → A:4, P:5, F:6, U:7, W:6, N:7, I:8
    └─ Juara Hackathon/Inovasi           → A:6, P:7, F:8, U:9, W:8, N:9, I:10
```

### Desain UI Halaman Komponen Penilaian

```
┌──────────────────────────────────────────────────────────────────────────────┐
│  Pengaturan > Komponen Penilaian                                              │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                               │
│  [Tab: Akademik] [Tab: Leadership] [Tab: Karakter Islami] [Tab: Kreatifitas]  │
│  ─────────────────────────────────────────────────────────────────────────── │
│                                                                               │
│  ╔═ Sub-Aspek: Prestasi Nilai ══════════════════════ [Edit Nama] [Hapus] [▼] ╗│
│  ║                                                                           ║│
│  ║  ┌────┬──────────────────────────────────────────┬─────────┬───────────┐ ║│
│  ║  │ No │ Jenis Kegiatan                           │Internal │ Eksternal │ ║│
│  ║  │    │                                          │A P F U  │  W  N  I  │ ║│
│  ║  ├────┼──────────────────────────────────────────┼─────────┼───────────┤ ║│
│  ║  │ 1  │ IP Semester ≥ 3,00                       │– – – 4  │  –  –  –  │ ║│
│  ║  │ 2  │ IP Semester ≥ 3,50                       │– – – 6  │  –  –  –  │ ║│
│  ║  │ 3  │ Berprestasi Tingkat Prodi                │– 6 – –  │  –  –  –  │ ║│
│  ║  │ 4  │ Berprestasi Tingkat Fakultas             │– – 7 –  │  –  –  –  │ ║│
│  ║  │ 5  │ Berprestasi Tingkat Universitas          │– – – 8  │  –  –  –  │ ║│
│  ║  ├────┴──────────────────────────────────────────┴─────────┴───────────┤ ║│
│  ║  │                           [+ Tambah Jenis Kegiatan]                  │ ║│
│  ║  └──────────────────────────────────────────────────────────────────────┘ ║│
│  ╚═══════════════════════════════════════════════════════════════════════════╝│
│                                                                               │
│  ╔═ Sub-Aspek: Kompetisi Akademik ══════════════════ [Edit Nama] [Hapus] [▼] ╗│
│  ║  ┌────┬──────────────────────────────────────────┬─────────┬───────────┐ ║│
│  ║  │ No │ Jenis Kegiatan                           │A P F U  │  W  N  I  │ ║│
│  ║  ├────┼──────────────────────────────────────────┼─────────┼───────────┤ ║│
│  ║  │ 1  │ Umum/Favorit/Harapan                     │4 4 5 6  │  5  6  7  │ ║│
│  ║  │ 2  │ Juara III                                │5 5 6 7  │  6  7  8  │ ║│
│  ║  │ 3  │ Juara II                                 │6 6 7 8  │  7  8  9  │ ║│
│  ║  │ 4  │ Juara I                                  │7 7 8 9  │  8  9 10  │ ║│
│  ║  └──────────────────────────────────────────────────────────────────────┘ ║│
│  ╚═══════════════════════════════════════════════════════════════════════════╝│
│                                                                               │
│  ╔═ Sub-Aspek: Mentoring Akademik ══════════════════ [Edit Nama] [Hapus] [▲] ╗│
│  ║  (collapsed)                                                              ║│
│  ╚═══════════════════════════════════════════════════════════════════════════╝│
│                                                                               │
│                              [+ Tambah Sub-Aspek]                            │
└──────────────────────────────────────────────────────────────────────────────┘
```

**Legend Header Kolom Poin:**
- Header **Internal** (biru muda): A · P · F · U
- Header **Eksternal** (merah): W · N · I
- Nilai kosong ditampilkan sebagai `–` (bukan 0)

### Interaksi & Form

- **4 Tab fixed** di atas (Akademik / Leadership / Karakter Islami / Kreatifitas) — tidak bisa dihapus/tambah
- **Accordion per Sub-Aspek** dalam setiap Tab — bisa expand/collapse, tambah, edit nama, hapus, reorder
- **Modal** saat tambah/edit Jenis Kegiatan → input: nama kegiatan, 7 field poin (A/P/F/U/W/N/I), keterangan/bukti
- Field poin bisa **kosong** (artinya `–`) atau diisi angka (integer/desimal)
- Kolom **Keterangan/Bukti** — teks bebas (contoh: "KHS/transkrip yang dilegalkan...")
- Validasi: minimal 1 kolom poin terisi per baris Jenis Kegiatan
- **Drag & drop** urutan Sub-Aspek dan Jenis Kegiatan (fase 2)

### Struktur Database (Rancangan)

```sql
-- Tabel aspek utama penilaian (4 baris tetap, di-seed)
komponen_penilaian_aspek
  id, nama_aspek, urutan, created_at, updated_at

-- Tabel sub-aspek, dinamis per Aspek Utama (accordion group di UI)
komponen_penilaian_sub_aspek
  id, aspek_id (FK -> komponen_penilaian_aspek), nama_sub_aspek, urutan,
  created_at, updated_at

-- Tabel jenis kegiatan & poin per level, dinamis per Sub-Aspek
komponen_penilaian_jenis
  id, sub_aspek_id (FK -> komponen_penilaian_sub_aspek), nama_kegiatan, urutan,
  poin_a, poin_p, poin_f, poin_u,   -- Internal
  poin_w, poin_n, poin_i,            -- Eksternal
  keterangan_bukti,
  created_at, updated_at
```

### Checklist

- [ ] Desain UI (wireframe/mockup) — **sedang**
- [ ] Review desain bersama sebelum coding
- [ ] Migration: tabel `komponen_penilaian_aspek`, `komponen_penilaian_sub_aspek` & `komponen_penilaian_jenis`
- [ ] Backend: CRUD controller + routes (Pengaturan)
- [ ] Frontend: halaman Komponen Penilaian (accordion + tabel inline edit)
- [ ] Integrasi ke form input penilaian mahasiswa (dropdown dinamis dari tabel ini)
- [ ] Migrasi data penilaian lama ke struktur baru

---

## Catatan

Beberapa item di atas masih perlu klarifikasi/desain lebih lanjut sebelum implementasi (khususnya #1 dan #2 poin "warga percobaan"). Urutan pengerjaan menyusul sesuai prioritas.
