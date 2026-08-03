<?php

namespace App\Console\Commands;

use App\Models\Province;
use App\Models\Regency;
use App\Models\User;
use Illuminate\Console\Command;

class NormalizeWilayahData extends Command
{
    protected $signature = 'app:normalize-wilayah {--dry-run}';

    protected $description = 'Cocokkan & rapikan data provinsi/kota lama di users (free-text) ke referensi wilayah resmi. Yang tidak cocok dilaporkan untuk dicek manual, bukan ditebak.';

    // Alias umum yang tidak bisa ditangkap normalisasi generik (beda kata, bukan
    // cuma beda kapitalisasi/spasi) — kunci sudah dalam bentuk ternormalisasi.
    private const PROVINCE_ALIAS = [
        'JAKARTA'          => 'DKI JAKARTA',
        'JAKARTA TIMUR'    => 'DKI JAKARTA',
        'JAKARTA BARAT'    => 'DKI JAKARTA',
        'JAKARTA SELATAN'  => 'DKI JAKARTA',
        'JAKARTA UTARA'    => 'DKI JAKARTA',
        'JAKARTA PUSAT'    => 'DKI JAKARTA',
        'YOGYAKARTA'       => 'DI YOGYAKARTA',
        'JOGJAKARTA'       => 'DI YOGYAKARTA',
        'JOGJA'            => 'DI YOGYAKARTA',
        'DIY'              => 'DI YOGYAKARTA',
    ];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $provinceByNormalized = [];
        foreach (Province::pluck('id', 'name') as $name => $id) {
            $provinceByNormalized[$this->norm($name)] = ['id' => $id, 'name' => $name];
        }

        $regencyExact    = []; // "province_id|NORM_NAME" => canonical name
        $regencyStripped = []; // NORM_NAME_TANPA_PREFIX => [ {province_id, name, province_name}, ... ]
        foreach (Regency::with('province')->get() as $r) {
            $normFull = $this->norm($r->name);
            $regencyExact["{$r->province_id}|{$normFull}"] = $r->name;
            $regencyStripped[$this->stripKotaKab($normFull)][] = [
                'province_id'   => $r->province_id,
                'name'          => $r->name,
                'province_name' => $r->province->name,
            ];
        }

        $unmatchedProvinsi = [];
        $unmatchedKota = [];
        $updated = 0;

        $users = User::where(fn ($q) => $q->whereNotNull('provinsi')->orWhereNotNull('kota'))
            ->get(['id', 'provinsi', 'kota']);

        foreach ($users as $user) {
            $newProvinsi = $user->provinsi;
            $newKota = $user->kota;
            $resolvedProvinceId = null;

            if ($user->provinsi) {
                $normP = preg_replace('/^PROVINSI\s+/', '', $this->norm($user->provinsi));
                $normP = self::PROVINCE_ALIAS[$normP] ?? $normP;
                if (isset($provinceByNormalized[$normP])) {
                    $newProvinsi = $provinceByNormalized[$normP]['name'];
                    $resolvedProvinceId = $provinceByNormalized[$normP]['id'];
                } else {
                    $unmatchedProvinsi[$user->provinsi] = ($unmatchedProvinsi[$user->provinsi] ?? 0) + 1;
                }
            }

            if ($user->kota) {
                $normK = $this->norm($user->kota);
                $matched = null;

                if ($resolvedProvinceId && isset($regencyExact["{$resolvedProvinceId}|{$normK}"])) {
                    $matched = $regencyExact["{$resolvedProvinceId}|{$normK}"];
                } else {
                    $candidates = $regencyStripped[$this->stripKotaKab($normK)] ?? [];
                    if ($resolvedProvinceId) {
                        $inProvince = array_values(array_filter($candidates, fn ($c) => $c['province_id'] === $resolvedProvinceId));
                        if (count($inProvince) === 1) {
                            $matched = $inProvince[0]['name'];
                        }
                    } elseif (count($candidates) === 1) {
                        $matched = $candidates[0]['name'];
                        $newProvinsi = $candidates[0]['province_name'];
                    }
                }

                if ($matched) {
                    $newKota = $matched;
                } else {
                    $unmatchedKota[$user->kota] = ($unmatchedKota[$user->kota] ?? 0) + 1;
                }
            }

            if ($newProvinsi !== $user->provinsi || $newKota !== $user->kota) {
                $updated++;
                if (!$dryRun) {
                    $user->forceFill(['provinsi' => $newProvinsi, 'kota' => $newKota])->save();
                }
            }
        }

        $this->info(($dryRun ? '[DRY-RUN] ' : '') . "{$updated} user diperbarui dari {$users->count()} total yang punya data wilayah.");

        if ($unmatchedProvinsi) {
            $this->warn('Provinsi tidak dikenali (butuh cek manual):');
            foreach ($unmatchedProvinsi as $v => $c) {
                $this->line("  [{$c}x] {$v}");
            }
        }
        if ($unmatchedKota) {
            $this->warn('Kota/Kabupaten tidak dikenali (butuh cek manual):');
            foreach ($unmatchedKota as $v => $c) {
                $this->line("  [{$c}x] {$v}");
            }
        }

        return self::SUCCESS;
    }

    private function norm(string $s): string
    {
        return strtoupper(trim(preg_replace('/\s+/', ' ', $s)));
    }

    private function stripKotaKab(string $s): string
    {
        return preg_replace('/^(KOTA|KABUPATEN|KAB\.?)\s+/', '', $s);
    }
}
