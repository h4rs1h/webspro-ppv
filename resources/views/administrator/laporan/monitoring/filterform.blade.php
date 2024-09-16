@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $title }}</h1>
        </div>
    </div>
    @if (session()->has('success'))
        <div class="alert alert-success col-lg-12" role="alert">
        {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-3">
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Form Filter
                    </div>
                    <form method="post" action="/admin/monitoring/getdata" class="mb-5" enctype="multipart/form-data">
                        @csrf
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
								@if ($rpt=='aging_inv')
                                    <input type="hidden" name="rpt" id="rpt" value="{{ $rpt }}">
                                    <div class="form-group">
                                        <label for="tgl_aging" >{{ __('Tanggal Aging') }}</label>
                                            <input type="date" class="form-control @error('tgl_aging') is-invalid  @enderror" id="tgl_aging" name="tgl_aging" required autofocus value="{{ old('tgl_aging') }}">
                                            @error('tgl_aging')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror

                                    </div>
                                    <div class="form-group">
                                        <label>Format Aging</label>

                                        <select class="form-control" name="opt_aging" id="opt_aging">
                                            <option value="" >-- Pilih --</option>
                                            @foreach ($opt_aging as $trn)
                                                @if (old('opt_aging') == $trn['id']))
                                                    <option value="{{ $trn['id'] }}" selected>{{ $trn['name'] }}</option>
                                                @else
                                                <option value="{{ $trn['id'] }}" >{{ $trn['name'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                            @error('opt_aging')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
                                @else
                                    <input type="hidden" name="rpt" id="rpt" value="{{ $rpt }}">
                                    <div class="form-group">
                                        <label>Tanggal Awal</label>

                                        <select class="form-control @error('awal_hari') is-invalid  @enderror" name="awal_hari" id="awal_hari">

                                            @for ($i=0;$i<=$opt_hari;$i++)
                                                @if (old('awal_hari') == $i))
                                                    <option value="{{ $i }}" selected>{{ $i }}</option>
                                                @else
                                                    <option value="{{ $i }}" >{{ $i }}</option>
                                                @endif
                                            @endfor
                                        </select>
                                            @error('awal_hari')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal Akhir</label>
                                        <select class="form-control @error('akhir_hari') is-invalid  @enderror" name="akhir_hari" id="akhir_hari">

                                            @for ($i=0;$i<=$opt_hari;$i++)
                                                @if (old('akhir_hari') == $i))
                                                    <option value="{{ $i }}" selected>{{ $i }}</option>
                                                @else
                                                    <option value="{{ $i }}" >{{ $i }}</option>
                                                @endif
                                            @endfor
                                        </select>
                                            @error('akhir_hari')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Tahun</label>
                                        <select class="form-control @error('tahun') is-invalid  @enderror" name="tahun" id="tahun">

                                            @for ($i=2022;$i<=$opt_tahun;$i++)
                                                @if (old('tahun') == $i))
                                                    <option value="{{ $i }}" selected>{{ $i }}</option>
                                                @else
                                                    <option value="{{ $i }}" >{{ $i }}</option>
                                                @endif
                                            @endfor
                                        </select>
                                            @error('tahun')
                                                <div class="invalid-feedback"  role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </div>
                                            @enderror
                                    </div>
						@endif
                                    <button type="submit" class="btn btn-default">Submit Button</button>
                                    <button type="reset" class="btn btn-default">Reset Button</button>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                    </div>
                    </form>
                    <!-- /.panel-body -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
