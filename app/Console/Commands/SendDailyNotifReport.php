<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendDailyNotifReport extends Command
{
    protected $signature = 'notif:daily-report {--date= : Tanggal laporan (default: hari ini)} {--no-email : Hanya tampilkan laporan tanpa queue email}';
    protected $description = 'Kirim laporan harian pengiriman notifikasi WA';

    public function handle()
    {
        $date = $this->option('date') ?: now()->toDateString();

        $this->info("Generating daily WA notification report for {$date}");

        // Ringkasan harian WA harus diambil dari database production,
        // termasuk saat command dijalankan dari server dev.
        $summary = $this->getProdNotifSummary($date);

        if (empty($summary)) {
            $this->warn("No data for {$date}");
            return self::SUCCESS;
        }

        $row = $summary[0];
        $total      = $row->total ?? 0;
        $terkirim   = $row->terkirim ?? 0;
        $pending    = $row->pending ?? 0;
        $gagal      = $row->gagal ?? 0;

        $persenSukses = $total > 0 ? round(($terkirim / $total) * 100, 1) : 0;

        $this->line("──────────────────────────────────────");
        $this->line(" 📊 NOTIFIKASI WA — {$date}");
        $this->line("──────────────────────────────────────");
        $this->line(" Total     : {$total}");
        $this->line(" Terkirim  : {$terkirim} ({$persenSukses}%)");
        $this->line(" Pending   : {$pending}");
        $this->line(" Gagal     : {$gagal}");
        $this->line("──────────────────────────────────────");

        // Queue email laporan ke production DB
        if ($this->option('no-email')) {
            $this->warn('Mode --no-email: laporan tidak dimasukkan ke queue email.');
        } else {
            $this->queueReportEmail($date, $row);
        }

        // Log
        Log::info('notif:daily-report', [
            'date'    => $date,
            'data_source' => 'production',
            'total'   => $total,
            'sent'    => $terkirim,
            'pending' => $pending,
            'failed'  => $gagal,
        ]);

        // Alert jika gagal > 10
        if ($gagal > 10) {
            Log::warning("notif:daily-report — ALERT: {$gagal} gagal pada {$date}");
            $this->warn("⚠️  ALERT: {$gagal} notifikasi gagal hari ini!");
        }

        return self::SUCCESS;
    }


    protected function getProdNotifSummary($date)
    {
        $pdo = $this->getProdPdo();

        try {
            $stmt = $pdo->prepare('CALL hr_v2_daily_notif_report_sp(?)');
            $stmt->execute([$date]);
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            // Production belum tentu punya SP report; fallback ke query langsung
            // agar laporan tetap memakai data production.
            if (($e->errorInfo[1] ?? null) != 1305) {
                throw $e;
            }
        }

        if ($this->prodTableExists($pdo, 'hr_v2_notif_queue')) {
            $stmt = $pdo->prepare(<<<SQL
SELECT
    ? AS tanggal,
    COUNT(*) AS total,
    SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) AS terkirim,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending,
    SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) AS processing,
    SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) AS gagal
FROM hr_v2_notif_queue
WHERE DATE(created_at) = ?
SQL);
            $stmt->execute([$date, $date]);
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }

        $stmt = $pdo->prepare(<<<SQL
SELECT
    ? AS tanggal,
    COUNT(*) AS total,
    SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) AS terkirim,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending,
    SUM(CASE WHEN status = 'proses' THEN 1 ELSE 0 END) AS processing,
    SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) AS gagal
FROM trx_sending
WHERE DATE(created_at) = ?
SQL);
        $stmt->execute([$date, $date]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }


    protected function prodTableExists(\PDO $pdo, $tableName)
    {
        $stmt = $pdo->prepare('SHOW TABLES LIKE ?');
        $stmt->execute([$tableName]);
        return (bool) $stmt->fetchColumn();
    }

    protected function getProdPdo()
    {
        $prodHost = env('DB_PROD_HOST', '103.229.73.45');
        $prodDb   = env('DB_PROD_DATABASE', 'dbbosmpj');
        $prodUser = env('DB_PROD_USERNAME', 'bosmpj');
        $prodPass = env('DB_PROD_PASSWORD', '');

        return new \PDO(
            "mysql:host={$prodHost};port=3306;dbname={$prodDb};charset=utf8mb4",
            $prodUser,
            $prodPass,
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
    }

    protected function queueReportEmail($date, $summary)
    {
        $recipients = env('INVOICE_REPORT_EMAIL', 'hrsanto@gmail.com,harsih.hhr@gmail.com');
        $appEnv     = env('APP_ENV', 'development');
        $dataSource = 'production';

        $total    = $summary->total ?? 0;
        $terkirim = $summary->terkirim ?? 0;
        $pending  = $summary->pending ?? 0;
        $gagal    = $summary->gagal ?? 0;
        $persen   = $total > 0 ? round(($terkirim / $total) * 100, 1) : 0;

        $subject = "Laporan Harian Notifikasi WA - {$date} [PROD DATA/{$appEnv}]";

        $bodyHtml = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Laporan Harian Notifikasi WA</title>
<style>
  body { font-family: Arial, sans-serif; font-size: 14px; color: #333; }
  h2 { color: #2563eb; }
  .summary { background: #f0fdf4; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
  .item { display: inline-block; margin-right: 24px; }
  .val { font-size: 20px; font-weight: bold; color: #2563eb; }
  .sent { color: #16a34a; } .failed { color: #dc2626; } .pending { color: #d97706; }
  .footer { font-size: 12px; color: #999; margin-top: 24px; border-top: 1px solid #eee; padding-top: 12px; }
</style>
</head>
<body>
<h2>📊 Laporan Harian Notifikasi WA</h2>
<p>Jakarta, {$date}</p>

<div class="summary">
  <div class="item">Total<br><span class="val">{$total}</span></div>
  <div class="item"><span class="sent">Terkirim</span><br><span class="val sent">{$terkirim} ({$persen}%)</span></div>
  <div class="item"><span class="pending">Pending</span><br><span class="val pending">{$pending}</span></div>
  <div class="item"><span class="failed">Gagal</span><br><span class="val failed">{$gagal}</span></div>
</div>

<div class="footer">
  <p>Proses dijalankan otomatis oleh scheduler BOS MPJ v2 (18:00 WIB).<br>Environment: {$appEnv}<br>Data source: {$dataSource} database</p>
  <p>Hormat kami,<br><strong>Billing Media Prima Jaringan</strong></p>
</div>
</body>
</html>
HTML;

        $emails = array_map('trim', explode(',', $recipients));
        foreach ($emails as $email) {
            if (empty($email)) continue;

            try {
                $pdo = $this->getProdPdo();
                $stmt = $pdo->prepare(
                    'INSERT INTO Trx_email_queue (recipient, subject, body_html, status, created_at) VALUES (?, ?, ?, ?, ?)'
                );
                $stmt->execute([$email, $subject, $bodyHtml, 'pending', now()->toDateTimeString()]);
                $this->info("Laporan di-queue untuk {$email}");
            } catch (\Throwable $e) {
                $this->warn("Gagal queue email untuk {$email}: " . $e->getMessage());
            }
        }
    }
}
