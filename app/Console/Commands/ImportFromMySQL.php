<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Import data dari dump SQL (phpMyAdmin export) MySQL lama ke PostgreSQL baru.
 *
 * Letakkan file .sql di:  database/migration-data/
 * Nama file harus sesuai nama tabel, contoh: users.sql, asramas.sql, dst.
 *
 * Jalankan:
 *   php artisan migrate:from-mysql            # semua tabel
 *   php artisan migrate:from-mysql --table=users  # satu tabel saja
 *   php artisan migrate:from-mysql --fresh    # truncate dulu sebelum import
 */
class ImportFromMySQL extends Command
{
    protected $signature = 'migrate:from-mysql
                            {--table= : Import satu tabel saja}
                            {--fresh  : Truncate tabel sebelum import}
                            {--dry-run : Jalankan tanpa benar-benar menyimpan ke DB}';

    protected $description = 'Import data dari CSV (export MySQL lama) ke PostgreSQL';

    private string $dataDir;

    // aspek `komponens` => nama_komponen (normalisasi) => id, dimuat sekali dari komponens.sql
    private ?array $komponenLookup = null;

    /** @var array<int, true> id user yang sudah diproses dari data lama, untuk validasi FK */
    private array $validUserIds = [];

    // tabel penilaian => aspek yang sesuai di tabel `komponens`
    private const ASPEK_PER_TABLE = [
        'akademiks'   => 'Akademik',
        'leaderships' => 'Leadership',
        'karakters'   => 'Karakter Islami',
        'kreatifs'    => 'Kreativitas & Kewirausahaan',
    ];

    // Nama asrama lama yang sebenarnya alias/nama-lama dari asrama yang sudah ada,
    // bukan asrama baru. Dinormalisasi ke nama kanonik saat import.
    private const ASRAMA_ALIASES = [
        'Asrama Darul Quran Fatahillah' => 'Asrama Putri',
    ];

    private function canonicalAsrama(?string $nama): ?string
    {
        $nama = $nama !== null ? trim($nama) : null;
        if ($nama === null || $nama === '') return $nama;
        return self::ASRAMA_ALIASES[$nama] ?? $nama;
    }

    // Urutan import penting — tabel yang di-referensikan harus lebih dulu
    private array $importOrder = [
        'asramas',
        'users',
        'kegiatans',
        'akademiks',
        'leaderships',
        'karakters',
        'kreatifs',
        'ipks',
        'alumnis',
        'hafalans',
        'hafalan_logs',
    ];

    private const UPSERT_CHUNK = 500;

    public function handle(): int
    {
        // Dump lama bisa puluhan ribu baris per tabel (mis. karakters ~52rb) —
        // default CLI memory_limit (128M) sering kurang untuk parse + hold sekaligus.
        ini_set('memory_limit', '1024M');

        $this->dataDir = database_path('migration-data');
        $dryRun = $this->option('dry-run');
        $fresh  = $this->option('fresh');
        $only   = $this->option('table');

        if ($dryRun) {
            $this->warn('⚠️  DRY-RUN — tidak ada data yang disimpan.');
        }

        // komponens harus ada duluan — akademiks/leaderships/karakters/kreatifs
        // punya FK komponen_id yang butuh tabel ini sudah terisi.
        if (! $dryRun && DB::table('komponens')->count() === 0) {
            $this->info('🌱  Seeding komponens (belum ada data)...');
            $this->call('db:seed', ['--class' => \Database\Seeders\KomponenSeeder::class, '--force' => true]);
        }

        $tables = $only ? [$only] : $this->importOrder;

        foreach ($tables as $table) {
            $sqlFile = "{$this->dataDir}/{$table}.sql";
            if (! file_exists($sqlFile)) {
                $this->line("  ⏭️  {$table}.sql tidak ditemukan, skip.");
                continue;
            }

            $this->info("\n📥  Import {$table}...");

            if ($fresh && ! $dryRun) {
                DB::statement("TRUNCATE TABLE \"{$table}\" RESTART IDENTITY CASCADE");
                $this->line("   🗑️  Tabel {$table} di-truncate.");
            }

            $rows = $this->readSql($sqlFile, $table);
            if (empty($rows)) {
                $this->warn("   ⚠️  Tidak ada baris INSERT ditemukan di dump.");
                continue;
            }

            // asramas lama = log riwayat kepengurusan per tahun (1 asrama fisik
            // bisa punya banyak baris). Skema baru pisah: asramas (master,
            // 1 baris/asrama) + asrama_jabatans (riwayat per tahun).
            if ($table === 'asramas') {
                $this->importAsramasWithJabatans($rows, $dryRun);
                continue;
            }

            $method = 'import' . Str::studly($table);
            if (! method_exists($this, $method)) {
                $this->warn("   ⚠️  Belum ada handler untuk {$table}, skip.");
                continue;
            }

            $imported = 0;
            $skipped  = 0;
            $orphans  = 0;
            $buffer   = [];

            DB::beginTransaction();
            try {
                foreach ($rows as $row) {
                    $data = $this->$method($row);
                    if ($data === null) {
                        $skipped++;
                        continue;
                    }
                    if ($table === 'users') {
                        $this->validUserIds[(int) $data['id']] = true;
                    }
                    // Data lama sering merujuk user_id yang sudah tidak ada
                    // (akun dihapus di sistem lama) — skip, jangan gagalkan seluruh tabel.
                    if (array_key_exists('user_id', $data) && $data['user_id'] !== null
                        && ! isset($this->validUserIds[(int) $data['user_id']])) {
                        $orphans++;
                        continue;
                    }
                    $imported++;
                    if ($dryRun) continue;

                    $buffer[] = $data;
                    if (count($buffer) >= self::UPSERT_CHUNK) {
                        DB::table($table)->upsert($buffer, $this->upsertKey($table), array_keys($data));
                        $buffer = [];
                    }
                }
                if (! $dryRun) {
                    if ($buffer) {
                        DB::table($table)->upsert($buffer, $this->upsertKey($table), array_keys($buffer[0]));
                    }
                    DB::commit();
                    // Reset PostgreSQL sequence setelah import dengan ID manual
                    DB::statement("SELECT setval(pg_get_serial_sequence('\"{$table}\"','id'), COALESCE(MAX(id),0)+1, false) FROM \"{$table}\"");
                }
                $suffix = ($skipped ? ", {$skipped} di-skip" : '') . ($orphans ? ", {$orphans} user_id tidak ditemukan" : '');
                $this->info("   ✅  {$imported} baris berhasil{$suffix}.");
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->error("   ❌  Gagal: " . $e->getMessage());
            }
        }

        $this->reconcileMissingAsramas($dryRun);

        if (! $dryRun) {
            // Kolom users.role (legacy) belum otomatis ke-sync ke role Spatie
            // (model_has_roles) — tanpa ini, middleware can:access-* di routes/web.php
            // akan block semua user hasil migrasi.
            $this->info('🔑  Sync role Spatie dari kolom users.role...');
            $this->call('db:seed', ['--class' => \Database\Seeders\RolePermissionSeeder::class, '--force' => true]);
        }

        $this->info("\n✅  Import selesai.");
        return self::SUCCESS;
    }

    /**
     * Beberapa asrama sudah terhapus dari tabel `asramas` di sistem lama sebelum
     * di-export, tapi namanya (kolom teks bebas `asrama`) masih dipakai di baris
     * akademiks/leaderships/karakters/kreatifs/users. Tanpa ini, asrama tsb tidak
     * pernah muncul di dropdown/filter manapun (semuanya query dari Asrama::all()).
     */
    private function reconcileMissingAsramas(bool $dryRun): void
    {
        if (! Schema::hasTable('asramas')) return;

        $known = DB::table('asramas')->pluck('nama_asrama')->map(fn ($n) => trim($n))->filter()->all();

        $referenced = [];
        foreach (['users', 'akademiks', 'leaderships', 'karakters', 'kreatifs'] as $table) {
            if (! Schema::hasColumn($table, 'asrama')) continue;
            foreach (DB::table($table)->whereNotNull('asrama')->distinct()->pluck('asrama') as $nama) {
                $nama = trim((string) $nama);
                if ($nama === '' || $nama === '-') continue;
                $referenced[$nama] = true;
            }
        }

        $missing = array_diff(array_keys($referenced), $known);
        if (empty($missing)) return;

        $this->warn("\n⚠️  Asrama dirujuk di data aktivitas tapi tidak ada di tabel master: " . implode(', ', $missing));
        if ($dryRun) return;

        foreach ($missing as $nama) {
            DB::table('asramas')->insert([
                'nama_asrama'   => $nama,
                'kapasitas'     => 40,
                'tahun_jabatan' => '-',
                'direktur'      => '-',
                'ketua'         => '-',
                'created_at'    => $this->now(),
                'updated_at'    => $this->now(),
            ]);
        }
        DB::statement("SELECT setval(pg_get_serial_sequence('\"asramas\"','id'), COALESCE(MAX(id),0)+1, false) FROM \"asramas\"");
        $this->info('   ✅  ' . count($missing) . ' asrama ditambahkan ke tabel master (data direktur/ketua tidak diketahui, diisi "-").');
    }

    /**
     * Dedupe log riwayat `asramas` lama (1 baris per pergantian direktur/ketua)
     * jadi tabel master `asramas` (1 baris per nama_asrama) + `asrama_jabatans`
     * (riwayat per tahun). Id master dipilih dari baris dengan tahun_jabatan
     * paling baru per grup.
     */
    private function importAsramasWithJabatans(array $rows, bool $dryRun): void
    {
        $groups = [];
        foreach ($rows as $row) {
            $nama = $this->canonicalAsrama($this->val($row, 'nama_asrama', 'nama')) ?? '';
            if ($nama === '') continue;
            $groups[$nama][] = $row;
        }

        $asramaData   = [];
        $jabatanData  = [];
        $jabatanSeen  = []; // "asrama_id|tahun" => true, cegah bentrok unique constraint

        foreach ($groups as $nama => $groupRows) {
            usort($groupRows, fn ($a, $b) => strcmp((string) $this->val($a, 'tahun_jabatan'), (string) $this->val($b, 'tahun_jabatan')));
            $latest    = end($groupRows);
            $masterId  = (int) min(array_map(fn ($r) => (int) $this->val($r, 'id'), $groupRows));

            $asramaData[] = [
                'id'            => $masterId,
                'nama_asrama'   => $nama,
                'kapasitas'     => 40,
                'tahun_jabatan' => $this->val($latest, 'tahun_jabatan') ?? '-',
                'direktur'      => $this->val($latest, 'direktur') ?? '-',
                'ketua'         => $this->val($latest, 'ketua') ?? '-',
                'created_at'    => $this->val($latest, 'created_at') ?? $this->now(),
                'updated_at'    => $this->val($latest, 'updated_at') ?? $this->now(),
            ];

            foreach ($groupRows as $row) {
                $tahunRaw = (string) $this->val($row, 'tahun_jabatan');
                if (! preg_match('/\d{4}/', $tahunRaw, $m)) continue;
                $tahun = $m[0];
                $dupKey = "{$masterId}|{$tahun}";
                if (isset($jabatanSeen[$dupKey])) continue; // ambil yang pertama (sudah terurut lama->baru)
                $jabatanSeen[$dupKey] = true;

                $jabatanData[] = [
                    'asrama_id'  => $masterId,
                    'tahun'      => $tahun,
                    'direktur'   => $this->val($row, 'direktur'),
                    'ketua'      => $this->val($row, 'ketua'),
                    'created_at' => $this->val($row, 'created_at') ?? $this->now(),
                    'updated_at' => $this->val($row, 'updated_at') ?? $this->now(),
                ];
            }
        }

        if ($dryRun) {
            $this->info('   ✅  ' . count($asramaData) . ' asrama unik (dari ' . count($rows) . ' baris riwayat), ' . count($jabatanData) . ' baris jabatan.');
            return;
        }

        DB::beginTransaction();
        try {
            DB::table('asramas')->upsert($asramaData, ['id'], ['nama_asrama', 'kapasitas', 'tahun_jabatan', 'direktur', 'ketua', 'updated_at']);
            DB::statement("SELECT setval(pg_get_serial_sequence('\"asramas\"','id'), COALESCE(MAX(id),0)+1, false) FROM \"asramas\"");

            DB::table('asrama_jabatans')->upsert($jabatanData, ['asrama_id', 'tahun'], ['direktur', 'ketua', 'updated_at']);

            DB::commit();
            $this->info('   ✅  ' . count($asramaData) . ' asrama unik (dari ' . count($rows) . ' baris riwayat), ' . count($jabatanData) . ' baris jabatan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('   ❌  Gagal: ' . $e->getMessage());
        }
    }

    // ── SQL dump reader ──────────────────────────────────────────────────────

    /**
     * Parse baris "INSERT INTO `table` (`col`, ...) VALUES (...), (...);"
     * dari dump phpMyAdmin, tanpa perlu convert ke CSV.
     */
    private function readSql(string $path, string $table): array
    {
        $sql = file_get_contents($path);
        if ($sql === false) return [];

        $rows = [];
        $needle = "INSERT INTO `{$table}`";
        $offset = 0;
        $len = strlen($sql);

        while (($pos = strpos($sql, $needle, $offset)) !== false) {
            $afterName = $pos + strlen($needle);
            // Kolom langsung menyusul nama tabel: (`col1`, `col2`, ...) VALUES
            if (! preg_match('/^\s*\(([^)]+)\)\s*VALUES\s*/is', substr($sql, $afterName, 4000), $m)) {
                $offset = $afterName;
                continue;
            }
            $columns     = array_map(fn ($c) => trim($c, " `\t\r\n"), explode(',', $m[1]));
            $valuesStart = $afterName + strlen($m[0]);

            [$tuples, $consumed] = $this->parseValueTuples($sql, $valuesStart);
            foreach ($tuples as $tuple) {
                if (count($tuple) !== count($columns)) continue;
                $rows[] = array_combine($columns, $tuple);
            }

            $offset = $valuesStart + max($consumed, 1);
            if ($offset > $len) break;
        }

        return $rows;
    }

    /**
     * Pecah "(v1, v2, ...), (v1, v2, ...);" mulai dari $start jadi array of
     * tuples, sadar-kutipan (termasuk ; atau , di dalam string, escape \' dan
     * '', serta NULL). Berhenti di ';' yang berada di luar string/tanda kurung.
     *
     * @return array{0: array<int, array>, 1: int} [tuples, panjang karakter yang dikonsumsi]
     */
    private function parseValueTuples(string $sql, int $start): array
    {
        $tuples = [];
        $currentTuple = [];
        $depth = 0;
        $field = '';
        $isString = false;
        $inString = false;
        $len = strlen($sql);

        $pushField = function () use (&$currentTuple, &$field, &$isString) {
            $trimmed = trim($field);
            $currentTuple[] = (! $isString && strtoupper($trimmed) === 'NULL') ? null : $field;
            $field = '';
            $isString = false;
        };

        $i = $start;
        for (; $i < $len; $i++) {
            $ch = $sql[$i];

            if ($inString) {
                if ($ch === '\\' && $i + 1 < $len) {
                    $next = $sql[++$i];
                    $field .= match ($next) {
                        'n' => "\n",
                        'r' => "\r",
                        't' => "\t",
                        '0' => "\0",
                        default => $next,
                    };
                    continue;
                }
                if ($ch === "'") {
                    if ($i + 1 < $len && $sql[$i + 1] === "'") {
                        $field .= "'";
                        $i++;
                        continue;
                    }
                    $inString = false;
                    continue;
                }
                $field .= $ch;
                continue;
            }

            if ($ch === "'") {
                $inString = true;
                $isString = true;
                continue;
            }
            if ($ch === '(') {
                $depth++;
                if ($depth === 1) { $currentTuple = []; $field = ''; $isString = false; }
                else $field .= $ch;
                continue;
            }
            if ($ch === ')') {
                $depth--;
                if ($depth === 0) {
                    $pushField();
                    $tuples[] = $currentTuple;
                } else {
                    $field .= $ch;
                }
                continue;
            }
            if ($ch === ',' && $depth === 1) {
                $pushField();
                continue;
            }
            if ($ch === ';' && $depth === 0) {
                $i++;
                break;
            }
            if ($depth >= 1) {
                // buang whitespace delimiter sebelum token mulai (mis. ", 'Akademik'")
                if ($field === '' && ctype_space($ch)) continue;
                $field .= $ch;
            }
        }

        return [$tuples, $i - $start];
    }

    private function upsertKey(string $table): array
    {
        return match ($table) {
            'users'        => ['email'],
            'hafalans'     => ['user_id'],
            'alumnis'      => ['email'],
            default        => ['id'],
        };
    }

    private function val(array $row, string ...$keys): mixed
    {
        foreach ($keys as $key) {
            if (isset($row[$key]) && $row[$key] !== '') {
                return $row[$key];
            }
        }
        return null;
    }

    private function now(): string
    {
        return now()->toDateTimeString();
    }

    private function normalizeKomponenText(?string $text): string
    {
        $text = strtolower((string) $text);
        $text = preg_replace('/[^a-z0-9]+/', ' ', $text);
        return trim($text);
    }

    /** @return array<string, array<string, int>> aspek => (nama_komponen ternormalisasi => id) */
    private function komponenLookup(): array
    {
        if ($this->komponenLookup !== null) {
            return $this->komponenLookup;
        }

        $this->komponenLookup = [];
        $path = "{$this->dataDir}/komponens.sql";
        if (file_exists($path)) {
            foreach ($this->readSql($path, 'komponens') as $row) {
                $aspek = $this->val($row, 'aspek');
                $nama  = $this->val($row, 'nama_komponen');
                $id    = $this->val($row, 'id');
                if (! $aspek || ! $nama || ! $id) continue;
                $this->komponenLookup[$aspek][$this->normalizeKomponenText($nama)] = (int) $id;
            }
        }

        return $this->komponenLookup;
    }

    /**
     * Ambil komponen_id dari data lama; kalau kosong, coba cocokkan teks
     * kolom `komponen` (free text) ke `nama_komponen` di tabel komponens.
     */
    private function resolveKomponenId(array $row, string $table): ?int
    {
        $existing = $this->val($row, 'komponen_id');
        if ($existing) {
            return (int) $existing;
        }

        $aspek = self::ASPEK_PER_TABLE[$table] ?? null;
        $teks  = $this->val($row, 'komponen');
        if (! $aspek || ! $teks) {
            return null;
        }

        $lookup = $this->komponenLookup()[$aspek] ?? [];
        return $lookup[$this->normalizeKomponenText($teks)] ?? null;
    }

    // ── Handlers per tabel ───────────────────────────────────────────────────

    private function importAsramas(array $row): ?array
    {
        $nama = $this->val($row, 'nama_asrama', 'nama');
        if (! $nama) return null;

        return [
            'id'            => $this->val($row, 'id'),
            'nama_asrama'   => $nama,
            'tahun_jabatan' => $this->val($row, 'tahun_jabatan') ?? '-',
            'direktur'      => $this->val($row, 'direktur') ?? '-',
            'ketua'         => $this->val($row, 'ketua') ?? '-',
            'created_at'    => $this->val($row, 'created_at') ?? $this->now(),
            'updated_at'    => $this->val($row, 'updated_at') ?? $this->now(),
        ];
    }

    private function importUsers(array $row): ?array
    {
        $email = $this->val($row, 'email');
        if (! $email) return null;

        // Role: pastikan valid
        $validRoles = ['super', 'admin', 'mentor', 'mahasiswa', 'alumni'];
        $role = $this->val($row, 'role') ?? 'mahasiswa';
        if (! in_array($role, $validRoles)) $role = 'mahasiswa';

        // Password: kalau kosong buat default
        $password = $this->val($row, 'password');
        if (! $password || strlen($password) < 20) {
            // Buat password hash dari NIM atau email
            $nim = $this->val($row, 'no_induk', 'nim') ?? Str::before($email, '@');
            $password = Hash::make($nim);
        }

        return [
            'id'              => $this->val($row, 'id'),
            'name'            => $this->val($row, 'name', 'nama') ?? 'Unknown',
            'email'           => $email,
            'password'        => $password,
            'role'            => $role,
            'asrama'          => $this->canonicalAsrama($this->val($row, 'asrama')),
            'no_induk'        => $this->val($row, 'no_induk', 'nim', 'no_induk'),
            'status_warga'    => $this->val($row, 'status_warga') ?? 'aktif',
            'tgl_masuk'       => $this->val($row, 'tgl_masuk'),
            'tgl_keluar'      => $this->val($row, 'tgl_keluar'),
            'tgl_lahir'       => $this->val($row, 'tgl_lahir'),
            'alamat'          => $this->val($row, 'alamat'),
            'alamat_sekarang' => $this->val($row, 'alamat_sekarang'),
            'provinsi'        => $this->val($row, 'provinsi'),
            'kota'            => $this->val($row, 'kota'),
            'kecamatan'       => $this->val($row, 'kecamatan'),
            'kode_pos'        => $this->val($row, 'kode_pos'),
            'no_telp'         => $this->val($row, 'no_telp', 'no_hp'),
            'no_hp'           => $this->val($row, 'no_hp', 'no_telp'),
            'nik'             => $this->val($row, 'nik'),
            'asal_sekolah'    => $this->val($row, 'asal_sekolah'),
            'angkatan'        => $this->val($row, 'angkatan'),
            'universitas'     => $this->val($row, 'universitas'),
            'fakultas'        => $this->val($row, 'fakultas'),
            'prodi'           => $this->val($row, 'prodi'),
            'pekerjaan'       => $this->val($row, 'pekerjaan'),
            'nama_ayah'       => $this->val($row, 'nama_ayah'),
            'nama_ibu'        => $this->val($row, 'nama_ibu'),
            'prestasi'        => $this->val($row, 'prestasi'),
            'organisasi'      => $this->val($row, 'organisasi'),
            'tgl_seminar'     => $this->val($row, 'tgl_seminar'),
            'tgl_skripsi'     => $this->val($row, 'tgl_skripsi'),
            'tgl_wisuda'      => $this->val($row, 'tgl_wisuda'),
            'mentor_id'       => $this->val($row, 'mentor_id') ?: null,
            'avatar'          => null, // avatar lama tidak dimigrasi
            'created_at'      => $this->val($row, 'created_at') ?? $this->now(),
            'updated_at'      => $this->val($row, 'updated_at') ?? $this->now(),
        ];
    }

    private function importKegiatans(array $row): ?array
    {
        if (! $this->val($row, 'nama_kegiatan')) return null;

        return [
            'id'             => $this->val($row, 'id'),
            'nama_kegiatan'  => $this->val($row, 'nama_kegiatan'),
            'tujuan'         => $this->val($row, 'tujuan') ?? '-',
            'penyelenggara'  => $this->val($row, 'penyelenggara') ?? '-',
            'jenis_kegiatan' => $this->val($row, 'jenis_kegiatan') ?? 'umum',
            'waktu'          => $this->val($row, 'waktu') ?? '-',
            'tempat'         => $this->val($row, 'tempat') ?? '-',
            'keterangan'     => $this->val($row, 'keterangan') ?? '-',
            'file'           => null,
            'created_at'     => $this->val($row, 'created_at') ?? $this->now(),
            'updated_at'     => $this->val($row, 'updated_at') ?? $this->now(),
        ];
    }

    private function importAkademiks(array $row): ?array
    {
        if (! $this->val($row, 'user_id', 'nama_warga')) return null;

        return [
            'id'           => $this->val($row, 'id'),
            'user_id'      => $this->val($row, 'user_id'),
            'komponen_id'  => $this->resolveKomponenId($row, 'akademiks'),
            'nama_warga'   => $this->val($row, 'nama_warga') ?? '-',
            'komponen'     => $this->val($row, 'komponen'),
            'asrama'       => $this->canonicalAsrama($this->val($row, 'asrama')) ?? '-',
            'kegiatan'     => $this->val($row, 'kegiatan') ?? '-',
            'waktu'        => $this->val($row, 'waktu') ?? '-',
            'tempat'       => $this->val($row, 'tempat') ?? '-',
            'keterangan'   => $this->val($row, 'keterangan'),
            'file'         => null,
            'nama_penilai' => $this->val($row, 'nama_penilai'),
            'nilai'        => $this->val($row, 'nilai'),
            'created_at'   => $this->val($row, 'created_at') ?? $this->now(),
            'updated_at'   => $this->val($row, 'updated_at') ?? $this->now(),
        ];
    }

    private function importLeaderships(array $row): ?array
    {
        if (! $this->val($row, 'user_id', 'nama_warga')) return null;

        return [
            'id'           => $this->val($row, 'id'),
            'user_id'      => $this->val($row, 'user_id'),
            'komponen_id'  => $this->resolveKomponenId($row, 'leaderships'),
            'nama_warga'   => $this->val($row, 'nama_warga') ?? '-',
            'komponen'     => $this->val($row, 'komponen'),
            'asrama'       => $this->canonicalAsrama($this->val($row, 'asrama')) ?? '-',
            'kegiatan'     => $this->val($row, 'kegiatan') ?? '-',
            'waktu'        => $this->val($row, 'waktu') ?? '-',
            'tempat'       => $this->val($row, 'tempat') ?? '-',
            'keterangan'   => $this->val($row, 'keterangan'),
            'file'         => null,
            'nama_penilai' => $this->val($row, 'nama_penilai'),
            'nilai'        => $this->val($row, 'nilai'),
            'created_at'   => $this->val($row, 'created_at') ?? $this->now(),
            'updated_at'   => $this->val($row, 'updated_at') ?? $this->now(),
        ];
    }

    private function importKarakters(array $row): ?array
    {
        if (! $this->val($row, 'user_id', 'nama_warga')) return null;

        return [
            'id'           => $this->val($row, 'id'),
            'user_id'      => $this->val($row, 'user_id'),
            'komponen_id'  => $this->resolveKomponenId($row, 'karakters'),
            'nama_warga'   => $this->val($row, 'nama_warga') ?? '-',
            'komponen'     => $this->val($row, 'komponen'),
            'asrama'       => $this->canonicalAsrama($this->val($row, 'asrama')) ?? '-',
            'kegiatan'     => $this->val($row, 'kegiatan') ?? '-',
            'waktu'        => $this->val($row, 'waktu') ?? '-',
            'tempat'       => $this->val($row, 'tempat') ?? '-',
            'keterangan'   => $this->val($row, 'keterangan'),
            'file'         => null,
            'nama_penilai' => $this->val($row, 'nama_penilai'),
            'nilai'        => $this->val($row, 'nilai'),
            'created_at'   => $this->val($row, 'created_at') ?? $this->now(),
            'updated_at'   => $this->val($row, 'updated_at') ?? $this->now(),
        ];
    }

    private function importKreatifs(array $row): ?array
    {
        if (! $this->val($row, 'user_id', 'nama_warga')) return null;

        return [
            'id'           => $this->val($row, 'id'),
            'user_id'      => $this->val($row, 'user_id'),
            'komponen_id'  => $this->resolveKomponenId($row, 'kreatifs'),
            'nama_warga'   => $this->val($row, 'nama_warga') ?? '-',
            'komponen'     => $this->val($row, 'komponen'),
            'asrama'       => $this->canonicalAsrama($this->val($row, 'asrama')) ?? '-',
            'kegiatan'     => $this->val($row, 'kegiatan') ?? '-',
            'waktu'        => $this->val($row, 'waktu') ?? '-',
            'tempat'       => $this->val($row, 'tempat') ?? '-',
            'keterangan'   => $this->val($row, 'keterangan'),
            'file'         => null,
            'nama_penilai' => $this->val($row, 'nama_penilai'),
            'nilai'        => $this->val($row, 'nilai'),
            'created_at'   => $this->val($row, 'created_at') ?? $this->now(),
            'updated_at'   => $this->val($row, 'updated_at') ?? $this->now(),
        ];
    }

    private function importIpks(array $row): ?array
    {
        if (! $this->val($row, 'user_id')) return null;

        return [
            'id'         => $this->val($row, 'id'),
            'user_id'    => $this->val($row, 'user_id'),
            'ip'         => $this->val($row, 'ip') ?? '0.00',
            'tahun'      => $this->val($row, 'tahun') ?? '-',
            'semester'   => $this->val($row, 'semester') ?? '1',
            'file'       => $this->val($row, 'file') ?? '-',
            'created_at' => $this->val($row, 'created_at') ?? $this->now(),
            'updated_at' => $this->val($row, 'updated_at') ?? $this->now(),
        ];
    }

    private function importAlumnis(array $row): ?array
    {
        $email = $this->val($row, 'email');
        $nama  = $this->val($row, 'nama', 'name');
        if (! $email && ! $nama) return null;

        return [
            'id'                     => $this->val($row, 'id'),
            'nama'                   => $nama,
            'email'                  => $email,
            'gelar_depan'            => $this->val($row, 'gelar_depan'),
            'gelar_belakang'         => $this->val($row, 'gelar_belakang'),
            'provinsi_asal'          => $this->val($row, 'provinsi_asal'),
            'tanggal_lahir'          => $this->val($row, 'tanggal_lahir', 'tgl_lahir'),
            'alamat_domisili'        => $this->val($row, 'alamat_domisili', 'alamat_sekarang'),
            'alamat_jalan'           => $this->val($row, 'alamat_jalan', 'alamat'),
            'kota'                   => $this->val($row, 'kota'),
            'provinsi'               => $this->val($row, 'provinsi'),
            'kode_pos'               => $this->val($row, 'kode_pos'),
            'no_whatsapp'            => $this->val($row, 'no_whatsapp', 'no_hp', 'no_telp'),
            'jumlah_anak'            => $this->val($row, 'jumlah_anak'),
            'asal_asrama'            => $this->canonicalAsrama($this->val($row, 'asal_asrama', 'asrama')),
            'tahun_masuk_asrama'     => $this->val($row, 'tahun_masuk_asrama', 'angkatan'),
            'tahun_keluar_asrama'    => $this->val($row, 'tahun_keluar_asrama'),
            'pengalaman_organisasi'  => $this->val($row, 'pengalaman_organisasi', 'organisasi'),
            'pendidikan_terakhir'    => $this->val($row, 'pendidikan_terakhir'),
            'jurusan_s1'             => $this->val($row, 'jurusan_s1', 'prodi'),
            'kampus_s1'              => $this->val($row, 'kampus_s1', 'universitas'),
            'jurusan_s2'             => $this->val($row, 'jurusan_s2'),
            'kampus_s2'              => $this->val($row, 'kampus_s2'),
            'jurusan_s3'             => $this->val($row, 'jurusan_s3'),
            'kampus_s3'              => $this->val($row, 'kampus_s3'),
            'pekerjaan_sekarang'     => $this->val($row, 'pekerjaan_sekarang', 'pekerjaan'),
            'bidang_pekerjaan'       => $this->val($row, 'bidang_pekerjaan'),
            'bidang_keahlian'        => $this->val($row, 'bidang_keahlian'),
            'pengalaman_pekerjaan'   => $this->val($row, 'pengalaman_pekerjaan'),
            'created_at'             => $this->val($row, 'created_at') ?? $this->now(),
            'updated_at'             => $this->val($row, 'updated_at') ?? $this->now(),
        ];
    }

    private function importHafalans(array $row): ?array
    {
        if (! $this->val($row, 'user_id')) return null;

        return [
            'id'                    => $this->val($row, 'id'),
            'user_id'               => $this->val($row, 'user_id'),
            'target_juz'            => $this->val($row, 'target_juz') ?? 30,
            'current_juz'           => $this->val($row, 'current_juz') ?? 0,
            'current_ayah'          => $this->val($row, 'current_ayah') ?? 0,
            'total_ayah_completed'  => $this->val($row, 'total_ayah_completed') ?? 0,
            'streak_days'           => $this->val($row, 'streak_days') ?? 0,
            'last_tasmi_at'         => $this->val($row, 'last_tasmi_at'),
            'created_at'            => $this->val($row, 'created_at') ?? $this->now(),
            'updated_at'            => $this->val($row, 'updated_at') ?? $this->now(),
        ];
    }

    private function importHafalanLogs(array $row): ?array
    {
        if (! $this->val($row, 'user_id')) return null;

        $validScores = ['memtas', 'layak_ulang', 'perlu_perbaikan', 'pending'];
        $score = $this->val($row, 'score') ?? 'pending';
        if (! in_array($score, $validScores)) $score = 'pending';

        return [
            'id'         => $this->val($row, 'id'),
            'user_id'    => $this->val($row, 'user_id'),
            'mentor_id'  => $this->val($row, 'mentor_id') ?: null,
            'surah'      => $this->val($row, 'surah') ?? 'Al-Fatihah',
            'ayat_start' => $this->val($row, 'ayat_start') ?? 1,
            'ayat_end'   => $this->val($row, 'ayat_end') ?? 1,
            'score'      => $score,
            'notes'      => $this->val($row, 'notes', 'catatan'),
            'tested_at'  => $this->val($row, 'tested_at', 'created_at'),
            'created_at' => $this->val($row, 'created_at') ?? $this->now(),
            'updated_at' => $this->val($row, 'updated_at') ?? $this->now(),
        ];
    }
}
