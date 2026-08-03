# Rencana Update SIMONAS

Backlog fitur yang diminta untuk dikerjakan setelah ini. Status: **belum dikerjakan**, baru didaftar untuk referensi & prioritas ke depan.

---

## 1. Onboarding Wizard

Desain alur onboarding wizard untuk warga baru (kemungkinan saat registrasi/aktivasi akun pertama kali). Perlu didesain dulu (alur langkah, data apa saja yang diisi) sebelum implementasi.

- [ ] Desain alur wizard (langkah-langkah, data yang dikumpulkan)
- [ ] Review desain bareng sebelum coding
- [ ] Implementasi

---

## 2. Filter Tambahan di Menu Warga

Tambahkan filter di halaman manajemen Warga:

- [ ] Filter berdasarkan **semester kuliah**
- [ ] Filter berdasarkan **status warga**
- [ ] Filter berdasarkan **lama menjadi warga percobaan** (butuh klarifikasi: field/kolom apa yang jadi acuan "warga percobaan" dan durasinya)

---

## 3. Log Book Mentoring

Tambahkan menu **Log Book Mentoring**, termasuk section-nya di dashboard mentoring.

- [ ] Desain struktur data log book (apa yang dicatat per sesi mentoring)
- [ ] Menu Log Book Mentoring (CRUD)
- [ ] Section ringkasan di Dashboard Mentoring

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
