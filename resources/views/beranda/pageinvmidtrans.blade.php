@extends('layouts.app')

@section('content')
{{--  carousel  --}}
<div class="container">
    @if (session()->has('success'))
        <div class="alert alert-success col-lg-12" role="alert">
        {{ session('success') }}
        </div>
    @endif
    @if (session()->has('danger'))
        <div class="alert alert-danger col-lg-12" role="alert">
        {{ session('danger') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-sm-5">
                    <div class="card">
                        <div class="card-header">
                            <strong>Konfirmasi Pembayaran melalui Payment Gateway</strong>
                        </div>
                        <div class="card-body">
                            <form method="post" action="/viewinvoice/bayar-midtrans" class="mb-5" enctype="multipart/form-data">
                                @csrf
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-12">

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>No Invoice</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" disabled name="no_tagihan" id="no_tagihan" value="{{ $tagihan->no_bayar }}">

                                                        <input type="hidden" name="pelanggan_id" id="pelanggan_id" value="{{ $tagihan->pelanggan_id }}">
                                                        <input type="hidden" name="no_order" id="no_order" value="{{ $tagihan->no_order }}">
                                                        <input type="hidden" name="trx_tagihan_id" id="trx_tagihan_id" value="{{ $inv->id }}">
                                                        <input type="hidden" name="nomer_tagihan" id="nomer_tagihan" value="{{ $inv->no_tagihan }}">
                                                        <input type="hidden" name="in_gtot_tagihan" id="in_gtot_tagihan" value="{{ $tagihan->amount }}">
                                                        <input type="hidden" name="in_biaya" id="in_biaya" value="{{ 6000 }}">
                                                        <input type="hidden" name="in_gtot_bayar" id="in_gtot_bayar" value="{{ $tagihan->amount+6000 }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Tanggal Invoice</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" disabled name="tgl_tagihan" id="tgl_tagihan" value="{{ Carbon\Carbon::parse($tagihan->tgl_bayar)->format('d-m-Y') }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Nominal Invoice</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control  text-end" disabled name="gtot_tagihan" id="gtot_tagihan" value="{{ number_format($tagihan->amount) }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Biaya Transaksi</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control  text-end" disabled name="biaya" id="biaya" value="{{ number_format(6000) }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Total Bayar</label>
                                                    </div>
                                                    <div class="col-md-8" >
                                                        <input type="text" class="form-control text-end" disabled name="total_bayar" id="total_bayar" value="{{ number_format($tagihan->amount+6000) }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                    </div>
                                                    <div class="col-md-8" >

                                                        @if (is_null($inv->status_tagihan) == 1)
                                                        <button class="btn btn-primary" id="pay-button">Bayar Sekarang</button>
														
                                                    @elseif($inv->status_bayar_midtrans =="Success")
                                                        <strong>Pembayaran berhasil </strong>&nbsp;&nbsp;
												@else
                                                         <button  class="btn btn-primary" id="pay-button">Bayar Sekarang</button>
                                                    @endif

                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-header">
                            Invoice ke:
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><strong>{{$tagihan->nama_lengkap}}</strong></h5>
                                  <p>{{ $tagihan->alamat_identitas }}</p>
                                  <p class="card-text">Apartemen Puri Parkview</p>
                        </div>

                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-header">
                            Dibayarkan Kepada:
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><strong>PT. Media Prima Jaringan</strong></h5>
                                  <p class="card-text">APARTEMENT PURI PARKVIEW<br>
                                    TOWER AAA / LT.1 / 16<br>
                                    Jl. Pesanggrahan Raya No.88<br>
                                    Meruya Utara, Kembangan<br>
                                    Jakarta Barat 11620 - Indonesia<br>
                                    Telpon:	021	-	5890	2704<br>
                                    Customer	Service:08588	177	177 9</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
	{{--	<script src="https://app.sandbox.midtrans.com/snap/v1/transactions" data-client-key="{{ config('midtrans.client_key') }}"> 
</script>--}}
{{-- <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"> 
</script> --}}
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
