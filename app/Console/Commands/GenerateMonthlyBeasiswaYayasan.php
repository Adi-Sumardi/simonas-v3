<?php

namespace App\Console\Commands;

use App\Models\Akademik;
use App\Models\Beasiswa;
use App\Models\Karakter;
use App\Models\Kegiatan;
use App\Models\KegiatanAttendance;
use App\Models\Kreatif;
use App\Models\Leadership;
use App\Models\User;
use App\Models\UserEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class GenerateMonthlyBeasiswaYayasan extends Command
{
    // Target aktivitas bulanan (sama dengan Leaderboard/Laporan — akademik+leadership+karakter+kreatif).
    private const ACTIVITY_TARGET = 120;
    private const KAJIAN_AHAD_TARGET = 3;
    private const SUBUH_TARGET = 20;

    protected $signature = 'app:generate-monthly-beasiswa-yayasan {--month=} {--dry-run}';

    protected $description = 'Ajukan Beasiswa Yayasan otomatis untuk mentee yang penuhi syarat bulan lalu (aktivitas >=120, Kajian Ahad >=3x, Subuh >=20x), menunggu approval mentor.';

    public function handle(): int
    {
        $month = $this->option('month')
            ? Carbon::parse($this->option('month'))->startOfMonth()
            : now()->subMonthNoOverflow()->startOfMonth();
        $from = $month->copy()->startOfMonth();
        $to   = $month->copy()->endOfMonth();
        $dryRun = (bool) $this->option('dry-run');

        $this->info("Mengevaluasi periode {$from->translatedFormat('F Y')}" . ($dryRun ? ' (dry-run)' : ''));

        $mentees = User::where('role', 'mahasiswa')->whereNotNull('mentor_id')->get();

        $kajianAhadIds = Kegiatan::where('nama_kegiatan', 'ilike', '%kajian ahad%')
            ->whereBetween('waktu', [$from, $to])
            ->pluck('id');

        $created = 0;
        $namaBeasiswa = 'Beasiswa Yayasan - ' . $from->translatedFormat('F Y');

        foreach ($mentees as $mentee) {
            $alreadyProposed = Beasiswa::where('user_id', $mentee->id)
                ->where('sumber', 'yayasan')
                ->where('nama_beasiswa', $namaBeasiswa)
                ->exists();

            if ($alreadyProposed) {
                continue;
            }

            $totalAktivitas = $this->countAktivitas($mentee->id, $from, $to);
            $totalKajianAhad = $kajianAhadIds->isEmpty() ? 0 : KegiatanAttendance::where('user_id', $mentee->id)
                ->whereIn('kegiatan_id', $kajianAhadIds)
                ->count();
            $totalSubuh = $this->countSubuh($mentee->id, $from, $to);

            $memenuhi = $totalAktivitas >= self::ACTIVITY_TARGET
                && $totalKajianAhad >= self::KAJIAN_AHAD_TARGET
                && $totalSubuh >= self::SUBUH_TARGET;

            if (! $memenuhi) {
                continue;
            }

            $this->line("  ✓ {$mentee->name}: aktivitas={$totalAktivitas}, kajian_ahad={$totalKajianAhad}, subuh={$totalSubuh}");

            if (! $dryRun) {
                Beasiswa::create([
                    'user_id'          => $mentee->id,
                    'mentor_id'        => $mentee->mentor_id,
                    'created_by'       => $mentee->mentor_id,
                    'nama_beasiswa'    => $namaBeasiswa,
                    'sumber'           => 'yayasan',
                    'tanggal_diajukan' => now(),
                    'status'           => 'pending',
                    'catatan'          => "Otomatis: aktivitas {$totalAktivitas}/120, Kajian Ahad {$totalKajianAhad}x, Subuh {$totalSubuh}x.",
                ]);
            }

            $created++;
        }

        $this->info("Selesai. {$created} pengajuan " . ($dryRun ? 'akan dibuat' : 'dibuat') . ', menunggu approval mentor.');

        return self::SUCCESS;
    }

    private function countAktivitas(int $userId, Carbon $from, Carbon $to): int
    {
        $models = [Akademik::class, Leadership::class, Karakter::class, Kreatif::class];
        $total = 0;
        foreach ($models as $model) {
            $total += $model::where('user_id', $userId)->whereBetween('waktu', [$from, $to])->count();
        }
        return $total;
    }

    private function countSubuh(int $userId, Carbon $from, Carbon $to): int
    {
        $events = UserEvent::where('user_id', $userId)
            ->where('type', 'shalat')
            ->where('title', 'ilike', '%subuh%')
            ->get();

        return $events->sum(function (UserEvent $e) use ($from, $to) {
            return collect($e->completed_at_dates ?? [])
                ->filter(fn ($d) => $d >= $from->toDateString() && $d <= $to->toDateString())
                ->count();
        });
    }
}
