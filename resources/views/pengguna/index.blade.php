@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="panel-heading"></h3>
        </div>
    </div>

@if (session()->has('success'))
    <div class="alert alert-success col-lg-6" role="alert">
        {{ session('success') }}
    </div>
@endif

<div class="table-responsive col-lg-7 mb-5 ">
    <div class="panel panel-default mt-3">
    <div class="panel-heading ">
        <h3 class="h3 ">Informasi Profile {{ Auth::user()->name }}</h3>

    </div>
    <div class="panel-body">
        <table class="table table-bordered table-sm">
            <tr>
               <td class="col-sm-5">Nama Pelanggan</td><td>{{ $pengguna->name }}</td>
            </tr>
            <tr>
                <td>No. Identitas (KTP/SIM/PASPORT)</td><td>{{ $pengguna->no_identitas }}</td>
            </tr>
            <tr>
               <td>Tower / Unit</td><td>{{ $pengguna->no_unit}}</td>
            </tr>
            <tr>
               <td>Alamat Sesuai KTP </td><td>{{ $pengguna->alamat }}</td>
            </tr>
            <tr>
               <td>Email</td><td>{{ $pengguna->email }}</td>
            </tr>
            <tr>
               <td>No. Telp</td><td>{{ $pengguna->no_telpon }}</td>
            </tr>
            <tr>
               <td>No. Whatsapp</td><td>{{ $pengguna->no_hp }}</td>
            </tr>

        </table>
    </div>
    </div>
</div>
</div>

@endsection
