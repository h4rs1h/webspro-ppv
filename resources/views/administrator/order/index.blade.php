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
		<div>
            <p><strong>{{ $filter }}</strong></p>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
             {{--   <a href="/admin/pelanggan/create" class="btn btn-primary mb-3">Tambah Pelanggan</a>
                <a href="/admin/trx_order/create" class="btn btn-primary mb-3">Tambah Order</a>
				 <a href="/admin/trx_order/upgrade" class="btn btn-primary mb-3">Upgrade Layanan</a> --}}
				 {{ $subtitle }}
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nomor Order</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">No Pelanggan</th>
								<th scope="col">No Unit</th>
                                <th scope="col">Nama Pelanggan</th>
                                <th scope="col">Nilai Order</th>
                                <th scope="col">Status Pembayaran</th>
								@if ($tipe_order=='4')
                                    <th>Periode Cuti</th>
                                @endif
								@if ($tipe_order=='4')
                                    <th>Catatan</th>
								@else
									<th scope="col">Target Tgl Instalasi</th>
                                @endif
                                
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order as $data )
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
										@if ($data->tipe_order=="1")
                                            <a href="/admin/trx_order/{{ $data->id }}" class="text-decoration-none" data-toggle="tooltip" data-placement="top" title="Lihat Detail Transaksi" >
                                            {{ $data->no_formulir }}</a>
                                        @elseif ($data->tipe_order=="2")
                                            <a href="/admin/trx_order/upgrade/getAksi?aksi=show&id={{ $data->id }}" class="text-decoration-none" data-toggle="tooltip" data-placement="top" title="Lihat Detail Transaksi">
                                            {{ $data->no_formulir }}</a>
                                        @elseif ($data->tipe_order=="3")
                                            <a href="/admin/trx_order/downgrade/getAksi?aksi=show&id={{ $data->id }}" class="text-decoration-none" data-toggle="tooltip" data-placement="top" title="Lihat Detail Transaksi">
                                            {{ $data->no_formulir }}</a>
                                        @elseif ($data->tipe_order=="4")
                                              <a href="/admin/trx_order/cuti/getAksi?aksi=show&id={{ $data->id }}" class="text-decoration-none" data-toggle="tooltip" data-placement="top" title="Lihat Detail Transaksi"> 
                                            {{ $data->no_formulir }}
                                              </a>  
                                        @elseif ($data->tipe_order=="5")
                                            <a href="/admin/trx_order/stop/getAksi?aksi=show&id={{ $data->id }}" class="text-decoration-none" data-toggle="tooltip" data-placement="top" title="Lihat Detail Transaksi">
                                            {{ $data->no_formulir }}</a>
                                        @elseif ($data->tipe_order=="6")
                                            <a href="/admin/trx_order/stop_lgn/getAksi?aksi=show&id={{ $data->id }}" class="text-decoration-none" data-toggle="tooltip" data-placement="top" title="Lihat Detail Transaksi">
                                            {{ $data->no_formulir }}</a>
                                        @endif
									
									</td>
                                    <td>{{ date('d-m-Y', strtotime($data->tgl_order)) }}</td>
									
                                    <td>{{ $data->pelanggan->no_pelanggan }}</td>
									 <td>{{ $data->pelanggan->sub_tower.'/'.$data->pelanggan->lantai.'/'.$data->pelanggan->nomer_unit }}</td>
                                    <td>{{ $data->pelanggan->nama_lengkap }}</td>
                                    <td>{{ number_format($data->gtot_amount) }}</td>
                                    <td>@if ($data->payment_status=='1')
                                        Menunggu Pembayaran
                                    @elseif ($data->payment_status=='2')
                                        Lunas
                                    @elseif ($data->payment_status=='3')
                                        Kadaluarsa
										@elseif ($data->payment_status=='4')
                                        Batal
                                    @else
                                        Kurang Bayar
                                    @endif</td>
									@if ($tipe_order=='4')
                                    <td>{{ date('d-M-Y', strtotime($data->tgl_rencana_belangganan)) }} s/d {{ date('d-M-Y', strtotime($data->tgl_target_instalasi)) }}</td>
                                @endif
                                    <td>{{ $data->catatan_instalasi }}</td>
                                    <td>
                                         @if ($data->tipe_order=="1")
                                            <a href="/admin/trx_order/{{ $data->id }}/edit" class="btn btn-primary btn-circle" data-toggle="tooltip" data-placement="top" title="Edit Transaksi">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @elseif ($data->tipe_order=="2")
{{-- @if($data->upg_layanan=="1") --}}
                                                <a href="/admin/trx_order/kirim_wa?no_order={{ $data->no_order }}&tipe_order={{ $data->tipe_order }}&tipe_wa=upg" class="btn btn-success btn-circle">
                                                    <i class="fa fa-whatsapp"></i>
                                                </a>
                                        {{--    @endif --}}
                                        @elseif ($data->tipe_order=="3")
@if($data->upg_layanan=="1")
                                                <a href="/admin/trx_order/kirim_wa?no_order={{ $data->no_order }}&tipe_order={{ $data->tipe_order }}&tipe_wa=dng" class="btn btn-success btn-circle">
                                                    <i class="fa fa-whatsapp"></i>
                                                </a>
                                            @endif
                                        @elseif ($data->tipe_order=="4")
  <a href="/admin/trx_order/kirim_wa?no_order={{ $data->no_order }}&tipe_order={{ $data->tipe_order }}&tipe_wa=cuti" class="btn btn-success btn-circle">
                                                <i class="fa fa-whatsapp"></i>
                                            </a>
                                        @elseif ($data->tipe_order=="5")
  <a href="/admin/trx_order/kirim_wa?no_order={{ $data->no_order }}&tipe_order={{ $data->tipe_order }}&tipe_wa=stop" class="btn btn-success btn-circle">
                                                <i class="fa fa-whatsapp"></i>
                                            </a>
                                        @elseif ($data->tipe_order=="6")
											<a href="/admin/trx_order/kirim_wa?no_order={{ $data->no_order }}&tipe_order={{ $data->tipe_order }}&tipe_wa=berhenti" class="btn btn-success btn-circle">
                                                <i class="fa fa-whatsapp"></i>
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
