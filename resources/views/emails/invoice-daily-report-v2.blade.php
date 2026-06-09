<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Generate Invoice Harian V2</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; }
        h2 { color: #2563eb; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 16px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; }
        .summary { background-color: #f0fdf4; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
        .summary-item { display: inline-block; margin-right: 24px; }
        .summary-value { font-size: 20px; font-weight: bold; color: #2563eb; }
        .footer { font-size: 12px; color: #999; margin-top: 24px; border-top: 1px solid #eee; padding-top: 12px; }
        .created { color: #16a34a; }
        .skipped { color: #d97706; }
        .failed { color: #dc2626; }
    </style>
</head>
<body>

    <h2>📊 Laporan Generate Invoice Harian V2</h2>
    <p>Jakarta, {{ $report['date'] ?? '-' }}</p>

    <p>Kepada Yth.<br>Tim Admin Media Prima Jaringan<br>Di Tempat.</p>

    <p>Berikut ringkasan hasil proses generate invoice harian v2 pada tanggal <strong>{{ $report['date'] ?? '-' }}</strong>:</p>

    <div class="summary">
        <div class="summary-item">
            Total Kandidat<br>
            <span class="summary-value">{{ $report['summary']['total_candidate'] ?? '-' }}</span>
        </div>
        <div class="summary-item">
            <span class="created">Invoice Baru</span><br>
            <span class="summary-value created">{{ $report['summary']['created_count'] ?? '-' }}</span>
        </div>
        <div class="summary-item">
            <span class="skipped">Sudah Ada</span><br>
            <span class="summary-value skipped">{{ $report['summary']['skipped_existing'] ?? '-' }}</span>
        </div>
        <div class="summary-item">
            <span class="failed">Gagal</span><br>
            <span class="summary-value failed">{{ $report['summary']['failed_count'] ?? '-' }}</span>
        </div>
    </div>

    <h3>📋 Verifikasi</h3>
    <table>
        <tr>
            <td>Total Kandidat</td>
            <td><strong>{{ $report['verify']['total_candidates'] ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td>Sudah Punya Invoice</td>
            <td>{{ $report['verify']['already_has_invoice'] ?? '-' }}</td>
        </tr>
        <tr>
            <td>Masih Missing</td>
            <td style="color: {{ ($report['verify']['still_missing_invoice'] ?? 0) > 0 ? '#dc2626' : '#16a34a' }};">
                <strong>{{ $report['verify']['still_missing_invoice'] ?? '-' }}</strong>
            </td>
        </tr>
    </table>

    @if(($report['summary']['created_count'] ?? 0) > 0)
    <h3>🆕 Invoice Baru Dibuat</h3>
    <p style="color: #16a34a;">Sebanyak <strong>{{ $report['summary']['created_count'] ?? 0 }}</strong> invoice baru berhasil dibuat.</p>
    @endif

    @if(($report['summary']['failed_count'] ?? 0) > 0)
    <h3>⚠️ Invoice Gagal</h3>
    <p style="color: #dc2626;">Terdapat <strong>{{ $report['summary']['failed_count'] ?? 0 }}</strong> invoice yang gagal dibuat. Mohon dicek.</p>
    @endif

    <div class="footer">
        <p>
            Proses dijalankan otomatis oleh scheduler BOS MPJ v2 (05:45 WIB).<br>
            Environment: {{ $report['env'] ?? 'development' }}<br>
            Mode: {{ ($report['dry_run'] ?? false) ? 'dry-run (tidak menyimpan)' : 'execute (menyimpan)' }}
        </p>
        <p>Hormat kami,<br><strong>Billing Media Prima Jaringan</strong></p>
    </div>

</body>
</html>
