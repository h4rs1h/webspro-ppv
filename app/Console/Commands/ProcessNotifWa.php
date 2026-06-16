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

        $waApiUrl  = env('WOOWA_URL_SEND', 'https://notifapi.com/');
        $waApiKey  = env('WOOWA_KEY', '7102f062dcec2541d848cc70a215dc6bd78bfa8fe9b30d4f');

        $devTestTo = env('WA_DEV_TEST_TO', null);
        $controlGroupId = env('WA_CONTROL_GROUP_ID', 'GuX193tTrfZ9geD0oFKOPv');
        if ($devTestTo) {
            $this->warn("⚠️  DEV MODE: Semua WA personal diteruskan ke {$devTestTo}");
        }

        $batch = DB::select('CALL hr_v2_process_notif_wa_sp(?)', [$batchSize]);

        if (empty($batch)) {
            $this->info('No pending notifications.');
            return 0;
        }

        $sent    = 0;
        $failed  = 0;
        $skipped = 0;

        foreach ($batch as $row) {
            // Replace placeholder: {TANDA_TERIMA:123} → short token URL
            $pesan = $this->resolvePlaceholders($row);

            // === GROUP MESSAGE ===
            if (!empty($row->group_id) || (($row->tipe_notif ?? null) === 'group_kontrol')) {
                if (empty($row->group_id)) {
                    $row->group_id = $controlGroupId;
                }
                $this->processGroupMessage($row, $waApiUrl, $waApiKey, $sent, $failed, $pesan);
                continue;
            }

            // === PERSONAL MESSAGE ===
            $targetNoWa = $devTestTo ?: $row->no_wa;
            $isOverridden = $devTestTo && $targetNoWa !== $row->no_wa;

            if (empty($targetNoWa) || strlen($targetNoWa) < 8) {
                DB::statement('CALL hr_v2_update_notif_status_sp(?, ?, ?, ?)', [
                    $row->id, 'failed', null,
                    'Invalid WA number: ' . ($targetNoWa ?? 'empty'),
                ]);
                $skipped++;
                $this->warn("Skipped #{$row->id}: invalid no_wa='{$targetNoWa}'");
                continue;
            }

            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->withOptions([
                    'verify'          => false,
                    'connect_timeout' => 10,
                    'timeout'         => 30,
                ])->post($waApiUrl . 'send_message', [
                    'phone_no'  => $targetNoWa,
                    'message'   => $pesan,
                    'key'       => $waApiKey,
                    'skip_link' => true,
                ]);

                $body      = trim($response->body());
                $isSuccess = $response->successful()
                    && (stripos($body, 'success') !== false
                        || stripos($body, '"status":"success"') !== false
                        || $body === 'success');

                if ($isSuccess) {
                    DB::statement('CALL hr_v2_update_notif_status_sp(?, ?, ?, ?)', [
                        $row->id, 'sent', $body, null,
                    ]);
                    $sent++;
                    $label = $isOverridden ? "{$row->no_wa}→{$targetNoWa}" : $row->no_wa;
                    $this->line("  ✅ #{$row->id} → {$row->nama_penerima} ({$label})");
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

        DB::table('Trx_logProses')->insert([
            'tgl_proses' => now(),
            'proses'     => 'Process Notif WA',
            'keterangan' => "Sent: {$sent}, Failed: {$failed}, Skipped: {$skipped}",
        ]);

        $this->info("Done: {$sent} sent, {$failed} failed, {$skipped} skipped");
        return 0;
    }

    /**
     * Replace {TANDA_TERIMA:35404} → https://domain/data/t/{short_token}
     * Token disimpan di hr_v2_short_token via SP.
     */
    protected function resolvePlaceholders($row)
    {
        return preg_replace_callback(
            '/\{TANDA_TERIMA:(\d+)\}/',
            function ($matches) use ($row) {
                $idBayar = (int) $matches[1];
                $result = DB::select('CALL hr_v2_create_short_token_sp(?, ?, ?)', [
                    $idBayar,
                    'ttbayar',
                    $row->pelanggan_id ?? null,
                ]);
                $token = $result[0]->token ?? null;
                if ($token) {
                    return url('/data/t/' . $token);
                }
                return url('/data/error');
            },
            $row->isi_pesan
        );
    }

    protected function processGroupMessage($row, $waApiUrl, $waApiKey, &$sent, &$failed, $pesan = null)
    {
        if ($pesan === null) {
            $pesan = $this->resolvePlaceholders($row);
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify'          => false,
                'connect_timeout' => 10,
                'timeout'         => 30,
            ])->post($waApiUrl . 'send_message_group_id', [
                'group_id' => $row->group_id,
                'message'  => $pesan,
                'key'      => $waApiKey,
            ]);

            $body      = trim($response->body());
            $isSuccess = $response->successful()
                && (stripos($body, 'success') !== false
                    || stripos($body, '"status":"success"') !== false
                    || $body === 'success');

            if ($isSuccess) {
                DB::statement('CALL hr_v2_update_notif_status_sp(?, ?, ?, ?)', [
                    $row->id, 'sent', $body, null,
                ]);
                $sent++;
                $this->line("  ✅ #{$row->id} → Group: {$row->nama_penerima}");
            } else {
                DB::statement('CALL hr_v2_update_notif_status_sp(?, ?, ?, ?)', [
                    $row->id, 'failed', $body,
                    'Group API response: ' . substr($body, 0, 300),
                ]);
                $failed++;
                $this->warn("  ❌ #{$row->id} Group: API non-success");
            }
        } catch (\Exception $e) {
            DB::statement('CALL hr_v2_update_notif_status_sp(?, ?, ?, ?)', [
                $row->id, 'failed', null,
                substr($e->getMessage(), 0, 500),
            ]);
            $failed++;
            $this->error("  💥 #{$row->id} Group: {$e->getMessage()}");
        }
    }
}
