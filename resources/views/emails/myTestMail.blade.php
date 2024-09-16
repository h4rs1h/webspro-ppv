<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>mediaprimajaringan.com</title>
</head>
<body>

<p>Jakarta, {{ $details['tgl'] }}</p>

<p>Kepada Yth.<br />
Tim Admin Media Prima Jaringan<br />
Di Tempat.</p>

<p>Dengan hormat,&nbsp;</p>

<p>Melalui Email ini dikirimkan data generate invoice pada tanggal {{ $details['tgl'] }}adalah sebagai berikut:</p>

<ol>
    <p></p><br>
    <li>Data Unit Perlu Proses</li>
        <table border="1">
            <thead>
                <tr>
                    <td>NO</td>
                    <td>NO Order</td>
                    <td>Pelanggan ID</td>
                    <td>Unit ID</td>
                    <td>Tanggal Aktivasi</td>
                    <td>Tanggal Exp</td>
                    <td>Status Layanan</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($details['data1'] as $d )
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->order_id }}</td>
                        <td>{{ $d->pelanggan_id }}</td>
                        <td>{{ $d->unitid }}</td>
                        <td>{{ $d->tgl_aktivasi }}</td>
                        <td>{{ $d->exp_date }}</td>
                        <td>{{ $d->status_layanan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
<p></p><br>
    <li>Data Unit Berhasil Proses Proforma Invoice</li>
    <table border="1">
        <thead>
            <th># No</th>
            <th>ID</th>
            <th>No Tagihan</th>
            <th>Unit ID</th>
            <th>Tanggal Aktivasi</th>
            <th>Tanggal Jatuh Tempo</th>
            <th>Periode Pemakaian</th>
            <th>Total Tagihan</th>

        </thead>
        <tbody>
            @foreach ( $details['data2'] as $data )
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->no_tagihan }}</td>
                    <td>{{ $data->unitid }}</td>
                    <td>{{ $data->tgl_aktivasi }}</td>
                    <td>{{ $data->tgl_jatuh_tempo }}</td>
                    <td>{{ $data->periode_pemakaian }}</td>
                    <td>{{ $data->gtot_tagihan }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
	<p></p><br>
    <li>Data Unit Proses Kirim Notifikas WA</li>
    <table border="1">
        <thead>
            <th># No</th>
            <th>ID Pelanggan</th>
            <th>Nama </th>
            <th>Unit ID</th>
            <th>No Order</th>
            <th>Tipe Order</th>
            <th>Lama Jatuh Tempo</th>
        </thead>
        <tbody>
            @foreach ( $details['data3'] as $data )
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->pelanggan_id }}</td>
                    <td>{{ $data->nama_lengkap }}</td>
                    <td>{{ $data->unitid }}</td>
                    <td>{{ $data->no_order }}</td>
                    <td>{{ $data->tipe_order }}</td>
                    <td>{{ $data->jt_exp }}</td>


                </tr>
            @endforeach
        </tbody>
    </table>
</ol>

<p>Demikian kami sampaikan. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>

<p>Hormat kami,<br />
Billing Media Prima Jaringan</p>

    <p>Thanks you</p>
</body>
</html>
