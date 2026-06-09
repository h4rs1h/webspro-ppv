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
                $this->queueReportEmail($date, $summary, $verifyFirst);
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

    /**
     * Insert laporan ke Trx_email_queue di production DB.
     * Hosting yang akan memproses queue dan mengirim email via SMTP internal.
     */
    protected function queueReportEmail($date, $summary, $verify)
    {
        $recipients = env('INVOICE_REPORT_EMAIL', 'hrsanto@gmail.com,harsih.hhr@gmail.com');
        $appEnv = env('APP_ENV', 'development');

        $subject = "Laporan Generate Invoice Harian V2 - {$date} [{$appEnv}]";

        $bodyHtml = $this->buildEmailHtml($date, $appEnv, $summary, $verify);

        $emails = array_map('trim', explode(',', $recipients));
        foreach ($emails as $email) {
            if (empty($email)) {
                continue;
            }

            try {
                DB::connection('mysql_prod')->table('Trx_email_queue')->insert([
                    'recipient' => $email,
                    'subject' => $subject,
                    'body_html' => $bodyHtml,
                    'status' => 'pending',
                    'created_at' => now(),
                ]);
                $this->info("Laporan di-queue ke production DB untuk {$email}");
            } catch (Throwable $e) {
                $this->warn("Gagal queue email untuk {$email}: " . $e->getMessage());
                Log::warning('invoice:generate-daily-v2 queue failed', [
                    'email' => $email,
                    'date' => $date,
                    'message' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Bangun HTML body untuk email laporan.
     */
    protected function buildEmailHtml($date, $env, $summary, $verify)
    {
        $total = $summary->total_candidate ?? 0;
        $created = $summary->created_count ?? 0;
        $skipped = $summary->skipped_existing ?? 0;
        $failed = $summary->failed_count ?? 0;
        $verifyTotal = $verify->total_candidates ?? 0;
        $hasInvoice = $verify->already_has_invoice ?? 0;
        $missing = $verify->still_missing_invoice ?? 0;

        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Laporan Generate Invoice Harian V2</title>
<style>
  body { font-family: Arial, sans-serif; font-size: 14px; color: #333; }
  h2 { color: #2563eb; }
  .summary { background: #f0fdf4; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
  .item { display: inline-block; margin-right: 24px; }
  .val { font-size: 20px; font-weight: bold; color: #2563eb; }
  .created { color: #16a34a; } .skipped { color: #d97706; } .failed { color: #dc2626; }
  table { border-collapse: collapse; width: 100%; margin-bottom: 16px; }
  th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
  th { background-color: #f3f4f6; }
  .footer { font-size: 12px; color: #999; margin-top: 24px; border-top: 1px solid #eee; padding-top: 12px; }
</style>
</head>
<body>
<h2>Laporan Generate Invoice Harian V2</h2>
<p>Jakarta, {$date}</p>
<p>Kepada Yth.<br>Tim Admin Media Prima Jaringan<br>Di Tempat.</p>
<p>Berikut ringkasan hasil proses generate invoice harian v2 tanggal <strong>{$date}</strong>:</p>

<div class="summary">
  <div class="item">Total Kandidat<br><span class="val">{$total}</span></div>
  <div class="item"><span class="created">Invoice Baru</span><br><span class="val created">{$created}</span></div>
  <div class="item"><span class="skipped">Sudah Ada</span><br><span class="val skipped">{$skipped}</span></div>
  <div class="item"><span class="failed">Gagal</span><br><span class="val failed">{$failed}</span></div>
</div>

<h3>Verifikasi</h3>
<table>
  <tr><td>Total Kandidat</td><td><strong>{$verifyTotal}</strong></td></tr>
  <tr><td>Sudah Punya Invoice</td><td>{$hasInvoice}</td></tr>
  <tr><td>Still Missing</td><td style="color: " . ({$missing} > 0 ? '#dc2626' : '#16a34a') . ";">{$missing}</td></tr>
</table>

<div class="footer">
  <p>Proses dijalankan otomatis oleh scheduler BOS MPJ v2 (05:45 WIB).<br>Environment: {$env}</p>
  <p>Hormat kami,<br><strong>Billing Media Prima Jaringan</strong></p>
</div>
</body>
</html>
HTML;
    }
}
