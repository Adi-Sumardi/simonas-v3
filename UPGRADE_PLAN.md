# Rencana Upgrade simonas-app — Path A (Konservatif)

Target akhir: **Laravel 10 + PHP 8.2**, Bootstrap 4 / jQuery / Mix tetap dipertahankan.

Estimasi total: **1–3 minggu** kerja efektif.

---

## Ringkasan State Saat Ini

| Komponen | Versi sekarang | Target |
|---|---|---|
| PHP | 7.2.5 | 8.2 |
| Laravel | 7.x | 10.x |
| Sanctum | 2.15 | 3.x |
| Laravel UI | 2.x | 4.x |
| Guzzle | 6.x | 7.x |
| PHPUnit | 8.5 | 10.x |
| Faker | fzaninotto/faker (abandoned) | fakerphp/faker |
| CORS | fruitcake/laravel-cors | bawaan Laravel |
| Ignition | facade/ignition 2 | spatie/laravel-ignition |
| Collision | 4.x | 7.x |
| Intervention Image | 2.7 | tetap 2.x (latest) |
| Maatwebsite Excel | 3.1 | 3.1 (latest) |
| Frontend | Bootstrap 4 + jQuery + Mix | **tetap** |

**Catatan struktur campuran yang harus dirapikan:**
- 21 model masih di `app/*.php` (namespace `App\`) — gaya Laravel 7
- 15 model sudah di `app/Models/*.php` — gaya Laravel 8+
- `database/seeds/` masih gaya L7 (harus jadi `database/seeders/`)
- Factory class-based gaya lama
- `phpunit.xml` masih pakai `<whitelist>` (PHPUnit 8)

---

## Tahap 0 — Persiapan (½ hari)

- [ ] `git init` di folder project (saat ini bukan git repo)
- [ ] Commit baseline: `git add -A && git commit -m "baseline before upgrade"`
- [ ] Buat branch: `git checkout -b upgrade/laravel-10`
- [ ] Backup `.env` ke `.env.backup`
- [ ] Backup database (`mysqldump` atau dump tool yang dipakai)
- [ ] Jalankan test suite existing: `vendor/bin/phpunit` — catat baseline pass/fail
- [ ] Cek package `latfur/laravel-event-crud` apakah support PHP 8 / Laravel 8+ — kalau tidak, siapkan rencana fork atau pengganti
- [ ] Dokumentasikan semua menu/fitur kritikal untuk smoke test (login, dashboard, alumni, mahasiswa, kegiatan, export Excel, captcha, API V2)

---

## Tahap 1 — PHP 7.2 → 8.0 di Laravel 7 (1 hari)

Tujuan: keluar dari PHP yang sudah EOL, sambil tetap di Laravel 7 dulu.

- [ ] Edit `composer.json`: `"php": "^8.0"`
- [ ] Naikkan `nunomaduro/collision` ke versi yang kompatibel PHP 8 (`^5.0`)
- [ ] `composer update`
- [ ] Jalankan `php artisan serve`, smoke test golden path
- [ ] Fix deprecation/warning PHP 8 yang umum:
  - parameter optional sebelum required
  - `null` di-pass ke `strlen()`, `trim()`, `str_replace()`
  - implicit float-to-int conversion
- [ ] Commit: `php 7.2 -> 8.0 on laravel 7`

---

## Tahap 2 — Laravel 7 → 8 (2–3 hari)

Tahap dengan **breaking change paling besar** karena harus rapikan struktur model & seeder.

### 2.1 Pindahkan model ke `app/Models/`
- [ ] Pindahkan 21 file dari `app/*.php` → `app/Models/*.php` (kecuali `User.php` yang sudah ada — merge manual jika bentrok)
- [ ] Ganti `namespace App;` → `namespace App\Models;` di semua file yang dipindah
- [ ] Bulk replace di seluruh codebase:
  - `use App\Akademik;` → `use App\Models\Akademik;`
  - `use App\Alumni;` → `use App\Models\Alumni;`
  - dst untuk: `Asrama`, `Karakter`, `Kegiatan`, `Komponen`, `Kreatif`, `Leadership`, `Event`, `FormAkses`, `Ipk`, `MasterJob`, `Mentor`, `Province`, `Regency`, `User`, `AlumniOrganisasi`, `AlumniPekerjaan`, `AlumniPendidikan`, `AlumniPrestasi`, `KegiatanKalender`
- [ ] Update `config/auth.php`: `'model' => App\Models\User::class`
- [ ] Update `app/Providers/AuthServiceProvider.php` jika ada policy mapping

### 2.2 Rename seeders
- [ ] Rename folder `database/seeds/` → `database/seeders/`
- [ ] Tambah `namespace Database\Seeders;` di semua file seeder
- [ ] Update `composer.json` `autoload.classmap`: hapus `database/seeds`, ganti dengan `autoload.psr-4` mapping `Database\\Seeders\\` → `database/seeders/`
- [ ] Update `DatabaseSeeder.php` agar pakai FQCN class lain

### 2.3 Update factories ke class-based
- [ ] Convert tiap file di `database/factories/` ke class extends `Factory`
- [ ] Tambah trait `HasFactory` di tiap model
- [ ] Replace `factory(User::class)` → `User::factory()` di tests/seeders

### 2.4 Update package
- [ ] `composer.json`:
  - `laravel/framework: ^8.0`
  - `laravel/sanctum: ^2.15` (latest 2.x)
  - `laravel/ui: ^3.0`
  - `fzaninotto/faker` → hapus, ganti `fakerphp/faker: ^1.9`
  - `nunomaduro/collision: ^5.0`
- [ ] `composer dump-autoload && composer update`
- [ ] `php artisan migrate:fresh --seed` (dengan DB test)
- [ ] Smoke test full
- [ ] Commit: `upgrade laravel 7 -> 8`

---

## Tahap 3 — Laravel 8 → 9 (2 hari)

- [ ] Edit `composer.json`:
  - `laravel/framework: ^9.0`
  - Hapus `fruitcake/laravel-cors`
  - `facade/ignition` → ganti `spatie/laravel-ignition: ^1.0`
  - `nunomaduro/collision: ^6.0`
  - `phpunit/phpunit: ^9.5`
- [ ] Update `config/cors.php` agar pakai middleware bawaan Laravel (`HandleCors`)
- [ ] Cek custom mailable — Symfony Mailer menggantikan SwiftMailer di L9 (signature method `build()` masih kompat tapi `Swift_Message` tidak ada lagi)
- [ ] Cek response macro / custom Symfony Request handling
- [ ] `composer update`
- [ ] `php artisan optimize:clear`
- [ ] Smoke test, fokus ke: email, file upload, response custom
- [ ] Commit: `upgrade laravel 8 -> 9`

---

## Tahap 4 — Laravel 9 → 10 (1–2 hari)

- [ ] Edit `composer.json`:
  - `"php": "^8.1"` (minimum L10)
  - `laravel/framework: ^10.0`
  - `laravel/sanctum: ^3.3`
  - `phpunit/phpunit: ^10.0`
  - `nunomaduro/collision: ^7.0`
  - `spatie/laravel-ignition: ^2.0`
  - `guzzlehttp/guzzle: ^7.2`
- [ ] Update `phpunit.xml`: ganti tag `<whitelist>` → `<coverage>` dengan `<include>`
- [ ] Cek tipe-tipe argument: L10 tambah strict types di banyak signature framework
- [ ] Jalankan `php artisan migrate` di staging — cek tidak ada migration error
- [ ] `composer update`
- [ ] Commit: `upgrade laravel 9 -> 10`

---

## Tahap 5 — Verifikasi & Hardening (1–2 hari)

Smoke test menyeluruh dengan checklist berikut:

- [ ] Login + register + reset password
- [ ] Sanctum API token (cek `routes/api.php`)
- [ ] Captcha (`mews/captcha`) tampil & validate
- [ ] CRUD Alumni full flow (`AlumniController`)
- [ ] CRUD Mahasiswa (`MahasiswaController`)
- [ ] CRUD Kegiatan, Karakter, Kreatif, Leadership
- [ ] Dashboard SuperAdmin (`SuperController`) — banyak query Carbon
- [ ] Mentor module
- [ ] Export Excel (Maatwebsite) — semua `app/Exports/`
- [ ] Import Excel — semua `app/Imports/`
- [ ] API V2 endpoints (`app/Http/Controllers/Api/V2/`)
- [ ] API TemanSeangkatan
- [ ] Email notifications (`app/Mail/`, `app/Notifications/`)
- [ ] File upload (Intervention Image)
- [ ] Schedule / queue jobs (`app/Console/`)
- [ ] Frontend assets build: `npm install && npm run dev`
- [ ] Run `php artisan route:list` — pastikan tidak ada error

---

## Risiko & Mitigasi

| Risiko | Mitigasi |
|---|---|
| `latfur/laravel-event-crud` tidak kompat | Cek di Tahap 0, fork atau ganti dengan event listener manual |
| `mews/captcha` issue di L10 | Cek versi terbaru kompat (3.3.x) |
| Maatwebsite Excel breaking di import/export besar | Test export setiap modul di Tahap 5 |
| Bulk replace namespace `App\` salah ganti string lain | Pakai regex `\buse App\\([A-Z]\w+);` agar presisi |
| Migration di L10 strict | Test di DB clone dulu sebelum prod |
| Custom helper / global function konflik | Cek `app/Helpers/` jika ada |

---

## Setelah Path A Selesai

Pertimbangkan Path B jika ada kebutuhan UI baru:
- Mix → Vite
- Bootstrap 4 → 5
- Optional: Livewire 3 untuk fitur reaktif tanpa rewrite
