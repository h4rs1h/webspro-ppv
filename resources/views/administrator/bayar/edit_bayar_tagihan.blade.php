@extends('layouts.main')

@section('content')
<div class="container-fluid">
    @if (session()->has('success'))
        <div class="alert alert-success col-lg-12" role="alert">
        {{ session('success') }}
        </div>
    @endif


    <div class="col-lg-12">
        <h1 class="page-header">{{ $title }}</h1>
        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="/admin/trx_bayar/edit_bayar_tagihan" class="mb-5" enctype="multipart/form-data">
                    @csrf
                <div class="penel panel-default">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Data Pembayaran Pelanggan
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div >
                                                <table>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Nomer Pembayaran') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-2" >
                                                            <input type="hidden" id="id_bayar" name="id_bayar" value="{{ $orderhdr->id  }}">
                                                            <input type="hidden" id="no_bayar" name="no_bayar" value="{{ $orderhdr->nomer_bayar }}">
															 <input type="hidden" id="tipe_bayar" name="tipe_bayar" value="{{ $orderhdr->tipe_bayar }}">
                                                            <input type="hidden" id="no_order" name="no_order" value="{{ $orderhdr->no_order }}">
                                                            <input type="hidden" id="pelanggan_id" name="pelanggan_id" value="{{ $orderhdr->pelanggan_id }}">
                                                            <input type="hidden" id="trx_tagihan_id" name="trx_tagihan_id" value="{{ $orderhdr->id_order_or_tagihan }}">
                                                            <input type="text" class="form-control @error('nomer_bayar') is-invalid  @enderror" id="nomer_bayar" name="nomer_bayar" disabled required autofocus value="{{ old('nomer_bayar',$orderhdr->nomer_bayar) }}">
                                                            @error('nomer_bayar')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </td>
                                                        <td class="col-md-1"><label class="text-md-start">{{ __('Metode ') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-3">
                                                            <select class="form-control @error('metode_bayar') is-invalid  @enderror" name="metode_bayar" id="metode_bayar">
                                                                <option value="" >Pilih Metode Bayar</option>
                                                                @foreach ($metode_bayar as $trn)
                                                                    @if (old('metode_bayar',$orderhdr->metode_bayar) == $trn['id']))
                                                                        <option value="{{ $trn['id'] }}" selected>{{ $trn['name'] }}</option>
                                                                    @else
                                                                    <option value="{{ $trn['id'] }}" >{{ $trn['name'] }}</option>
                                                                    @endif
                                                                @endforeach
                                                                </select>
                                                                @error('metode_bayar')
                                                                    <div class="invalid-feedback"  role="alert">
                                                                        <strong>
                                                                            {{ $message }}
                                                                        </strong>
                                                                    </div>
                                                                @enderror
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Tanggal') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-2" >
                                                            <input type="date" class="form-control " value="{{ old(date('d-m-Y', strtotime(now())),$orderhdr->tgl_bayar) }}" name="tgl_bayar" id="tgl_bayar">
                                                        </td>
                                                        <td class="col-md-1"><label class="text-md-start">{{ __('Status  ') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-3">
                                                            <select class="form-control @error('status_bayar') is-invalid  @enderror" name="status_bayar" id="status_bayar">
                                                                <option value="" >Pilih Status Bayar</option>
                                                                @foreach ($status_bayar as $trn)
                                                                    @if (old('status_bayar',$orderhdr->status_bayar) == $trn['id']))
                                                                        <option value="{{ $trn['id'] }}" selected>{{ $trn['name'] }}</option>
                                                                    @else
                                                                    <option value="{{ $trn['id'] }}" >{{ $trn['name'] }}</option>
                                                                    @endif
                                                                @endforeach
                                                                </select>
                                                                @error('status_bayar')
                                                                    <div class="invalid-feedback"  role="alert">
                                                                        <strong>
                                                                            {{ $message }}
                                                                        </strong>
                                                                    </div>
                                                                @enderror
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Total Bayar') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-3" >
                                                            <input type="text" class="form-control @error('amount') is-invalid  @enderror" id="amount" name="amount" required autofocus value="{{ old('amount',$orderhdr->amount) }}">
                                                            @error('amount')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </td>
                                                        <td class="col-md-1"><label class="text-md-start">{{ __('Catatan') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-5">
                                                            <input type="text" class="form-control @error('catatan') is-invalid  @enderror" id="catatan" name="catatan" autofocus value="{{ old('catatan',$orderhdr->catatan) }}">
                                                            @error('catatan')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </td>
                                                    </tr>
													<tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Biaya Transaksi') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-3">
                                                            <input type="text" class="form-control @error('biaya_transaksi	') is-invalid  @enderror" id="biaya_transaksi	" name="biaya_transaksi	" required autofocus value="{{ old('biaya_transaksi	',$orderhdr->biaya_transaksi	) }}">
                                                            @error('biaya_transaksi	')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Upload Bukti Bayar') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="4">
                                                            <input type="hidden" name="oldImage" value="{{ $orderhdr->bukti_bayar }}">
                                                            @if ($orderhdr->bukti_bayar)
                                                                <img src="{{ asset(env('FOLDER_IN_PUBLIC_HTML').'/storage/'.$orderhdr->bukti_bayar) }}" class="img-preview img-fluid mb-3 col-sm-5 d-block">
                                                            @else
                                                                <img class="img-preview img-fluid mb-3 col-sm-5">
                                                            @endif

                                                            <img class="img-preview img-fluid mb-3 col-sm-5">
                                                            <input class="form-control  @error('image') is-invalid  @enderror" type="file" id="image" name="image" onchange="previewImage()">
                                                            @error('image')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<div class="panel-heading">
                                    Validasi Finance
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div >
                                                <table>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Tanggal Mutasi Bank') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-2" >
                                                            <input type="date" class="form-control " value="{{ old(date('d-m-Y', strtotime(now())),$orderhdr->tgl_mutasi_bank) }}" name="tgl_mutasi_bank" id="tgl_mutasi_bank">
															@error('tgl_mutasi_bank')
                                                                        <div class="invalid-feedback alert-danger">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                        </td>
                                                        <td class="col-md-1"></td>
                                                        <td></td>
                                                        <td class="col-md-3">

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Apprved By ') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-3" >

                                                            @if($id_user == '1' or $id_user =='14' or $id_user=='20' or $id_user=='37')
                                                                @if($orderhdr->approved_by=='0')
                                                                   {{-- <a href="/admin/trx_bayar/approved?no_bayar={{ $orderhdr->no_bayar }}" class="btn btn-primary" onclick="return confirm('Yakin Approved?')">approved</a> --}}
															<input type="submit" value="approved"
                                                                                onclick="return confirm('Yakin Approved?')"
                                                                                name="approved" class="btn btn-primary">
															
                                                                @else
                                                                <select class="form-control @error('approved_by') is-invalid  @enderror" name="approved_by" id="approved_by" disabled>
                                                                    <option value="" >Un Approved</option>
                                                                    @foreach ($approved as $trn)
                                                                        @if (old('approved_by',$orderhdr->approved_by) == $trn['id']))
                                                                            <option value="{{ $trn['id'] }}" selected>{{ $trn['name'] }}</option>
                                                                        @else
                                                                            <option value="{{ $trn['id'] }}" >{{ $trn['name'] }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                    </select>
                                                                @endif
                                                            @else
                                                                <select class="form-control @error('approved_by') is-invalid  @enderror" name="approved_by" id="approved_by" disabled>
                                                                    <option value="" >Un Approved</option>
                                                                    @foreach ($approved as $trn)
                                                                        @if (old('approved_by',$orderhdr->approved_by) == $trn['id']))
                                                                            <option value="{{ $trn['id'] }}" selected>{{ $trn['name'] }}</option>
                                                                        @else
                                                                            <option value="{{ $trn['id'] }}" >{{ $trn['name'] }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                    </select>
                                                                    @error('approved_by')
                                                                        <div class="invalid-feedback"  role="alert">
                                                                            <strong>
                                                                                {{ $message }}
                                                                            </strong>
                                                                        </div>
                                                                    @enderror
                                                                </select>
                                                            @endif
                                                        </td>
                                                        <td class="col-md-1"></td>
                                                        <td></td>
                                                        <td class="col-md-5">

                                                        </td>
                                                    </tr>


                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
                                @if($orderhdr->tipe_bayar=="1")
                                {{--  Data Tagihan Pendaftaraan  --}}
                                <div class="panel-heading">
                                    Data Pendaftaran Pelanggan
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div >
                                                <table >
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Nomer Formulir Pendaftaran') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="3">
                                                            <input type="text" class="form-control @error('no_formulir') is-invalid  @enderror" id="no_formulir" name="no_formulir" disabled required autofocus value="{{ old('no_formulir',$orderhdr->no_formulir) }}">
                                                            @error('no_formulir')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Tanggal Pendaftaran') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="3">
                                                            <input class="form-control " readonly value="{{ date('d-m-Y', strtotime($orderhdr->tgl_order)) }}" name="tgl_order" id="tgl_order">

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Nama Pelanggan)') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="3">
                                                            <input type="text" class="form-control @error('nama_pelanggan') is-invalid  @enderror" id="nama_pelanggan" name="nama_pelanggan" required autofocus readonly value="{{ old('nama_pelanggan',$orderhdr->nama_lengkap) }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Tower / Unit') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-8">
                                                            <input type="text" class="form-control @error('no_unit') is-invalid  @enderror" id="no_unit" name="no_unit" required autofocus readonly value="{{ old('no_unit',$orderhdr->unitid) }}">
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('No. Telpon') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-3" >
                                                            <input type="text" class="form-control @error('no_hp') is-invalid  @enderror" id="no_hp" name="no_hp" required autofocus readonly value="{{ old('no_hp') }}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Email') }}</td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="3">
                                                            <input type="text" class="form-control @error('email') is-invalid  @enderror" id="email" name="email" required autofocus readonly value="{{ old('email',$orderhdr->email) }}">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 {{--  End Data Tagihan Pendafraan  --}}
                                @else
                                    {{--  Data tagihan bulanan  --}}
                                    <div class="panel-heading">
                                        Data Tagihan Pelanggan
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div >
                                                    <table >
                                                        <tr>
                                                            <td class="col-md-4"><label class="text-md-start">{{ __('Nomer Tagihan') }}</label></td>
                                                            <td>:</td>
                                                            <td class="col-md-8" colspan="3">
                                                                <input type="text" class="form-control @error('no_formulir') is-invalid  @enderror" id="no_formulir" name="no_formulir" disabled required autofocus value="{{ old('no_formulir',$tagihan->no_tagihan) }}">
                                                                @error('no_formulir')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-md-4"><label class="text-md-start">{{ __('Tanggal Tagihan') }}</label></td>
                                                            <td>:</td>
                                                            <td class="col-md-8" colspan="3">
                                                                <input class="form-control " readonly value="{{ date('d-m-Y', strtotime($tagihan->tgl_tagihan)) }}" name="tgl_tagihan" id="tgl_tagihan">

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-md-4"><label class="text-md-start">{{ __('Nama Pelanggan') }}</td>
                                                            <td>:</td>
                                                            <td class="col-md-8" colspan="3">
                                                                <input type="text" class="form-control @error('nama_pelanggan') is-invalid  @enderror" id="nama_pelanggan" name="nama_pelanggan" required autofocus readonly value="{{ old('nama_pelanggan',$tagihan->nama_lengkap) }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-md-4"><label class="text-md-start">{{ __('Tower / Unit') }}</td>
                                                            <td>:</td>
                                                            <td class="col-md-8">
                                                                <input type="text" class="form-control @error('no_unit') is-invalid  @enderror" id="no_unit" name="no_unit" required autofocus readonly value="{{ old('no_unit',$tagihan->unitid) }}">
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td class="col-md-4"><label class="text-md-start">{{ __('Tanggal Jatuh Tempo') }}</td>
                                                            <td>:</td>
                                                            <td class="col-md-3" >
                                                                <input type="text" class="form-control @error('tgl_jatuh_tempo') is-invalid  @enderror" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo" required autofocus readonly value="{{ date('d-m-Y', strtotime($tagihan->tgl_jatuh_tempo)) }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-md-4"><label class="text-md-start">{{ __('Total Tagihan') }}</td>
                                                            <td>:</td>
                                                            <td class="col-md-8" colspan="3">
                                                                <input type="text" class="form-control @error('total_tagihan') is-invalid  @enderror" id="total_tagihan" name="total_tagihan" required autofocus readonly value="{{ old('total_tagihan',number_format($tagihan->gtot_tagihan)) }}">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{--  End Data Tagihan bulanan  --}}
                                @endif
                                <div class="panel-heading">
                                    Order Layanan
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Jenis Layanan</th>
                                                            <th>Amount</th>
                                                            <th>Tax Amount</th>
                                                            <th>Total</th>
                                                            <th>Nominal Bayar</th>
															 @if($id_user == '1' or $id_user =='14' or $id_user=='20')
                                                                <th>Action</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($orderdtl as $data )
                                                            <tr>
                                                                <td>{{ $loop->iteration }}
                                                                    <input type="hidden" name="line_no_{{ $data->cek  }}" value="{{ $data->line_no }}">
                                                                    <input type="hidden" name="layanan_id_{{ $data->cek  }}" value="{{ $data->layanan_id }}">
                                                                    <input type="hidden" name="amount_{{ $data->cek  }}" value="{{ $data->amount }}">
                                                                    <input type="hidden" name="qty_{{ $data->cek  }}" value="{{ $data->qty }}">
                                                                    <input type="hidden" name="diskon_{{ $data->cek  }}" value="{{ $data->diskon }}">
                                                                    <input type="hidden" name="sub_amount_{{ $data->cek  }}" value="{{ $data->subtotal }}">
                                                                    <input type="hidden" name="ppn_amount_{{ $data->cek  }}" value="{{ $data->subtotal*0.11 }}">


                                                                </td>
                                                                <td>{{ $data->title}}
                                                                    @if ($data->sub_title)
                                                                    <br>( {{ $data->sub_title }})
                                                                    @endif</td>
                                                                <td>
                                                                    <div class="text-right">
																	{{--	@if ($data->tipe_bayar=='1')
                                                                        	 number_format($data->subtotal-$data->ppnamount) 
																		{{ number_format($data->subtotal) }}
																		@else
																			{{ number_format($data->amount_order) }}
																		@endif --}}
																		 {{ number_format($data->harga) }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="text-right">
                                                                    {{--    @if ($data->tipe_bayar=='1')
                                                                            {{ number_format($data->ppnamount) }}
																		 @else
                                                                            {{ number_format($data->ppn_amount) }}
                                                                        @endif --}}
																		{{ number_format($data->ppnamount) }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="text-right">
                                                                     {{--     @if ($data->tipe_bayar=='1')
                                                                            {{ number_format($data->subtotal) }}

                                                                        @else
                                                                        	{{ number_format($data->gtot_amount) }}
                                                                        @endif --}}
																		 {{ number_format($data->subtotal) }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    @if ($data->tipe_bayar=="1")

                                                                    <input type="text" class="form-control @error('nominal_bayar') is-invalid  @enderror" id="nominal_bayar_{{ $data->cek }}" name="nominal_bayar_{{ $data->cek}}" autofocus value="{{ old('nominal_bayar',$data->payment_amount) }}">

                                                                    @else
                                                                    <input type="text" class="form-control @error('nominal_bayar') is-invalid  @enderror" id="nominal_bayar_{{ $data->cek }}" name="nominal_bayar_{{ $data->cek}}" autofocus value="{{ old('nominal_bayar',$data->payment_amount) }}">

                                                                    @endif
                                                            @error('nominal_bayar')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                                </td>
																@if($id_user == '1' or $id_user =='14' or $id_user=='20')
                                                                    <td>
                                                                        <a href="/admin/trx_bayar/hapus_detail?id={{ $data->id }}&line_no={{ $data->line_no }}&layanan_id={{ $data->layanan_id }}" class="btn btn-danger btn-circle" onclick="return confirm('Are you sure?')" data-toggle="tooltip" data-placement="top" title="{{ 'Hapus '.$data->title}}" >
                                                                            <i class="fa fa-times"></i>
                                                                        </a>

                                                                    </td>

                                                                @endif
                                                            </tr>
                                                            <input type="hidden" name="jmlrow" value="{{ $loop->iteration }}">
                                                        @endforeach

                                                        <tr>

                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="col-lg-5">
                                                {{--  <button type="submit" class="btn btn-primary" name="hitung" id="hitung">Hitung Total</button>  --}}
                                                <button type="submit" class="btn btn-primary" name="simpan" id="simpan">Simpan Pembayaran</button>
                                                </div><div class="col-lg-5">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row (nested) -->
                                </div>

                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                    </div>

                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const tgl_bayar = document.querySelector('#tgl_bayar');
    const nomer_bayar = document.querySelector('#nomer_bayar');
    const no_bayar = document.querySelector('#no_bayar');
    
    function previewImage(){
        const image = document.querySelector('#image');
        const imgPreview = document.querySelector('.img-preview');

        imgPreview.style.display = 'block';

        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);

        oFReader.onload = function(oFREvent){
            imgPreview.src = oFREvent.target.result;
        }

    }
</script>

@endsection
