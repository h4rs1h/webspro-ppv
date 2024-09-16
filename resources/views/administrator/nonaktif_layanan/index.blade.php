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
    @if (session()->has('danger'))
        <div class="alert alert-danger col-lg-12" role="alert">
        {{ session('danger') }}
        </div>
    @endif


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                 <a href="/admin/trx_nonaktif/create" class="btn btn-primary mb-3">Proses Request Non Aktif</a>
				<a href="/admin/trx_nonaktif/getdata?show=rekap" class="btn btn-primary mb-3">Monitoring Non Aktif</a>
                <a href="/admin/trx_nonaktif/getdata?show=dtlPelanggan" class="btn btn-primary mb-3">Monitoring Pelanggan Non Aktif</a>
            </div>

            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nomor Request</th>
                                <th scope="col">Tanggal </th>
                                <th scope="col">Jumlah Unit</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $dt )
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $dt->no_nonaktif_layanan }}
                                    </td>
                                    <td>{{ date('d-m-Y', strtotime($dt->tgl)) }}</td>
                                    <td>{{ $dt->jml }}</td>
                                    <td>
                                        <a href="/admin/trx_nonaktif/getdata?show=list&id={{ $dt->id }}" class="btn btn-primary btn-circle" data-toggle="tooltip" data-placement="top" title="Lihat Detail Transaksi">
                                            <i class="fa fa-book"></i>
                                        </a>
                                        @if (!isset($dt->kirim_notif))
                                            <a href="/admin/trx_nonaktif/getdata?sendgroup=list&id={{ $dt->id }}" class="btn btn-success btn-circle" data-toggle="tooltip" data-placement="top" title="Kirim Notifikasi ke Group Problem Handling">
                                                <i class="fa fa-whatsapp"></i>
                                            </a>
                                        @else
											@if ($id_user=='1')
                                                <a href="/admin/trx_nonaktif/getdata?sendgroup=list&id={{ $dt->id }}" class="btn btn-danger btn-circle" onclick="return confirm('Nomer {{ $dt->no_nonaktif_layanan }}, sudah pernah kirim notifikasi ke Moratel sebanyak {{ $dt->kirim_notif }} kali, Yakin Kirim Notif ke Mora lagi?')" data-toggle="tooltip" data-placement="top" title="Kirim Notifikasi ke Group Problem Handling">
                                                    <i class="fa fa-whatsapp"></i>
                                                </a>
                                            @else
                                            <a href="/admin/trx_nonaktif/getdata?sendgroup=list&id={{ $dt->id }}" class="btn btn-danger btn-circle" onclick="return confirm('Nomer {{ $dt->no_nonaktif_layanan }}, sudah pernah kirim notifikasi ke Moratel sebanyak {{ $dt->kirim_notif }} kali, Yakin Kirim Notif ke Mora lagi?')" data-toggle="tooltip" data-placement="top" title="Kirim Notifikasi ke Group Problem Handling">
                                                <i class="fa fa-whatsapp"></i>
                                            </a>
										@endif
                                        @endif
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
