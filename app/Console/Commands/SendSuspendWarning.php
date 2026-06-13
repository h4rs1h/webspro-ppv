<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendSuspendWarning extends Command
{
    protected $signature = 'notif:suspend-warning';
    protected $description = 'Kirim notifikasi peringatan suspend untuk pelanggan yang lewat jatuh tempo';

    public function handle()
    {
        $this->info("Enqueue suspend warning notifications");

        $result = DB::select("CALL hr_v2_enqueue_suspend_warning_sp()");

        if (empty($result)) {
            $this->info("Tidak ada suspend warning yang perlu dikirim.");
            return self::SUCCESS;
        }

        $row = $result[0];
        $enqueued  = $row->enqueued ?? 0;
        $skipped   = $row->skipped_no_wa ?? 0;
        $already   = $row->already_warned ?? 0;

        $this->line("──────────────────────────────────────");
        $this->line(" 🚫 SUSPEND WARNING — " . now()->toDateString());
        $this->line("──────────────────────────────────────");
        $this->line(" Enqueued    : {$enqueued}");
        $this->line(" Skipped     : {$skipped} (no WA)");
        $this->line(" Already     : {$already} (sudah ada)");
        $this->line("──────────────────────────────────────");

        Log::info('notif:suspend-warning', [
            'enqueued'      => $enqueued,
            'skipped_no_wa' => $skipped,
            'already_warned'=> $already,
        ]);

        return self::SUCCESS;
    }
}
