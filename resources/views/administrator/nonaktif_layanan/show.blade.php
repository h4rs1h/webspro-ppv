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
                Data Request Non Aktif Layanan
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-4">
                            <label  class="col-md-1 col-form-label text-md-start">{{ __('Nomor ') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control " readonly value="{{ $hdrdata->no_nonaktif_layanan }}" >

                        </div>
                        <div class="col-md-4">

                            <label  class="col-md-1 col-form-label text-md-end">{{ __('Tanggal ') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control " readonly value="{{ $hdrdata->tgl }}" >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Data Transaksi
            </div>

            <!-- /.panel-heading -->

            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nomor Trx</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Periode Pemakaian</th>
                                <th scope="col">Outstanding</th>
                                {{--  <th scope="col">Tanggal Jt Tempo</th>
                                <th scope="col">Jumlah Hari</th>  --}}
                                <th scope="col">Request Off </th>
                                <th scope="col">Action Off </th>
                                <th scope="col">Request On </th>
                                <th scope="col">Action On </th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $dt )
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($dt->tipe_trx=='1')
                                            {{ $dt->nomer_trx }}
                                        @else
                                            {{ 'Inv-'.$dt->nomer_trx }}
                                        @endif
                                    </td>
                                    <td>
                                       {{ $dt->nama_lengkap }}
                                    </td>
                                    <td>
                                       {{ $dt->periode_pemakaian }}
                                    </td>
                                    <td>{{ number_format($dt->gtot_tagihan) }}</td>
                                    {{--  <td>
                                        @isset($dt->tgl_jatuh_tempo)
                                        {{ Carbon\Carbon::parse($dt->tgl_jatuh_tempo)->format('d-m-Y')  }}

                                        @endisset
                                    </td>
                                    {{--  <td>{{ date('d-m-Y', strtotime($dt->tgl)) }}</td>  --}}
                                    {{--  <td>{{ $dt->jml_hari_telat }}</td>    --}}
                                    <td>
                                        @isset($dt->tgl_req_off)
                                        {{ Carbon\Carbon::parse($dt->tgl_req_off)->format('d-m-Y H:i:s')  }}

                                        @endisset
                                    </td>
                                    <td>
                                        @if (isset($dt->tgl_act_off))
                                            {{ Carbon\Carbon::parse($dt->tgl_act_off)->format('d-m-Y H:i:s')  }}

                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($dt->tgl_req_on))
                                            {{ Carbon\Carbon::parse($dt->tgl_req_on)->format('d-m-Y H:i:s')  }}


                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($dt->tgl_act_on))
                                            {{ Carbon\Carbon::parse($dt->tgl_act_on)->format('d-m-Y H:i:s')  }}

                                        @endif
                                    </td>
                                    <td>
                                        <a href="/admin/trx_nonaktif/getdata?show=upd&id={{ $dt->id }}&tipe_trx={{ $dt->tipe_trx }}&id_trx={{ $dt->id_trx }}" class="btn btn-primary btn-circle" data-toggle="tooltip" data-placement="top" title="Edit Data Non Aktif">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @if (!isset($dt->kirim_notif))
                                        <a href="/admin/trx_nonaktif/getdata?sendgroup=req_on&id={{ $dt->id }}&tipe_trx={{ $dt->tipe_trx }}&id_trx={{ $dt->id_trx }}" class="btn btn-success btn-circle" data-toggle="tooltip" data-placement="top" title="Kirim Notifikasi ke Group Problem Handling">
                                            <i class="fa fa-whatsapp"></i>
                                        </a>
                                    @else
										@if ($id_user=='1')
										<a href="/admin/trx_nonaktif/getdata?sendgroup=req_on&id={{ $dt->id }}&tipe_trx={{ $dt->tipe_trx }}&id_trx={{ $dt->id_trx }}" class="btn btn-danger btn-circle" onclick="return confirm('Nomer {{ $dt->nomer_trx }}, sudah pernah kirim notifikasi ke Moratel sebanyak {{ $dt->kirim_notif }} kali, Yakin Kirim Notif ke Mora lagi?')" data-toggle="tooltip" data-placement="top" title="Kirim Notifikasi ke Group Problem Handling" >
                                            <i class="fa fa-whatsapp"></i>
                                        </a>
										@else
                                        <a href="/admin/trx_nonaktif/getdata?sendgroup=req_on&id={{ $dt->id }}&tipe_trx={{ $dt->tipe_trx }}&id_trx={{ $dt->id_trx }}" class="btn btn-danger btn-circle" onclick="return confirm('Nomer {{ $dt->nomer_trx }}, sudah pernah kirim notifikasi ke Moratel sebanyak {{ $dt->kirim_notif }} kali, Yakin Kirim Notif ke Mora lagi?')"  data-toggle="tooltip" data-placement="top" title="Kirim Notifikasi ke Group Problem Handling" >
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
