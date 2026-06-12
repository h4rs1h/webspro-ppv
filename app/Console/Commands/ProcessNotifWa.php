<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ProcessNotifWa extends Command
{
    protected $signature = 'notif:process-wa {--batch=10 : Batch size per run}';
    protected $description = 'Process pending WA notification queue (hr_v2_notif_queue)';

    public function handle()
    {
        $batchSize = (int) $this->option('batch');

        // Konfigurasi WA API (dari .env atau fallback ke existing key)
        $waApiUrl = env('WOOWA_URL_SEND', 'https://notifapi.com/') . 'send_message';
        $waApiKey = env('WOOWA_KEY', '7102f062dcec2541d848cc70a215dc6bd78bfa8fe9b30d4f');

        // Ambil pending items via SP
        $batch = DB::select('CALL hr_v2_process_notif_wa_sp(?)', [$batchSize]);

        if (empty($batch)) {
            $this->info('No pending notifications.');
            return 0;
        }

        $sent = 0;
        $failed = 0;
        $skipped = 0;

        foreach ($batch as $row) {
            // Skip jika no_wa invalid
            if (empty($row->no_wa) || strlen($row->no_wa) < 8) {
                DB::statement('CALL hr_v2_update_notif_status_sp(?, ?, ?, ?)', [
                    $row->id, 'failed',
                    null,
                    'Invalid WA number: ' . ($row->no_wa ?? 'empty'),
                ]);
                $skipped++;
                $this->warn("Skipped #{$row->id}: invalid no_wa='{$row->no_wa}'");
                continue;
            }

            try {
                // Kirim ke API woo-wa (matching pola existing MessageController)
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->withOptions([
                    'verify' => false,
                    'connect_timeout' => 10,
                    'timeout' => 30,
                ])->post($waApiUrl, [
                    'phone_no'  => $row->no_wa,
                    'message'   => $row->isi_pesan,
                    'key'       => $waApiKey,
                    'skip_link' => true,
                ]);

                // Deteksi sukses: HTTP 2xx + body "success" (pola woo-wa)
                $body = trim($response->body());
                $isSuccess = $response->successful()
                    && (stripos($body, 'success') !== false
                        || stripos($body, '"status":"success"') !== false
                        || $body === 'success');

                if ($isSuccess) {
                    DB::statement('CALL hr_v2_update_notif_status_sp(?, ?, ?, ?)', [
                        $row->id, 'sent', $body, null,
                    ]);
                    $sent++;
                    $this->line("  ✅ #{$row->id} → {$row->nama_penerima} ({$row->no_wa})");
                } else {
                    DB::statement('CALL hr_v2_update_notif_status_sp(?, ?, ?, ?)', [
                        $row->id, 'failed', $body,
                        'API response: ' . substr($body, 0, 300),
                    ]);
                    $failed++;
                    $this->warn("  ❌ #{$row->id}: API returned non-success");
                }
            } catch (\Exception $e) {
                DB::statement('CALL hr_v2_update_notif_status_sp(?, ?, ?, ?)', [
                    $row->id, 'failed', null,
                    substr($e->getMessage(), 0, 500),
                ]);
                $failed++;
                $this->error("  💥 #{$row->id}: {$e->getMessage()}");
            }
        }

        // Log ke Trx_logProses
        DB::table('Trx_logProses')->insert([
            'tgl_proses' => now(),
            'proses'     => 'Process Notif WA',
            'keterangan' => "Sent: {$sent}, Failed: {$failed}, Skipped: {$skipped}",
        ]);

        $this->info("Done: {$sent} sent, {$failed} failed, {$skipped} skipped");
        return 0;
    }
}
