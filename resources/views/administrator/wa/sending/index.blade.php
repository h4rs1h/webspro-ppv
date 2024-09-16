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
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Data Sending Whastapp
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Tanggal Kirim</th>
                                            <th scope="col">Tanggal Terkirim</th>
                                            <th scope="col">Unit ID</th>
                                            <th scope="col">Nomer HP</th>
                                            <th scope="col">Isi Pesan</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sending as $data )
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ Carbon\Carbon::parse($data->tgl_kirim)->format('d m Y H:i:s') }}</td>
                                            <td>{{ Carbon\Carbon::parse($data->tgl_terkirim)->format('d m Y H:i:s') }}</td>
                                            <td>{{ $data->id_unit }}</td>
                                            <td>{{ $data->no_wa }}</td>
                                            <td>{{ $data->isi_pesan }}</td>
                                            <td>{{ $data->status }}</td>
                                            <td></td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
