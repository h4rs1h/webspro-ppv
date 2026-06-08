<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class GenerateDailyInvoiceV2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:generate-daily-v2 {--date=} {--dry-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate invoice harian v2 berbasis exp_date v11';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = $this->option('date') ?: now()->toDateString();
        $dryRun = $this->option('dry-run') ? 1 : 0;

        $this->info("Mulai invoice:generate-daily-v2 | date={$date} | dry_run={$dryRun}");

        try {
            // 1) Sync exp_date dulu (dry-run aman)
            DB::statement("CALL hr_v2_sync_expdate_sp(" . ($dryRun ? "TRUE" : "FALSE") . ")");
            $this->info("Sync exp_date selesai");

            // 2) Generate invoice v2
            $generate = DB::select("CALL hr_v2_generate_invoice_daily_sp(?, ?)", [
                $date,
                $dryRun
            ]);
            $this->info("Generate invoice v2 selesai");
            $this->line(json_encode($generate, JSON_PRETTY_PRINT));

            // 3) Verify hasil berdasarkan target date
            $verify = DB::select("CALL hr_v2_verify_invoice_daily_target_sp(?)", [
                $date
            ]);
            $this->info("Verify selesai");
            $this->line(json_encode($verify, JSON_PRETTY_PRINT));

            Log::info('invoice:generate-daily-v2 success', [
                'date' => $date,
                'dry_run' => $dryRun,
                'generate' => $generate,
                'verify' => $verify,
            ]);

            return self::SUCCESS;
        } catch (Throwable $e) {
            Log::error('invoice:generate-daily-v2 failed', [
                'date' => $date,
                'dry_run' => $dryRun,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->error("Gagal: " . $e->getMessage());
            return self::FAILURE;
        }
    }
}
