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
                    Laporan Data Pelanggan
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
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <th>No Order</th>
                                <th>No Formulir</th>
                                <th>No Pelanggan </th>
                                <th>Unit ID </th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Tempat Lahir</th>
                                <th>Tanggal Lahir</th>
                                <th>Jenis Kelamin</th>
                                <th>Identitas</th>
                                <th>Nomer Identitas</th>
                                 <th>Alamat </th>
                                <th>Agama </th>
                                <th>No HP </th>
                               <th>Tower </th>
                                <th>Lantai </th>
                                <th>Nomer Unit </th>
								<th>NPWP </th>
								<th>Nama NPWP </th>
								<th>Alamat NPWP </th>
								<th>CID</th>
								<th>Pelanggan ID</th>
                            </thead>
                            <tbody>
                                @foreach ( $pelanggan as $data )
                                    <tr>
                                        <td>{{ $data->no_order }}</td>
                                        <td>{{ $data->no_formulir }}</td>
                                        <td>{{ $data->no_pelanggan }}</td>
                                        <td>{{ $data->unitid }}</td>
                                        <td>{{ $data->nama_lengkap }}</td>
                                        <td>{{ $data->email }}</td>
                                        <td>{{ $data->tempat_lahir }}</td>
                                        <td>{{ $data->tanggal_lahir }}</td>
                                        <td>
                                            @if($data->jenis_kelamin=='1')
                                                Laki-Laki
                                            @else
                                                Perempuan
                                            @endif
                                        </td>
                                        <td>
                                            @if($data->identitas=='1')
                                                KTP
                                            @elseif($data->identitas=='2')
                                                SIM
                                            @else
                                                PASPORT
                                            @endif
                                        </td>
                                        <td>{{ $data->nomer_identitas }}</td>
                                         <td>{{ $data->alamat_identitas }}</td>
                                       <td>
                                            @if($data->agama=='1')
                                                ISLAM
                                            @elseif($data->agama=='2')
                                                Katolik
                                            @elseif($data->agama=='3')
                                                Kristen
                                            @elseif($data->agama=='4')
                                                Hindu
                                            @else
                                                Budha
                                            @endif
                                        </td>
                                        <td>{{ $data->nomer_hp }}</td>
                                        <td>{{ $data->sub_tower }}</td>
                                        <td>{{ $data->lantai }}</td>
                                        <td>{{ $data->nomer_unit }}</td>
										<td>{{ $data->npwp }}</td>
										<td>{{ $data->nama_npwp }}</td>
										<td>{{ $data->alamat_npwp }}</td>
										<td>{{ $data->cid }}</td>
										<td>{{ $data->id }}</td>
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
@endsection
