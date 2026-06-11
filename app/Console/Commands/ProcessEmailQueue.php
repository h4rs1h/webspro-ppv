<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ProcessEmailQueue extends Command
{
    protected $signature = 'email:process-queue';
    protected $description = 'Process pending email queue (Trx_email_queue)';

    public function handle()
    {
        $pending = DB::table('Trx_email_queue')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->limit(20)
            ->get();

        $sent = 0;
        $failed = 0;

        foreach ($pending as $row) {
            try {
                Mail::html($row->body_html, function ($msg) use ($row) {
                    $msg->to($row->recipient)->subject($row->subject);
                });

                DB::table('Trx_email_queue')
                    ->where('id', $row->id)
                    ->update(['status' => 'sent', 'sent_at' => now()]);

                $sent++;
            } catch (\Exception $e) {
                DB::table('Trx_email_queue')
                    ->where('id', $row->id)
                    ->update([
                        'status' => 'failed',
                        'error_message' => $e->getMessage()
                    ]);

                $failed++;
            }
        }

        DB::table('Trx_logProses')->insert([
            'tgl_proses' => now(),
            'proses' => 'Process Email Queue',
            'keterangan' => "Sent: {$sent}, Failed: {$failed}",
        ]);

        $this->info("Processed {$sent} sent, {$failed} failed");
        return 0;
    }
}
