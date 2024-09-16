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
        <div class="col-lg-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Status Koneksi Whastapp
                        </div>
                        <div class="panel-body">
                            @if($st=="online")
                                <img src="{{ asset('asset/images/whatsapp-logo-whatsapp-online.png') }}" width="300" height="150"><br>
                                <strong><h3></h3>Koneksi ke Nomor WA Blast: {{ $msg }}</h3></strong>
                            @else
                                <img src="{{ asset('asset/images/whatsapp-logo-whatsapp-offline.png') }}" width="300" height="150"><br>
                                <strong><h3></h3>Koneksi ke Nomor WA Blast: {{ $msg }}</h3></strong>
                                <a href="/admin/wa/setting?wablast=scanqr" class="btn btn-primary">QR Code</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
