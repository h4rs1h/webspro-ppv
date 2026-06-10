<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\GenerateDailyInvoiceV2;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('invoice:generate-daily-v2')
            ->timezone('Asia/Jakarta')
            ->dailyAt('05:45')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/invoice-generate-daily-v2.log'));
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
