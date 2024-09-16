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
                                <tr>
                                    <th># No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Unit ID</th>
                                    <th>Status Layanan</th>
                                    <th>Nomer Formulir</th>
                                    <th>Tanggal Aktifasi</th>
                                    <th>Description</th>
                                    <th>Jenis Layanan</th>
                                    <th>Periode Layanan</th>
                                    <th >Invoice Januari</th>
                                    <th >Periode Pemakaian Januari</th>
                                    <th >Amount Januari</th>
                                    <th >Invoice Februari</th>
                                    <th >Periode Pemakaian Februari</th>
                                    <th >Amount Februari</th>
                                    <th >Invoice Maret</th>
                                    <th >Periode Pemakaian Maret</th>
                                    <th >Amount Maret</th>
                                    <th >Invoice April</th>
                                    <th >Periode Pemakaian April</th>
                                    <th >Amount April</th>
                                    <th >Invoice Mei</th>
                                    <th >Periode Pemakaian Mei</th>
                                    <th >Amount Mei</th>
                                    <th >Invoice Juni</th>
                                    <th >Periode Pemakaian Juni</th>
                                    <th >Amount Juni</th>
                                    <th >Invoice Juli</th>
                                    <th >Periode Pemakaian Juli</th>
                                    <th >Amount Juli</th>
                                    <th >Invoice Agustus</th>
                                    <th >Periode Pemakaian Agustus</th>
                                    <th >Amount Agustus</th>
                                    <th >Invoice September</th>
                                    <th >Periode Pemakaian September</th>
                                    <th >Amount September</th>
                                    <th >Invoice Oktober</th>
                                    <th >Periode Pemakaian Oktober</th>
                                    <th >Amount Oktober</th>
                                    <th >Invoice November</th>
                                    <th >Periode Pemakaian November</th>
                                    <th >Amount November</th>
                                    <th >Invoice Desember</th>
                                    <th >Periode Pemakaian Desember</th>
                                    <th >Amount Desember</th>
                                    <th>Jumlah Invoice</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ( $pelanggan as $data )
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->nama_lengkap }}</td>
                                        <td>{{ $data->unitid }}</td>
                                        <td>{{ $data->ket_status_layanan }}</td>
                                        <td>{{ $data->no_order }}</td>
                                        <td>{{ $data->tgl_aktivasi }}</td>
                                        <td>{{ $data->item_desc }}</td>
                                        <td>{{ $data->jenis_layanan }}</td>
                                        <td>{{ $data->periode_layanan }}</td>
                                        <td>
                                            @if (isset($data->bln_1))
                                                {{ Str::replace(',',';',$data->bln_1) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->pakai_1))
                                                {{ Str::replace(',',';',$data->pakai_1) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->amt_1))
                                                {{ number_format($data->amt_1) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_2))
                                                {{ Str::replace(',',';',$data->bln_2) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->pakai_2))
                                                {{ Str::replace(',',';',$data->pakai_2) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->amt_2))
                                                {{ number_format($data->amt_2)  }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_3))
                                                {{ Str::replace(',',';',$data->bln_3) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->pakai_3))
                                                {{ Str::replace(',',';',$data->pakai_3) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->amt_3))
                                                {{  number_format($data->amt_3) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_4))
                                                {{ Str::replace(',',';',$data->bln_4) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->pakai_4))
                                                {{ Str::replace(',',';',$data->pakai_4) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->amt_4))
                                                {{ number_format($data->amt_4) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_5))
                                                {{ Str::replace(',',';',$data->bln_5) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->pakai_5))
                                                {{ Str::replace(',',';',$data->pakai_5) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->amt_5))
                                                {{  number_format($data->amt_5) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_6))
                                                {{ Str::replace(',',';',$data->bln_6) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->pakai_6))
                                                {{ Str::replace(',',';',$data->pakai_6) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->amt_6))
                                                {{ number_format($data->amt_6) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_7))
                                                {{ Str::replace(',',';',$data->bln_7) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->pakai_7))
                                                {{ Str::replace(',',';',$data->pakai_7) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->amt_7))
                                                {{ number_format($data->amt_7)  }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_8))
                                                {{ Str::replace(',',';',$data->bln_8) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->pakai_8))
                                                {{ Str::replace(',',';',$data->pakai_8) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->amt_8))
                                                {{ number_format($data->amt_8) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_9))
                                                {{ Str::replace(',',';',$data->bln_9) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->pakai_9))
                                                {{ Str::replace(',',';',$data->pakai_9) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->amt_9))
                                                {{ number_format($data->amt_9) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_10))
                                                {{ Str::replace(',',';',$data->bln_10) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->pakai_10))
                                                {{ Str::replace(',',';',$data->pakai_10) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->amt_10))
                                                {{  number_format($data->amt_10) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_11))
                                                {{ Str::replace(',',';',$data->bln_11) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->pakai_11))
                                                {{ Str::replace(',',';',$data->pakai_11) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->amt_11))
                                                {{ number_format($data->amt_11) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->bln_12))
                                                {{ Str::replace(',',';',$data->bln_12) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->pakai_12))
                                                {{ Str::replace(',',';',$data->pakai_12) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->amt_12))
                                                {{ number_format($data->amt_12) }}
                                            @endif
                                        </td>
                                        <td>{{ $data->jml_inv }}</td>
                                        <td>{{ number_format($data->Total_Invoice) }}</td>
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
