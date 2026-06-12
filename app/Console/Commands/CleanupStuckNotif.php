<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupStuckNotif extends Command
{
    protected $signature = 'notif:cleanup-stuck';
    protected $description = 'Reset stuck "processing" notifications and cancel expired ones';

    public function handle()
    {
        $results = DB::select('CALL hr_v2_cleanup_stuck_notif_sp()');

        if (!empty($results)) {
            $row = $results[0];
            $this->info("Cleaned: {$row->processing_reset} stuck reset, {$row->expired_cancelled} expired cancelled");
        }

        return 0;
    }
}
