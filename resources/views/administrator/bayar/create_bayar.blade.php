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
                <form method="post" action="/admin/trx_bayar/simpan" class="mb-5" enctype="multipart/form-data">
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
                                                            <input type="hidden" id="no_bayar" name="no_bayar">
                                                            <input type="hidden" id="no_order" name="no_order" value="{{ $orderhdr->no_order }}">
															<input type="hidden" id="tipe_order" name="tipe_order" value="{{ $orderhdr->tipe_order }}">
                                                            <input type="hidden" id="pelanggan_id" name="pelanggan_id" value="{{ $orderhdr->pelanggan_id }}">
                                                            <input type="text" class="form-control @error('nomer_bayar') is-invalid  @enderror" id="nomer_bayar" name="nomer_bayar" disabled required autofocus value="{{ old('nomer_bayar') }}">
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
                                                                    @if (old('metode_bayar') == $trn['id']))
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
                                                            <input type="date" class="form-control " value="{{ date('d-m-Y', strtotime(now())) }}" name="tgl_bayar" id="tgl_bayar">
                                                        </td>
                                                        <td class="col-md-1"><label class="text-md-start">{{ __('Status  ') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-3">
                                                            <select class="form-control @error('status_bayar') is-invalid  @enderror" name="status_bayar" id="status_bayar">
                                                                <option value="" >Pilih Status Bayar</option>
                                                                @foreach ($status_bayar as $trn)
                                                                    @if (old('status_bayar') == $trn['id']))
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
                                                            <input type="text" class="form-control @error('amount') is-invalid  @enderror" id="amount" name="amount" required autofocus value="{{ old('amount') }}">
                                                            @error('amount')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </td>
                                                        <td class="col-md-1"><label class="text-md-start">{{ __('Catatan') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-5">
                                                            <input type="text" class="form-control @error('catatan') is-invalid  @enderror" id="catatan" name="catatan" autofocus value="{{ old('catatan') }}">
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
                                                        <td class="col-md-3" >
                                                            <input type="number" class="form-control @error('biaya_transaksi') is-invalid  @enderror" id="biaya_transaksi" name="biaya_transaksi" required autofocus value="{{ old('biaya_transaksi') }}">
                                                            @error('biaya_transaksi')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </td>
                                                        <td class="col-md-1"></td>
                                                        <td></td>
                                                        <td class="col-md-5">

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="col-md-4"><label class="text-md-start">{{ __('Upload Bukti Bayar') }}</label></td>
                                                        <td>:</td>
                                                        <td class="col-md-8" colspan="4">
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
                                                            <input type="text" class="form-control @error('no_hp') is-invalid  @enderror" id="no_hp" name="no_hp" required autofocus readonly value="{{ old('no_hp',$orderhdr->nomer_hp) }}">
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
                                                                    <input type="hidden" name="sub_amount_{{ $data->cek  }}" value="{{ $data->sub_amount }}">
                                                                    <input type="hidden" name="ppn_amount_{{ $data->cek  }}" value="{{ $data->tax_amount }}">


                                                                </td>
                                                                <td>{{ $data->title}}
                                                                    @if ($data->sub_title)
                                                                    <br>( {{ $data->sub_title }})
                                                                    @endif</td>
                                                                <td>
                                                                    <div class="text-right">
                                                                        {{ number_format($data->sub_amount-$data->tax_amount) }}

                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="text-right">
                                                                        {{ number_format($data->tax_amount) }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="text-right">
                                                                        {{ number_format($data->sub_amount) }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control @error('nominal_bayar') is-invalid  @enderror" id="nominal_bayar_{{ $data->cek }}" name="nominal_bayar_{{ $data->cek}}" autofocus value="{{ old('nominal_bayar') }}">
                                                            @error('nominal_bayar')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                                </td>
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
    tgl_bayar.addEventListener('change', function(){
        fetch('/admin/trx_bayar/checkno_bayar?tgl_bayar=' + tgl_bayar.value)
        .then(response => response.json())
        .then(data=> [(nomer_bayar.value = data.no_bayar),
        (no_bayar.value = data.no_bayar)])
    });
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
