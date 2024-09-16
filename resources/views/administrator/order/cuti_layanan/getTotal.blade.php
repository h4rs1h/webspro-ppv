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
                <form method="post" action="/admin/trx_order/cuti/Simpan" class="mb-5" enctype="multipart/form-data">
                    @csrf
                <div class="penel panel-default">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Data Order Cuti Layanan
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="col-md-4">
                                                <label  class="col-md-1 col-form-label text-md-start">{{ __('Nomor ') }}</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input class="form-control " readonly value="{{ $no_formulir }}" name="no_formulir" id="no_formulir">

                                            </div>
                                            <div class="col-md-4">

                                                <label  class="col-md-1 col-form-label text-md-end">{{ __('Tanggal ') }}</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input class="form-control " readonly value="{{ old(date('d-m-Y', strtotime(now())),$tgl_formulir) }}" name="tgl_formulir" id="tgl_formulir">
                                                <input type="hidden" name="tgl_order" value="{{ old(date('Y-m-d', strtotime(now())),$tgl_order) }}">
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
                                                            <input type="text" class="form-control @error('no_pelanggan') is-invalid  @enderror" id="no_pelanggan" name="no_pelanggan" required autofocus value="{{ old('no_pelanggan',$no_pelanggan) }}">
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
                                                            <input type="hidden" name="pelanggan_id" id="pelanggan_id" value="{{ old('pelanggan_id',$pelanggan_id) }}">
                                                            <input type="text" class="form-control @error('nama_pelanggan') is-invalid  @enderror" id="nama_pelanggan" name="nama_pelanggan" required autofocus readonly value="{{ old('nama_pelanggan',$nama_pelanggan) }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('No. Indentitas (KTP/SIM/PASSPORT)') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="3">
                                                            <input type="text" class="form-control @error('nomer_identitas') is-invalid  @enderror" id="nomer_identitas" name="nomer_identitas" required autofocus readonly value="{{ old('nomer_identitas',$nomer_identitas) }}">
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
                                                            <input type="text" class="form-control @error('alamat') is-invalid  @enderror" id="alamat" name="alamat" required autofocus readonly value="{{ old('alamat',$alamat) }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('No. Telpon') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-3" >
                                                            <input type="text" class="form-control @error('no_hp') is-invalid  @enderror" id="no_hp" name="no_hp" required autofocus readonly value="{{ old('no_hp',$no_hp) }}">
                                                        </td>
                                                        <td class="col-md-2">
                                                            <label>No. HP/WhatsApp : </label>
                                                        </td>
                                                        <td class="col-md-3"><input type="text" class="form-control @error('no_hp2') is-invalid  @enderror" id="no_hp2" name="no_hp2" required autofocus readonly value="{{ old('no_hp2',$no_hp2) }}"></td>

                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Email') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="3">
                                                            <input type="text" class="form-control @error('email') is-invalid  @enderror" id="email" name="email" required autofocus readonly value="{{ old('email',$email) }}">
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
                                    Lama Cuti Layanan Pelanggan
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
                                                            <input type="text" class="form-control text-left @error('catatan_request_pelanggan') is-invalid  @enderror" id="catatan_request_pelanggan" name="catatan_request_pelanggan" autofocus value="{{ old('catatan_request_pelanggan',$catatan_request_pelanggan) }}">
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
                                                        <div class="col-md-2">
                                                            <label> Tanggal </label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            {{--  <input class="form-control" type="date">  --}}
                                                            <input type="date" class="form-control text-left @error('tgl_rencana_upgrade') is-invalid  @enderror" id="tgl_rencana_upgrade" name="tgl_rencana_upgrade" autofocus value="{{ old('tgl_rencana_upgrade',$tgl_rencana_upgrade) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-2">
                                                            <label>Catatan</label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control text-left @error('catatan_instalasi') is-invalid  @enderror" id="catatan_instalasi" name="catatan_instalasi" autofocus  value="{{ old('catatan_instalasi',$catatan_instalasi) }}">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">

                                                <div class="col-lg-5">
                                                {{--  <button type="submit" class="btn btn-primary" name="hitung" id="hitung">Hitung Total</button>  --}}
                                                <button type="submit" class="btn btn-primary" name="simpan" id="simpan">Simpan</button>
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
<script src="{{ asset('js/trxorderupg.js') }}"></script>
@endsection
