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
            <div class="col-lg-5">
                <div class="panel panel-default">
                    <div class="panel-heading">
                       Filter {{ $subtitle }}
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
                                <form method="post" action="/admin/trx_order/getdata" class="mb-5" enctype="multipart/form-data">
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
                                                                <input type="hidden" name="tipe_order" id="tipe_order" value="{{ $tipe_order }}">
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
                                <form method="post" action="/admin/trx_order/getdata" class="mb-5" enctype="multipart/form-data">
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
                                                                <input type="hidden" name="tipe_order" id="tipe_order" value="{{ $tipe_order }}">
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
    </div>

</div>

@endsection
