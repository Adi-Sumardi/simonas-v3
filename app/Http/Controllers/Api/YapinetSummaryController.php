<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Akademik;
use App\Models\HafalanLog;
use App\Models\Ipk;
use App\Models\Karakter;
use App\Models\Kreatif;
use App\Models\Leadership;
use App\Models\User;
use App\Models\UserEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

/**
 * Ringkasan untuk portal Yapinet — kontrak v1.1 (yapinet/rules/detail-pages.md).
 * Dipanggil server Yapinet (middleware `yapinet.auth`). Satu payload memuat
 * varian per asrama (`when: {asrama}`). Cakupan "semua asrama" hanya agregat;
 * daftar nama warga (IPK & poin) tampil saat satu asrama dipilih — permintaan
 * pengurus yayasan.
 */
class YapinetSummaryController extends Controller
{
    /** IPK di bawah ini dianggap perlu pendampingan akademik. */
    private const LOW_IPK = 2.75;

    /**
     * Rasio warga nonaktif di atas ini → perlu perhatian. `status_warga` di
     * Simonas hanya `aktif` / `nonaktif` — endpoint lama keliru melabeli
     * nonaktif sebagai "Cuti".
     */
    private const HIGH_INACTIVE_RATIO = 0.2;

    private const IPK_BUCKETS = [
        ['label' => '3,50 – 4,00', 'min' => 3.5, 'max' => 4.01],
        ['label' => '3,00 – 3,49', 'min' => 3.0, 'max' => 3.5],
        ['label' => '2,75 – 2,99', 'min' => 2.75, 'max' => 3.0],
        ['label' => 'Di bawah 2,75', 'min' => 0, 'max' => 2.75],
    ];

    public function summary(): JsonResponse
    {
        $warga = User::where('role', 'mahasiswa')->get(['id', 'name', 'asrama', 'status_warga', 'universitas', 'prodi', 'angkatan']);
        $ipk = $this->ipkPerUser($warga->pluck('id'));
        $asramas = $warga->pluck('asrama')->filter()->unique()->sort()->values();
        // Mesin poin yang sama dengan dashboard mahasiswa (User::calculatePoints()), dihitung sekali.
        $points = User::whereIn('id', $warga->where('status_warga', 'aktif')->pluck('id'))->get()
            ->mapWithKeys(fn (User $u) => [$u->id => $u->calculatePoints()]);

        $scopes = collect([['key' => 'all', 'label' => 'Semua asrama']])
            ->merge($asramas->map(fn ($a) => ['key' => $a, 'label' => $a]));

        $metrics = [];
        $attention = [];
        $sections = [];

        foreach ($scopes as $scope) {
            $when = ['asrama' => $scope['key']];
            $members = $scope['key'] === 'all' ? $warga : $warga->where('asrama', $scope['key']);
            $d = $this->scopeData($members, $ipk, $scope['key']);

            foreach ($this->metrics($d) as $metric) {
                $metrics[] = $metric + ['when' => $when];
            }
            foreach ($this->attention($d) as $item) {
                $attention[] = $item + ['when' => $when];
            }

            $sections[] = $this->ipkChart($d) + ['when' => $when];
            $sections[] = $this->activityChart($members->where('status_warga', 'aktif')->pluck('id')) + ['when' => $when];

            if ($scope['key'] === 'all') {
                $sections[] = $this->asramaTable($asramas, $warga, $ipk, $points) + ['when' => $when];
                $all = $d;
            } else {
                $sections[] = $this->memberTable($scope['label'], $members, $ipk, $points) + ['when' => $when];
            }
        }

        $status = ($all['low_ipk'] > 0 || $all['nonaktif_ratio'] > self::HIGH_INACTIVE_RATIO) ? 'warning' : 'ok';

        return response()->json([
            'contract_version' => 2,
            'status' => $status,
            'headline' => "{$all['aktif']} warga aktif di asrama".($all['low_ipk'] > 0 ? " · {$all['low_ipk']} perlu pendampingan akademik" : ''),
            'updated_at' => now()->toIso8601String(),
            'filters' => [[
                'key' => 'asrama',
                'label' => 'Asrama',
                'default' => 'all',
                'options' => $scopes->map(fn ($s) => ['value' => $s['key'], 'label' => $s['label']])->values(),
            ]],
            'attention' => $attention,
            'metrics' => $metrics,
            'sections' => $sections,
            'detail_path' => '/super/warga',
        ]);
    }

    /**
     * IPK terbaru & sebelumnya per warga. Tabel `ipks` menyimpan IP per
     * semester (string, kadang memakai koma) — tidak ada IPK kumulatif.
     *
     * @return Collection<int, array{current: float|null, previous: float|null}>
     */
    private function ipkPerUser(Collection $userIds): Collection
    {
        return Ipk::whereIn('user_id', $userIds)
            ->orderByDesc('tahun')
            ->orderByDesc('semester')
            ->get(['user_id', 'ip', 'tahun', 'semester'])
            ->groupBy('user_id')
            ->map(function (Collection $rows) {
                $values = $rows->map(fn ($r) => (float) str_replace(',', '.', (string) $r->ip))->filter(fn ($v) => $v > 0)->values();

                return ['current' => $values->get(0), 'previous' => $values->get(1)];
            });
    }

    private function scopeData(Collection $members, Collection $ipk, string $scope): array
    {
        $aktif = $members->where('status_warga', 'aktif');
        $current = $aktif->map(fn ($u) => $ipk[$u->id]['current'] ?? null)->filter();
        $previous = $aktif->map(fn ($u) => $ipk[$u->id]['previous'] ?? null)->filter();
        $total = $members->count();

        return [
            'aktif' => $aktif->count(),
            'nonaktif' => $total - $aktif->count(),
            'nonaktif_ratio' => $total > 0 ? ($total - $aktif->count()) / $total : 0,
            'ipk_values' => $current,
            'avg_ipk' => $current->isNotEmpty() ? round($current->avg(), 2) : null,
            'prev_avg_ipk' => $previous->isNotEmpty() ? round($previous->avg(), 2) : null,
            'low_ipk' => $current->filter(fn ($v) => $v < self::LOW_IPK)->count(),
            'alumni' => User::where('role', 'alumni')->when($scope !== 'all', fn ($q) => $q->where('asrama', $scope))->count(),
        ];
    }

    private function metrics(array $d): array
    {
        $trend = null;
        if ($d['avg_ipk'] !== null && $d['prev_avg_ipk'] !== null && $d['avg_ipk'] !== $d['prev_avg_ipk']) {
            $diff = round($d['avg_ipk'] - $d['prev_avg_ipk'], 2);
            $trend = [
                'text' => ($diff > 0 ? '▲ ' : '▼ ').number_format(abs($diff), 2, ',', '.').' dari semester lalu',
                'tone' => $diff > 0 ? 'ok' : 'warning',
            ];
        }

        return [
            ['label' => 'Warga aktif', 'value' => $d['aktif'], 'format' => 'number', 'hint' => 'dari '.($d['aktif'] + $d['nonaktif']).' warga terdaftar'],
            ['label' => 'Nonaktif', 'value' => $d['nonaktif'], 'format' => 'number', 'hint' => (int) round($d['nonaktif_ratio'] * 100).'% dari total warga'],
            array_filter([
                'label' => 'Rata-rata IPK',
                'value' => $d['avg_ipk'] !== null ? number_format($d['avg_ipk'], 2, ',', '.') : '—',
                'format' => 'text',
                'hint' => 'semester terakhir',
                'trend' => $trend,
            ], fn ($v) => $v !== null),
            ['label' => 'Alumni', 'value' => $d['alumni'], 'format' => 'number'],
        ];
    }

    private function attention(array $d): array
    {
        $items = [];

        if ($d['low_ipk'] > 0) {
            $items[] = [
                'tone' => 'warning',
                'title' => "{$d['low_ipk']} warga IPK di bawah ".number_format(self::LOW_IPK, 2, ',', '.'),
                'description' => 'Perlu pendampingan akademik dari mentor.',
                'link' => '/super/warga',
                'link_label' => 'Lihat warga',
            ];
        }

        if ($d['nonaktif_ratio'] > self::HIGH_INACTIVE_RATIO) {
            $items[] = [
                'tone' => 'warning',
                'title' => (int) round($d['nonaktif_ratio'] * 100).'% warga berstatus nonaktif',
                'description' => "{$d['nonaktif']} dari ".($d['aktif'] + $d['nonaktif']).' warga — periksa apakah datanya masih perlu diperbarui.',
                'link' => '/super/warga',
                'link_label' => 'Lihat warga',
            ];
        }

        return $items;
    }

    private function ipkChart(array $d): array
    {
        return [
            'type' => 'chart',
            'title' => 'Sebaran IPK warga aktif',
            'format' => 'number',
            'items' => collect(self::IPK_BUCKETS)->map(fn ($b) => [
                'label' => $b['label'],
                'value' => $d['ipk_values']->filter(fn ($v) => $v >= $b['min'] && $v < $b['max'])->count(),
            ])->values(),
        ];
    }

    /** Jumlah aktivitas pembinaan tercatat 30 hari terakhir, per aspek. */
    private function activityChart(Collection $userIds): array
    {
        $since = now()->subDays(30);
        $events = fn (string $type) => (int) UserEvent::whereIn('user_id', $userIds)->where('type', $type)
            ->where('created_at', '>=', $since)->get()->sum(fn ($e) => count($e->completed_at_dates ?? []));
        $count = fn (string $model) => $model::whereIn('user_id', $userIds)->where('created_at', '>=', $since)->count();

        return [
            'type' => 'chart',
            'title' => 'Aktivitas pembinaan 30 hari',
            'format' => 'number',
            'items' => [
                ['label' => 'Shalat', 'value' => $events('shalat')],
                ['label' => 'Kegiatan', 'value' => $events('kegiatan')],
                ['label' => 'Hafalan', 'value' => HafalanLog::whereIn('user_id', $userIds)->where('score', 'memtas')->where('created_at', '>=', $since)->count()],
                ['label' => 'Akademik', 'value' => $count(Akademik::class)],
                ['label' => 'Leadership', 'value' => $count(Leadership::class)],
                ['label' => 'Karakter', 'value' => $count(Karakter::class)],
                ['label' => 'Kreatif', 'value' => $count(Kreatif::class)],
            ],
        ];
    }

    private function asramaTable(Collection $asramas, Collection $warga, Collection $ipk, Collection $points): array
    {
        return [
            'type' => 'table',
            'title' => 'Per asrama',
            'columns' => [
                ['key' => 'asrama', 'label' => 'Asrama'],
                ['key' => 'aktif', 'label' => 'Aktif', 'format' => 'number'],
                ['key' => 'nonaktif', 'label' => 'Nonaktif', 'format' => 'number'],
                ['key' => 'ipk', 'label' => 'Rata IPK'],
                ['key' => 'poin', 'label' => 'Rata poin', 'format' => 'number'],
                ['key' => 'rendah', 'label' => 'IPK < 2,75', 'format' => 'number'],
            ],
            'rows' => $asramas->map(function (string $asrama) use ($warga, $ipk, $points) {
                $d = $this->scopeData($warga->where('asrama', $asrama), $ipk, $asrama);
                $aktifIds = $warga->where('asrama', $asrama)->where('status_warga', 'aktif')->pluck('id');
                $poin = $aktifIds->map(fn ($id) => $points[$id] ?? null)->filter(fn ($v) => $v !== null)->avg();

                return array_filter([
                    'asrama' => $asrama,
                    'aktif' => $d['aktif'],
                    'nonaktif' => $d['nonaktif'],
                    'ipk' => $d['avg_ipk'] !== null ? number_format($d['avg_ipk'], 2, ',', '.') : '—',
                    'poin' => (int) round($poin ?? 0),
                    'rendah' => $d['low_ipk'],
                    '_emphasis' => $d['low_ipk'] > 0 ? ['rendah' => 'warning'] : null,
                ], fn ($v) => $v !== null);
            })->values(),
        ];
    }

    /** Daftar warga satu asrama: aktif dulu, lalu urut nama. */
    private function memberTable(string $asrama, Collection $members, Collection $ipk, Collection $points): array
    {
        return [
            'type' => 'table',
            'title' => "Warga {$asrama} ({$members->count()})",
            'columns' => [
                ['key' => 'nama', 'label' => 'Nama'],
                ['key' => 'kampus', 'label' => 'Kampus / prodi'],
                ['key' => 'angkatan', 'label' => 'Angkatan'],
                ['key' => 'status', 'label' => 'Status'],
                ['key' => 'ipk', 'label' => 'IPK terakhir'],
                ['key' => 'poin', 'label' => 'Poin', 'format' => 'number'],
            ],
            'rows' => $members
                ->sortBy(fn ($u) => [$u->status_warga === 'aktif' ? 0 : 1, mb_strtolower((string) $u->name)])
                ->map(function ($u) use ($ipk, $points) {
                    $current = $ipk[$u->id]['current'] ?? null;
                    $aktif = $u->status_warga === 'aktif';

                    return array_filter([
                        'nama' => $u->name,
                        'kampus' => trim(implode(' · ', array_filter([$u->universitas, $u->prodi]))) ?: '—',
                        'angkatan' => $u->angkatan ? (string) $u->angkatan : '—',
                        'status' => $aktif ? 'Aktif' : 'Nonaktif',
                        'ipk' => $current !== null ? number_format($current, 2, ',', '.') : '—',
                        'poin' => $aktif ? (int) ($points[$u->id] ?? 0) : null,
                        '_emphasis' => array_filter([
                            'ipk' => $current !== null && $current < self::LOW_IPK ? 'warning' : null,
                            'status' => $aktif ? null : 'neutral',
                        ]) ?: null,
                    ], fn ($v) => $v !== null);
                })
                ->values(),
        ];
    }
}
