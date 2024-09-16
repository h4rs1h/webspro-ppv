@extends('layouts.main')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $title }}</h1>
        </div>
        @if (session()->has('success'))
            <div class="alert alert-success col-lg-12" role="alert">
            {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Exp Date Pelanggan
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Pelanggan ID</th>
                                    <th scope="col">Nama Lengkap</th>
                                    <th scope="col">Unit ID</th>
                                    <th scope="col">Jenis Layanan</th>
                                    <th scope="col">Exp Date</th>
                                    <th scope="col">Status Layanan</th>
                                    <th scope="col">Exp Date Terakhir</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order as $data )
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->pelanggan_id }}</td>
                                        <td>{{ $data->nama_lengkap }}</td>
                                        <td>{{ $data->unitid }}</td>
                                        <td>{{ $data->jenis_layanan }}</td>
                                        <td>{{ date('d-m-Y', strtotime($data->exp_date)) }}</td>
                                        <td>{{ $data->status_layanan }}</td>
                                        <td>{{ date('d-m-Y', strtotime($data->expdate)) }}</td>


                                        <td>
                                            {{--  <a href="/admin/trx_bayar/kwitansi/{{ $data->pelanggan_id }}" class="btn btn-primary btn-circle">
                                                <i class="fa fa-money"></i>
                                            </a>  --}}
                                            <form action="/admin/trx_invoice/upd_expdate" method="post" class="btn" style="padding: 0">
                                                @csrf
                                                <input type="hidden" id="id" name="id" value="{{ $data->pelanggan_id }}">
												<input type="hidden" id="layanan_id" name="layanan_id" value="{{ $data->layanan_id }}">
                                                <button class="btn btn-primary btn-circle" onclick="return confirm('Yakin di Update?')"><i class="fa fa-money"></i></button>
                                            </form>
											<form action="/admin/trx_invoice" method="post" class="btn" style="padding: 0">
                                                @csrf
                                                <input type="hidden" id="unitid" name="unitid" value="{{ $data->unitid }}">
                                                <button class="btn btn-primary btn-circle" onclick="return confirm('Yakin Proses Buat Invoice?')"><i class="fa fa-plus"></i></button>
                                            </form>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>

</div>
@endsection
