@extends('layouts.main')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $title }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading ">

                </div>
                <div class="penel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="post" action="/admin/trx_invoice" class="mb-5" enctype="multipart/form-data">
                            @csrf
                            <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="Unit_id" >{{ __('Unit ID') }}</label>
                                            <input type="text" class="form-control @error('unitid') is-invalid  @enderror" id="unitid" name="unitid" required autofocus value="{{ old('unitid') }}">
                                            @error('unitid')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin Simpan Data?')">Proses Buat Invoice </button>
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
