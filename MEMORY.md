# MEMORY.md — SIMONAS Development & Architectural Log

## Overview
SIMONAS (Sistem Monitoring Asrama) adalah aplikasi web berbasis **Laravel 10 + Inertia.js (React + TypeScript) + Tailwind CSS** untuk manajemen, penilaian, monitoring hafalan, mentoring, aktivitas warga asrama, dan beasiswa.

---

## Modul & Fitur Terkini

### 1. Aktivitas Harian & Rate Limiting (September 2026)
- **Tujuan**: Mencegah penumpukan data di akhir bulan (*bulk submit/panic logging*) dan mendisiplinkan mahasiswa untuk input kegiatan secara harian.
- **Batasan Kuota**: Maksimal **10 data per kategori per hari** untuk tiap mahasiswa:
  - Akademik: 10 data / hari
  - Leadership: 10 data / hari
  - Karakter Islami: 10 data / hari
  - Kreativitas & Kewirausahaan: 10 data / hari
- **Mekanisme Backend**:
  - `AktivitasController@index` (`Web/Mahasiswa`): Menghitung input hari ini (`whereDate('created_at', today())`) untuk ke-4 kategori dan mengirim payload `dailyQuota: { limit, counts }` ke frontend.
  - `AktivitasController@store` (`Web/Mahasiswa` & `Api/V2`): Validasi jumlah input harian sebelum insert database. Melempar `ValidationException` jika kuota harian telah tercapai.
  - **Dynamic Setting**: Batas kuota harian dikonfigurasi melalui `AppSetting::val('max_daily_activity_per_category', 10)` dan dapat disesuaikan Super Admin via menu **Pengaturan**.
- **Preventive Frontend UX (`Index.tsx`)**:
  - Kartu ringkasan 4 kategori menampilkan counter harian `Hari ini: X / 10`, mini progress bar, dan status `Sisa Kuota` atau `Penuh (10/10)`.
  - Tab header menampilkan badge status kuota untuk kategori aktif.
  - Form modal input menampilkan banner peringatan dan menonaktifkan tombol simpan jika kategori yang dipilih sudah mencapai batas kuota harian (create baru diblokir, edit data lama tetap diizinkan).

### 2. Komponen Penilaian 3-Level (Agustus 2026)
- Struktur cascade 3-level:
  - `komponen_penilaian_aspek`: 4 aspek utama (`akademik`, `leadership`, `karakter_islami`, `kreatifitas`).
  - `komponen_penilaian_sub_aspek`: Kelompok sub-aspek dinamis.
  - `komponen_penilaian_jenis`: Jenis kegiatan dengan tabel poin berjenjang (Internal: A, P, F, U; Eksternal: W, N, I) serta keterangan bukti.
- Integrasi ke formulir pencatatan aktivitas mahasiswa (`Mahasiswa/Aktivitas/Index.tsx`) dan halaman konfigurasi Super Admin (`Super/Pengaturan.tsx`).

### 3. Log Book Mentoring & Beasiswa Yayasan
- Mentoring logs per mentee dengan integrasi ringkasan dashboard.
- Modul proposal bulanan beasiswa yayasan otomatis ter-generate setiap awal bulan berdasarkan syarat aktivitas & kehadiran kajian/subuh.

---

## Konvensi Kode & Operasional
- **Waktu & Timezone**: `Asia/Jakarta` (WIB).
- **Format File**: PDF & JPEG/PNG (dikompres otomatis max 1600px, quality 75) disimpan sebagai BLOB (`file_data`) di PostgreSQL / driver database.
- **Frontend**: Inertia React dengan TypeScript (`resources/js/Pages`), styling Tailwind CSS + glassmorphism.
- **Deploy Script**: `update.sh` (shared hosting) / `deploy.sh` (VPS Ubuntu/Nginx).
