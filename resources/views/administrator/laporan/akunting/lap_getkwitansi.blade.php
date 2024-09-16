@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $title }}</h1><br>
            <p><Strong>{{ $sub_title }}</Strong></p>

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
                    Laporan Data Pembayaran Pelanggan
                    <div class="pull-right">
                        <div class="nav">
                            <select name="export_btn" id="export_btn" class="form-control btn-primary">
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
                                <th>No Formulir / No Tagihan</th>
                                <th>Tgl Transaksi</th>
                                <th>Nama Lengkap</th>
                                <th>Unit ID</th>
                                <th>Nomer Kwitansi</th>
                                <th>Tanggal Kwitansi</th>
                                <th>Tanggal Mutasi Bank</th>
                                <th>Status Pembayaran</th>
                                <th>Metode Pembayaran</th>
                                <th>Detail Item</th>
                                <th>Payment Amount</th>
                                <th>Grand Total Payment Amount</th>
                            </thead>
                            <tbody>
                                @foreach ( $pelanggan as $data )
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->no_formulir }}</td>
                                        <td>{{ $data->tgl_order }}</td>
                                        <td>{{ $data->nama_lengkap }}</td>
                                        <td>{{ $data->unitid }}</td>
                                        <td>{{ $data->no_kwitansi }}</td>
                                        <td>{{ $data->tgl_kwitansi }}</td>
                                        <td>{{ $data->tgl_mutasi_bank }}</td>
                                        <td>{{ $data->StatusPembayaran }}</td>
                                        <td>{{ $data->ket_metode_bayar }}</td>
                                        <td>{{ $data->detail_item }}</td>
                                        <td>{{ $data->sum_detail_amount }}</td>
                                        <td>{{ $data->gtotamount }}</td>

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
