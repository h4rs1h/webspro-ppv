@extends('layouts.main')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $title }}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading ">
                    Formulir Ubah NPWP Pelanggan
                </div>
                <div class="penel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="post" action="/admin/pelanggan/upnpwp" class="mb-5" enctype="multipart/form-data">
                            {{--  @method('put')  --}}
                            @csrf
                            <input type="hidden" name="pelangganid" id="pelangganid" value="{{$pelanggan->id}}">
                            <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="no_pelanggan" >{{ __('No Unit Pelanggan') }}</label>
                                            <input type="text" class="form-control @error('no_pelanggan') is-invalid  @enderror" id="no_pelanggan" name="no_pelanggan" required autofocus value="{{ old('no_pelanggan',$pelanggan->sub_tower.'/'.$pelanggan->lantai.'/'.$pelanggan->nomer_unit) }}" readonly>
                                            @error('no_pelanggan')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_lengkap" >{{ __('Nama Lengkap') }}</label>
                                            <input type="text" class="form-control @error('nama_lengkap') is-invalid  @enderror" id="nama_lengkap" name="nama_lengkap" required autofocus value="{{ old('nama_lengkap',$pelanggan->nama_lengkap) }}" readonly>
                                            @error('nama_lengkap')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email" >{{ __('Alamat Email') }}</label>
                                            <input type="text" class="form-control @error('email') is-invalid  @enderror" id="email" name="email" required autofocus value="{{ old('email',$pelanggan->email) }}" readonly>
                                            @error('email')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="npwp" >{{ __('No NPWP') }}</label>
                                            <input type="text" class="form-control @error('npwp') is-invalid  @enderror" id="npwp" name="npwp" required autofocus value="{{ old('npwp',$pelanggan->npwp) }}" >
                                            @error('npwp')
                                                <div class="alert alert-danger"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_npwp" >{{ __('Nama NPWP') }}</label>
                                            <input type="text" class="form-control @error('nama_npwp') is-invalid  @enderror" id="nama_npwp" name="nama_npwp" required autofocus value="{{ old('nama_npwp',$pelanggan->nama_npwp) }}" >
                                            @error('nama_npwp')
                                                <div class="alert alert-danger"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div><div class="form-group">
                                        <label for="alamat_npwp" >{{ __('Alamat NPWP') }}</label>
                                            <input type="text" class="form-control @error('alamat_npwp') is-invalid  @enderror" id="alamat_npwp" name="alamat_npwp" required autofocus value="{{ old('alamat_npwp',$pelanggan->alamat_npwp) }}" >
                                            @error('alamat_npwp')
                                                <div class="alert alert-danger"  role="alert">
                                                    <strong >
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin Update Data?')">Update NPWP Pelanggan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
</script>

@endsection
