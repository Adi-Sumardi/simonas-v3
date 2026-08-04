<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Ajukan Beasiswa Yayasan otomatis untuk periode bulan lalu, tanggal 1 tiap bulan.
        $schedule->command('app:generate-monthly-beasiswa-yayasan')
            ->monthlyOn(1, '02:00')
            ->withoutOverlapping();

        // Reminder H-1 + hapus rekaman Live Meet superadmin yang lewat retensi 7 hari.
        $schedule->command('app:cleanup-expired-live-meet-recordings')
            ->daily()
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
