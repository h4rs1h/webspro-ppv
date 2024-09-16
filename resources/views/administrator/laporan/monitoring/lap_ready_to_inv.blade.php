@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $title }}</h1><br>
            <p>{{ $sub_title }}</p>
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
                                <th>Order No</th>
                                <th>Pelanggan ID</th>
                                <th>Unit ID</th>
                                <th>Tanggal Aktivasi</th>
                                <th>Exp Date</th>
                                <th>Status Layanan</th>
 								<th>Cek Tgl Exp</th>
                            </thead>
                            <tbody>
                                @foreach ( $pelanggan as $data )
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->order_id }}</td>
                                        <td>{{ $data->pelanggan_id }}</td>
                                        <td>{{ $data->unitid }}</td>
                                        <td>{{ $data->tgl_aktivasi }}</td>
                                        <td>{{ $data->exp_date }}</td>
                                        <td>{{ $data->status_layanan }}</td>
										<td>{{ $data->cek_date }}</td>

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
