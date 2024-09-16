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
                <a href="/admin/users/create" class="btn btn-primary mb-3">Tambah User</a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Email</th>
                                <th scope="col">Level User</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user as $data )
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->name }}</td>
                    <td>{{ $data->email }}</td>
                    <td>@if ($data->role=='0')
                        Pelanggan
                    @elseif($data->role=='1')
                        Teknisi
                    @elseif($data->role=='2')
                        Marketing
                    @elseif ($data->role=='3')
                        Admin / Kasir
                    @else
                        Administrator
                    @endif</td>

                    <td>
                        <a href="/admin/users/{{ $data->id }}/edit" class="btn btn-primary btn-circle">
                            <i class="fa fa-edit"></i>
                        </a>
@if ($level == '4')
                                                    <a href="/admin/users/change-password?userid={{ $data->id }}"
                                                        class="btn btn-primary btn-circle">
                                                        <i class="fa fa-lock"></i>
                                                    </a>
                                                @endif
                        <form action="/admin/users/{{ $data->id }}" method="post" class="btn" style="padding: 0">
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
