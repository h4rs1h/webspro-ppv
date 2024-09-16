@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Formulir Registrasi') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Nama Lengkap') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Alamat Email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Konfirmasi Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="no_unit" class="col-md-4 col-form-label text-md-end">{{ __('Unit (Contoh: AAA/L1/16)') }}</label>

                            <div class="col-md-6">
                                <input id="no_unit" type="text" class="form-control @error('no_unit') is-invalid @enderror"  name="no_unit" value="{{ old('no_unit') }}" required >

                                @error('no_unit')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            </div>
                        </div>
                        <div class="row mb-0">
                            <label for="is_pemilik" class="col-md-4 col-form-label text-md-end">{{ __('Status Kepemilikan') }}</label>

                            <div class="col-md-6">
                                <input type="radio" class="form-control-radio  @error('is_pemilik') is-invalid  @enderror" name="is_pemilik" value="{{ old('is_pemilik','0') }}" id="is_pemilik" >&nbsp;&nbsp;Pemilik&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="form-control-radio  @error('is_pemilik') is-invalid  @enderror" name="is_pemilik" value="{{ old('is_pemilik','1') }}"  id="is_pemilik"  >&nbsp;&nbsp;Penyewa
                                @error('is_pemilik')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="no_identitas" class="col-md-4 col-form-label text-md-end">{{ __('No. Identitas') }}</label>

                            <div class="col-md-6">
                                <input id="no_identitas" type="number" class="form-control @error('no_identitas') is-invalid @enderror"  name="no_identitas" value="{{ old('no_identitas') }}" required >

                                @error('no_identitas')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="alamat" class="col-md-4 col-form-label text-md-end">{{ __('Alamat Sesuai KTP') }}</label>

                            <div class="col-md-6">
                                <input id="alamat" type="text" class="form-control @error('alamat') is-invalid @enderror"  name="alamat" value="{{ old('alamat') }}" required >

                                @error('alamat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="no_telpon" class="col-md-4 col-form-label text-md-end">{{ __('Nomor Telpon') }}</label>

                            <div class="col-md-6">
                                <input id="no_telpon" type="number" class="form-control @error('no_telpon') is-invalid @enderror"  name="no_telpon" value="{{ old('no_telpon') }}" required >

                                @error('no_telpon')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="no_hp" class="col-md-4 col-form-label text-md-end">{{ __('No. HP (Whatsapp)') }}</label>

                            <div class="col-md-6">
                                <input id="no_hp" type="number" class="form-control @error('no_hp') is-invalid @enderror"  name="no_hp" value="{{ old('no_hp') }}" required >

                                @error('no_hp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Registrasi') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
