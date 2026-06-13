<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     */
    protected $commands = [
        Commands\GenerateDailyInvoiceV2::class,
        Commands\ProcessNotifWa::class,
        Commands\SendDailyNotifReport::class,
        Commands\SendReminderNotif::class,
        Commands\SendSuspendWarning::class,
        Commands\CleanupStuckNotif::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * ONLY production cron runs schedule:run every minute via Plesk:
     *   cd /var/www/vhosts/mediaprimajaringan.com/laravel && \
     *   /opt/plesk/php/8.1/bin/php artisan schedule:run >> /dev/null 2>&1
     */
    protected function schedule(Schedule $schedule)
    {
        // ============================================================
        // Issue #1: Invoice Generator v2
        // ============================================================
        $schedule->command('invoice:generate-daily-v2')
                 ->dailyAt('05:45')
                 ->withoutOverlapping()
                 ->runInBackground()
                 ->description('Generate invoice harian v2');

        // ============================================================
        // Issue #4: Notifikasi WA — Processor
        // ============================================================

        // Processor queue WA — jalan setiap 2 menit
        $schedule->command('notif:process-wa')
                 ->everyTwoMinutes()
                 ->withoutOverlapping(5)
                 ->runInBackground()
                 ->description('Process WA notification queue');

        // Cleanup notif stuck > 1 jam di 'processing'
        $schedule->command('notif:cleanup-stuck')
                 ->hourly()
                 ->withoutOverlapping()
                 ->description('Cleanup stuck WA notifications');

        // ============================================================
        // Issue #4: Notifikasi WA — Reminder & Warning
        // ============================================================

        // Pengingat H-3 jatuh tempo
        $schedule->command('notif:reminder --days=3')
                 ->dailyAt('07:00')
                 ->withoutOverlapping()
                 ->description('Reminder tagihan H-3 jatuh tempo');

        // Pengingat H-1 jatuh tempo
        $schedule->command('notif:reminder --days=1')
                 ->dailyAt('07:05')
                 ->withoutOverlapping()
                 ->description('Reminder tagihan H-1 jatuh tempo');

        // Suspend warning untuk yang lewat jatuh tempo
        $schedule->command('notif:suspend-warning')
                 ->dailyAt('07:10')
                 ->withoutOverlapping()
                 ->description('Suspend warning untuk tagihan lewat jatuh tempo');

        // ============================================================
        // Issue #4: Laporan Harian Notifikasi WA
        // ============================================================
        $schedule->command('notif:daily-report')
                 ->dailyAt('18:00')
                 ->withoutOverlapping()
                 ->description('Laporan harian pengiriman notifikasi WA');

        // ============================================================
        // Email queue processor (existing)
        // ============================================================
        $schedule->command('email:process-queue')
                 ->everyFiveMinutes()
                 ->withoutOverlapping()
                 ->runInBackground()
                 ->description('Process email queue');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
