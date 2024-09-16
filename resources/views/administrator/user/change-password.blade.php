@extends('layouts.main')

@section('content')
    <div class="col-lg-12">
        <h1 class="page-header">{{ $title }}</h1>
        <div class="row">
            <div class="col-lg-6">
                <div class="penel panel-default">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Formulir Ubah User
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">

                                            <form method="post" action="{{ route('postChangePassword') }}" class="mb-5"
                                                enctype="multipart/form-data">
                                                @method('post')
                                                @csrf
                                                <div class="form-group">
                                                    <input type="hidden" name="id" value="{{ $dtUser->id }}">
                                                    <label for="name">{{ __('Nama Lengkap') }}</label>
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid  @enderror"
                                                        id="name" name="name" required autofocus
                                                        value="{{ old('name', $dtUser->name) }}">
                                                    @error('name')
                                                        <div class="invalid-feedback" role="alert">
                                                            <strong>
                                                                {{ $message }}
                                                            </strong>
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">{{ __('Alamat Email') }}</label>
                                                    <input type="text"
                                                        class="form-control @error('email') is-invalid  @enderror"
                                                        id="email" name="email" required autofocus
                                                        value="{{ old('email', $dtUser->email) }}">
                                                    @error('email')
                                                        <div class="invalid-feedback" role="alert">
                                                            <strong>
                                                                {{ $message }}
                                                            </strong>
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="role">{{ __('Group Level') }}</label>
                                                    <select class="form-control @error('role') is-invalid  @enderror"
                                                        name="role" id="role">
                                                        <option value="">Pilih Group Level</option>
                                                        @foreach ($grlevel as $trn)
                                                            @if (old('role', $dtUser->role) == $trn['id'])
                                                                )
                                                                <option value="{{ $trn['id'] }}" selected>
                                                                    {{ $trn['name'] }}</option>
                                                            @else
                                                                <option value="{{ $trn['id'] }}">{{ $trn['name'] }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    @error('role')
                                                        <div class="invalid-feedback" role="alert">
                                                            <strong>
                                                                {{ $message }}
                                                            </strong>
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="new_password" class="form-label">New Password</label>
                                                    <input type="password" class="form-control" id="new_password"
                                                        name="new_password">
                                                    @error('new_password')
                                                        <div class="invalid-feedback" role="alert">
                                                            <strong>
                                                                {{ $message }}
                                                            </strong>
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="new_password_confirmation" class="form-label">Confirm New
                                                        Password</label>
                                                    <input type="password" class="form-control"
                                                        id="new_password_confirmation" name="new_password_confirmation">
                                                    @error('new_password_confirmation')
                                                        <div class="invalid-feedback" role="alert">
                                                            <strong>
                                                                {{ $message }}
                                                            </strong>
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
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
            </div>
        </div>
    </div>


    <script>
        const title = document.querySelector('#title');
        const slug = document.querySelector('#slug');

        title.addEventListener('change', function() {
            fetch('/admin/produks/checkSlug?title=' + title.value)
                .then(response => response.json())
                .then(data => slug.value = data.slug)
        });

        document.addEventListener('trix-file-accept', function(e) {
            e.preventDefault();
        });

        function previewImage() {
            const image = document.querySelector('#image');
            const imgPreview = document.querySelector('.img-preview');

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }

        }
    </script>
@endsection
