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
               {{--  Data Pelanggan  --}}
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nomor Order</th>
                                <th scope="col">Tanggal Order</th>
                                <th scope="col">No Pelanggan</th>
                                <th scope="col">Nama Pelanggan</th>
                                <th scope="col">Unit ID</th>
                                {{--  <th scope="col">Sub Tower</th>  --}}
                                {{--  <th scope="col">Lantai</th>  --}}
                                {{--  <th scope="col">Nomor Unit</th>  --}}
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order as $data )
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td> <!--<a href="/admin/trx_aktivasi/tambah/{{ $data->id }}" class="text-decoration-none">-->
                                        {{ $data->no_formulir }}<!--</a>--></td>
                                    <td>{{ date('d-m-Y', strtotime($data->tgl_order)) }}</td>
                                    <td>{{ $data->no_pelanggan }}</td>
                                    <td>{{ $data->nama_lengkap }}</td>
                                    <td>{{ $data->unitid }}</td>
                                    {{--  <td>{{ $data->sub_tower }}</td>  --}}
                                    {{--  <td>{{ $data->lantai }}</td>  --}}
                                    {{--  <td>{{ $data->nomer_unit }}</td>  --}}
                                    <td>
                                        @if ($data->internet=='1')
                                            <a href="/admin/trx_aktivasi/tambah?id={{ $data->id }}&lyn=int" class="btn btn-primary btn-circle">
                                                <i class="fa fa-wifi"></i>
                                            </a>
                                        @endif
                                        @if ($data->tv=='2')
                                            <a href="/admin/trx_aktivasi/tambah?id={{ $data->id }}&lyn=tv" class="btn btn-success btn-circle">
                                                <i class="fa fa-tv"></i>
                                            </a>

                                        @endif
                                        @if ($data->telepon=='3')
                                            <a href="/admin/trx_aktivasi/tambah?id={{ $data->id }}&lyn=telepon" class="btn btn-info btn-circle">
                                                <i class="fa fa-tty"></i>
                                            </a>

                                        @endif
                                        {{--  <a href="/admin/trx_order/{{ $data->id }}/edit" class="btn btn-primary btn-circle">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <form action="/admin/trx_order/{{ $data->id }}" method="post" class="btn" style="padding: 0">
                                            @method('delete')
                                            @csrf
                                            <input type="hidden" id="id" name="id" value="{{ $data->id }}">
                                            <button class="btn btn-danger btn-circle" onclick="return confirm('Are you sure?')"><i class="fa fa-times"></i></button>
                                        </form>  --}}
                                    </td>

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
