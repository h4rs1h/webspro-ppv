<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendReminderNotif extends Command
{
    protected $signature = 'notif:reminder {--days=3 : Jumlah hari sebelum jatuh tempo (3 atau 1)}';
    protected $description = 'Kirim notifikasi pengingat tagihan H-3 atau H-1 jatuh tempo';

    public function handle()
    {
        $days = (int) $this->option('days');

        if (!in_array($days, [1, 3])) {
            $this->error('--days harus 1 atau 3');
            return self::FAILURE;
        }

        $this->info("Enqueue pengingat H-{$days} jatuh tempo");

        $result = DB::select("CALL hr_v2_enqueue_reminder_sp(?)", [$days]);

        if (empty($result)) {
            $this->info("Tidak ada pengingat yang perlu dikirim.");
            return self::SUCCESS;
        }

        $row = $result[0];
        $enqueued  = $row->enqueued ?? 0;
        $skipped   = $row->skipped_no_wa ?? 0;
        $already   = $row->already_reminded ?? 0;

        $this->line("──────────────────────────────────────");
        $this->line(" 🔔 PENGINGAT H-{$days} — " . now()->toDateString());
        $this->line("──────────────────────────────────────");
        $this->line(" Enqueued    : {$enqueued}");
        $this->line(" Skipped     : {$skipped} (no WA)");
        $this->line(" Already     : {$already} (sudah ada)");
        $this->line("──────────────────────────────────────");

        Log::info("notif:reminder H-{$days}", [
            'enqueued'        => $enqueued,
            'skipped_no_wa'   => $skipped,
            'already_reminded'=> $already,
        ]);

        return self::SUCCESS;
    }
}
