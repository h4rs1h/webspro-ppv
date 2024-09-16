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
                {{--  <a href="/admin/trx_aktivasi/create" class="btn btn-primary mb-3">Tambah Aktivasi</a>  --}}
                <a href="/admin/trx_aktivasi/nonaktivasi" class="btn btn-primary mb-3">Data Pelanggan Siap Aktivasi</a>
				
				<div class="pull-right">
                        <div class="nav">
                            <select name="export_btn" id="export_btn" class="form-control">
                                <option value="0">== Pilih Metode Export ==</option>
                                <option value="1"> Export CSV</option>
                                <option value="2"> Export Excel</option>
                                <option value="3"> Export PDF</option>
                                <option value="4"> Export Print</option>
                            </select>
                        </div>
                    </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th scope="col">Action</th>
                                <th scope="col">#</th>
                                <th scope="col">Nomor Order</th>
                                <th scope="col">Tanggal Order</th>
                                 <th scope="col">Unit ID</th>  
                                <th scope="col">Nama Pelanggan</th>
                                <th scope="col">No Router</th>
                                <th scope="col">Tanggal Aktivasi Router</th>
                                <th scope="col">STB Free</th>
                                <th scope="col">Tanggal Aktivasi STB Free</th>
                                <th scope="col">No STB Berbayar</th>
                                <th scope="col">Tanggal Aktivasi STB Berbayar</th>
                                <th scope="col">Telepon</th>
                                <th scope="col">Tanggal Aktivasi Telepon</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order as $data )
                                <tr>
                                    <td>
                                        @if ($data->internet=='1')
                                            <a href="/admin/trx_aktivasi/edit?id={{ $data->id_aktivasi_router }}&lyn=int" class="btn btn-primary btn-circle">
                                                <i class="fa fa-wifi"></i>
                                            </a>

                                        @endif
                                        @if ($data->tv =='2')
                                            <a href="/admin/trx_aktivasi/edit?id={{ $data->id_aktivasi_stb }}&lyn=tv" class="btn btn-primary btn-circle">
                                                <i class="fa fa-tv"></i>
                                            </a>

                                        @endif
                                        @if ($data->telepon=='3')
                                            <a href="/admin/trx_aktivasi/edit?id={{ $data->id_aktivasi_telp }}&lyn=telepon" class="btn btn-primary btn-circle">
                                                <i class="fa fa-tty"></i>
                                            </a>

                                        @endif
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><!--<a href="/admin/trx_order/{{ $data->id }}" class="text-decoration-none">-->
                                        {{ $data->no_formulir }}<!--</a>--></td>
									{{-- date('d-m-Y', strtotime($data->tgl_order)) --}}
                                    <td>{{ $data->tgl_order }}</td>
                                      <td>{{ $data->unitid }}</td>  
                                    <td>{{ $data->nama_lengkap }}</td>
                                    <td>
                                        <a href="/admin/trx_aktivasi/delete?id={{ $data->id_aktivasi_router }}" onclick="return confirm('Yakin Akan di Hapus?')">
                                        {{ $data->no_router }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($data->tgl_aktivasi_router)
                                            {{-- date('d-m-Y', strtotime($data->tgl_aktivasi_router)) --}}
											{{ $data->tgl_aktivasi_router }}
                                        @endif
                                    </td>
                                    <td>
                                        <a href="/admin/trx_aktivasi/delete?id={{ $data->id_aktivasi_free_stb }}" onclick="return confirm('Yakin Akan di Hapus?')">

                                            {{ $data->no_free_stb }}
                                            </a>
                                    </td>
                                    <td>
                                        @if ($data->tgl_aktivasi_free_stb)
                                            {{-- date('d-m-Y', strtotime($data->tgl_aktivasi_free_stb)) --}}
											{{ $data->tgl_aktivasi_free_stb }}
                                        @endif
                                    </td>
                                   <td>
                                        <a href="/admin/trx_aktivasi/delete?id={{ $data->id_aktivasi_stb }}" onclick="return confirm('Yakin Akan di Hapus?')">

                                            {{ $data->no_stb }}
                                            </a>
                                    </td>
                                    <td>
                                        @if ($data->tgl_aktivasi_stb)
                                        {{-- date('d-m-Y', strtotime($data->tgl_aktivasi_stb)) --}}
										{{ $data->tgl_aktivasi_stb }}
                                        @endif
                                    </td>
                                    <td>{{ $data->no_tpl }}</td>
                                    <td>
                                        @if ($data->tgl_aktivasi_tlp)
                                        {{-- date('d-m-Y', strtotime($data->tgl_aktivasi_tlp)) --}}
										 {{ $data->tgl_aktivasi_tlp }}
                                        @endif
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
