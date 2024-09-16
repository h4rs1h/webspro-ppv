@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $title }}</h1>
        </div>
    </div>
	@if (!empty($successs))
            <div class="alert alert-success"> {{ $successs }}</div>
        @endif
	
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
                <a href="/admin/trx_bayar/create" class="btn btn-primary mb-3">Outstanding Pembayaran Tagihan</a> --}}
                <a href="/admin/trx_bayar/getoutdaftar" class="btn btn-primary mb-3">Outstanding Pembayaran Order Layanan</a>
				
                <a href="/admin/trx_bayar/getouttagihan" class="btn btn-success mb-3">Outstanding Pembayaran Tagihan</a>
				 <a href="/admin/trx_bayar/getout?aksi=upgrade" class="btn btn-success mb-3">Outstanding Pembayaran Upgrade</a>
				
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
                                <th scope="col">Nomor Pembayaran</th>
                                <th scope="col">Tanggal Bayar</th>
								<th scope="col">Tanggal Mutasi Bank</th>
								 <th scope="col">No Order / No Invoice</th>
                                <th scope="col">No Pelanggan</th>
								 <th scope="col">Unit ID</th>
                                <th scope="col">Nama Pelanggan</th>
								 <th scope="col">Periode Layanan</th> 
                                <th scope="col">Nominal Tagihan</th>
                                <th scope="col">Nominal Bayar</th>
                                <th scope="col">Outstading</th>
                                <th scope="col">Status Bayar</th>
								 <th scope="col">Metode</th>
                                        <th scope="col">Catatan</th>
                                {{--  <th scope="col">Target Tgl Instalasi</th>  --}}
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order as $data )
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->nomer_bayar }}
                                        {{--  <a href="/admin/trx_bayar/{{ $data->id }}" class="text-decoration-none">
                                        </a>  --}}
										@if ($data->approved_by<>'0')
                                            <i class="fa fa-check-square-o "></i>

                                        @endif
                                    </td>
									{{-- date('d-m-Y', strtotime($data->tgl_bayar)) --}}
                                    <td>{{ $data->tgl_bayar }}</td>
									<td>
                                    @isset($data->tgl_mutasi_bank)
										{{-- date('d-m-Y', strtotime($data->tgl_mutasi_bank)) --}}
                                    {{ $data->tgl_mutasi_bank }}
                                    @endisset</td>
                                    <td>{{ $data->no_formulir }}</td>
									<td>{{ $data->no_pelanggan }}</td>
									<td>{{ $data->unitid }}</td>
                                    <td>{{ $data->nama_lengkap }}</td>
									 <td>{{ $data->periodelayanan }}</td> 
                                    <td>{{ number_format($data->gtot_amount) }}</td>
                                    <td>{{ number_format($data->amount) }}</td>
                                    <td>
                                        @if ($data->status_bayar=='1')
                                            {{ number_format($data->gtot_amount-$data->totalamountpay)  }}
                                        @else
                                            {{ number_format($data->gtot_amount-$data->totalamountpay)  }}
                                        @endif</td>
                                    <td>@if ($data->status_bayar=='1')
                                        Lunas
                                    @elseif ($data->status_bayar=='2')
                                        DP (Kurang Bayar)
                                    @else
                                        <a href="/admin/trx_bayar/bayar?no_bayar={{ $data->id }}" data-toggle="tooltip" data-placement="top" title="Lakukan Konfirmasi Pembayara"> Konfirmasi Pembayaran </a>
                                    @endif</td>
 <td>{{ $data->Ket_metode_bayar }}</td>
                                            <td>{{ $data->catatan }}</td>
                                    <td>
                                        @if ($data->approved_by<>'0')

                                            <a href="/admin/trx_bayar/kwitansi/{{ $data->id }}" class="btn btn-primary btn-circle" data-toggle="tooltip" data-placement="top" title="Cetak PDF Kwitansi">
                                                <i class="fa fa-money"></i>
                                            </a>
                                        @else
                                            <a href="/admin/trx_bayar/ttbayar/{{ $data->id }}" class="btn btn-primary btn-circle" data-toggle="tooltip" data-placement="top" title="Cetak PDF Tanda Terima Pembayaran">
                                                <i class="fa fa-money"></i>
                                            </a>

                                        @endif
										@if ($data->approved_by<>'0')
                                            @if($id_user=='1' or $id_user=='12' or $id_user=='20'  or $id_user=='37')
                                                <a href="/admin/trx_bayar/bayar?no_bayar={{ $data->id }}" class="btn btn-primary btn-circle" data-toggle="tooltip" data-placement="top" title="Edit Transaksi Pembayaran">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <form action="/admin/trx_bayar/{{ $data->id }}" method="post" class="btn" style="padding: 0">
                                                    @method('delete')
                                                    @csrf
                                                    <input type="hidden" id="id" name="id" value="{{ $data->id }}">
                                                    <button class="btn btn-danger btn-circle" onclick="return confirm('Are you sure?')" data-toggle="tooltip" data-placement="top" title="Hapus Transaksi Pembayaran"><i class="fa fa-times"></i></button>
                                                </form>
                                            @endif
                                        @else
                                            <a href="/admin/trx_bayar/bayar?no_bayar={{ $data->id }}" class="btn btn-primary btn-circle" data-toggle="tooltip" data-placement="top" title="Edit Transaksi Pembayaran">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <form action="/admin/trx_bayar/{{ $data->id }}" method="post" class="btn" style="padding: 0">
                                                @method('delete')
                                                @csrf
                                                <input type="hidden" id="id" name="id" value="{{ $data->id }}">
                                                <button class="btn btn-danger btn-circle" onclick="return confirm('Are you sure?')" data-toggle="tooltip" data-placement="top" title="Hapus Transaksi Pembayaran"><i class="fa fa-times"></i></button>
                                            </form>
                                        @endif
                                        @if($id_user=='1' or $id_user=='14' or $id_user=='20'  or $id_user=='37')
                                            @if ($data->approved_by=='0')

                                                <a href="/admin/trx_bayar/approved?no_bayar={{ $data->no_bayar }}" class="btn btn-primary btn-circle" onclick="return confirm('Yakin Approved?')" data-toggle="tooltip" data-placement="top" title="Approved Transaksi Pembayaran"><i class="fa fa-check"></i></a>

                                            @endif
                                        @endif
										
										@if ($data->approved_by<>'0')

                                            <a href="/admin/trx_bayar/notif?kirimwa=kwitansi&id={{ $data->id }}" class="btn btn-success btn-circle" data-toggle="tooltip" data-placement="top" title="Kirim Notifikasi Kwitansi">
                                                <i class="fa fa-whatsapp"></i>
                                            </a>
                                        @else
                                            <a href="/admin/trx_bayar/notif?kirimwa=ttbayar&id={{ $data->id }}" class="btn btn-success btn-circle" data-toggle="tooltip" data-placement="top" title="Kirim Notifikasi Tanda Terima Pembayaran">
                                                <i class="fa fa-whatsapp"></i>
                                            </a>

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
