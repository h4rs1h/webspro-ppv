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
                    Laporan Data Pelanggan Non Aktif
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
                                <th>No Form Request Non aktif Pelanggan</th>
                                <th>Tgl Transaksi Non Aktif Pelanggan</th>
                                <th>Nomer Transaksi Pendaftaran / Invoice</th>
                                <th>Nama Lengkap</th>
                                <th>Unit ID</th>
                                <th>Tanggal Aktivasi Router</th>
                                <th>Tanggal Jatuh Tempo</th>
                                <th>Jumlah Hari Keterlambatan</th>
                                <th>Jenis Layanan</th>
                                <th>Periode Pemakaian</th>
                                <th>Outstanding Amount</th>
                                <th>Permintaan Layanan Dimatikan/Non aktif</th>
                                <th>Permintaan Layanan Dinyalakan/aktif</th>
                                <th>Layanan Dimatikan/Non aktif</th>
                                <th>Layanan Dinyalakan/aktif</th>
								<th>Non Aktif Selama</th>
                                @if($user_id =='1')
                                <th>Harga Paket Mora</th>
                                <th>Harga Prorate</th>
                                <th>Jumlah Sudah Ditagihkan Mora Saat Layanan Suspend</th>
                                @endif
                            </thead>
                            <tbody>
                                @foreach ( $pelanggan as $data )
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->no_nonaktif_layanan }}</td>
                                        <td>{{ $data->tgl_nonaktif_layanan }}</td>
                                        <td>{{ $data->nomer_trx }}</td>
                                        <td>{{ $data->nama_lengkap }}</td>
                                        <td>{{ $data->unitid }}</td>
                                        <td>{{ $data->tgl_aktivasi }}</td>

                                        <td>{{ $data->tgljatuhtempo }}</td>
                                        <td>{{ $data->exp }}</td>
                                        <td>{{ $data->jenis_layanan }}</td>
                                        <td>{{ $data->periode_pemakaian }}</td>
                                        <td>{{ $data->amt_outstanding }}</td>
                                        <td>{{ $data->tgl_req_off }}</td>
                                        <td>{{ $data->tgl_req_on }}</td>
                                        <td>{{ $data->tgl_act_off }}</td>
                                        <td>{{ $data->tgl_act_on }}</td>
										<td>{{ $data->lamaNonAktif }}</td>
                                        @if($user_id =='1')
                                        <td>{{ $data->harga_provider }}</td>
                                        <td>{{ $data->prorate }}</td>
                                        <td>{{ $data->tagihanmora }}</td>
                                        @endif
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
