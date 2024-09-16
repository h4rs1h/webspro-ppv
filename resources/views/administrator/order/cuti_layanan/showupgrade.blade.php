@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="col-lg-12">
        <h1 class="page-header">{{ $title }}</h1>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        @if (session()->has('success'))
                            <div class="alert alert-success col-lg-12" role="alert">
                            {{ session('success') }}
                            </div>
                        @endif
                        @if (session()->has('warning'))
                            <div class="alert alert-danger col-lg-12" role="alert">
                            {{ session('warning') }}
                            </div>
                        @endif

                    </div>
                </div>
                <div class="penel panel-default">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Data Order
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row mb-3">
                                                <label  class="col-md-1 col-form-label text-md-end">{{ __('Nomor :') }}</label>
                                                <label  class="col-md-4 col-form-label text-md-end">{{ $order->no_formulir }}</label>
                                                <div class="row mb-3">
                                                    <label  class="col-md-1 col-form-label text-md-end">{{ __('Tanggal :') }}</label>
                                                    <label  class="col-md-4 col-form-label text-md-end">{{ date('d-m-Y', strtotime( $order->tgl_order)) }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-heading">
                                    Data Pelanggan
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div >
                                                <table >
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Nomor Pelanggan') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="2"><label class="text-md-start">{{ $order->pelanggan->no_pelanggan }}</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Nama Pelanggan') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="2"><label class="text-md-start">{{ $order->pelanggan->nama_lengkap }}</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('No. Indentitas (KTP/SIM/PASSPORT)') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="2"><label class="text-md-start">{{ $order->pelanggan->nomer_identitas }}</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Tower / Unit') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-3"><label class="text-md-start">{{ $order->pelanggan->sub_tower.'/'.$order->pelanggan->lantai.'/'.$order->pelanggan->nomer_unit }}</label></td>
                                                        <td><label>Status Pemilik / Penyewa </label></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Alamat Sesuai KTP') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="2"><label class="text-md-start">{{ $order->pelanggan->alamat_identitas }}</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('No. Telpon') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-3" ><label class="text-md-start">{{ $order->pelanggan->nomer_hp }}</label></td>
                                                        <td><label>No. HP/WhatsApp : {{ $order->pelanggan->nomer_hp }}</label></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Email') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="2"><label class="text-md-start">{{ $order->pelanggan->email }}</label></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-heading">
                                    Order Layanan
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Jenis Layanan</th>
                                                            <th>Harga Layanan (Rp)</th>
                                                            <th>Periode</th>
                                                            <th>Promo/Diskon</th>
                                                            <th>Total Harga</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($order_dtl as $data )
                                                        @if ($data->layanan_id <>'10' & $data->layanan_id <>'11')
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $data->title }}</td>
                                                            <td class="text-right">{{ number_format($data->amount) }}</td>
                                                            <td class="text-center">{{ $data->qty }}</td>
                                                            <td class="text-right">{{ number_format($data->diskon) }}</td>
                                                            <td class="text-right">{{ number_format($data->sub_amount-$data->tax_amount) }}</td>

                                                        </tr>
                                                        @elseif($data->layanan_id =='10' )
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td colspan="4">{{ $data->title }}</td>
                                                            {{--  <td class="text-right">{{ number_format($data->amount) }}</td>
                                                            <td>{{ $data->qty }}</td>
                                                            <td class="text-right">{{ number_format($data->diskon) }}</td>  --}}
                                                            <td class="text-right">{{ number_format($data->sub_amount-$data->tax_amount) }}</td>

                                                        </tr>
                                                        @elseif($data->layanan_id =='11' )
                                                            <?php
                                                                $deposit = $data->sub_amount;
                                                                if($data->trx_order_id>400){
                                                                    $ppndeposit=0;
                                                                }else{

                                                                    $ppndeposit = $data->sub_amount*0.11;
                                                                }
                                                            ?>
                                                        @endif

                                                        @endforeach
                                                        <tr>
                                                            <td colspan="5" class="text-right">PPN 11%</td>
                                                            <td class="text-right"> {{ number_format($order_ppn->ppn) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" class="text-right">Grand Total</td>
                                                            <td class="text-right"> {{ number_format($order->gtot_amount) }}</td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row (nested) -->
                                </div>
                                <div class="panel-heading">
                                    Periode Berlangganan
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label>Mulai : {{ Carbon\Carbon::parse($order->tgl_rencana_belangganan)->format('d-M-Y') }}</label><br>
                                            <label>Pilihan :
                                                @if ($order->langganan_status=='1')
                                                <input type="checkbox" id="bulanan" name="bulanan" value="bulanan" checked>
                                                <label for="bulanan"> Bulanan</label>
                                                <input type="checkbox" id="tahunan" name="tahunan" value="tahunan" >
                                                <label for="tahunan"> Tahunan</label>
                                                @else
                                                <input type="checkbox" id="bulanan" name="bulanan" value="bulanan" >
                                                <label for="bulanan"> Bulanan</label>
                                                <input type="checkbox" id="tahunan" name="tahunan" value="tahunan" checked>
                                                <label for="tahunan"> Tahunan</label>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body text-center">
                                    <a class="btn btn-primary" href="/admin/trx_order/cuti/getAksi?aksi=print&id={{ $order->id }}">  Lembar PPn</a>
                                 {{--   <a class="btn btn-primary" href="/admin/trx_order/cuti/getAksi?aksei=print-non-ppn&id={{ $order->id }}">  Lembar Non PPn</a>
                                    @if($tunggakan>0)
                                        <a class="btn btn-primary" href="/admin/trx_order/inv_tagihan_daftar/{{ $order->id }}">  Lembar Format Invoice</a>
                                    @endif
                                </div>--}}
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
