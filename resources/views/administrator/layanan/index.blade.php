@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $title }}</h1>
        </div>
    </div>
    @if (session()->has('success'))
        <div class="alert alert-success col-lg-8" role="alert">
        {{ session('success') }}
        </div>
    @endif
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="/admin/layanan/create" class="btn btn-primary mb-3">Tambah Produk / Layanan</a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Jenis Layanan</th>
                                <th scope="col">Speed</th>
                                <th scope="col">Harga (Rp)</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($layanan as $data )
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->title }}</td>
                    <td>{{ $data->jenis_layanan }}</td>
                    <td>{{ $data->spead }}</td>
                    <td>{{ number_format($data->harga) }}</td>
                    <td>
                        <a href="/admin/layanan/{{ $data->slug }}/edit" class="btn btn-primary btn-circle">
                            <i class="fa fa-edit"></i>
                        </a>

                        <form action="/admin/layanan/{{ $data->slug }}" method="post" class="btn" style="padding: 0">
                            @method('delete')
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ $data->id }}">
                            <button class="btn btn-danger btn-circle" onclick="return confirm('Are you sure?')"><i class="fa fa-times"></i></button>
                        </form>
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
