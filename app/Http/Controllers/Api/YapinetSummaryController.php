<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ipk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Feeds the external Yapinet portal (yapinet.id) a lightweight status card +
 * detail view for this app (Simonas). Guarded by the `yapinet.auth`
 * middleware (static bearer token), NOT Sanctum — see routes/api.php.
 */
class YapinetSummaryController extends Controller
{
    /**
     * Max number of warga rows returned in `details.warga` per request, to
     * avoid dumping the entire student body on every poll.
     */
    private const WARGA_LIMIT = 50;

    public function summary(Request $request)
    {
        $asrama = $request->query('asrama');

        $baseQuery = User::query()->where('role', 'mahasiswa');
        if ($asrama) {
            $baseQuery->where('asrama', $asrama);
        }

        // status_warga in this app is a simple binary flag ('aktif' / 'nonaktif'),
        // there is no dedicated "cuti" value stored — see app/Http/Middleware
        // usage in resources/js/Pages/Super/Warga.tsx and SuperController.php.
        // We treat anything not explicitly 'aktif' as "Cuti" for the purposes of
        // this contract, and alumni are identified by role = 'alumni' (kept as
        // a separate top-level metric, not mixed into the warga/mahasiswa list).
        $aktifCount = (clone $baseQuery)->where('status_warga', 'aktif')->count();
        $totalWarga = (clone $baseQuery)->count();
        $cutiCount  = $totalWarga - $aktifCount;

        $alumniQuery = User::query()->where('role', 'alumni');
        if ($asrama) {
            $alumniQuery->where('asrama', $asrama);
        }
        $alumniCount = $alumniQuery->count();

        $wargaModels = (clone $baseQuery)
            ->orderByDesc('updated_at')
            ->limit(self::WARGA_LIMIT)
            ->get();

        $userIds = $wargaModels->pluck('id');

        // ipk: the `ipks` table stores one row per (user, semester, tahun) with
        // an `ip` (indeks prestasi) string column — there's no cumulative "ipk"
        // field anywhere. We approximate `ipk` as the average of a user's `ip`
        // values across all recorded semesters, rounded to 2 decimals, or null
        // if the user has no Ipk records.
        $avgIpkByUser = Ipk::whereIn('user_id', $userIds)
            ->selectRaw('user_id, AVG(CAST(ip AS DECIMAL(4,2))) as avg_ip')
            ->groupBy('user_id')
            ->pluck('avg_ip', 'user_id');

        $warga = $wargaModels->map(function (User $user) use ($avgIpkByUser) {
            $ipk = $avgIpkByUser[$user->id] ?? null;

            return [
                'nama'          => $user->name,
                'asrama'        => $user->asrama,
                'status'        => $user->status_warga === 'aktif' ? 'Aktif' : 'Cuti',
                'ipk'           => $ipk !== null ? round((float) $ipk, 2) : null,
                // poin_simonas: reuses the existing point engine on the User
                // model (User::calculatePoints(), also used by the mahasiswa
                // dashboard), which sums weighted activity counts
                // (shalat/hafalan/kegiatan/akademik/leadership/karakter/kreatif).
                'poin_simonas'  => $user->calculatePoints(),
                'tahun_masuk'   => $this->extractYear($user->tgl_masuk),
                'tahun_keluar'  => $this->extractYear($user->tgl_keluar),
            ];
        })->values();

        $status = 'ok';
        if ($totalWarga > 0 && ($cutiCount / $totalWarga) > 0.2) {
            $status = 'warning';
        }

        $headline = $asrama
            ? "{$aktifCount} warga aktif di {$asrama}"
            : "{$aktifCount} warga aktif di asrama";

        return response()->json([
            'status'   => $status,
            'headline' => $headline,
            'metrics'  => [
                ['label' => 'Warga Aktif', 'value' => $aktifCount],
                ['label' => 'Warga Cuti', 'value' => $cutiCount],
                ['label' => 'Alumni', 'value' => $alumniCount],
            ],
            'details' => [
                'warga' => $warga,
            ],
            'updated_at'  => Carbon::now()->toIso8601String(),
            'detail_path' => null,
        ]);
    }

    /**
     * tgl_masuk/tgl_keluar are stored as free-form strings on `users`, so we
     * defensively parse and fall back to null on unparsable/empty values.
     */
    private function extractYear(?string $date): ?int
    {
        if (!$date) {
            return null;
        }

        try {
            return Carbon::parse($date)->year;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
