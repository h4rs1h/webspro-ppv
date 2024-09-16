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
                <a href="/admin/pelanggan/create" class="btn btn-primary mb-3">Tambah Pelanggan</a>
				 <a href="/admin/pelanggan/get?action=deposit" class="btn btn-primary mb-3">Deposit</a>
                <a href="/admin/pelanggan/get?action=status&layanan=1" class="btn btn-primary mb-3">Status Pelanggan</a>
				<div class="pull-right">
                        <div class="nav">
                            <select name="export_btn" id="export_btn" class="form-control btn-primary">
                                <option value="0">Download</option>
                                <option value="1"> Export CSV</option>
                                <option value="2"> Export Excel</option>
                               
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
                                <th scope="col">#</th>
                                <th scope="col">No Pelanggan</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Email</th>
                               {{--  <th scope="col">Tempat Lahir</th>  --}}
                                <th scope="col">Jenis Kelamin</th>
                                {{--  <th scope="col">Identitas</th>  --}}
                                <th scope="col">HP</th>
                                <th scope="col">Tower</th>
                                <th scope="col">Unit</th>
								<th scope="col">NPWP</th>
								 <th scope="col">CID</th>
								<th scope="col">Layanan</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pelanggan as $data )
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->no_pelanggan }}</td>
					 <td>{{ $data->nama_lengkap }}</td>
                    <td>{{ $data->email }}</td>
                     {{--  <td>{{ $data->tempat_lahir }}</td>  --}}
                    <td>@if ($data->jenis_kelamin=='1')
                        Laki-Laki
                    @else
                        Perempuan
                    @endif</td>
                    {{--  <td>@if ($data->identitas=='1')
                        KTP
                    @elseif ($data->identitas=='2')
                        SIM
                    @else
                        PASPORT
                    @endif</td>  --}}
                    <td>{{ $data->nomer_hp }}</td>
                    <td>{{ $data->tower }}</td>
                    <td>{{ $data->sub_tower."/".$data->lantai."/".$data->nomer_unit }}</td>
					<td>{{ $data->npwp }}</td>
					<td>
                        @if (isset($data->cid))
                           
						<a href="/admin/pelanggan/get?action=upd_cid&pelangganid={{ $data->id }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Update CID">
                             {{ $data->cid }}
                        </a>
                        @else
                        <a href="/admin/pelanggan/get?action=upd_cid&pelangganid={{ $data->id }}" class="btn btn-primary btn-circle" data-toggle="tooltip" data-placement="top" title="Update CID">
                            <i class="fa fa-credit-card"></i>
                        </a>
                        @endif
                    </td>
					<td class="col-sm-2 ">{{ $data->deskripsi_layanan }}</td>
                    <td class="col-sm-2 " >

                        <a href="/admin/pelanggan/aktifasi/{{ $data->id }}" class="btn btn-primary btn-circle" data-toggle="tooltip" data-placement="top" title="Aktivasi Pelanggan" >
                            <i class="fa fa-user"></i>
                        </a>
                        {{--  @if (!$data->user->pelanggan_id)

                        @else
                        <a href="/admin/pelanggan/aktifasi/{{ $data->id }}" class="btn btn-primary btn-circle" data-toggle="tooltip" data-placement="top" title="Aktivasi Pelanggan" >
                            <i class="fa fa-user"></i>
                        </a>

                        @endif  --}}
                        <a href="/admin/pelanggan/{{ $data->id }}/edit" class="btn btn-primary btn-circle" data-toggle="tooltip" data-placement="top" title="Edit Pelanggan">
                            <i class="fa fa-edit"></i>
                        </a>
						<a href="/admin/pelanggan/get?action=upd&pelangganid={{ $data->id }}" class="btn btn-success btn-circle" data-toggle="tooltip" data-placement="top" title="Update NPWP" >
                            <i class="fa fa-credit-card"></i>
                        </a>
                       <form action="/admin/pelanggan/{{ $data->id }}" method="post" class="btn" style="padding: 0">
                            @method('delete')
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ $data->id }}">
                            <button  class="btn btn-danger btn-circle" onclick="return confirm('Are you sure?')" data-toggle="tooltip" data-placement="top" title="Hapus Pelanggan" ><i class="fa fa-times"></i></button>
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
