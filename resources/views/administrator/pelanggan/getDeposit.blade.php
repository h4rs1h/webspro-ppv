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
    @if (session()->has('danger'))
        <div class="alert alert-danger col-lg-12" role="alert">
        {{ session('danger') }}
        </div>
    @endif


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{--  <a href="/admin/pelanggan/create" class="btn btn-primary mb-3">Tambah Pelanggan</a>
                <a href="/admin/pelanggan/deposit" class="btn btn-primary mb-3">Deposit Pelanggan</a>  --}}
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">No Pelanggan</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Email</th>
                                {{--  <th scope="col">Status Layanan</th>  --}}
                                <th scope="col">Nilai Deposit</th>
                                {{--  <th scope="col">Action</th>  --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pelanggan as $data )
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->no_pelanggan }}</td>
                    <td>{{ $data->sub_tower."/".$data->lantai."/".$data->nomer_unit }}</td>
                    <td>{{ $data->nama_lengkap }}</td>
                    <td>{{ $data->email }}</td>
                    {{--  <td>
                        @if($data->status_layanan=="1")
                            Aktif
                        @elseif ($data->status_layanan=="2")
                            Suspend
                        @elseif ($data->status_layanan=="3")
                            Non Aktif
                        @elseif ($data->status_layanan=="4")
                            Berhenti Langganan
                        @else
                            -
                        @endif
                    </td>  --}}
                    <td>{{ number_format($data->nilai_deposit) }}</td>


                </tr>
              @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>

@endsection
