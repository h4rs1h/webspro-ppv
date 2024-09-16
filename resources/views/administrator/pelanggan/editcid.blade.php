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
                    Formulir Ubah CID Pelanggan
                </div>
                <div class="penel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="post" action="/admin/pelanggan/upcid" class="mb-5" enctype="multipart/form-data">
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
                                        <label for="cid" >{{ __('CID Pelanggan') }}</label>
                                            <input type="text" class="form-control @error('cid') is-invalid  @enderror" id="cid" name="cid" required autofocus value="{{ old('cid',$pelanggan->cid) }}" >
                                            @error('cid')
                                                <div class="alert alert-danger"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>


                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin Update Data?')">Update CID Pelanggan</button>
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
