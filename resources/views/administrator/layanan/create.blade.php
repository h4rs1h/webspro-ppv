@extends('layouts.main')

@section('content')

<div class="col-lg-12">
    <h1 class="page-header">Tambah Produk / Layanan</h1>
    <div class="row">
        <div class="col-lg-8">
            <div class="penel panel-default">
                <div class="penel-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <form method="post" action="/admin/layanan" class="mb-5" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="title" >{{ __('Nama Produk / Layanan') }}</label>
                                        <input type="text" class="form-control @error('title') is-invalid  @enderror" id="title" name="title" required autofocus value="{{ old('title') }}">
                                        @error('title')
                                            <div class="invalid-feedback"  role="alert">
                                                <strong>
                                                    {{ $message }}
                                                </strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="slug" >{{ __('Slug') }}</label>
                                        <input type="text" class="form-control @error('slug') is-invalid  @enderror" id="slug" name="slug" readonly required value="{{ old('slug') }}">
                                        @error('slug')
                                            <div class="invalid-feedback"  role="alert">
                                                <strong>
                                                    {{ $message }}
                                                </strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="name">{{ __('Jenis Layanan') }}</label>
                                        <input type="text" class="form-control @error('jenis_layanan') is-invalid  @enderror" id="jenis_layanan" name="jenis_layanan" required autofocus value="{{ old('jenis_layanan') }}">
                                        @error('jenis_layanan')
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="spead" >{{ __('Spead') }} </label>

                                        <input type="text" class="form-control @error('spead') is-invalid  @enderror" id="spead" name="spead" required autofocus value="{{ old('spead') }}">
                                        @error('spead')
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </div>
                                        @enderror

                                </div>
                                <div class="form-group">
                                    <label for="benefit" >{{ __('Benefit') }}</label>

                                        <input type="hidden" id="benefit" name="benefit" value="{{ old('benefit') }}">
                                        <trix-editor input="benefit"></trix-editor>
                                        @error('benefit')
                                            <div class="invalid-feedback" role="alert">
                                                <strong> {{ $message }} </strong>
                                            </div>
                                        @enderror

                                </div>
                                <div class="form-group">
                                    <label for="harga" >{{ __('Harga') }}</label>

                                        <input type="text" class="form-control @error('harga') is-invalid  @enderror" id="harga" name="harga" required autofocus value="{{ old('harga') }}">
                                        @error('harga')
                                            <div class="invalid-feedback" role="alert">
                                                <strong> {{ $message }}</strong>
                                            </div>
                                        @enderror

                                </div>
                                <div class="form-group">

                                        <button type="submit" class="btn btn-primary">Simpan</button>

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
    const title = document.querySelector('#title');
    const slug = document.querySelector('#slug');

    title.addEventListener('change', function(){
        fetch('/admin/produks/checkSlug?title=' + title.value)
        .then(response => response.json())
        .then(data=> slug.value = data.slug)
    });

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
