@extends('layouts.main')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3  border-bottom">
    {{--  <h1 class="h2">Konfirmasi Pesanan Anda</h1>  --}}
</div>
@if (session()->has('success'))
    <div class="alert alert-success col-lg-6" role="alert">
        {{ session('success') }}
    </div>
@endif
<div class="table-responsive col-lg-7 mb-5">
    <div class="panel-heading"><h1>Pendaftaran Pelanggan</h1></div>
    <table class="table table-bordered table-sm">
        <tbody>
            <tr >
                <td class="col-lg-3">No Pemesanan</td><td colspan="3">{{ $nomer_formulir }}</td>
            </tr>
            <tr>
                <td>Tanggal Order</td><td colspan="3">{{ $head_order->tgl_order}} </td>
                     {{--  date('d-m-Y') }}</td>  --}}
            </tr>
            <tr >
                <td class="col-lg-3">Nama Pelanggan</td><td colspan="3">{{ Auth::user()->name }}</td>
            </tr>
            <tr>
                <td>No. Identitas</td><td colspan="3">{{ Auth::user()->no_identitas }}</td>
            </tr>
            <tr>
                <td>Tower/Unit</td><td>{{ Auth::user()->no_unit }}</td><td> Status: &nbsp;&nbsp;
                    @if (Auth::user()->is_pemilik==0)
                        Pemilik &nbsp;&nbsp;/&nbsp;&nbsp;<s>Penyewa</s>
                    @else
                        <s>Pemilik</s> &nbsp;&nbsp;/&nbsp;&nbsp;Penyewa
                    @endif
                    </td>
            </tr>
            <tr>
                <td>Alama Sesuai KTP</td><td colspan="3" >{{ Auth::user()->alamat }}</td>
            </tr>
            <tr>
                <td>No. Telp</td><td>{{ Auth::user()->no_telpon }}</td><td>&nbsp;&nbsp; No. Whatsapp: &nbsp;&nbsp;&nbsp;&nbsp; {{ Auth::user()->no_hp }}</td>
            </tr>
            <tr>
                <td>Email</td><td colspan="3">{{ Auth::user()->email }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="table-responsive col-lg-7">
    <table class="table table-bordered table-sm">
        <thead>
        <tr>
            <th scope="col"># </th>
            <th scope="col">Jenis Layanan </th>
            <th scope="col">Periode</th>
            <th scope="col">Harga</th>
            <th scope="col">Total</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($detail_order as $item)
                <tr>
                <td>{{ $loop->iteration }}</td>
                <td><p class="mb-2 md:ml-4">{{ $item->layanan->title }}</p></td>
                <td >
                    <form action="{{ route('cart.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item->id}}" >
                    <input type="number" name="quantity" value="{{ $item->periode_langganan }}" style='width:3em' disabled />Bln
                    </form>
                </td>
                <td  style="text-align:end">
                    <span class="text-sm font-medium lg:text-base">
                    </span>
                    Rp. {{ number_format($item->harga_layanan) }}
                </td>
                <td  style="text-align:end">
                    <span class="text-sm font-medium lg:text-base">
                    </span>
                    Rp. {{ number_format($item->harga_layanan*$item->periode_langganan) }}
                </td>
                </form>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" style="text-align:end"><strong>Subtotal Pemesanan</strong></td>
                <td  style="text-align:end"><strong>Rp. {{ number_format($subtotal) }}</strong></td>

            </tr>
            <tr>
                <td colspan="4" style="text-align:end"><strong>Ppn 11%</strong></td>
                <td  style="text-align:end"><strong>Rp. {{ number_format($head_order->ppn) }}</strong></td>

            </tr>
            <tr>
                <td colspan="4" style="text-align:end"><strong>Biaya Pemasangan</strong></td>
                <td  style="text-align:end"><strong>Rp. {{ number_format($head_order->biaya_pemasangan) }}</strong></td>

            </tr>
            <tr>
                <td colspan="4" style="text-align:end"><strong>Deposit</strong></td>
                <td  style="text-align:end"><strong>Rp. {{ number_format($head_order->deposit) }}</strong></td>

            </tr>
            <tr>
                <td colspan="4" style="text-align:end"><strong>Grand Total</strong></td>
                <td  style="text-align:end"><strong>Rp. {{ number_format($head_order->total_tagihan) }}</strong></td>

            </tr>
        </tbody>
    </table>
    <div class="panel panel-default">
        <div class="panel-heading">
            Metode Pembayaran
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                @if ($head_order->jenis_pembayaran=='midtrans')
                <li class="active"><a href="#home" data-toggle="tab" aria-expanded="true">Otomatis Midtrans</a>
                </li>
                @else
                <li class=""><a href="#profile" data-toggle="tab" aria-expanded="false">Manual Transfer</a>
                </li>
                @endif
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                @if ($head_order->jenis_pembayaran=='midtrans')
                    <div class="tab-pane fade active in" id="home">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <td>Grand Total </td><td style="text-align:end"><strong>Rp. {{ number_format($head_order->total_tagihan) }}</strong></td>
                            </tr>
                            {{--  <tr>
                                <td>Kode Unik </td><td  style="text-align:end"><strong>Rp. {{ number_format($head_order->kodeunik) }}</strong></td>
                            </tr>  --}}
                            <tr>
                                <td>Biaya Transfer </td><td  style="text-align:end"><strong>Rp. {{ number_format($biayatransfer) }}</strong></td>
                            </tr>
                            <tr>
                                <td>Total  Transfer </td><td style="text-align:end"><strong>Rp. {{ number_format($head_order->total_tagihan_transfer) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    {{--  <button type="button" class="btn btn-primary btn-lg btn-block">Bayar Menggunakan Midtrans</button>  --}}
                                    <form action="{{ route('cart.prosesPdf') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="jenis_pembayaran" value="midtrans" >
                                        <input type="hidden" name="nomerpesan" value="{{ $nomer_formulir }}" >
                                        <input type="hidden" name="kodeunik" value="{{ $kodeunik}}" >
                                        <input type="hidden" name="total_tagihan_transfer" value="{{ $ttlTransfer}}" >
                                        {{--  <button type="submit" name="bayar" class="btn btn-primary">Bayar Sekarang</button>  --}}
                                        @if ($head_order->payment_status == 1)
                                            <button class="btn btn-primary" id="pay-button">Bayar Sekarang</button>
                                        @else
                                            <strong>Pembayaran berhasil </strong>&nbsp;&nbsp;
                                        @endif
                                        {{--  <button type="submit" name="download" class="btn btn-primary">Download </button>  --}}

                                    </form>

                                </td>
                            </tr>
                        </table>
                    </div>
                @else
                    <div class="tab-pane fade" id="profile">
                        <div><p>Lakukan Pembayaran ke:</br>
                            Rekening BCA PT. Media Prima Jaringan</br>
                            No. Rekening : 4899 77 2005</p>
                        </div>
                        <p>Metode Manual Transfer memerlukan bukti transaksi dan akan diproses selama 2 x 24 jam, setelah Anda mengirimkan Bukti pembayaran</p>
                        <table class="table table-bordered table-sm">
                            <tr>
                                <td>Grand Total </td><td style="text-align:end"><strong>Rp. {{ number_format($gdTotal) }}</strong></td>
                            </tr>
                            <tr>
                                <td>Kode Unik </td><td  style="text-align:end"><strong>Rp. {{ number_format($kodeunik) }}</strong></td>
                            </tr>
                            <tr>
                                <td>Total  Transfer </td><td style="text-align:end"><strong>Rp. {{ number_format($ttlmantransfer) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <form action="{{ route('cart.confirm-bayar') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="jenis_pembayaran" value="manual" >
                                        <input type="hidden" name="nomerpesan" value="{{ substr($noPesan,0,4) }}" >
                                        <input type="hidden" name="kodeunik" value="{{ $kodeunik}}" >
                                        <input type="hidden" name="total_tagihan_transfer" value="{{ $ttlmantransfer}}" >

                                        <button type="submit" class="btn btn-primary btn-lg btn-block">Bayar Menggunakan Manual Transfer</button>
                                    </form>
                                    </td>
                            </tr>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        <!-- /.panel-body -->
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
</script>
<script>
    const payButton = document.querySelector('#pay-button');
    payButton.addEventListener('click', function(e) {
        e.preventDefault();

        snap.pay('{{ $snapToken }}', {
            // Optional
            onSuccess: function(result) {
                /* You may add your own js here, this is just example */
                // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                console.log(result)
            },
            // Optional
            onPending: function(result) {
                /* You may add your own js here, this is just example */
                // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                console.log(result)
            },
            // Optional
            onError: function(result) {
                /* You may add your own js here, this is just example */
                // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                console.log(result)
            }
        });
    });
</script>
@endsection
