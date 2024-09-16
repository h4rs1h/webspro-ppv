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

        <div>

        </div>
        <div class="panel panel-default">

            <div class="panel-heading">
Data Cicilan Pelanggan
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

            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nomor Formulir</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Nama Pelanggan</th>
                                <th scope="col">No Unit</th>
                                {{--  <th scope="col">Nilai Order</th>  --}}
                                {{--  <th scope="col">Deskripsi Layanan </th>
                                <th scope="col">Periode</th>  --}}
                                <th scope="col">Nilai Tagihan</th>
                                <th scope="col">Lama Cicilan</th>
                                <th scope="col">Tanggal Aktivasi</th>
                                <th scope="col">Tanggal Tagihan</th>
                                        <th scope="col">Tanggal Jatuh Tempo</th>
                                <th scope="col">Outstanding</th>
                                <th scope="col">Cetak Tagihan</th>
								<th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($termin as $data )
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->no_formulir }}</td>
									{{-- date('d-m-Y', strtotime($data->tgl_order)) --}}
                                    <td>{{ $data->tgl_order }}</td>
                                    <td>{{ $data->nama_lengkap }}</td>
                                    <td>{{ $data->unitid }}</td>
                                    {{--  <td>{{ number_format($data->gtot_amount) }}</td>  --}}
                                    {{--  <td>{{ $data->title.' '.$data->sub_title }}</td>  --}}
                                    {{--  <td>{{ $data->qty }}</td>  --}}
                                    <td>{{ number_format($data->sub_amount) }}</td>
                                    <td>{{ $data->termin_bayar }}</td>
                                    <td>
                                        @if (isset($data->tgl_aktivasi))
                                        {{ $data->tgl_aktivasi }}
										{{-- date('d-m-Y', strtotime($data->tgl_aktivasi)) --}}
                                        @endif
                                    </td>
                                    <td>
                                                @if (isset($data->tgl_tagih))
                                                    {{-- date('d-m-Y', strtotime($data->tgl_tagih)) --}}
													{{ $data->tgl_tagih }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($data->tgl_jt_tempo))
												{{-- date('d-m-Y', strtotime($data->tgl_jt_tempo)) --}}
                                                    {{ $data->tgl_jt_tempo }}
                                                @endif

                                            </td>
                                    <td>{{ number_format($data->outstanding) }}</td>
                                    <td>
                                        {{--  <a href="/admin/trx_order/" class="btn btn-primary btn-circle">
                                            <i class="fa fa-paper-plane"></i>
                                        </a>
                                        <a href="/admin/trx_order/" class="btn btn-primary btn-circle">
                                            <i class="fa fa-paper-plane"></i>
                                        </a>
                                        <a href="/admin/trx_order/" class="btn btn-primary btn-circle">
                                            <i class="fa fa-paper-plane"></i>
                                        </a>
                                        <a href="/admin/trx_order/" class="btn btn-primary btn-circle">
                                            <i class="fa fa-paper-plane"></i>
                                        </a>  --}}
                                        <a href="/admin/trx_order/termin?no_order={{ $data->no_order }}&tipe_order={{ $data->tipe_order }}" class="btn btn-primary mb-3">Inv-{{ $data->jum_bayar }}</a>
                                    </td>
									<td>
									 <a href="/admin/trx_order/kirim_wa?no_order={{ $data->no_order }}&tipe_order={{ $data->tipe_order }}&tipe_wa=termin" class="btn btn-success btn-circle">
                                            <i class="fa fa-whatsapp"></i>
                                        </a>
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
