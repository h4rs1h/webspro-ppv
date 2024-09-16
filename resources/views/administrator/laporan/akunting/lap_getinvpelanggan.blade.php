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
                                <th>Id</th>
                                <th>No Formulir</th>
                                <th>Tanggal Daftar</th>
								<th>Nama Lengkap</th>
                                <th>Unit ID</th>
                                <th>Tanggal Aktivasi Router</th>
                                <th>Tanggal Aktivasi STB</th>
                                <th>Status</th>
                                {{-- <th>Sub Tower</th>
                                <th>Lantai</th>
                                <th>Nomer Unit</th>
                                <th>No Pelanggan</th> --}}
								
                                <th>Line No</th>
                                <th>Description</th>
                                <th>Jenis Layanan</th>
                                <th>Amount</th>
                                <th>Periode</th>
                                <th>Periode Diskon</th>
                                <th>Sub Total</th>
                                <th>Tax Amount</th>
                                <th>Total Amount</th>
                                <th>Grand Total Amount</th>
                                <th>No Kwitansi</th>
                                <th>Tanggal Kwitansi</th>
                                <th>Status Pembayaran</th>
								<th>Metode Pembayaran</th>
                                <th>Payment Amount</th>
                                <th>Grand Total Payment Amount</th>
                            </thead>
                            <tbody>
                                @foreach ( $pelanggan as $data )
                                    <tr>
                                        <td>{{ $data->id }}</td>
                                        <td>{{ $data->no_formulir }}</td>
                                        <td>{{ $data->tgl_daftar }}</td>
										<td>{{ $data->nama_lengkap }}</td>
                                        <td>{{ $data->unitid }}</td>
                                        <td>{{ $data->tgl_aktivasi }}</td>
                                        <td>{{ $data->tgl_aktivasi_stb }}</td>
                                        <td>
                                            @if ($data->statuslayanan=="1")
                                                Aktif
                                            @elseif ($data->statuslayanan=="2")
                                                Suspend
                                            @elseif ($data->statuslayanan=="3")
                                                Non Aktif
                                            @elseif ($data->statuslayanan=="4")
                                                Berhenti Langganan
                                            @elseif ($data->statuslayanan=="5")
                                                Batal Aktivasi
                                            @elseif ($data->statuslayanan=="6")
                                                Belum Aktivasi
                                            @else
                                                None
                                            @endif
                                        </td>
                                      {{--  <td>{{ $data->sub_tower }}</td>
                                        <td>{{ $data->lantai }}</td>
                                        <td>{{ $data->nomer_unit }}</td>
                                        <td>{{ $data->no_pelanggan }}</td> --}}
										
                                        <td>{{ $data->line_no }}</td>
                                        <td>{{ $data->description }}</td>
                                        <td>{{ $data->jenis_layanan }}</td>
                                        <td>{{ $data->amount }}</td>
                                        <td>{{ $data->periode }}</td>
                                        <td>{{ $data->periode_diskon }}</td>
                                        <td>{{ $data->subtotal }}</td>
                                        <td>{{ $data->tax_amount }}</td>
                                        <td>{{ $data->sub_amount }}</td>
                                        <td>{{ $data->gtot_amount }}</td>
                                        <td>
											@if($data->kwitansi_)
												{{ 'KWT-'.$data->kwitansi_ }}
											@endif
										</td>
                                        <td>{{ $data->tgl_kwitansi_ }}</td>
                                        <td>{{ $data->statusPembayaran_ }}</td>
										<td>{{ $data->ket_metode_bayar_ }}</td>
                                        <td>{{ $data->payment_amount_ }}</td>
                                        <td>{{ $data->payment_gtotal_ }}</td>
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
