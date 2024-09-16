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
                                <th>No Pelanggan</th>
                                <th>Status</th>
                                <th>No Formulir</th>
                                <th>Nomer Invoice</th>
                                <th>Unit ID</th>
                                <th>Tanggal Invoice</th>
                                <th>Periode Pemakaian</th>
                                <th>Tanggal Jatuh Tempo</th>
                                <th>Deskripsi Item</th>
                                <th>Amount</th>
                                <th>Tax Amount</th>
                                <th>Total Amount</th>
                                <th>No Kwitansi</th>
                                <th>Tanggal Payment</th>
                                <th>Amount Payment</th>
                                <th>Status Payment</th>
                                <th>Tanggal Upload Bukti Payment </th>
                            </thead>
                            <tbody>
                                @foreach ( $pelanggan as $data )
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->nama_lengkap }}</td>
                                        <td>{{ $data->no_pelanggan }}</td>
                                        <td>
                                            @if ($data->status_layanan=="1")
                                                Aktif
                                            @elseif ($data->status_layanan=="2")
                                                Suspend
                                            @elseif ($data->status_layanan=="3")
                                                Non Aktif
                                            @elseif ($data->status_layanan=="4")
                                                Berhenti Langganan
                                            @elseif ($data->status_layanan=="5")
                                                Batal Aktivasi
                                            @elseif ($data->status_layanan=="6")
                                                Belum Aktivasi
                                            @else
                                                None
                                            @endif
                                        </td>
                                        <td>{{ $data->no_formulir }}</td>
                                        <td>{{ 'INV-'.$data->no_invoice }}</td>
                                        <td>{{ $data->unitid }}</td>
                                        <td>{{ $data->tgl_invoice }}</td>
                                        <td>{{ $data->periode_pemakaian }}</td>
                                        <td>{{ $data->tgl_jatuh_tempo }}</td>
                                        <td>{{ $data->keterangan_item }}</td>
                                        <td>{{ $data->sub_total }}</td>
                                        <td>{{ $data->tax_amount }}</td>
                                        <td>{{ $data->gtot_tagihan }}</td>
                                        <td> @if($data->nopayment)
                                                {{ 'KWT-'.$data->nopayment }}
                                             @endif
                                        </td>
                                        <td>{{ $data->tglpayment }}</td>
                                        <td>{{ $data->amountpayment }}</td>
                                        <td>{{ $data->statuspembayaaran_ }}</td>
                                        <td>{{ $data->tglbuktibayar }}</td>

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
