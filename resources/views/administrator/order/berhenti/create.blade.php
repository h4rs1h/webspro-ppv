@extends('layouts.main')

@section('content')
<div class="container-fluid">
    @if (session()->has('success'))
        <div class="alert alert-success col-lg-12" role="alert">
        {{ session('success') }}
        </div>
    @endif


    <div class="col-lg-12">
        <h1 class="page-header">{{ $title }}</h1>
        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="/admin/trx_order/stop_lgn/getTotal" class="mb-5" enctype="multipart/form-data">
                    @csrf
                <div class="penel panel-default">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Data Order Berhenti Berlangganan
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="col-md-4">
                                                <label  class="col-md-1 col-form-label text-md-start">{{ __('Nomor ') }}</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input class="form-control "  value="{{ $no_formulir }}" name="no_formulir" id="no_formulir">

                                            </div>
                                            <div class="col-md-4">

                                                <label  class="col-md-1 col-form-label text-md-end">{{ __('Tanggal ') }}</label>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="date" class="form-control "  value="{{ date('d-m-Y', strtotime(now())) }}" name="tgl_formulir" id="tgl_formulir" value="{{ old('tgl_formulir') }}">
                                                <input type="hidden" name="tgl_order" value="{{ date('Y-m-d', strtotime(now())) }}">
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
                                                            <input type="text" class="form-control @error('no_pelanggan') is-invalid  @enderror" id="no_pelanggan" name="no_pelanggan" required autofocus value="{{ old('no_pelanggan') }}">
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
                                                            <input type="hidden" name="pelanggan_id" id="pelanggan_id" value="{{ old('pelanggan_id') }}">
                                                            <input type="text" class="form-control @error('nama_pelanggan') is-invalid  @enderror" id="nama_pelanggan" name="nama_pelanggan" required autofocus readonly value="{{ old('nama_pelanggan') }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('No. Indentitas (KTP/SIM/PASSPORT)') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="3">
                                                            <input type="text" class="form-control @error('nomer_identitas') is-invalid  @enderror" id="nomer_identitas" name="nomer_identitas" required autofocus readonly value="{{ old('nomer_identitas') }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Tower / Unit') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-3">
                                                            <input type="text" class="form-control @error('no_unit') is-invalid  @enderror" id="no_unit" name="no_unit" required autofocus readonly value="{{ old('no_unit') }}">
                                                        </td>
                                                        <td><label>Status Pemilik / Penyewa </label></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Alamat Sesuai KTP') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="3">
                                                            <input type="text" class="form-control @error('alamat') is-invalid  @enderror" id="alamat" name="alamat" required autofocus readonly value="{{ old('alamat') }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('No. Telpon') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-3" >
                                                            <input type="text" class="form-control @error('no_hp') is-invalid  @enderror" id="no_hp" name="no_hp" required autofocus readonly value="{{ old('no_hp') }}">
                                                        </td>
                                                        <td class="col-md-2">
                                                            <label>No. HP/WhatsApp : </label>
                                                        </td>
                                                        <td class="col-md-3"><input type="text" class="form-control @error('no_hp2') is-invalid  @enderror" id="no_hp2" name="no_hp2" required autofocus readonly value="{{ old('no_hp2') }}"></td>

                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Email') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="3">
                                                            <input type="text" class="form-control @error('email') is-invalid  @enderror" id="email" name="email" required autofocus readonly value="{{ old('email') }}">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-heading col-md-6">
                                    Request Pelanggan
                                </div>
                                <div class="panel-heading col-md-6">
                                   Estimasi Berhenti Berlangganan
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-md-6">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-5">
                                                            <label> Catatan Permintaan  </label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            {{--  <input class="form-control" type="date">  --}}
                                                            <input type="text" class="form-control text-left @error('catatan_request_pelanggan') is-invalid  @enderror" id="catatan_request_pelanggan" name="catatan_request_pelanggan" autofocus value="{{ old('catatan_request_pelanggan') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-5">
                                                            <label> Metode Pembayaran  </label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <select class="form-control" name="metode_bayar">
                                                                <option value="" >-- Pilih --</option>
                                                                @foreach ($metode_bayar as $st)
                                                                @if (old('metode_bayar') == $st['id'])
                                                                    <option value="{{ $st['id'] }}" selected>{{ $st['name'] }}</option>
                                                                @else
                                                                <option value="{{ $st['id'] }}" >{{ $st['name'] }}</option>
                                                                @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">

                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                            <label> Tanggal Layanan Dimatikan </label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            {{--  <input class="form-control" type="date">  --}}
                                                            <input type="date" class="form-control text-left @error('tgl_rencana_upgrade') is-invalid  @enderror" id="tgl_rencana_upgrade" name="tgl_rencana_upgrade" autofocus value="{{ old('tgl_rencana_upgrade') }}">
                                                        </div>
                                                    </div>
                                                </div>
 <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                            <label> Tanggal Pengambilan Perangkat</label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            {{--  <input class="form-control" type="date">  --}}
                                                            <input type="date" class="form-control text-left @error('tgl_rencana_upgrade2') is-invalid  @enderror" id="tgl_rencana_upgrade2" name="tgl_rencana_upgrade2" autofocus value="{{ old('tgl_rencana_upgrade2') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-3">
                                                            <label>Catatan</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control text-left @error('catatan_instalasi') is-invalid  @enderror" id="catatan_instalasi" name="catatan_instalasi" autofocus  value="{{ old('catatan_instalasi') }}">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="panel-heading">
                                    Order Layanan Berhenti Langganan
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
                                                                <select class="form-control" name="ly_internet" id="ly_internet">
                                                                    <option value="" >-- Pilih --</option>
                                                                    @foreach ($internet as $d)
                                                                    @if (old('ly_internet') == $d->id)
                                                                        <option value="{{ $d->id }}" selected>{{ $d->title }}</option>
                                                                    @else
                                                                    <option value="{{ $d->id }}" >{{ $d->title }}</option>
                                                                    @endif
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" class="form-control text-right @error('hrg_int') is-invalid  @enderror" id="hrg_int" name="hrg_int" autofocus readonly value="{{ old('hrg_int') }}">
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" class="form-control text-center @error('qty_int') is-invalid  @enderror" id="qty_int" name="qty_int" required autofocus value="{{ old('qty_int') }}" >
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="number" class="form-control text-right @error('promo_int') is-invalid  @enderror" id="promo_int" name="promo_int"  autofocus  value="{{ old('promo_int') }}">
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" class="form-control text-right  @error('subt_int') is-invalid  @enderror" id="subt_int" name="subt_int"  autofocus readonly value="{{ old('subt_int') }}"  >
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>2
                                                                <input type="hidden" name="line_no_2" value="2">
                                                            </td>
                                                            <td>
                                                                <select class="form-control" name="ly_tv" id="ly_tv">
                                                                    <option value="" >-- Pilih --</option>
                                                                    @foreach ($tv as $d)
                                                                    @if (old('ly_tv') == $d->id)
                                                                        <option value="{{ $d->id }}" selected>{{ $d->title }}</option>
                                                                    @else
                                                                    <option value="{{ $d->id }}" >{{ $d->title }}</option>
                                                                    @endif
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" class="form-control text-right @error('hrg_tv') is-invalid  @enderror" id="hrg_tv" name="hrg_tv"  autofocus readonly value="{{ old('hrg_tv') }}">
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" class="form-control text-center @error('qty_tv') is-invalid  @enderror" id="qty_tv" name="qty_tv"  autofocus value="{{ old('qty_tv') }}">
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="number" class="form-control text-right @error('promo_tv') is-invalid  @enderror" id="promo_tv" name="promo_tv"  autofocus  value="{{ old('promo_tv') }}">
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" class="form-control text-right  @error('subt_tv') is-invalid  @enderror" id="subt_tv" name="subt_tv"  autofocus readonly value="{{ old('subt_tv') }}">
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td>3
                                                                <input type="hidden" name="line_no_3" value="3">
                                                            </td>
                                                            <td>
                                                                <select class="form-control" name="ly_telepony" id="ly_telepony" disabled>
                                                                    <option value="" >-- Pilih --</option>
                                                                    @foreach ($telepony as $d)
                                                                    @if (old('ly_telepony') == $d->id)
                                                                        <option value="{{ $d->id }}" selected>{{ $d->title }}</option>
                                                                    @else
                                                                    <option value="{{ $d->id }}" >{{ $d->title }}</option>
                                                                    @endif
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" class="form-control text-right @error('hrg_telepony') is-invalid  @enderror" id="hrg_telepony" name="hrg_telepony"  autofocus readonly value="{{ old('hrg_telepony') }}">
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" class="form-control text-center @error('qty_telepony') is-invalid  @enderror" id="qty_telepony" name="qty_telepony" disabled autofocus value="{{ old('qty_telepony') }}">
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="number" class="form-control text-right @error('promo_telepony') is-invalid  @enderror" id="promo_telepony" name="promo_telepony"  disabled autofocus  value="{{ old('promo_telepony') }}">
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="text" class="form-control text-right  @error('subt_telepony') is-invalid  @enderror" id="subt_telepony" name="subt_telepony" disabled autofocus readonly value="{{ old('subt_telepony') }}">
                                                            </td>

                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="col-lg-5">
                                                {{--  <button type="submit" class="btn btn-primary" name="hitung" id="hitung">Hitung Total</button>  --}}
                                                <button type="submit" class="btn btn-primary" name="simpan" id="simpan">Hitung Total Berhenti Langganan </button>
                                                </div><div class="col-lg-5">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row (nested) -->
                                </div>

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
<script src="{{ asset('js/trxorderstop.js') }}"></script>
@endsection
