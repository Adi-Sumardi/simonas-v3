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

## 4. Statistik Penerimaan Beasiswa Per Bulan (Laporan)

Tambahkan section **Statistik Penerimaan Beasiswa Per Bulan** di menu Laporan.

- [ ] Section statistik di halaman Laporan
- [ ] **Tidak ikut di-export** (dikecualikan dari export PDF Laporan yang sudah ada)

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

## Catatan

Beberapa item di atas masih perlu klarifikasi/desain lebih lanjut sebelum implementasi (khususnya #1 dan #2 poin "warga percobaan"). Urutan pengerjaan menyusul sesuai prioritas.
