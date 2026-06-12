<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Generate invoice harian v2 — jam 05:45 WIB
        $schedule->command('invoice:generate-daily-v2')
            ->timezone('Asia/Jakarta')
            ->dailyAt('05:45')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/invoice-generate-daily-v2.log'));

        // Process email queue — setiap 5 menit
        $schedule->command('email:process-queue')
            ->everyFiveMinutes()
            ->withoutOverlapping(5)
            ->appendOutputTo(storage_path('logs/email-queue-process.log'));

        // Process notif WA — setiap 2 menit (Issue #4)
        $schedule->command('notif:process-wa')
            ->everyTwoMinutes()
            ->withoutOverlapping(3)
            ->appendOutputTo(storage_path('logs/notif-wa-process.log'));

        // Cleanup stuck notif — setiap jam (Issue #4)
        $schedule->command('notif:cleanup-stuck')
            ->hourly()
            ->appendOutputTo(storage_path('logs/notif-wa-cleanup.log'));
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
