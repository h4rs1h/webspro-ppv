@extends('layouts.main')

@section('content')

<div class="col-lg-12">
    <h1 class="page-header">Ubah Data Pelanggan</h1>
    <div class="row">
        <div class="col-lg-12">
            <div class="penel panel-default">
                <div class="panel-heading">
                    Formulir Tambah Pelanggan
                </div>
                <div class="penel-body">
                    <div class="row">
                        <form method="post" action="/admin/pelanggan/{{ $pelanggan->id }}" class="mb-5" enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nama_lengkap" >{{ __('Nama Lengkap') }}</label>
                                        <input type="text" class="form-control @error('nama_lengkap') is-invalid  @enderror" id="nama_lengkap" name="nama_lengkap" required autofocus value="{{ old('nama_lengkap',$pelanggan->nama_lengkap) }}">
                                        @error('nama_lengkap')
                                            <div class="alert alert-danger"  role="alert">
                                                <strong>
                                                    {{ $message }}
                                                </strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email" >{{ __('Alamat Email') }}</label>
                                        <input type="text" class="form-control @error('email') is-invalid  @enderror" id="email" name="email" required autofocus value="{{ old('email',$pelanggan->email) }}">
                                        @error('email')
                                            <div class="alert alert-danger"  role="alert">
                                                <strong>
                                                    {{ $message }}
                                                </strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="tempat_lahir" >{{ __('Tempat Lahir') }}</label>
                                        <input type="text" class="form-control @error('tempat_lahir') is-invalid  @enderror" id="tempat_lahir" name="tempat_lahir" required value="{{ old('tempat_lahir',$pelanggan->tempat_lahir) }}">
                                        @error('tempat_lahir')
                                            <div class="alert alert-danger"  role="alert">
                                                <strong>
                                                    {{ $message }}
                                                </strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_lahir">{{ __('Tanggal Lahir') }}</label>
                                        <input type="date" class="form-control @error('tanggal_lahir') is-invalid  @enderror" id="tanggal_lahir" name="tanggal_lahir" required autofocus value="{{ old('tanggal_lahir',$pelanggan->tanggal_lahir) }}">
                                        @error('tanggal_lahir')
                                            <div class="alert alert-danger" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="jenis_kelamin" >{{ __('Jenis Kelamin') }} </label>
                                    <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                    @foreach ($jkel as $trn)
                                        @if (old('jenis_kelamin',$pelanggan->jenis_kelamin) == $trn['id']))
                                            <option value="{{ $trn['id'] }}" selected>{{ $trn['name'] }}</option>
                                        @else
                                        <option value="{{ $trn['id'] }}" >{{ $trn['name'] }}</option>
                                        @endif
                                    @endforeach

                                    </select>
                                        {{--  <input type="text" class="form-control @error('jenis_kelamin') is-invalid  @enderror" id="jenis_kelamin" name="jenis_kelamin" required autofocus value="{{ old('jenis_kelamin') }}">  --}}
                                        @error('jenis_kelamin')
                                            <div class="alert alert-danger" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </div>
                                        @enderror

                                </div>
                                <div class="form-group">
                                    <label for="agama" >{{ __('Agama') }}</label>
                                    <select class="form-control @error('agama') is-invalid  @enderror" name="agama" id="agama">
                                    @foreach ($agama as $trn)
                                        @if (old('agama',$pelanggan->agama) == $trn['id']))
                                            <option value="{{ $trn['id'] }}" selected>{{ $trn['name'] }}</option>
                                        @else
                                        <option value="{{ $trn['id'] }}" >{{ $trn['name'] }}</option>
                                        @endif
                                    @endforeach


                                    </select>
                                        {{--  <input type="text" class="form-control @error('agama') is-invalid  @enderror" id="agama" name="agama" required autofocus value="{{ old('agama') }}">  --}}
                                        @error('agama')
                                            <div class="alert alert-danger" role="alert">
                                                <strong> {{ $message }}</strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="nomer_hp" >{{ __('Nomer HP Utama') }}</label>

                                        <input type="number" class="form-control @error('nomer_hp') is-invalid  @enderror" id="nomer_hp" name="nomer_hp" required autofocus value="{{ old('nomer_hp',$pelanggan->nomer_hp) }}">
                                        @error('nomer_hp')
                                            <div class="alert alert-danger" role="alert">
                                                <strong> {{ $message }}</strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="nomer_hp2" >{{ __('Nomer HP ') }}</label>

                                        <input type="number" class="form-control @error('nomer_hp2') is-invalid  @enderror" id="nomer_hp2" name="nomer_hp2" autofocus value="{{ old('nomer_hp2',$pelanggan->nomer_hp2) }}">
                                        @error('nomer_hp2')
                                            <div class="alert alert-danger" role="alert">
                                                <strong> {{ $message }}</strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="pekerjaan" >{{ __('Status Pekerjaan') }}</label>

                                        <input type="text" class="form-control @error('pekerjaan') is-invalid  @enderror" id="pekerjaan" name="pekerjaan" required autofocus value="{{ old('pekerjaan',$pelanggan->pekerjaan) }}">
                                        @error('pekerjaan')
                                            <div class="alert alert-danger" role="alert">
                                                <strong> {{ $message }}</strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="alamat_identitas" >{{ __('Alamat Sesuai (KTP/SIM/PASPORT)') }} </label>

                                        <input type="text" class="form-control @error('alamat_identitas') is-invalid  @enderror" id="alamat_identitas" name="alamat_identitas" required autofocus value="{{ old('alamat_identitas',$pelanggan->alamat_identitas) }}">
                                        @error('alamat_identitas')
                                            <div class="alert alert-danger" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </div>
                                        @enderror

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="identitas" >{{ __('Identitas') }} </label>
                                    <select class="form-control @error('identitas') is-invalid  @enderror" name="identitas" id="identitas">
                                        @foreach ($identitas as $trn)
                                            @if (old('identitas',$pelanggan->identitas) == $trn['id']))
                                                <option value="{{ $trn['id'] }}" selected>{{ $trn['name'] }}</option>
                                            @else
                                            <option value="{{ $trn['id'] }}" >{{ $trn['name'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                        {{--  <input type="text" class="form-control @error('identitas') is-invalid  @enderror" id="identitas" name="identitas" required autofocus value="{{ old('identitas') }}">  --}}
                                        @error('identitas')
                                            <div class="alert alert-danger" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </div>
                                        @enderror

                                </div>
                                <div class="form-group">
                                    <label for="nomer_identitas" >{{ __('Nomer Identitas') }} </label>

                                        <input type="text" class="form-control @error('nomer_identitas') is-invalid  @enderror" id="nomer_identitas" name="nomer_identitas" required autofocus value="{{ old('nomer_identitas',$pelanggan->nomer_identitas) }}">
                                        @error('nomer_identitas')
                                            <div class="alert alert-danger" role="alert">
                                                <strong>{{ $message }} </strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="image" class="form-label">{{ __('Foto Identitas') }} </label><br>
                                    <input type="hidden" name="oldImage" value="{{ $pelanggan->image }}">
                                    @if ($pelanggan->image)
                                        <img src="{{ asset(env('FOLDER_IN_PUBLIC_HTML').'/storage/'.$pelanggan->image) }}" class="img-preview img-fluid mb-3 col-sm-5 d-block">
                                    @else
                                        <img class="img-preview img-fluid mb-3 col-sm-5">
                                    @endif

                                    <input class="form-control  @error('image') is-invalid  @enderror" type="file" id="image" name="image" onchange="previewImage()">
                                    @error('image')
                                    <div class="alert alert-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="tower" >{{ __('Tower') }}</label>
                                        <select class="form-control" name="tower">
                                            @foreach ($tower as $twr)
                                            @if (old('tower',$pelanggan->tower) == $twr->name)
                                                <option value="{{ $twr->name }}" selected>{{ $twr->name }}</option>
                                            @else
                                            <option value="{{ $twr->name }}" >{{ $twr->name }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        {{--  <input type="text" class="form-control @error('tower') is-invalid  @enderror" id="tower" name="tower" required autofocus value="{{ old('tower') }}">  --}}
                                        @error('tower')
                                            <div class="alert alert-danger" role="alert">
                                                <strong> {{ $message }}</strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="sub_tower" >{{ __('Sub Tower') }}</label>
                                    <select class="form-control" name="sub_tower">
                                        @foreach ($subtower as $twr)
                                        @if (old('sub_tower',$pelanggan->sub_tower) == $twr->name)
                                            <option value="{{ $twr->name }}" selected>{{ $twr->name }}</option>
                                        @else
                                        <option value="{{ $twr->name }}" >{{ $twr->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                        {{--  <input type="text" class="form-control @error('sub_tower') is-invalid  @enderror" id="sub_tower" name="sub_tower" required autofocus value="{{ old('sub_tower') }}">  --}}
                                        @error('sub_tower')
                                            <div class="alert alert-danger" role="alert">
                                                <strong> {{ $message }}</strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="lantai" >{{ __('Lantai') }}</label>
                                    <select class="form-control" name="lantai">
                                        @foreach ($lantai as $lt)
                                        @if (old('lantai',$pelanggan->lantai) == $lt->name)
                                            <option value="{{ $lt->name }}" selected>{{ $lt->name }}</option>
                                        @else
                                        <option value="{{ $lt->name }}" >{{ $lt->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                        {{--  <input type="text" class="form-control @error('lantai') is-invalid  @enderror" id="lantai" name="lantai" required autofocus value="{{ old('lantai') }}">  --}}
                                        @error('lantai')
                                            <div class="alert alert-danger" role="alert">
                                                <strong> {{ $message }}</strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="nomer_unit" >{{ __('Nomor Unit') }}</label>

                                        <input type="text" class="form-control @error('nomer_unit') is-invalid  @enderror" id="nomer_unit" name="nomer_unit" required autofocus value="{{ old('nomer_unit',$pelanggan->nomer_unit) }}">
                                        @error('nomer_unit')
                                            <div class="alert alert-danger" role="alert">
                                                <strong> {{ $message }}</strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="status" >{{ __('Status') }}</label>
                                    <select class="form-control" name="status">
                                        <option value="" >-- Pilih --</option>
                                        @foreach ($status_unit as $st)
                                        @if (old('status',$pelanggan->status) == $st['id'])
                                            <option value="{{ $st['id'] }}" selected>{{ $st['name'] }}</option>
                                        @else
                                        <option value="{{ $st['id'] }}" >{{ $st['name'] }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                        @error('status')
                                            <div class="alert alert-danger" role="alert">
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
