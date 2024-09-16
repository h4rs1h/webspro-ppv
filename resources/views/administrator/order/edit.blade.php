@extends('layouts.main')

@section('content')
<div class="container-fluid">
    @if (session()->has('success'))
        <div class="alert alert-success col-lg-12" role="alert">
        {{ session('success') }}
        </div>
    @endif
	@if (session()->has('warning'))
        <div class="alert alert-success col-lg-12" role="alert">
        {{ session('warning') }}
        </div>
    @endif


    <div class="col-lg-12">
        <h1 class="page-header">{{ $title }}</h1>
        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="/admin/trx_order/{{ $order->id }}" class="mb-5" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <input type="hidden" name="order_id" id="order_id" value="{{ $order->id }}">
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
                                            <div class="col-md-4">
                                                <label  class="col-md-1 col-form-label text-md-start">{{ __('Nomor ') }}</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input class="form-control " value="{{ old('no_formulir',$order->no_formulir) }}" name="no_formulir" id="no_formulir">

                                            </div>
                                            <div class="col-md-4">

                                                <label  class="col-md-1 col-form-label text-md-end">{{ __('Tanggal ') }}</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input class="form-control " type="date" value="{{ old('tgl_order',$order->tgl_order) }}" name="tgl_formulir" id="tgl_formulir">
                                                <input type="hidden" name="tgl_order" value="{{ old('tgl_order',$order->tgl_order) }}">
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
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Nomor Unit Pelanggan') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="3">
															@if($status_bayar=="kosong")
                                                            <input type="text" class="form-control @error('no_pelanggan') is-invalid  @enderror" id="no_pelanggan" name="no_pelanggan" required autofocus value="{{ old('no_pelanggan',$no_unit) }}">
															@else
																<input type="text" class="form-control @error('no_pelanggan') is-invalid  @enderror" id="no_pelanggan" name="no_pelanggan" required readonly autofocus value="{{ old('no_pelanggan',$no_unit) }}">
															@endif
                                                            @error('no_pelanggan')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Nama Pelanggan') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="3">
                                                            <input type="hidden" name="pelanggan_id" id="pelanggan_id" value="{{ old('pelanggan_id',$order->pelanggan_id) }}">
                                                            <input type="text" class="form-control @error('nama_pelanggan') is-invalid  @enderror" id="nama_pelanggan" name="nama_pelanggan" required autofocus readonly value="{{ old('nama_pelanggan',$order->pelanggan->nama_lengkap) }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('No. Indentitas (KTP/SIM/PASSPORT)') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="3">
                                                            <input type="text" class="form-control @error('nomer_identitas') is-invalid  @enderror" id="nomer_identitas" name="nomer_identitas" required autofocus readonly value="{{ old('nomer_identitas',$order->pelanggan->nomer_identitas) }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Tower / Unit') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-3">
                                                            <input type="text" class="form-control @error('no_unit') is-invalid  @enderror" id="no_unit" name="no_unit" required autofocus readonly value="{{ old('no_unit',$no_unit) }}">
                                                        </td>
                                                        <td><label>Status Pemilik / Penyewa </label></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Alamat Sesuai KTP') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="3">
                                                            <input type="text" class="form-control @error('alamat') is-invalid  @enderror" id="alamat" name="alamat" required autofocus readonly value="{{ old('alamat',$order->pelanggan->alamat) }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('No. Telpon') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-3" >
                                                            <input type="text" class="form-control @error('no_hp') is-invalid  @enderror" id="no_hp" name="no_hp" required autofocus readonly value="{{ old('no_hp',$order->pelanggan->nomer_hp) }}">
                                                        </td>
                                                        <td class="col-md-2">
                                                            <label>No. HP/WhatsApp : </label>
                                                        </td>
                                                        <td class="col-md-3"><input type="text" class="form-control @error('no_hp2') is-invalid  @enderror" id="no_hp2" name="no_hp2" required autofocus readonly value="{{ old('no_hp2',$order->pelanggan->nomer_hp) }}"></td>

                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Email') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="3">
                                                            <input type="text" class="form-control @error('email') is-invalid  @enderror" id="email" name="email" required autofocus readonly value="{{ old('email',$order->pelanggan->email) }}">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-heading col-md-6">
                                    Periode Berlangganan
                                </div>
                                <div class="panel-heading col-md-6">
                                    Estimasi Instalasi
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-md-6">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-3">
                                                            <label> Mulai </label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            {{--  <input class="form-control" type="date">  --}}
                                                            <input type="date" class="form-control text-left @error('tgl_rencana_berlangganan') is-invalid  @enderror" id="tgl_rencana_berlangganan" name="tgl_rencana_berlangganan" autofocus value="{{ old('tgl_rencana_berlangganan',$order->tgl_rencana_belangganan) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-3">
                                                            <label>Periode</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <select class="form-control" name="langganan_status">
                                                                <option value="" >-- Pilih --</option>
                                                                @foreach ($period as $st)
                                                                @if (old('langganan_status',$order->langganan_status) == $st['id'])
                                                                    <option value="{{ $st['id'] }}" selected>{{ $st['name'] }}</option>
                                                                @else
                                                                <option value="{{ $st['id'] }}" >{{ $st['name'] }}</option>
                                                                @endif
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-2">
                                                            <label> Tanggal </label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            {{--  <input class="form-control" type="date">  --}}
                                                            <input type="date" class="form-control text-left @error('tgl_rencana_berlangganan') is-invalid  @enderror" id="tgl_target_instalasi" name="tgl_target_instalasi" autofocus value="{{ old('tgl_target_instalasi',$order->tgl_target_instalasi) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-2">
                                                            <label>Catatan</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control text-left @error('catatan_instalasi') is-invalid  @enderror" id="catatan_instalasi" name="catatan_instalasi" autofocus  value="{{ old('catatan_instalasi',$order->catatan_instalasi) }}">

                                                        </div>
                                                    </div>
                                                </div>
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
                                                        <tr>
                                                            <td>1
                                                                <input type="hidden" name="line_no_1" value="1">
                                                            </td>
                                                            <td>
                                                                {{--  <select class="form-control" name="ly_internet" id="ly_internet">
                                                                    <option value="" >-- Pilih --</option>
                                                                    @foreach ($internet as $d)

                                                                    @if (old('ly_internet',$order->order_detail->first()->layanan_id) == $d->id)
                                                                        <option value="{{ $d->id }}" selected>{{ $d->title }}</option>
                                                                    @else
                                                                    <option value="{{ $d->id }}" >{{ $d->title }}</option>
                                                                    @endif
                                                                    @endforeach
                                                                </select>  --}}
																
                                                                <select class="form-control" name="ly_internet" id="ly_internet">
                                                                    <option value="" >-- Pilih --</option>
                                                                    @if ($order_cekdtl->Internet == '0')
                                                                        @foreach ( $internet as $d )
                                                                            @if (old('ly_internet') == $d->id)
                                                                                <option value="{{ $d->id }}" selected>{{ $d->title}}</option>
                                                                            @else
                                                                                <option value="{{ $d->id}}" >{{ $d->title }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                        <?php
                                                                        $hrg_int = '';
                                                                        $qty_int = '';
                                                                        $promo_int = '';
                                                                        $subt_int = '';
                                                                        ?>
                                                                    @else
                                                                        @foreach ($internet as $d)
                                                                            @foreach ($order_dtl1 as $d2 )
																				@if ($d2->line_no=='1')
                                                                                    @if (old('ly_internet',$d2->layanan_id) == $d->id)
                                                                                        <option value="{{ $d->id }}" selected>{{ $d->title }}-1</option>
                                                                                        <?php
                                                                                            $cek =$d2->layanan_id;
                                                                                            $hrg_int = $d2->amount;
                                                                                            $qty_int = $d2->qty;
                                                                                            $promo_int = $d2->diskon;
                                                                                            $subt_int = $d2->sub_amount-$d2->tax_amount;
                                                                                        ?>
                                                                                    @else
                                                                                        <?php
                                                                                            $cek = 0;
                                                                                        ?>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                            @if ($cek != $d->id )
                                                                                <option value="{{ $d->id }}" >{{ $d->title }}-2</option>
                                                                            @endif
                                                                        @endforeach
																	
                                                                    @endif
                                                                </select>
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" class="form-control text-right @error('hrg_int') is-invalid  @enderror" id="hrg_int" name="hrg_int" autofocus readonly value="{{ old('hrg_int',$hrg_int) }}">
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" class="form-control text-center @error('qty_int') is-invalid  @enderror" id="qty_int" name="qty_int" required autofocus value="{{ old('qty_int',$qty_int) }}" >
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="number" class="form-control text-right @error('promo_int') is-invalid  @enderror" id="promo_int" name="promo_int"  autofocus  value="{{ old('promo_int',$promo_int) }}">
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" class="form-control text-right  @error('subt_int') is-invalid  @enderror" id="subt_int" name="subt_int"  autofocus readonly value="{{ old('subt_int',$subt_int) }}"  >
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td>2
                                                                <input type="hidden" name="line_no_2" value="2">
                                                            </td>
                                                            <td>
                                                                <select class="form-control" name="ly_tv" id="ly_tv">
                                                                    <option value="" >-- Pilih --</option>
                                                                    @if ($order_cekdtl->Tv == '0')
                                                                        @foreach ( $tv as $d )
                                                                            @if (old('ly_tv') == $d->id)
                                                                                <option value="{{ $d->id }}" selected>{{ $d->title}}</option>
                                                                            @else
                                                                                <option value="{{ $d->id}}" >{{ $d->title }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                        <?php
                                                                        $hrg_tv = '';
                                                                        $qty_tv = '';
                                                                        $promo_tv = '';
                                                                        $subt_tv = '';
                                                                        ?>
                                                                    @else
                                                                        @foreach ($tv as $d)
                                                                            @foreach ($order_dtl1 as $d2 )
                                                                                @if ($d2->line_no=='2')
                                                                                    @if (old('ly_tv',$d2->layanan_id) == $d->id)
                                                                                        <option value="{{ $d->id }}" selected>{{ $d->title }}</option>
                                                                                        <?php
                                                                                            $cek =$d2->layanan_id;
                                                                                            $hrg_tv = $d2->amount;
                                                                                            $qty_tv = $d2->qty;
                                                                                            $promo_tv = $d2->diskon;
                                                                                            $subt_tv = $d2->sub_amount-$d2->tax_amount;
                                                                                        ?>
                                                                                    @else
                                                                                        <?php
                                                                                            $cek=0;
                                                                                            ?>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                            @if ($cek != $d->id )
                                                                                <option value="{{ $d->id }}" >{{ $d->title }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" class="form-control text-right @error('hrg_tv') is-invalid  @enderror" id="hrg_tv" name="hrg_tv"  autofocus readonly value="{{ old('hrg_tv',$hrg_tv) }}">
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" class="form-control text-center @error('qty_tv') is-invalid  @enderror" id="qty_tv" name="qty_tv"  autofocus value="{{ old('qty_tv',$qty_tv) }}">
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="number" class="form-control text-right @error('promo_tv') is-invalid  @enderror" id="promo_tv" name="promo_tv"  autofocus  value="{{ old('promo_tv',$promo_tv) }}">
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" class="form-control text-right  @error('subt_tv') is-invalid  @enderror" id="subt_tv" name="subt_tv"  autofocus readonly value="{{ old('subt_tv',$subt_tv) }}">
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td>3
                                                                <input type="hidden" name="line_no_3" value="3">
                                                            </td>
                                                            <td>
                                                                <select class="form-control" name="ly_telepony" id="ly_telepony">
                                                                    <option value="" >-- Pilih --</option>
                                                                    @if ($order_cekdtl->telephony == '0')
                                                                        @foreach ( $telepony as $d )
                                                                            @if (old('ly_telepony') == $d->id)
                                                                                <option value="{{ $d->id }}" selected>{{ $d->title}}</option>
                                                                            @else
                                                                                <option value="{{ $d->id}}" >{{ $d->title }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                        <?php
                                                                        $hrg_telepony = '';
                                                                        $qty_telepony = '';
                                                                        $promo_telepony = '';
                                                                        $subt_telepony = '';
                                                                        ?>
                                                                    @else
                                                                        @foreach ($telepony as $d)
                                                                            @foreach ($order_dtl1 as $d2 )
                                                                                @if ($d2->line_no=='3')
                                                                                    @if (old('ly_telepony',$d2->layanan_id) == $d->id)
                                                                                        <option value="{{ $d->id }}" selected>{{ $d->title }}</option>
                                                                                        <?php
                                                                                            $cek =$d2->layanan_id;
                                                                                            $hrg_telepony = $d2->amount;
                                                                                            $qty_telepony = $d2->qty;
                                                                                            $promo_telepony = $d2->diskon;
                                                                                            $subt_telepony = $d2->sub_amount;
                                                                                        ?>
                                                                                    @else
                                                                                        <?php
                                                                                            $cek = 0;
                                                                                        ?>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                            @if ($cek != $d->id )
                                                                                <option value="{{ $d->id }}" >{{ $d->title }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" class="form-control text-right @error('hrg_telepony') is-invalid  @enderror" id="hrg_telepony" name="hrg_telepony"  autofocus readonly value="{{ old('hrg_telepony',$hrg_telepony) }}">
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" class="form-control text-center @error('qty_telepony') is-invalid  @enderror" id="qty_telepony" name="qty_telepony"  autofocus value="{{ old('qty_telepony',$qty_telepony) }}">
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="number" class="form-control text-right @error('promo_telepony') is-invalid  @enderror" id="promo_telepony" name="promo_telepony"  autofocus  value="{{ old('promo_telepony',$promo_telepony) }}">
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" class="form-control text-right  @error('subt_telepony') is-invalid  @enderror" id="subt_telepony" name="subt_telepony"  autofocus readonly value="{{ old('subt_telepony',$subt_telepony) }}">
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td>4
                                                                <input type="hidden" name="line_no_4" value="4">
                                                            </td>
                                                            <td colspan="4">
                                                                <select class="form-control" name="biaya_pasang" id="biaya_pasang">
                                                                    <option value="" >-- Pilih --</option>
                                                                    @if ($order_cekdtl->biaya_pasang == '0')
                                                                        @foreach ( $biaya_pasang as $d )
                                                                            @if (old('biaya_pasang') == $d->id)
                                                                                <option value="{{ $d->id }}" selected>{{ $d->title}}</option>
                                                                            @else
                                                                                <option value="{{ $d->id}}" >{{ $d->title }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                        <?php
                                                                        $subt_biaya_pasang = '';
                                                                        ?>
                                                                    @else
                                                                        @foreach ($biaya_pasang as $d)
                                                                            @foreach ($order_dtl1 as $d2 )
                                                                                @if ($d2->line_no=='4')
                                                                                    @if (old('biaya_pasang',$d2->layanan_id) == $d->id)
                                                                                        <option value="{{ $d->id }}" selected>{{ $d->title }}</option>
                                                                                        <?php
                                                                                            $cek =$d2->layanan_id;
                                                                                            $subt_biaya_pasang = $d2->sub_amount-$d2->tax_amount;
                                                                                        ?>
                                                                                    @else
                                                                                        <?php
                                                                                            $cek = 0;
                                                                                        ?>
                                                                                    @endif
                                                                                @endif

                                                                            @endforeach
                                                                            @if ($cek != $d->id )
                                                                                <option value="{{ $d->id }}" >{{ $d->title }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </td>

                                                            <td class="text-right">
                                                                <input type="text" class="form-control text-right  @error('subt_biaya_pasang') is-invalid  @enderror" id="subt_biaya_pasang" name="subt_biaya_pasang"  autofocus readonly value="{{ old('subt_biaya_pasang',$subt_biaya_pasang) }}">
                                                            </td>
                                                        </tr>
                                                            @foreach ( $order_dtl1 as $d2 )
                                                                @if ($d2->line_no =='5')
                                                                    <?php
                                                                        $deposit_amount = $d2->sub_amount;
                                                                        $deposit_ppn = ($d2->sub_amount * 0.11);
                                                                        $deposit_amount_plus_ppn = $d2->sub_amount;
                                                                    ?>
                                                                @endif
                                                            @endforeach
                                                        <tr>
                                                            <td colspan="5" class="text-right">PPN 11%
                                                                <input type="hidden" name="line_no_5" value="5">
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" class="form-control text-right @error('ppn') is-invalid  @enderror" id="ppn" name="ppn"  autofocus readonly value="{{ old('ppn',$order->ppn_amount) }}">
                                                                {{--  Subtotal  --}}
                                                                <input type="hidden" class="form-control text-right" name="amount" id="amount" value={{ old('amount',$order->amount) }}>
                                                                {{--  Nilai ppn  --}}
                                                                <input type="hidden" class="form-control text-right" name="ppn_amount" id="ppn_amount" value={{ old('ppn_amount',$order->ppn_amount) }}>

                                                                {{--  Nilai Deposit  --}}
                                                                <input type="hidden" class="form-control text-right" name="deposit_amount" id="deposit_amount" value={{ old('deposit_amount',$deposit_amount) }}>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" class="text-right">Deposit </td>
                                                            <td class="text-right">

                                                                <input type="text" class="form-control text-right @error('deposit') is-invalid  @enderror" id="deposit" name="deposit"  autofocus readonly value="{{ old('deposit',$deposit_amount_plus_ppn)}}">
                                                            </td>
                                                        </tr><tr>
                                                            <td colspan="5" class="text-right">Grand Total</td>
                                                            <td class="text-right">
                                                                <input type="text" class="form-control text-right @error('gt_amount') is-invalid  @enderror" id="gt_amount" name="gt_amount"  autofocus readonly value="{{ old('gt_amount',$order->gtot_amount) }}">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="col-lg-5">
													@if($status_bayar=="kosong")
                                                <button type="submit" class="btn btn-primary" name="simpan" id="simpan">Update Order</button>
													@elseif($status_bayar->status_bayar=='2')
													<button type="submit" class="btn btn-primary" name="simpan" id="simpan">Update Order</button>
													@endif
                                                </div><div class="col-lg-5">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row (nested) -->
                                </div>
                                <div class="panel-body text-center">
                                    {{--  <a href="/admin/trx_order/download/{{ $order->no_order }}"> Download</a>  --}}
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                    </div>

                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/trxorder.js') }}"></script>
@endsection
