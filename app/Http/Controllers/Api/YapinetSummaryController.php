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
 * Ringkasan untuk portal Yapinet — kontrak v1.2 (yapinet/rules/detail-pages.md).
 * Dipanggil server Yapinet (middleware `yapinet.auth`). Satu payload memuat
 * varian per asrama (`when: {asrama}`). Cakupan "semua asrama" hanya agregat;
 * daftar nama warga tampil saat satu asrama dipilih — permintaan pengurus yayasan.
 *
 * Poin & keaktifan memakai metrik yang sama persis dengan Leaderboard / Dashboard
 * Simonas: jumlah kolom `poin` (Komponen Penilaian: jenis kegiatan × level) di
 * akademik, leadership, karakter & kreatif pada bulan berjalan (kolom `waktu`).
 * "Aktif mengisi" = punya ≥1 catatan bulan ini — bukan `status_warga`, yang
 * tidak selalu diperbarui pengurus.
 */
class YapinetSummaryController extends Controller
{
    /** IPK di bawah ini dianggap perlu pendampingan akademik. */
    private const LOW_IPK = 2.75;

    /** Sama dengan SuperController::MONTHLY_ACTIVITY_TARGET (target poin per bulan). */
    private const MONTHLY_TARGET = 120;

    /** Rasio warga yang belum mengisi bulan ini di atas ini → perlu perhatian. */
    private const HIGH_IDLE_RATIO = 0.3;

    private const ACTIVITY_MODELS = [
        'akademik' => Akademik::class,
        'leadership' => Leadership::class,
        'karakter' => Karakter::class,
        'kreatif' => Kreatif::class,
    ];

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
        $month = $this->monthActivity($warga->pluck('id'));

        $scopes = collect([['key' => 'all', 'label' => 'Semua asrama']])
            ->merge($asramas->map(fn ($a) => ['key' => $a, 'label' => $a]));

        $metrics = [];
        $attention = [];
        $sections = [];
        $hero = [];

        foreach ($scopes as $scope) {
            $when = ['asrama' => $scope['key']];
            $members = $scope['key'] === 'all' ? $warga : $warga->where('asrama', $scope['key']);
            $d = $this->scopeData($members, $ipk, $month, $scope['key']);

            foreach ($this->metrics($d) as $metric) {
                $metrics[] = $metric + ['when' => $when];
            }
            foreach ($this->attention($d) as $item) {
                $attention[] = $item + ['when' => $when];
            }
            $hero[] = $this->hero($d) + ['when' => $when];

            $sections[] = $this->activityChart($members->pluck('id')) + ['when' => $when];
            $sections[] = $this->ipkChart($d) + ['when' => $when];

            if ($scope['key'] === 'all') {
                $sections[] = $this->asramaTable($asramas, $warga, $ipk, $month) + ['when' => $when];
                $all = $d;
            } else {
                $sections[] = $this->memberTable($scope['label'], $members, $ipk, $month) + ['when' => $when];
            }
        }

        $status = ($all['low_ipk'] > 0 || $all['idle_ratio'] > self::HIGH_IDLE_RATIO) ? 'warning' : 'ok';

        return response()->json([
            'contract_version' => 2,
            'status' => $status,
            'headline' => "{$all['filled']} dari {$all['total']} warga aktif mengisi Simonas {$month['label']}",
            'updated_at' => now()->toIso8601String(),
            'filters' => [[
                'key' => 'asrama',
                'label' => 'Asrama',
                'default' => 'all',
                'options' => $scopes->map(fn ($s) => ['value' => $s['key'], 'label' => $s['label']])->values(),
            ]],
            'attention' => $attention,
            'hero' => $hero,
            'metrics' => $metrics,
            'sections' => $sections,
            'detail_path' => '/super/warga',
        ]);
    }

    /**
     * Poin & jumlah catatan bulan berjalan per warga — rentang & kolom sama
     * dengan SuperController::leaderboard().
     *
     * @return array{label: string, points: Collection<int, int>, entries: Collection<int, int>}
     */
    private function monthActivity(Collection $userIds): array
    {
        $from = now()->startOfMonth()->toDateString();
        $to = now()->endOfMonth()->toDateString();
        $points = collect();
        $entries = collect();

        foreach (self::ACTIVITY_MODELS as $model) {
            $rows = $model::whereIn('user_id', $userIds)
                ->whereBetween('waktu', [$from, $to])
                ->selectRaw('user_id, SUM(poin) as poin, COUNT(*) as jumlah')
                ->groupBy('user_id')
                ->get();
            foreach ($rows as $row) {
                $points[$row->user_id] = ($points[$row->user_id] ?? 0) + (int) $row->poin;
                $entries[$row->user_id] = ($entries[$row->user_id] ?? 0) + (int) $row->jumlah;
            }
        }

        return ['label' => now()->locale('id')->translatedFormat('F Y'), 'points' => $points, 'entries' => $entries];
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

    private function scopeData(Collection $members, Collection $ipk, array $month, string $scope): array
    {
        $aktif = $members->where('status_warga', 'aktif');
        $current = $aktif->map(fn ($u) => $ipk[$u->id]['current'] ?? null)->filter();
        $previous = $aktif->map(fn ($u) => $ipk[$u->id]['previous'] ?? null)->filter();
        $total = $members->count();
        $points = $members->map(fn ($u) => (int) ($month['points'][$u->id] ?? 0));
        $filled = $members->filter(fn ($u) => ($month['entries'][$u->id] ?? 0) > 0)->count();

        return [
            'month' => $month['label'],
            'total' => $total,
            'filled' => $filled,
            'filled_ratio' => $total > 0 ? $filled / $total : 0,
            'idle_ratio' => $total > 0 ? ($total - $filled) / $total : 0,
            'avg_points' => $total > 0 ? (int) round($points->avg()) : 0,
            'on_target' => $points->filter(fn ($p) => $p >= self::MONTHLY_TARGET)->count(),
            'nonaktif' => $total - $aktif->count(),
            'ipk_values' => $current,
            'avg_ipk' => $current->isNotEmpty() ? round($current->avg(), 2) : null,
            'prev_avg_ipk' => $previous->isNotEmpty() ? round($previous->avg(), 2) : null,
            'low_ipk' => $current->filter(fn ($v) => $v < self::LOW_IPK)->count(),
            'alumni' => User::where('role', 'alumni')->when($scope !== 'all', fn ($q) => $q->where('asrama', $scope))->count(),
        ];
    }

    /** v1.2: angka utama panel atas; label sama dengan metric supaya tidak tampil dua kali. */
    private function hero(array $d): array
    {
        return [
            'value' => $d['total'],
            'format' => 'number',
            'label' => 'Warga terdaftar',
            'story' => "**{$d['filled']}** aktif mengisi Simonas {$d['month']} · rata-rata **{$d['avg_points']} poin**"
                ." · **{$d['on_target']}** mencapai target ".self::MONTHLY_TARGET.' poin.',
            'side' => ['Aktif mengisi'],
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
            ['label' => 'Warga terdaftar', 'value' => $d['total'], 'format' => 'number'],
            [
                'label' => 'Aktif mengisi',
                'value' => $d['filled_ratio'],
                'format' => 'percent',
                'progress' => round($d['filled_ratio'], 4),
                'display' => 'ring',
                'hint' => "{$d['filled']} dari {$d['total']} warga punya catatan {$d['month']}",
            ],
            ['label' => 'Rata-rata poin', 'value' => $d['avg_points'], 'format' => 'number', 'hint' => 'target '.self::MONTHLY_TARGET.' poin/bulan'],
            ['label' => 'Capai target', 'value' => $d['on_target'], 'format' => 'number', 'hint' => '≥ '.self::MONTHLY_TARGET.' poin bulan ini'],
            array_filter([
                'label' => 'Rata-rata IPK',
                'value' => $d['avg_ipk'] !== null ? number_format($d['avg_ipk'], 2, ',', '.') : '—',
                'format' => 'text',
                'hint' => 'semester terakhir, warga berstatus aktif',
                'trend' => $trend,
            ], fn ($v) => $v !== null),
            ['label' => 'Alumni', 'value' => $d['alumni'], 'format' => 'number'],
        ];
    }

    private function attention(array $d): array
    {
        $items = [];
        $idle = $d['total'] - $d['filled'];

        if ($d['idle_ratio'] > self::HIGH_IDLE_RATIO) {
            $items[] = [
                'tone' => 'warning',
                'title' => "{$idle} warga belum mengisi Simonas {$d['month']}",
                'description' => (int) round($d['idle_ratio'] * 100).'% dari '.$d['total'].' warga belum punya catatan kegiatan bulan ini.',
                'link' => '/super/leaderboard',
                'link_label' => 'Lihat leaderboard',
            ];
        }

        if ($d['low_ipk'] > 0) {
            $items[] = [
                'tone' => 'warning',
                'title' => "{$d['low_ipk']} warga IPK di bawah ".number_format(self::LOW_IPK, 2, ',', '.'),
                'description' => 'Perlu pendampingan akademik dari mentor.',
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

    /** Jumlah aktivitas pembinaan bulan berjalan per aspek (semua warga di cakupan). */
    private function activityChart(Collection $userIds): array
    {
        $from = now()->startOfMonth();
        $fromDate = $from->toDateString();
        $toDate = now()->endOfMonth()->toDateString();
        $events = fn (string $type) => (int) UserEvent::whereIn('user_id', $userIds)->where('type', $type)->get()
            ->sum(fn ($e) => collect($e->completed_at_dates ?? [])->filter(fn ($day) => $day >= $fromDate && $day <= $toDate)->count());
        $count = fn (string $model) => $model::whereIn('user_id', $userIds)->whereBetween('waktu', [$fromDate, $toDate])->count();

        return [
            'type' => 'chart',
            'title' => 'Aktivitas pembinaan '.$from->locale('id')->translatedFormat('F'),
            'format' => 'number',
            'items' => [
                ['label' => 'Shalat', 'value' => $events('shalat')],
                ['label' => 'Kegiatan', 'value' => $events('kegiatan')],
                ['label' => 'Hafalan', 'value' => HafalanLog::whereIn('user_id', $userIds)->where('score', 'memtas')->whereBetween('tested_at', [$fromDate, $toDate])->count()],
                ['label' => 'Akademik', 'value' => $count(Akademik::class)],
                ['label' => 'Leadership', 'value' => $count(Leadership::class)],
                ['label' => 'Karakter', 'value' => $count(Karakter::class)],
                ['label' => 'Kreatif', 'value' => $count(Kreatif::class)],
            ],
        ];
    }

    private function asramaTable(Collection $asramas, Collection $warga, Collection $ipk, array $month): array
    {
        return [
            'type' => 'table',
            'title' => "Per asrama · {$month['label']}",
            'columns' => [
                ['key' => 'asrama', 'label' => 'Asrama'],
                ['key' => 'warga', 'label' => 'Warga', 'format' => 'number'],
                ['key' => 'mengisi', 'label' => 'Aktif mengisi', 'format' => 'progress'],
                ['key' => 'poin', 'label' => 'Rata poin', 'format' => 'number'],
                ['key' => 'target', 'label' => 'Capai target', 'format' => 'number'],
                ['key' => 'ipk', 'label' => 'Rata IPK'],
                ['key' => 'rendah', 'label' => 'IPK < 2,75', 'format' => 'number'],
            ],
            'rows' => $asramas->map(function (string $asrama) use ($warga, $ipk, $month) {
                $d = $this->scopeData($warga->where('asrama', $asrama), $ipk, $month, $asrama);

                return array_filter([
                    'asrama' => $asrama,
                    'warga' => $d['total'],
                    'mengisi' => round($d['filled_ratio'], 4),
                    'poin' => $d['avg_points'],
                    'target' => $d['on_target'],
                    'ipk' => $d['avg_ipk'] !== null ? number_format($d['avg_ipk'], 2, ',', '.') : '—',
                    'rendah' => $d['low_ipk'],
                    '_emphasis' => array_filter([
                        'mengisi' => $d['idle_ratio'] > self::HIGH_IDLE_RATIO ? 'warning' : 'ok',
                        'rendah' => $d['low_ipk'] > 0 ? 'warning' : null,
                    ]),
                ], fn ($v) => $v !== null && $v !== []);
            })->values(),
        ];
    }

    /** Daftar warga satu asrama: poin terbanyak dulu; yang belum mengisi ditandai. */
    private function memberTable(string $asrama, Collection $members, Collection $ipk, array $month): array
    {
        return [
            'type' => 'table',
            'display' => 'cards',
            'title' => "Warga {$asrama} ({$members->count()})",
            'columns' => [
                ['key' => 'nama', 'label' => 'Nama'],
                ['key' => 'kampus', 'label' => 'Kampus / prodi'],
                ['key' => 'angkatan', 'label' => 'Angkatan'],
                ['key' => 'catatan', 'label' => 'Catatan'],
                ['key' => 'ipk', 'label' => 'IPK'],
                ['key' => 'status', 'label' => 'Status'],
                ['key' => 'poin', 'label' => 'Poin', 'format' => 'number'],
            ],
            'rows' => $members
                ->sortBy(fn ($u) => [-(int) ($month['points'][$u->id] ?? 0), mb_strtolower((string) $u->name)])
                ->map(function ($u) use ($ipk, $month) {
                    $current = $ipk[$u->id]['current'] ?? null;
                    $points = (int) ($month['points'][$u->id] ?? 0);
                    $entries = (int) ($month['entries'][$u->id] ?? 0);

                    return [
                        'nama' => $u->name,
                        'kampus' => trim(implode(' · ', array_filter([$u->universitas, $u->prodi]))) ?: '—',
                        'angkatan' => $u->angkatan ? (string) $u->angkatan : '—',
                        'catatan' => $entries > 0 ? (string) $entries : 'belum ada',
                        'ipk' => $current !== null ? number_format($current, 2, ',', '.') : '—',
                        'status' => $u->status_warga === 'aktif' ? 'Aktif' : 'Nonaktif',
                        'poin' => $points,
                        '_emphasis' => array_filter([
                            'poin' => $entries === 0 ? 'neutral' : ($points >= self::MONTHLY_TARGET ? 'ok' : 'info'),
                            'catatan' => $entries === 0 ? 'warning' : null,
                            'ipk' => $current !== null && $current < self::LOW_IPK ? 'warning' : null,
                        ]),
                    ];
                })
                ->values(),
        ];
    }
}
