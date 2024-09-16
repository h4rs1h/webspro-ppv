@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $title }}</h1>
        </div>
    </div>
    @if (session()->has('success'))
        <div class="alert alert-success col-lg-12" role="alert">
        {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-3">
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Form Filter
                    </div>
                    <form method="post" action="/admin/akunting/pelanggan/getdata" class="mb-5" enctype="multipart/form-data">
                        @csrf
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
<input type="hidden" name="rpt" id="rpt" value="{{ $rpt }}">
                                    <div class="form-group">
                                        <label>Tanggal Awal</label>

                                        <input type="date" class="form-control @error('tgl_awal') is-invalid  @enderror" id="tgl_awal" name="tgl_awal" required autofocus value="{{ old('tgl_awal') }}">
                                            @error('tgl_awal')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Tangga Akhir</label>

                                        <input type="date" class="form-control @error('tgl_akhir') is-invalid  @enderror" id="tgl_akhir" name="tgl_akhir" required autofocus value="{{ old('tgl_akhir') }}">
                                            @error('tgl_akhir')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
								@if($rpt=="out-daftar")
                                        <div class="form-group">
                                            <label>Status Outstanding</label>
                                            <select class="form-control @error('status_out') is-invalid  @enderror" name="status_out" id="status_out">
                                                <option value="" >Pilih Status Outstanding</option>
                                                @foreach ($opt_status as $trn)
                                                    @if (old('status_out') == $trn['id']))
                                                        <option value="{{ $trn['id'] }}" selected>{{ $trn['name'] }}</option>
                                                    @else
                                                    <option value="{{ $trn['id'] }}" >{{ $trn['name'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                                @error('status_out')
                                                    <div class="invalid-feedback"  role="alert">
                                                        <strong>
                                                            {{ $message }}
                                                        </strong>
                                                    </div>
                                                @enderror
                                        </div>
								@elseif ($rpt=="pelanggan")
                                    <div class="form-group">
                                        <label>Format Laporan</label>
                                        <select class="form-control @error('status_rpt') is-invalid  @enderror" name="status_rpt" id="status_rpt">
                                            <option value="" >Pilih format </option>
                                            @foreach ($opt_show_rpt as $trn)
                                                @if (old('status_rpt') == $trn['id']))
                                                    <option value="{{ $trn['id'] }}" selected>{{ $trn['name'] }}</option>
                                                @else
                                                <option value="{{ $trn['id'] }}" >{{ $trn['name'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                            @error('status_rpt')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Status Pelanggan</label>
                                        <select class="form-control @error('status_out') is-invalid  @enderror" name="status_out" id="status_out">
                                            <option value="" >Pilih Status </option>
                                            @foreach ($opt_status as $trn)
                                                @if (old('status_out') == $trn['id']))
                                                    <option value="{{ $trn['id'] }}" selected>{{ $trn['name'] }}</option>
                                                @else
                                                <option value="{{ $trn['id'] }}" >{{ $trn['name'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                            @error('status_out')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
								@elseif ($rpt=="invoice")
                                    <div class="form-group">
                                        <label>Format Laporan</label>
                                        <select class="form-control @error('status_rpt') is-invalid  @enderror" name="status_rpt" id="status_rpt">
                                            <option value="" >Pilih format </option>
                                            @foreach ($opt_show_rpt as $trn)
                                                @if (old('status_rpt') == $trn['id']))
                                                    <option value="{{ $trn['id'] }}" selected>{{ $trn['name'] }}</option>
                                                @else
                                                <option value="{{ $trn['id'] }}" >{{ $trn['name'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                            @error('status_rpt')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Status Pelanggan</label>
                                        <select class="form-control @error('status_out') is-invalid  @enderror" name="status_out" id="status_out">
                                            <option value="" >Pilih Status </option>
                                            @foreach ($opt_status as $trn)
                                                @if (old('status_out') == $trn['id']))
                                                    <option value="{{ $trn['id'] }}" selected>{{ $trn['name'] }}</option>
                                                @else
                                                <option value="{{ $trn['id'] }}" >{{ $trn['name'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                            @error('status_out')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
								@elseif ($rpt=="nonaktif")
                                    <div class="form-group">
                                        <label>Filter By</label>
                                        <select class="form-control @error('status_out') is-invalid  @enderror" name="status_out" id="status_out">
                                            <option value="" >Pilih </option>
                                            @foreach ($opt_status as $trn)
                                                @if (old('status_out') == $trn['id']))
                                                    <option value="{{ $trn['id'] }}" selected>{{ $trn['name'] }}</option>
                                                @else
                                                <option value="{{ $trn['id'] }}" >{{ $trn['name'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                            @error('status_out')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Input Filter</label>
                                            <input class="form-control " name="input_filter" id="input_filter">
                                    </div>
								@elseif ($rpt=="kwitansi")
                                    <div class="form-group">
                                        <label>Filter By</label>
                                        <select class="form-control @error('status_out') is-invalid  @enderror" name="status_out" id="status_out">
                                            <option value="" >Pilih </option>
                                            @foreach ($opt_status as $trn)
                                                @if (old('status_out') == $trn['id']))
                                                    <option value="{{ $trn['id'] }}" selected>{{ $trn['name'] }}</option>
                                                @else
                                                <option value="{{ $trn['id'] }}" >{{ $trn['name'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                            @error('status_out')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Input Filter</label>
                                            <input class="form-control " name="input_filter" id="input_filter">
                                            <label for="tgl_jtempo" >{{ __('Bila pilih Tanggal Mutasi Bank, format input tahun-bulan-tanggal, Contoh: 2022-07-07') }}</label>
                                    </div>
                                    @endif
                                    <button type="submit" class="btn btn-default">Submit Button</button>
                                    <button type="reset" class="btn btn-default">Reset Button</button>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                    </div>
                    </form>
                    <!-- /.panel-body -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
