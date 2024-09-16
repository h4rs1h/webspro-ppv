@extends('layouts.main')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $title }}</h1>
        </div>
        @if (session()->has('success'))
            <div class="alert alert-success col-lg-12" role="alert">
            {{ session('success') }}
            </div>
        @endif
    </div>
    <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                       Filter Tagihan Invoice
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#home" data-toggle="tab" aria-expanded="true">Filter Basic</a>
                            </li>
                            <li class=""><a href="#profile" data-toggle="tab" aria-expanded="false">Filter Advance</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane fade active in" id="home">
                                <form method="post" action="/admin/trx_invoice/getdata" class="mb-5" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="col-md-4">
                                                                <label  class="text-md-start">{{ __('Pilih Filter') }}</label>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="hidden" name="penel" id="penel" value="0">
                                                                <select class="form-control" name="statuspelanggan" id="statuspelanggan">
                                                                    <option value="" >-- Pilih --</option>
                                                                    @foreach ($statuspelanggan as $trn)
                                                                        @if (old('statuspelanggan') == $trn['id']))
                                                                            <option value="{{ $trn['id'] }}" selected>{{ $trn['name'] }}</option>
                                                                        @else
                                                                        <option value="{{ $trn['id'] }}" >{{ $trn['name'] }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                                    @error('statuspelanggan')
                                                                        <div class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }} </strong>
                                                                        </div>
                                                                    @enderror
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label  class="text-md-end">{{ __('Input Filter') }}</label>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input class="form-control " name="input_filter" id="input_filter">
																<label for="tgl_jtempo" >{{ __('Tanggal Jatuh Tempo harus diisi sesuai dengan format tahun-bulan-tanggal. Contoh (2022-07-31).') }}</label>
                                                            </div>
                                                                <div class="col-md-4">
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <button type="submit" class="btn btn-primary">Submit </button>
                                                                <button type="reset" class="btn btn-primary">reset </button>
                                                                </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade " id="profile">
                                <form method="post" action="/admin/trx_invoice/getdata" class="mb-5" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="col-md-4">
                                                                <label  class="text-md-start">{{ __('Dari Tanggal ') }}</label>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="hidden" name="penel" id="penel" value="1">
                                                                <input type="date" class="form-control @error('tgl_awal') is-invalid  @enderror" id="tgl_awal" name="tgl_awal" required value="{{ old('tgl_awal') }}">
                                                                @error('tgl_awal')
                                                                    <div class="invalid-feedback"  role="alert">
                                                                        <strong>
                                                                            {{ $message }}
                                                                        </strong>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label  class="text-md-end">{{ __('Sampai Tanggal ') }}</label>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="date" class="form-control @error('tgl_akhir') is-invalid  @enderror" id="tgl_akhir" name="tgl_akhir" required value="{{ old('tgl_akhir') }}">
                                                                @error('tgl_akhir')
                                                                    <div class="invalid-feedback"  role="alert">
                                                                        <strong>
                                                                            {{ $message }}
                                                                        </strong>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label  class="text-md-start">{{ __('Status Tagihan') }}</label>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <select class="form-control" name="statustagihan" id="statustagihan">
                                                                    <option value="" >-- Pilih --</option>
                                                                    @foreach ($statustagihan as $trn)
                                                                        @if (old('statustagihan') == $trn['id']))
                                                                            <option value="{{ $trn['id'] }}" selected>{{ $trn['name'] }}</option>
                                                                        @else
                                                                        <option value="{{ $trn['id'] }}" >{{ $trn['name'] }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                                    @error('statustagihan')
                                                                        <div class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }} </strong>
                                                                        </div>
                                                                    @enderror
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label  class="text-md-end">{{ __('Filter Pelanggan') }}</label>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <select class="form-control" name="statuspelanggan" id="statuspelanggan">
                                                                    <option value="" >-- Pilih --</option>
                                                                    @foreach ($statuspelanggan as $trn)
                                                                        @if (old('statuspelanggan') == $trn['id']))
                                                                            <option value="{{ $trn['id'] }}" selected>{{ $trn['name'] }}</option>
                                                                        @else
                                                                        <option value="{{ $trn['id'] }}" >{{ $trn['name'] }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                                    @error('statuspelanggan')
                                                                        <div class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }} </strong>
                                                                        </div>
                                                                    @enderror
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label  class="text-md-end">{{ __('Input Filter') }}</label>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input class="form-control " name="input_filter" id="input_filter">
																<label for="tgl_jtempo" >{{ __('Tanggal Jatuh Tempo harus diisi sesuai dengan format tahun-bulan-tanggal. Contoh (2022-07-31).') }}</label>
                                                            </div>
                                                            <div class="col-md-4">
                                                            </div>
                                                            <div class="col-md-8">
                                                                <button type="submit" class="btn btn-primary">Submit </button>
                                                            <button type="reset" class="btn btn-primary">reset </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
        </div>
         {{--  kolom  --}}
         @if (Auth::user()->id=="1" or Auth::user()->id=="12" or Auth::user()->id=="37")
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading ">
                    Cek Tanggal Exp Date
                </div>
                <div class="penel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="post" action="/admin/trx_invoice/cek_expdate" class="mb-5" enctype="multipart/form-data">
                            @csrf
                            <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="Unit_id" >{{ __('Unit ID') }}</label>
                                        <input type="hidden" name="tipe_form" value="1">
                                            <input type="text" class="form-control @error('unitid') is-invalid  @enderror" id="unitid" name="unitid" required autofocus value="{{ old('unitid') }}">
                                            @error('unitid')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin Cek Data?')">Submit </button>
                                    </div>

                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
{{--
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading ">
                    Tambah Invoice Manual
                </div>
                <div class="penel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="post" action="/admin/trx_invoice" class="mb-5" enctype="multipart/form-data">
                            @csrf
                            <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="Unit_id" >{{ __('Unit ID') }}</label>
                                        <input type="hidden" name="tipe_form" value="2">
                                            <input type="text" class="form-control @error('unitid') is-invalid  @enderror" id="unitid" name="unitid" required autofocus value="{{ old('unitid') }}">
                                            @error('unitid')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="tgl_jtempo" >{{ __('Tanggal Jatuh Tempo') }}</label>
                                            <input type="date" class="form-control @error('tgl_jtempo') is-invalid  @enderror" id="tgl_jtempo" name="tgl_jtempo" required autofocus value="{{ old('tgl_jtempo') }}">
                                            @error('tgl_jtempo')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                        <label for="tgl_jtempo" >{{ __('Tanggal Jatuh Tempo harus diisi sesuai dengan tanggal aktivasi dikurang 1 hari. Untuk unit yang aktivasi sebelum tanggal 1 Mei 2022, maka tanggal jatuh tempo adalah di tanggal akhir bulan.') }}</label>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin Simpan Data?')">Proses Buat Invoice </button>
                                    </div>

                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
		<div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading ">
                    Update Invoice Batal
                </div>
                <div class="penel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="post" action="/admin/trx_invoice/upd_invbatal" class="mb-5" enctype="multipart/form-data">
                            @csrf
                            <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="nomor" >{{ __('Nomor Tagihan Invoice') }}</label>
                                        <input type="hidden" name="tipe_form" value="3">
                                            <input type="text" class="form-control @error('no_tagihan') is-invalid  @enderror" id="no_tagihan" name="no_tagihan" required autofocus value="{{ old('no_tagihan') }}">
                                            @error('no_tagihan')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="ket" >{{ __('Keterangan Batal') }}</label>
                                            <input type="text" class="form-control @error('ket') is-invalid  @enderror" id="ket" name="ket" required autofocus value="{{ old('ket') }}">
                                            @error('ket')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin Simpan Data?')">Update Invoice Batal </button>
                                    </div>

                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		{{--  end kolom  --}}
         @endif
		@if (Auth::user()->id=="1")
		
		<div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading ">
                    Update Exp Date Invoice
                </div>
                <div class="penel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="post" action="/admin/trx_invoice/upd_expdateinv" class="mb-5" enctype="multipart/form-data">
                            @csrf
                            <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="no_tagihan" >{{ __('Nomer Tagihan Invoice') }}</label>
                                        <input type="hidden" name="tipe_form" value="4">
                                            <input type="text" class="form-control @error('no_tagihan') is-invalid  @enderror" id="no_tagihan" name="no_tagihan" required autofocus value="{{ old('no_tagihan') }}">
                                            @error('no_tagihan')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="tgl_jtempo" >{{ __('Tanggal Jatuh Tempo') }}</label>
                                            <input type="date" class="form-control @error('tgl_jtempo') is-invalid  @enderror" id="tgl_jtempo" name="tgl_jtempo" required autofocus value="{{ old('tgl_jtempo') }}">
                                            @error('tgl_jtempo')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror

                                    </div>
                                    <div class="form-group">
                                        <label for="periode_pemakaian" >{{ __('Periode Pemakaian') }}</label>
                                        <input type="hidden" name="tipe_form" value="4">
                                            <input type="text" class="form-control @error('periode_pemakaian') is-invalid  @enderror" id="periode_pemakaian" name="periode_pemakaian" required autofocus value="{{ old('periode_pemakaian') }}">
                                            @error('periode_pemakaian')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin Simpan Data?')">Update Exp Date Invoice </button>
                                    </div>

                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
         {{--  end kolom  --}}
         @endif
    </div>

</div>

@endsection
