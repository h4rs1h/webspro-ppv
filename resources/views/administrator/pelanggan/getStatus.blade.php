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
				@foreach ($dtstatus as $dt)
                <a href="/admin/pelanggan/get?action=status&layanan={{ $dt->status_layanan }}" class="btn btn-primary mb-3">{{ $dt->ket_status_layanan }}</a>

                @endforeach
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
								<th scope="col">No Formulir</th>
                                <th scope="col">No Pelanggan</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Status Layanan</th>
                                <th scope="col">Tanggal Aktivasi</th>
                                {{--  <th scope="col">Aktif Sampai</th>  --}}
                                {{--  <th scope="col">Action</th>  --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pelanggan as $data )
                <tr>
                    <td>{{ $loop->iteration }}</td>
					<td>{{ $data->no_formulir }}</td>
                    <td>{{ $data->no_pelanggan }}</td>
                    <td>{{ $data->sub_tower."/".$data->lantai."/".$data->nomer_unit }}</td>
                    <td>{{ $data->nama_lengkap }}</td>
					<td>
                        {{ $data->ket_status_layanan }}

                    </td>
                    {{--  <td>{{ $data->email }}</td>  --}}
                
                    <td> 
						@isset($data->tgl_aktivasi)
						{{ Carbon\Carbon::parse($data->tgl_aktivasi)->format('d-m-Y')  }}
						@endisset
					</td>
                    {{--  <td>{{ Carbon\Carbon::parse($data->tgl_tagihan_router)->format('d-m-Y')  }}</td>  --}}
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
