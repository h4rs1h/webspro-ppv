@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="panel-heading"></h3>
        </div>
    </div>

@if (session()->has('success'))
    <div class="alert alert-success col-lg-6" role="alert">
        {{ session('success') }}
    </div>
@endif

<div class="table-responsive col-lg-7 mb-5 ">
    <div class="panel panel-default mt-3">
    <div class="panel-heading mt-3">
        <h3 class="h1 ">Order Layanan Pelanggan</h3>

    </div>
    <div class="panel-body">
        <table class="table table-bordered table-sm">
            <tr>
                <th># </th>
                <th>Nomer Order</th>
                <th>Tanggal Order</th>
                <th>Total Tagihan</th>
                <th>Status Pembayaran</th>
                {{--  <th>Aksi</th>  --}}
            </tr>
            <tr>
                <td>1</td>
                <td>{{ $head_order->nomor_order }}</td>
                <td>{{ $head_order->tgl_order}}</td>
                <td><strong>Rp. {{ number_format($head_order->total_tagihan) }}</strong></td>
                @if ($head_order->payment_status=='1')
                    <td>Menunggu Pembayaran &nbsp;
                        <form action="{{ route('cart.confirm-bayar') }}" method="POST">
                            @csrf
                            <input type="hidden" name="jenis_pembayaran" value="midtrans" >
                            <input type="hidden" name="nomerpesan" value="{{ $head_order->nomor_order }}" >
                            <input type="hidden" name="kodeunik" value="{{ $head_order->kodeunik}}" >
                            <input type="hidden" name="total_tagihan_transfer" value="{{ $head_order->total_tagihan_transfer}}" >

                            <button type="submit" class="btn btn-primary btn-block">Bayar Sekarang</button>
                        </form>
                    </td>
                @elseif ($head_order->payment_status=='2')
                    <td>Pembayaran Berhasil</td>
                @elseif ($head_order->payment_status=='3')
                    <td>Kadaluarasa</td>
                @elseif($head_order->payment_status=='4')
                    <td>Batal</td>
                @endif
                {{--  <td></td>  --}}
            </tr>
        </table>
    </div>
    </div>
</div>
</div>

@endsection
