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
				<a href="/admin/trx_invoice/create" class="btn btn-primary mb-3" disabled >Buat Invoice Tagihan Manual</a>
                {{--  <a href="/admin/pelanggan/create" class="btn btn-primary mb-3" disabled>Tambah Pelanggan</a>  --}}
                {{--  <a href="/admin/trx_bayar/create" class="btn btn-primary mb-3">Tambah Pembayaran</a>  --}}
				<div class="pull-right">
                        <div class="nav">
                            <select name="export_btn" id="export_btn" class="form-control btn-primary">
                                <option value="0">Download Data</option>
                                <option value="1">CSV</option>
                                <option value="2">Excel</option>
                                <option value="3">PDF</option>
                                <option value="4">Print</option>
                            </select>
                        </div>
                    </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nomor Invoice</th>
                                <th scope="col">Tanggal Ivoice</th>
								 <th scope="col">Tanggal Aktivasi</th>
								<th scope="col">Tanggal Jatuh Tempo</th>
                                <th scope="col">Jml Hari</th>
                                <th scope="col">No Formulir </th><th> Periode Tagihan</th>
                                <th scope="col">Unit ID</th> 
                                <th scope="col">Nama Pelanggan</th>
                               {{-- <th scope="col">Tower - Unit</th> --}}
                                <th scope="col">Total Tagihan</th>
                                 <th scope="col">Status Pembayaran</th> 
                                 <th scope="col">Keterangan Batal</th>
                                 <th scope="col">Action</th> 
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order as $data )
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="/admin/trx_invoice/inv?no_tagihan={{ $data->no_tagihan }}" class="text-decoration-none" data-toggle="tooltip" data-placement="top" title="Cetak Invoice PDF" target="_blank">
                                            {{ $data->new_notagihan }}
                                        </a>
                                    </td>
                                    <td>{{ $data->tgl_tagihan }}</td>
									<td>{{ $data->tgl_aktivasi }}</td>
									<td>{{ $data->tgl_jatuh_tempo }}</td>
									{{-- <td>{{ date('d-m-Y', strtotime($data->tgl_tagihan)) }}</td>
									<td>{{ date('d-m-Y', strtotime($data->tgl_aktivasi)) }}</td>
									<td>{{ date('d-m-Y', strtotime($data->tgl_jatuh_tempo)) }}</td> --}}
                                    <td>{{ $data->exp }}</td>
									<td>{{ $data->no_formulir }} </td><td> {{ $data->periode_pemakaian }}</td>
                                    <td>{{ $data->unitid }}</td>
                                    <td>{{ $data->nama_lengkap }}</td>
                                 {{--   <td>{{ $data->tower_unit }}</td> --}}
                                    <td>{{ number_format($data->gtot_tagihan) }}</td>
									<td>{{ $data->ket }}</td>
									<td>{{ $data->ket_batal }}</td>
                                    <td>
										 <a href="/admin/trx_invoice/kirim_wa?no_tagihan={{ $data->no_tagihan }}" class="btn btn-success btn-circle" data-toggle="tooltip" data-placement="top" title="Kirim Notifikasi WA">
                                            <i class="fa fa-whatsapp"></i>
                                        </a>
                                       {{-- <a href="/admin/trx_bayar/kwitansi/{{ $data->id }}" class="btn btn-primary btn-circle">
                                            <i class="fa fa-money"></i>
                                        </a> --}}
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
