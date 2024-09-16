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
                                    <th>Tanggal Aktivasi</th>
                                    <th ><= 0 Days </th>
                                    <th >1 - 30 Days</th>
                                    <th >31 - 60 Days</th>
                                    <th >61 - 90 Days</th>
                                    <th >91 - 120 Days</th>
                                    <th >121 - 150 Days</th>
                                    <th >>150 Days</th>
                                    <th >Total Outstanding</th>
                                    <th >Deposit</th>
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
                                        <td>
                                            @if (isset($data->aging_0))
                                                {{ number_format($data->aging_0) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->aging_1))
                                                {{ number_format($data->aging_1) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->aging_2))
                                                {{ number_format($data->aging_2) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->aging_3))
                                                {{ number_format($data->aging_3) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->aging_4))
                                                {{ number_format($data->aging_4) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->aging_5))
                                                {{ number_format($data->aging_5) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->aging_6))
                                                {{ number_format($data->aging_6) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($data->tot_outstanding))
                                                {{ number_format($data->tot_outstanding) }}
                                            @endif
                                        </td>
                                        <td>{{ number_format($data->nilai_deposit) }}</td>
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
