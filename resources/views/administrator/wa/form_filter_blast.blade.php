@extends('layouts.main')

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $title }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading ">
                    Formulir Kirim WA Blast
                </div>
                <div class="penel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="post" action="/admin/wa/kirim_blast" class="mb-5" enctype="multipart/form-data">
                            @csrf
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="isi_pesan" >{{ __('Template Isi Pesan Blast WA') }}</label>
                                        <textarea class="form-control @error('isi_pesan') is-invalid  @enderror" id="isi_pesan" name="isi_pesan" required autofocus value="{{ old('isi_pesan') }}" rows="8" cols="120"></textarea>
                                        @error('isi_pesan')
                                            <div class="invalid-feedback"  role="alert">
                                                <strong>
                                                    {{ $message }}
                                                </strong>
                                            </div>
                                        @enderror

                                </div>
                                <div class="form-group">
                                    <label for="tower" >{{ __('Tower') }}</label>
                                        <select class="form-control" name="tower">
                                            <option value="0" >All</option>
                                            @foreach ($tower as $twr)
                                            @if (old('tower') == $twr->name)
                                                <option value="{{ $twr->name }}" selected>{{ $twr->name }}</option>
                                            @else
                                            <option value="{{ $twr->name }}" >{{ $twr->name }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        {{--  <input type="text" class="form-control @error('tower') is-invalid  @enderror" id="tower" name="tower" required autofocus value="{{ old('tower') }}">  --}}
                                        @error('tower')
                                            <div class="invalid-feedback" role="alert">
                                                <strong> {{ $message }}</strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="sub_tower" >{{ __('Sub Tower') }}</label>
                                    <select class="form-control" name="sub_tower">
                                        <option value="0" >All</option>
                                        @foreach ($subtower as $twr)
                                        @if (old('sub_tower') == $twr->name)
                                            <option value="{{ $twr->name }}" selected>{{ $twr->name }}</option>
                                        @else
                                        <option value="{{ $twr->name }}" >{{ $twr->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                        {{--  <input type="text" class="form-control @error('sub_tower') is-invalid  @enderror" id="sub_tower" name="sub_tower" required autofocus value="{{ old('sub_tower') }}">  --}}
                                        @error('sub_tower')
                                            <div class="invalid-feedback" role="alert">
                                                <strong> {{ $message }}</strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="lantai1" >{{ __('Dari Lantai') }}</label>
                                    <select class="form-control" name="lantai1">
                                        <option value="0" >All</option>
                                        @foreach ($lantai as $lt)
                                        @if (old('lantai1') == $lt->name)
                                            <option value="{{ $lt->name }}" selected>{{ $lt->name }}</option>
                                        @else
                                        <option value="{{ $lt->name }}" >{{ $lt->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                        {{--  <input type="text" class="form-control @error('lantai') is-invalid  @enderror" id="lantai" name="lantai" required autofocus value="{{ old('lantai') }}">  --}}
                                        @error('lantai1')
                                            <div class="invalid-feedback" role="alert">
                                                <strong> {{ $message }}</strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <label for="lantai2" >{{ __('Sampai Lantai') }}</label>
                                    <select class="form-control" name="lantai2">
                                        <option value="0" >All</option>
                                        @foreach ($lantai as $lt)
                                        @if (old('lantai2') == $lt->name)
                                            <option value="{{ $lt->name }}" selected>{{ $lt->name }}</option>
                                        @else
                                        <option value="{{ $lt->name }}" >{{ $lt->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                        {{--  <input type="text" class="form-control @error('lantai') is-invalid  @enderror" id="lantai" name="lantai" required autofocus value="{{ old('lantai') }}">  --}}
                                        @error('lantai2')
                                            <div class="invalid-feedback" role="alert">
                                                <strong> {{ $message }}</strong>
                                            </div>
                                        @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin Kirim Pesan?')">Kirim Pesan Blast</button>
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

@endsection
