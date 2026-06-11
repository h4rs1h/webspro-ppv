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
        // Generate invoice harian v2 — jam 05:45 WIB
        $schedule->command('invoice:generate-daily-v2')
            ->timezone('Asia/Jakarta')
            ->dailyAt('05:45')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/invoice-generate-daily-v2.log'));

        // Process email queue — setiap 5 menit
        $schedule->exec(
            'curl -s -o /dev/null -w "%{http_code}" ' .
            '-H "X-Invoice-Report-Secret: ' . env('INVOICE_REPORT_API_SECRET', '') . '" ' .
            '"https://bos.mediaprimajaringan.com/api/bos/process-email-queue"'
        )
            ->everyFiveMinutes()
            ->withoutOverlapping(5)
            ->appendOutputTo(storage_path('logs/email-queue-process.log'));
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
