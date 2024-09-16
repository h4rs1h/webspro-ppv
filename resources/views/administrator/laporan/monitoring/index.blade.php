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
            <div class="panel panel-default">
                <div class="panel-heading">
                    @foreach ($data as $d)
                        <a href="{{ $d['link'] }}" class="btn btn-primary mb-3">{{ $d['title'] }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @if ($id_user=='1')
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @foreach ($data2 as $d)
                        <a href="{{ $d['link'] }}" class="btn btn-primary mb-3">{{ $d['title'] }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @endif
</div>
@endsection
