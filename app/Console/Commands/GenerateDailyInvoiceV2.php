<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceDailyReportV2;
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

            // 4) Susun ringkasan untuk log dan email
            $summary = $generate[0] ?? (object) [
                'total_candidate' => 0,
                'created_count' => 0,
                'skipped_existing' => 0,
                'failed_count' => 0,
            ];
            $verifyFirst = $verify[0] ?? (object) [
                'total_candidates' => 0,
                'already_has_invoice' => 0,
                'still_missing_invoice' => 0,
            ];

            Log::info('invoice:generate-daily-v2 success', [
                'date' => $date,
                'dry_run' => $dryRun,
                'generate' => $generate,
                'verify' => $verify,
            ]);

            // 5) Kirim email notifikasi (hanya saat execute, bukan dry-run)
            if (! $dryRun) {
                $this->sendReportEmail($date, $summary, $verifyFirst);
            } else {
                $this->info("Mode dry-run: email tidak dikirim");
            }

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

    /**
     * Kirim laporan via email ke satu atau banyak penerima.
     *
     * @param string $date
     * @param object $summary
     * @param object $verify
     * @return void
     */
    protected function sendReportEmail($date, $summary, $verify)
    {
        $recipients = env('INVOICE_REPORT_EMAIL', 'hrsanto@gmail.com');
        $emails = array_map('trim', explode(',', $recipients));
        $appEnv = env('APP_ENV', 'production');

        $report = [
            'subject' => "Laporan Generate Invoice Harian V2 - {$date} [{$appEnv}]",
            'date'    => $date,
            'env'     => $appEnv,
            'dry_run' => false,
            'summary' => [
                'total_candidate'  => $summary->total_candidate ?? 0,
                'created_count'    => $summary->created_count ?? 0,
                'skipped_existing' => $summary->skipped_existing ?? 0,
                'failed_count'     => $summary->failed_count ?? 0,
            ],
            'verify' => [
                'total_candidates'    => $verify->total_candidates ?? 0,
                'already_has_invoice'  => $verify->already_has_invoice ?? 0,
                'still_missing_invoice' => $verify->still_missing_invoice ?? 0,
            ],
        ];

        foreach ($emails as $email) {
            if (empty($email)) continue;
            try {
                Mail::to($email)->send(new InvoiceDailyReportV2($report));
                $this->info("Email terkirim ke {$email}");
                Log::info('invoice:generate-daily-v2 email sent', [
                    'to' => $email,
                    'date' => $date,
                ]);
            } catch (Throwable $e) {
                $this->warn("Email gagal terkirim ke {$email}: " . $e->getMessage());
                Log::warning('invoice:generate-daily-v2 email failed', [
                    'to' => $email,
                    'date' => $date,
                    'message' => $e->getMessage(),
                ]);
            }
        }
    }
}
