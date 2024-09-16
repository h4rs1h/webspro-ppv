@extends('layouts.main')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">{{ $title }}</h1>
    </div>
</div>
{{--  Penel form   --}}
<div class="col-lg-12">
    <div class="row">
        <div class="col-Lg-12">
            <div class="panel panel-default">
                <div class="panel-heading"> Invoice Saya</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nomor Invoice</th>
                                    <th>Tanggal Invoice</th>
                                    <th>Tanggal Jatuh Tempo</th>
                                    <th>Nilai Tagihan</th>
                                    <th>Status </th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bayar as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($data->ket=='inv')
                                            <a href="/admin/trx_invoice/inv?no_tagihan={{ $data->no_bayar }}" class="text-decoration-none">
                                                {{ $data->no_bayar }}
                                            </a>
                                        @else
                                            {{ $data->no_bayar }}
                                        @endif
                                    </td>
                                    <td>{{ date('d-m-Y', strtotime($data->tgl_bayar)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($data->tgl_jatuh_tempo)) }}</td>
                                    <td>{{ number_format($data->amount) }}</td>
                                    <td>@if ($data->status_bayar=='1')

                                        <button type="button" class="btn btn-success"><i class="fa fa-paypal"></i> Terbayar</button>
                                    @elseif ($data->status_bayar=='2')
                                        {{--  DP (Kurang Bayar)  --}}
                                        <a href="/pelanggan/billing/bayar?no_inv={{ $data->no_bayar }}&uid={{ $data->pelanggan_id }}" class="btn btn-warning">
                                            <i class="fa fa-paypal"></i>
                                        </a>
                                        Belum Terbayar
                                    {{--  @elseif ($data->status_bayar=='3')
                                        Kadaluarsa  --}}
                                        <button type="button" class="btn btn-success">Success</button>
                                    @else
                                    <a href="/pelanggan/billing/bayar?no_inv={{ $data->no_bayar }}&uid={{ $data->pelanggan_id }}" class="btn btn-warning">
                                        <i class="fa fa-paypal"></i>
                                        Belum Terbayar
                                    </a>
                                    @endif</td>

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
{{--  Enda Penel Form  --}}

@endsection
