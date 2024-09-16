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
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-header">
                            Invoice ke:
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><strong>{{$tagihan->nama_lengkap}}</strong></h5>
                                  <p>{{ $tagihan->alamat_identitas }}</p>
                                  <p class="card-text">Apartemen Puri Parkvie</p>
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
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-header">
                            Informasi Invoice:
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><strong>Invoice:&nbsp;&nbsp; #{{ $tagihan->no_bayar }}</strong></h5>
                            <p>
                                Tanggal Invoice: {{ Carbon\Carbon::parse($tagihan->tgl_bayar)->format('d-m-Y') }} <br>
                                Tanggal Jatuh Tempo: {{ Carbon\Carbon::parse($tagihan->tgl_jatuh_tempo)->format('d-m-Y') }}<br>
                            </p>
                            <p>
                            <h5 class="card-title btn btn-primary" >Status Invoice:&nbsp;&nbsp;{{ $status }}
                            </h5>
                            </p>
                        </div>

                    </div>
                </div>


            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Item yang ditagihkan
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <th colspan="2">Diskripsi</th>
                                    <th >Jumlah</th>
                                </tr>
                                @foreach ($tagihandtl as $data )
                                    <tr>
                                        <td colspan="2">
                                            {{ $data->title }}<br>
                                            ({{ $data->sub_title }}) - {{ $data->pemakaian }}
                                        </td>

                                        <td align="right" >
                                            {{ number_format($data->amount_tagihan) }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>&nbsp;</td>
                                    <td align="right">Sub Total : </td>
                                    <td align="right">
                                        {{ number_format($tagihan->sub_total) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td align="right">11% PPN : </td>
                                    <td align="right">
                                       {{ number_format($tagihan->ppn_tagihan) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td align="right">
                                        <strong>Total: </strong>
                                    </td>
                                    <td align="right" >
                                     <strong>{{ number_format($tagihan->amount) }}</strong>
                                    </td>
                                </tr>
                            </table>
                        </div>
                </div>
            </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Informasi Tagihan
                        </div>
                        <div class="card-body text-center">

                            <p><a href="/viewinvoice/download?no_inv={{ $tagihan->no_bayar }}&uid={{ $tagihan->pelanggan_id }}" >download</a></p>
                            Silahkan hubungi kami jika butuh bantuan, melalui <br>Whatsapp : 08588 177 177 9 atau datang langsung ke unit AAA/L1/16
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Bukti-->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">

        <form method="post" action="/viewinvoice/tambah" class="mb-5" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Informasi Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                                            <input type="text" class="form-control" disabled name="gtot_tagihan" id="gtot_tagihan" value="{{ number_format($tagihan->amount) }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Tanggal Transfer</label>
                                        </div>
                                        <div class="col-md-8">

                                            <input type="date" class="form-control @error('tgl_bayar') is-invalid  @enderror" id="tgl_bayar" name="tgl_bayar" required autofocus value="{{ old('tgl_bayar') }}">
                                                @error('tgl_bayar')
                                                    <div class="invalid-feedback"  role="alert">
                                                        <strong>
                                                            {{ $message }}
                                                        </strong>
                                                    </div>
                                                @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Jumlah Transfer</label>
                                        </div>
                                        <div class="col-md-8">

                                            <input type="number" class="form-control @error('nominal_bayar') is-invalid  @enderror" id="nominal_bayar" name="nominal_bayar" required autofocus value="{{ old('nominal_bayar') }}">
                                                @error('nominal_bayar')
                                                    <div class="invalid-feedback"  role="alert">
                                                        <strong>
                                                            {{ $message }}
                                                        </strong>
                                                    </div>
                                                @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Bukti Transfer</label><br>
                                        </div>
                                        <div class="col-md-8">
                                            <code>* 1MB maksimum. (JPG, JPEG, PNG saja)</code>
                                            <input type="file" class="form-control"  name="image" id="image">
                                        </div>
                                    </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{--  <button type="button" class="btn btn-primary">Submit</button>  --}}
                    <input type="submit" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Payment Gateway-->

<div class="modal fade" id="midtrans" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">

        <form method="post" action="/viewinvoice/bayar-midtrans" class="mb-5" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Midtrans Informasi Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                                    </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{--  <button type="button" class="btn btn-primary">Submit</button>  --}}
                    <input type="submit" class="btn btn-primary" value="Bayar Sekarang">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
