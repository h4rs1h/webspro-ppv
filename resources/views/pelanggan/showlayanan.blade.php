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
                <div class="panel-heading"> Layanan Saya</div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nomor Order</th>
                                    <th>Tanggal</th>
                                    <th>Tanggal Mulai Layanan</th>
                                    <th>Tanggal Berakhir Layanan</th>
                                    <th>Nilai Tagihan</th>
                                    <th>Status Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a href="/admin/trx_order/{{ $data->id }}" class="text-decoration-none">
                                        {{ $data->no_formulir }}</a></td>
                                    <td>{{ date('d-m-Y', strtotime($data->tgl_order)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($data->tgl_rencana_belangganan)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($tglJt)) }}</td>
                                    <td>{{ number_format($data->gtot_amount) }}</td>
                                    <td>@if ($data->payment_status=='1')
                                        Menunggu Pembayaran
                                    @elseif ($data->payment_status=='2')
                                        Lunas
                                    @elseif ($data->payment_status=='3')
                                        Kadaluarsa
                                    @else
                                        Batal
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
