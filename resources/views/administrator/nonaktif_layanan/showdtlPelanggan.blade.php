@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $title }}</h1>
        </div>
    </div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Rekap Data Non Aktif
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
                                <th scope="col">#</th>
                                <th scope="col">Nomer</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Unit ID</th>
                                <th scope="col">Tanggal Non Aktif</th>
                                <th scope="col">Tanggal Aktif</th>
                                <th scope="col">Lama Non Aktif</th>
								<th scope="col">Status Layanan</th>
								<th scope="col">Umur Suspend</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $dt )
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($dt->tipe_trx=='1')
                                            {{ $dt->nomer_trx }}
                                        @else
                                            {{ 'Inv-'.$dt->nomer_trx }}
                                        @endif

                                    </td>
                                    <td>
                                        {{ Carbon\Carbon::parse($dt->tgl_req_off)->format('Y-m-d')  }}
                                    </td>
                                    <td class="text-left">
                                       {{ $dt->nama_lengkap }}
                                    </td>
                                    <td class="text-center">
                                       {{ $dt->unitid }}
                                    </td>
                                    <td>
                                        {{ Carbon\Carbon::parse($dt->tgl_act_off)->format('Y-m-d')  }}
                                    </td>
                                    <td>
                                        @if (isset($dt->tgl_act_on))

                                        {{ Carbon\Carbon::parse($dt->tgl_act_on)->format('Y-m-d')  }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $dt->lamaSuspen  }}
                                    </td>
<td>
                                                {{ $dt->ket_status_layanan }}
                                            </td>
									<td>
									@if(!isset($dt->lamaSuspen))
										 {{ $dt->lamaNonAktif }}	
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
