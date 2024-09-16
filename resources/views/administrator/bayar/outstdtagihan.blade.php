@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $title }}</h1>
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
                {{--  <a href="/admin/pelanggan/create" class="btn btn-primary mb-3">Tambah Pelanggan</a>  --}}
                {{--  <a href="/admin/trx_bayar/create" class="btn btn-primary mb-3">Outstanding Pembayaran Tagihan</a>
                <a href="/admin/trx_bayar/getoutdaftar" class="btn btn-primary mb-3">Outstanding Pembayaran Pendaftaran</a>  --}}
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nomor Tagihan</th>
                                <th scope="col">Tanggal Tagihan</th>
                                <th scope="col">Nama Pelanggan</th>
                                <th scope="col">Unit ID</th>
                                <th scope="col">Periode Tagihan</th>
                                <th scope="col">Nominal Tagihan</th>
                                <th scope="col">Nominal Bayar</th>
                                <th scope="col">Outstading</th>
                                {{--  <th scope="col">Status Pembayaran</th>  --}}
                                {{--  <th scope="col">Target Tgl Instalasi</th>  --}}
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($outstd as $data )
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->no_tagihan }}
                                        {{--  <a href="/admin/trx_bayar/{{ $data->id }}" class="text-decoration-none">
                                        </a>  --}}
                                    </td>
                                    <td>{{ date('d-m-Y', strtotime($data->tgl_tagihan)) }}</td>
                                    <td>{{ $data->nama_lengkap }}</td>
									<td>{{ $data->unitid }}</td>
									<td>{{ $data->periode_pemakaian }}</td>
                                    <td>{{ number_format($data->gtot_tagihan) }}</td>
                                    <td>{{ number_format($data->amount) }}</td>
                                    <td>{{ number_format($data->outstanding) }}</td>
                                    <td>
                                        <a href="/admin/trx_bayar/bayar?no_tagihan={{ $data->id }}" class="btn btn-primary btn-circle" data-toggle="tooltip" data-placement="top" title="Proses Pembayaran">
                                            <i class="fa fa-paypal"></i>
                                        </a>
                                        {{--  <a href="/admin/trx_bayar/bayar/{{ $data->no_order }}" class="btn btn-primary btn-circle">
                                            <i class="fa fa-money"></i>
                                        </a>  --}}
                                        {{--  <a href="/admin/trx_order/{{ $data->id }}/edit" class="btn btn-primary btn-circle">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <form action="/admin/trx_order/{{ $data->id }}" method="post" class="btn" style="padding: 0">
                                            @method('delete')
                                            @csrf
                                            <input type="hidden" id="id" name="id" value="{{ $data->id }}">
                                            <button class="btn btn-danger btn-circle" onclick="return confirm('Are you sure?')"><i class="fa fa-times"></i></button>
                                        </form>  --}}
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
@endsection
