@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $title }}</h1><br>
            <p><Strong>{{ $sub_title }}</Strong></p>
            <p><strong>{{ $msg }}</strong></p>
        </div>

    </div>
    @if (session()->has('success'))
        <div class="alert alert-success col-lg-12" role="alert">
        {{ session('success') }}
        </div>

    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Laporan Data Pelanggan
                    <div class="pull-right">
                        <div class="nav">
                            <select name="export_btn" id="export_btn" class="form-control">
                                <option value="0">== Pilih Metode Export ==</option>
                                <option value="1"> Export CSV</option>
                                <option value="2"> Export Excel</option>
                                <option value="3"> Export PDF</option>
                                <option value="4"> Export Print</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <th># No</th>
                                <th>Nomor Formulir</th>
                                <th>Tanggal Daftar</th>
								<th>Tanggal Jatuh Tempo</th>
                                <th>Nama Lengkap</th>
                                <th>Unit ID</th>
                                <th>Grand Total Pendaftaran</th>
                                <th>Nomor Kwitansi</th>
                                <th>Tanggal Kwitansi</th>
                                <th>Status Pembayaran</th>
                                <th>Metode Pembayaran</th>
                                <th>Total Pembayaran</th>
                                <th>Outstanding</th>
                            </thead>
                            <tbody>
                                @foreach ( $pelanggan as $data )
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->no_formulir }}</td>
                                        <td>{{ $data->tgl_daftar }}</td>
										<td>{{ $data->tgl_aktivasi }}</td>
                                        <td>{{ $data->nama_lengkap }}</td>
                                        <td>{{ $data->unitid }}</td>
                                        <td>{{ $data->gtot_amount }}</td>
                                        <td> @if($data->kwitansi)
                                                {{ 'KWT-'.$data->kwitansi }}
                                             @endif
                                        </td>
                                        <td>{{ $data->tgl_kwitansi }}</td>
                                        <td>{{ $data->StatusPembayaran }}</td>
                                        <td>{{ $data->Ket_metode_bayar }}</td>
                                        <td>{{ $data->payment_gtotal }}</td>
                                        <td>{{ $data->Outstanding }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
