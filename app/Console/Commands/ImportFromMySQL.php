<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Import data dari CSV hasil export MySQL lama ke PostgreSQL baru.
 *
 * Letakkan CSV di:  database/migration-data/
 * Nama file harus sesuai nama tabel, contoh: users.csv, asramas.csv, dst.
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
                            {--dry-run: Jalankan tanpa benar-benar menyimpan ke DB}';

    protected $description = 'Import data dari CSV (export MySQL lama) ke PostgreSQL';

    private string $dataDir;

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

    public function handle(): int
    {
        $this->dataDir = database_path('migration-data');
        $dryRun = $this->option('dry-run');
        $fresh  = $this->option('fresh');
        $only   = $this->option('table');

        if ($dryRun) {
            $this->warn('⚠️  DRY-RUN — tidak ada data yang disimpan.');
        }

        $tables = $only ? [$only] : $this->importOrder;

        foreach ($tables as $table) {
            $csv = "{$this->dataDir}/{$table}.csv";
            if (! file_exists($csv)) {
                $this->line("  ⏭️  {$table}.csv tidak ditemukan, skip.");
                continue;
            }

            $this->info("\n📥  Import {$table}...");

            if ($fresh && ! $dryRun) {
                DB::statement("TRUNCATE TABLE \"{$table}\" RESTART IDENTITY CASCADE");
                $this->line("   🗑️  Tabel {$table} di-truncate.");
            }

            $rows = $this->readCsv($csv);
            if (empty($rows)) {
                $this->warn("   ⚠️  File kosong atau header saja.");
                continue;
            }

            $method = 'import' . Str::studly($table);
            if (! method_exists($this, $method)) {
                $this->warn("   ⚠️  Belum ada handler untuk {$table}, skip.");
                continue;
            }

            $imported = 0;
            $skipped  = 0;

            DB::beginTransaction();
            try {
                foreach ($rows as $row) {
                    $data = $this->$method($row);
                    if ($data === null) {
                        $skipped++;
                        continue;
                    }
                    if (! $dryRun) {
                        DB::table($table)->upsert($data, $this->upsertKey($table), array_keys($data));
                    }
                    $imported++;
                }
                if (! $dryRun) {
                    DB::commit();
                    // Reset PostgreSQL sequence setelah import dengan ID manual
                    DB::statement("SELECT setval(pg_get_serial_sequence('\"{$table}\"','id'), COALESCE(MAX(id),0)+1, false) FROM \"{$table}\"");
                }
                $this->info("   ✅  {$imported} baris berhasil" . ($skipped ? ", {$skipped} di-skip" : '') . '.');
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->error("   ❌  Gagal: " . $e->getMessage());
            }
        }

        $this->info("\n✅  Import selesai.");
        return self::SUCCESS;
    }

    // ── CSV reader ────────────────────────────────────────────────────────────

    private function readCsv(string $path): array
    {
        $rows = [];
        if (($fh = fopen($path, 'r')) === false) return [];
        $headers = fgetcsv($fh);
        if (! $headers) { fclose($fh); return []; }
        $headers = array_map('trim', $headers);
        while (($line = fgetcsv($fh)) !== false) {
            if (count($line) !== count($headers)) continue;
            $rows[] = array_combine($headers, $line);
        }
        fclose($fh);
        return $rows;
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
            'asrama'          => $this->val($row, 'asrama'),
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
            'nama_warga'   => $this->val($row, 'nama_warga') ?? '-',
            'komponen'     => $this->val($row, 'komponen'),
            'asrama'       => $this->val($row, 'asrama') ?? '-',
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
            'nama_warga'   => $this->val($row, 'nama_warga') ?? '-',
            'komponen'     => $this->val($row, 'komponen'),
            'asrama'       => $this->val($row, 'asrama') ?? '-',
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
            'nama_warga'   => $this->val($row, 'nama_warga') ?? '-',
            'komponen'     => $this->val($row, 'komponen'),
            'asrama'       => $this->val($row, 'asrama') ?? '-',
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
            'nama_warga'   => $this->val($row, 'nama_warga') ?? '-',
            'komponen'     => $this->val($row, 'komponen'),
            'asrama'       => $this->val($row, 'asrama') ?? '-',
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
            'semester'   => $this->val($row, 'semester') ?? '1',
            'ipk'        => $this->val($row, 'ipk') ?? '0.00',
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
            'asal_asrama'            => $this->val($row, 'asal_asrama', 'asrama'),
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
