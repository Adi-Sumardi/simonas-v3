<?php

namespace App\Console\Commands;

use App\Models\LiveMeetingRecording;
use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupExpiredLiveMeetRecordings extends Command
{
    protected $signature = 'app:cleanup-expired-live-meet-recordings {--dry-run}';

    protected $description = 'Kirim reminder H-1 dan hapus rekaman Live Meet superadmin yang sudah lewat masa retensi 7 hari.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        // H-1: kirim reminder ke host/co-host yang belum download.
        $expiringSoon = LiveMeetingRecording::where('status', 'ready')
            ->whereNull('reminder_sent_at')
            ->whereNull('downloaded_at')
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDay()])
            ->with('room.host')
            ->get();

        foreach ($expiringSoon as $recording) {
            $host = $recording->room?->host;
            $this->info("Reminder: rekaman #{$recording->id} (room \"{$recording->room?->title}\") akan dihapus " . $recording->expires_at->diffForHumans());

            if (!$dryRun && $host) {
                Notification::send(
                    $host->id,
                    'Rekaman Live Meet akan dihapus besok',
                    "Rekaman meeting \"{$recording->room->title}\" akan dihapus otomatis " . $recording->expires_at->translatedFormat('d M Y H:i') . ". Download sekarang jika masih diperlukan.",
                    'warning',
                    route('super.live-meet.index')
                );
                $recording->update(['reminder_sent_at' => now()]);
            }
        }

        // Lewat masa retensi: hapus file fisik + baris DB.
        $expired = LiveMeetingRecording::whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expired as $recording) {
            $this->info("Menghapus rekaman #{$recording->id} (expired " . $recording->expires_at->diffForHumans() . ")");

            if (!$dryRun) {
                if ($recording->file_path && Storage::disk('live_meet_recordings')->exists(basename($recording->file_path))) {
                    Storage::disk('live_meet_recordings')->delete(basename($recording->file_path));
                }
                $recording->delete();
            }
        }

        $this->info("Selesai. Reminder: {$expiringSoon->count()}, dihapus: {$expired->count()}." . ($dryRun ? ' (dry-run, tidak ada perubahan)' : ''));

        return self::SUCCESS;
    }
}
