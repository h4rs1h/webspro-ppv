@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $title }}</h1>
        </div>
    </div>

<div class="row">
    <div class="col-lg-6">
        <form method="post" action="/admin/trx_nonaktif/getdatanonaktif" class="mb-5" enctype="multipart/form-data">
            @csrf
        <div class="panel panel-default">
            {{--  <div class="panel-heading">
                Data Request Non Aktif Layanan
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-4">
                            <label  class="col-md-1 col-form-label text-md-start">{{ __('Nomor ') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control " readonly value="{{ $nomerRequest }}" name="nomer">

                        </div>
                        <div class="col-md-4">

                            <label  class="col-md-1 col-form-label text-md-end">{{ __('Tanggal ') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="datetime" class="form-control " readonly value="{{ date('d-m-Y H:i:s', strtotime(now())) }}" name="tgl">

                        </div>
                    </div>
                </div>
            </div>  --}}
            <div class="panel-heading">
                Filter Lama Tunggakan
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-4">
                            <label  class="col-md-1 col-form-label text-md-start">{{ __('Dari') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control @error('awal') is-invalid  @enderror" type="number" name="awal" id="awal" >
                            @error('awal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        </div>
                        <div class="col-md-4">

                            <label  class="col-md-1 col-form-label text-md-end">{{ __('Sampai') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control @error('akhir') is-invalid  @enderror" type="number" name="akhir" id="akhir" >
                            @error('akhir')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary" name="simpan" id="simpan">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

@endsection
