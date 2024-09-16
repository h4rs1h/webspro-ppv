@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="col-lg-12">
        <h1 class="page-header">{{ $title }}</h1>
        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="/admin/trx_aktivasi/{{ $id_aktivasi }}" class="mb-5" enctype="multipart/form-data" role="form">
                    @csrf
                    @method('put')
                    <div class="penel panel-default">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Data Pemesana Pelanggan
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="col-md-3">
                                                    <label for="no_pendaftaran" class="text-md-start">{{ __('Nomor Pendaftaran') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="hidden" value="{{ $order->trx_order_id }}" name="trx_order_id">
                                                    <input type="hidden" value="{{ $id_aktivasi }}" name="id">
													 <input type="hidden" name="layanan_id" value="{{ $order->layanan_id_int }}">
                                                    <input class="form-control" value="{{ $order->no_formulir }}" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="no_pelanggan" >{{ __('Nomor Pelanggan') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" value="{{ $order->no_pelanggan }}" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="nama_pelanggan" >{{ __('Nama Pelanggan') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input class="form-control" value="{{ $order->nama_lengkap }}" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="tower" >{{ __('Tower / Unit') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input class="form-control" value="{{ $order->unitid }}" readonly>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    @if ($jns_aktivasi=='int')
                                    <input type="hidden" name="jenis_aktivasi" value="1">
                                    <div class="panel-heading">
                                        Aktivasi Perangkat Router
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="col-md-3">
                                                    <label for="tanggal_aktivasi_int" >{{ __('Tanggal Aktivasi') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="date" class="form-control @error('tanggal_aktivasi_int') is-invalid  @enderror" id="tanggal_aktivasi_int" name="tanggal_aktivasi_int" required autofocus value="{{ old('tanggal_aktivasi_int',$order->tgl_aktivasi_router) }}">
                                                    @error('tanggal_aktivasi_int')
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }} </strong>
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="nomor_router" >{{ __('Nomer Router (SN)') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control @error('nomor_router') is-invalid  @enderror" id="nomor_router" name="nomor_router" required autofocus value="{{ old('nomor_router',$order->no_router) }}">
                                                    @error('nomor_router')
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }} </strong>
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="jml_router" >{{ __('Jumlah Router') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="number" class="form-control @error('jml_router') is-invalid  @enderror" id="jml_router" name="jml_router" required autofocus value="{{ old('jml_router',$order->jml_perangkat_router) }}">
                                                        @error('jml_router')
                                                        <div class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }} </strong>
                                                        </div>
                                                        @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="denda_router" >{{ __('Denda Hilang/Rusak') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control @error('denda_router') is-invalid  @enderror" id="denda_router" name="denda_router" required autofocus value="{{ old('denda_router',$order->denda_rusak_hilang_router) }}">
                                                        @error('denda_router')
                                                        <div class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }} </strong>
                                                        </div>
                                                        @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="img-preview" >{{ __('Upload Tanda Terima Alat') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="hidden" name="oldImage" value="{{ $order->images_router }}">
                                                    @if ($order->images_router)
                                                        <img src="{{ asset('laravel/storage/'.$order->images_router) }}" class="img-preview img-fluid mb-3 col-sm-5 d-block">
                                                    @else
                                                        <img class="img-preview img-fluid mb-3 col-sm-5">
                                                    @endif

                                                    <input class="form-control  @error('image') is-invalid  @enderror" type="file" id="image" name="image" onchange="previewImage()">
                                                    @error('image')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="nama_penerima" >{{ __('Nama Penerima') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control @error('nama_penerima') is-invalid  @enderror" id="nama_penerima" name="nama_penerima" required autofocus value="{{ old('nama_penerima',$order->nama_penerima_router) }}">
                                                            @error('nama_penerima')
                                                            <div class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }} </strong>
                                                            </div>
                                                            @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="nama_teknisi" >{{ __('Nama Teknisi') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control @error('nama_teknisi') is-invalid  @enderror" id="nama_teknisi" name="nama_teknisi" required autofocus value="{{ old('nama_teknisi',$order->nama_teknisi_router) }}">
                                                    @error('nama_teknisi')
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }} </strong>
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-heading">
                                        Aktivasi Perangkat Free STB
                                    </div>
                                    <input type="hidden" name="jenis_aktivasi_free_stb" value="4">
                                    <input type="hidden" name="id_aktivasi_free_stb" value="{{ $order->id_aktivasi_free_stb }}">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="col-md-3">
                                                    <label for="tanggal_aktivasi_free_stb" >{{ __('Tanggal Aktivasi') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="date" class="form-control @error('tanggal_aktivasi_free_stb') is-invalid  @enderror" id="tanggal_aktivasi_free_stb" name="tanggal_aktivasi_free_stb" required autofocus value="{{ old('tanggal_aktivasi_free_stb',$order->tgl_aktivasi_free_stb) }}">
                                                    @error('tanggal_aktivasi_free_stb')
                                                        <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }} </strong>
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="nomor_free_stb" >{{ __('Nomer STB') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control @error('nomor_free_stb') is-invalid  @enderror" id="nomor_free_stb" name="nomor_free_stb" required autofocus value="{{ old('nomor_free_stb',$order->no_free_stb) }}">
                                                        @error('nomor_free_stb')
                                                        <div class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }} </strong>
                                                        </div>
                                                        @enderror

                                                </div>
                                                <div class="col-md-3">
                                                    <label for="jml_free_stb" >{{ __('Jumlah STB') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="number" class="form-control @error('jml_free_stb') is-invalid  @enderror" id="jml_free_stb" name="jml_free_stb" required autofocus value="{{ old('jml_free_stb',1) }}">
                                                        @error('jml_free_stb')
                                                        <div class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }} </strong>
                                                        </div>
                                                        @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="denda_free_stb" >{{ __('Denda Hilang/Rusak') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control @error('denda_free_stb') is-invalid  @enderror" id="denda_free_stb" name="denda_free_stb" required autofocus value="{{ old('denda_free_stb',1000000) }}">
                                                        @error('denda_free_stb')
                                                        <div class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }} </strong>
                                                        </div>
                                                        @enderror

                                                </div>
                                                <div class="col-md-3">
                                                    <label for="image_free_stb" class="form-label">{{ __('Upload Tanda Terima Alat STB') }} </label><br>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="hidden" name="oldImage_free_stb" value="{{ $order->images_free_stb }}">
                                                    @if ($order->images_free_stb)
                                                        <img src="{{ asset('laravel/storage/'.$order->images_free_stb) }}" class="img-preview-free-stb img-fluid mb-3 col-sm-5 d-block">
                                                    @else
                                                        <img class="img-preview-free-stb img-fluid mb-3 col-sm-5">
                                                    @endif
                                                    {{--  <img class="img-preview-stb img-fluid mb-3 col-sm-5">  --}}
                                                    <input class="form-control  @error('image_free_stb') is-invalid  @enderror" type="file" id="image_free_stb" name="image_free_stb" onchange="previewImagefreeStb()">
                                                    @error('image_free_stb')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror

                                                </div>
                                                <div class="col-md-3">
                                                    <label for="nama_penerima_free_stb" >{{ __('Nama Penerima') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control @error('nama_penerima_free_stb') is-invalid  @enderror" id="nama_penerima_free_stb" name="nama_penerima_free_stb" required autofocus value="{{ old('nama_penerima_free_stb',$order->nama_penerima_free_stb) }}">
                                                            @error('nama_penerima_free_stb')
                                                            <div class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }} </strong>
                                                            </div>
                                                            @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="nama_teknisi_free_stb" >{{ __('Nama Teknisi') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control @error('nama_teknisi_free_stb') is-invalid  @enderror" id="nama_teknisi_free_stb" name="nama_teknisi_free_stb" required autofocus value="{{ old('nama_teknisi_free_stb',$order->nama_teknisi_free_stb) }}">
                                                    @error('nama_teknisi_free_stb')
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }} </strong>
                                                    </div>
                                                    @enderror
                                                </div>
                                                {{--  tv  --}}

                                            </div>
                                        </div>
                                    </div>
                                    @elseif ($jns_aktivasi=='tv')
                                    <input type="hidden" name="jenis_aktivasi" value="2">
                                    <div class="panel-heading">
                                        Aktivasi STB
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="col-md-3">
                                                    <label for="tanggal_aktivasi_tv" >{{ __('Tanggal Aktivasi') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="date" class="form-control @error('tanggal_aktivasi_tv') is-invalid  @enderror" id="tanggal_aktivasi_tv" name="tanggal_aktivasi_tv" required autofocus value="{{ old('tanggal_aktivasi_tv',$order->tgl_aktivasi_stb) }}">
                                                    @error('tanggal_aktivasi_tv')
                                                        <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }} </strong>
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="nomor_stb" >{{ __('Nomer STB') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control @error('nomor_stb') is-invalid  @enderror" id="nomor_stb" name="nomor_stb" required autofocus value="{{ old('nomor_stb',$order->no_stb) }}">
                                                        @error('nomor_stb')
                                                        <div class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }} </strong>
                                                        </div>
                                                        @enderror

                                                </div>
                                                <div class="col-md-3">
                                                    <label for="jml_stb" >{{ __('Jumlah STB') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="number" class="form-control @error('jml_stb') is-invalid  @enderror" id="jml_stb" name="jml_stb" required autofocus value="{{ old('jml_stb',$order->jml_perangkat_stb) }}">
                                                        @error('jml_stb')
                                                        <div class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }} </strong>
                                                        </div>
                                                        @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="denda_stb" >{{ __('Denda Hilang/Rusak') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control @error('denda_stb') is-invalid  @enderror" id="denda_stb" name="denda_stb" required autofocus value="{{ old('denda_stb',$order->denda_rusak_hilang_stb) }}">
                                                        @error('denda_stb')
                                                        <div class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }} </strong>
                                                        </div>
                                                        @enderror

                                                </div>
                                                <div class="col-md-3">
                                                    <label for="image_stb" class="form-label">{{ __('Upload Tanda Terima Alat STB') }} </label><br>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="hidden" name="oldImage_stb" value="{{ $order->images_stb }}">
                                                    @if ($order->images_stb)
                                                        <img src="{{ asset('laravel/storage/'.$order->images_stb) }}" class="img-preview-stb img-fluid mb-3 col-sm-5 d-block">
                                                    @else
                                                        <img class="img-preview-stb img-fluid mb-3 col-sm-5">
                                                    @endif
                                                    {{--  <img class="img-preview-stb img-fluid mb-3 col-sm-5">  --}}
                                                    <input class="form-control  @error('image_stb') is-invalid  @enderror" type="file" id="image_stb" name="image_stb" onchange="previewImageStb()">
                                                    @error('image_stb')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror

                                                </div>
                                                <div class="col-md-3">
                                                    <label for="nama_penerima" >{{ __('Nama Penerima') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control @error('nama_penerima') is-invalid  @enderror" id="nama_penerima" name="nama_penerima" required autofocus value="{{ old('nama_penerima',$order->nama_penerima_stb) }}">
                                                            @error('nama_penerima')
                                                            <div class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }} </strong>
                                                            </div>
                                                            @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="nama_teknisi" >{{ __('Nama Teknisi') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control @error('nama_teknisi') is-invalid  @enderror" id="nama_teknisi" name="nama_teknisi" required autofocus value="{{ old('nama_teknisi',$order->nama_teknisi_stb) }}">
                                                    @error('nama_teknisi')
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }} </strong>
                                                    </div>
                                                    @enderror
                                                </div>
                                                {{--  tv  --}}

                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="panel-heading">
                                        Aktivasi Telepon
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="col-md-3">
                                                    <input type="hidden" name="jenis_aktivasi" value="3">
                                                    <label for="tanggal_aktivasi_telepon" >{{ __('Tanggal Aktivasi') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="date" class="form-control @error('tanggal_aktivasi_telepon') is-invalid  @enderror" id="tanggal_aktivasi_telepon" name="tanggal_aktivasi_telepon" required autofocus value="{{ old('tanggal_aktivasi_telepon',$order->tgl_aktivasi) }}">
                                                    @error('tanggal_aktivasi_telepon')
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }} </strong>
                                                    </div>
                                                    @enderror

                                                </div>
                                                <div class="col-md-3">
                                                    <label for="nama_penerima" >{{ __('Nama Penerima') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control @error('nama_penerima') is-invalid  @enderror" id="nama_penerima" name="nama_penerima" required autofocus value="{{ old('nama_penerima',$order->nama_penerima) }}">
                                                            @error('nama_penerima')
                                                            <div class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }} </strong>
                                                            </div>
                                                            @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="nama_teknisi" >{{ __('Nama Teknisi') }}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control @error('nama_teknisi') is-invalid  @enderror" id="nama_teknisi" name="nama_teknisi" required autofocus value="{{ old('nama_teknisi',$order->nama_teknisi) }}">
                                                    @error('nama_teknisi')
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }} </strong>
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin Simpan Data?')">Update Aktivasi
                                                    @if ($jns_aktivasi=='int')
                                                        Internet
                                                    @elseif ($jns_aktivasi=='tv')
                                                        TV
                                                    @else
                                                        Telepon
                                                    @endif
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

    document.addEventListener('trix-file-accept', function(e){
        e.preventDefault();
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
    function previewImagefreeStb(){
        const image = document.querySelector('#image_free_stb');
        const imgPreview = document.querySelector('.img-preview-free-stb');

        imgPreview.style.display = 'block';

        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);

        oFReader.onload = function(oFREvent){
            imgPreview.src = oFREvent.target.result;
        }

    }
    function previewImageStb(){
        const image = document.querySelector('#image_stb');
        const imgPreview = document.querySelector('.img-preview-stb');

        imgPreview.style.display = 'block';

        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);

        oFReader.onload = function(oFREvent){
            imgPreview.src = oFREvent.target.result;
        }

    }
</script>

@endsection
