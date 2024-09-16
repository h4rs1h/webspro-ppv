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
                                <th>Nama Lengkap</th>
                                <th>Unit ID</th>
                                <th>Status Layanan</th>
                                <th>Nomer Formulir</th>
                                <th>Tanggal Aktifasi</th>
								<th>Periode Layanan</th>
                                <th>Januari</th>
                                <th>Februari</th>
                                <th>Maret</th>
                                <th>April</th>
                                <th>Mei</th>
                                <th>Juni</th>
                                <th>Juli</th>
                                <th>Agustus</th>
                                <th>September</th>
                                <th>Oktober</th>
                                <th>November</th>
                                <th>Desember</th>
                            </thead>
                            <tbody>
                                @foreach ( $pelanggan as $data )
                                    @if ($data->status_layanan=='1')
                                        <tr>
                                    @elseif ($data->status_layanan=='2')
                                        <tr class="info">
                                    @elseif ($data->status_layanan=='3')
                                        <tr class="warning">
                                    @elseif ($data->status_layanan=='4')
                                        <tr class="danger">
                                    @elseif ($data->status_layanan=='5')
                                        <tr class="info">
                                    @elseif ($data->status_layanan=='6')
                                        <tr class="success">
                                    @endif
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->nama_lengkap }}</td>
                                        <td>{{ $data->unitid }}</td>
                                        <td>{{ $data->ket_status_layanan }}</td>
                                        <td>{{ $data->no_order }}</td>
                                        <td>{{ $data->tgl_aktivasi }}</td>
										<td>{{ $data->periode_layanan }}</td>
                                        <td>
                                            @if (isset($data->bln_1))
                                                {{ $data->bln_1 }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_2))
                                                {{ $data->bln_2  }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_3))
                                                {{  $data->bln_3 }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_4))
                                                {{ $data->bln_4 }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_5))
                                                {{  $data->bln_5 }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_6))
                                                {{ $data->bln_6 }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_7))
                                                {{ $data->bln_7  }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_8))
                                                {{ $data->bln_8 }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_9))
                                                {{ $data->bln_9 }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_10))
                                                {{  $data->bln_10 }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_11))
                                                {{ $data->bln_11 }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_12))
                                                {{ $data->bln_12 }}
                                            @endif
                                        </td>
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
