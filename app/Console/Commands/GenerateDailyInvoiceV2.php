<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class GenerateDailyInvoiceV2 extends Command
{
    protected $signature = 'invoice:generate-daily-v2 {--date=} {--dry-run}';
    protected $description = 'Generate invoice harian v2 berbasis exp_date v11';

    public function handle()
    {
        $date = $this->option('date') ?: now()->toDateString();
        $dryRun = $this->option('dry-run') ? 1 : 0;

        $this->info("Mulai invoice:generate-daily-v2 | date={$date} | dry_run={$dryRun}");

        try {
            DB::statement("CALL hr_v2_sync_expdate_sp(" . ($dryRun ? "TRUE" : "FALSE") . ")");
            $this->info("Sync exp_date selesai");

            $generate = DB::select("CALL hr_v2_generate_invoice_daily_sp(?, ?)", [$date, $dryRun]);
            $this->info("Generate invoice v2 selesai");
            $this->line(json_encode($generate, JSON_PRETTY_PRINT));

            $verify = DB::select("CALL hr_v2_verify_invoice_daily_target_sp(?)", [$date]);
            $this->info("Verify selesai");
            $this->line(json_encode($verify, JSON_PRETTY_PRINT));

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

            if (! $dryRun) {
                $this->sendReportToHosting($date, $summary, $verifyFirst);
            } else {
                $this->info("Mode dry-run: laporan tidak dikirim");
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

    protected function sendReportToHosting($date, $summary, $verify)
    {
        $url = env('INVOICE_REPORT_PROD_URL', 'https://mediaprimajaringan.com/api/bos/send-invoice-report-v2');
        $secret = env('INVOICE_REPORT_API_SECRET', '');
        $recipients = env('INVOICE_REPORT_EMAIL', 'hrsanto@gmail.com,harsih.hhr@gmail.com');
        $appEnv = env('APP_ENV', 'development');

        $report = [
            'subject' => "Laporan Generate Invoice Harian V2 - {$date} [{$appEnv}]",
            'date' => $date,
            'env' => $appEnv,
            'dry_run' => false,
            'summary' => [
                'total_candidate' => $summary->total_candidate ?? 0,
                'created_count' => $summary->created_count ?? 0,
                'skipped_existing' => $summary->skipped_existing ?? 0,
                'failed_count' => $summary->failed_count ?? 0,
            ],
            'verify' => [
                'total_candidates' => $verify->total_candidates ?? 0,
                'already_has_invoice' => $verify->already_has_invoice ?? 0,
                'still_missing_invoice' => $verify->still_missing_invoice ?? 0,
            ],
        ];

        try {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode(['report' => $report, 'to' => $recipients]),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'X-Invoice-Report-Secret: ' . $secret,
                ],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CONNECTTIMEOUT => 10,
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                $this->warn("Gagal POST ke hosting: {$error}");
                Log::warning('invoice:generate-daily-v2 post failed', [
                    'url' => $url,
                    'error' => $error,
                    'date' => $date,
                ]);
            } else {
                $this->info("Laporan dikirim ke hosting [HTTP {$httpCode}]");
                Log::info('invoice:generate-daily-v2 report sent to hosting', [
                    'url' => $url,
                    'http_code' => $httpCode,
                    'response' => $response,
                    'date' => $date,
                ]);
            }
        } catch (Throwable $e) {
            $this->warn("Gagal kirim laporan: " . $e->getMessage());
            Log::warning('invoice:generate-daily-v2 report failed', [
                'date' => $date,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
