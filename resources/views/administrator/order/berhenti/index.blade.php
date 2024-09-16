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
                <a href="/admin/trx_order/stop_lgn/create" class="btn btn-primary mb-3">Tambah Data Berhenti Langganan</a>

            </div>
            <div class="row">
                <ul class="nav navbar-right navbar-top-links">
                <ul class="dropdown-menu dropdown-alerts">
                    <li>
                        <a href="#">
                            <div>
                                <i class="fa fa-comment fa-fw"></i> New Comment
                                <span class="pull-right text-muted small">4 minutes ago</span>
                            </div>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a class="text-center" href="#">
                            <strong>See All Alerts</strong>
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </li>
                </ul>
                </ul>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="table-responsive">

                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nomor Order</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">No Pelanggan</th>
                                <th scope="col">Nama Pelanggan</th>
                                <th scope="col">Nilai Order</th>
                                <th scope="col">Status Pembayaran</th>
                                {{--  <th scope="col">Target Tgl Instalasi</th>  --}}
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order as $data )
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a href="/admin/trx_order/stop_lgn/getAksi?aksi=show&id={{ $data->id }}" class="text-decoration-none">
                                        {{ $data->no_formulir }}</a></td>
                                    <td>{{ date('d-m-Y', strtotime($data->tgl_order)) }}</td>
                                    <td>{{ $data->pelanggan->no_pelanggan }}</td>
                                    <td>{{ $data->pelanggan->nama_lengkap }}</td>
                                    <td>{{ number_format($data->gtot_amount) }}</td>
                                    <td>@if ($data->payment_status=='1')
                                        Menunggu Pembayaran
                                    @elseif ($data->payment_status=='2')
                                        Lunas
                                    @elseif ($data->payment_status=='3')
                                        Kadaluarsa
                                    @else
                                        Batal
                                    @endif</td>
                                    {{--  <td>{{ $data->catatan_instalasi }}</td>  --}}
                                    <td>
                                        {{--  <a href="/admin/trx_order/{{ $data->id }}/edit" class="btn btn-primary btn-circle">
                                            <i class="fa fa-edit"></i>
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
