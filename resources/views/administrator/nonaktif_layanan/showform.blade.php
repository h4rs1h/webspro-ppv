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
            <div class="panel-heading">
                Data Request Non Aktif Layanan
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-4">
                            <label  class="col-md-1 col-form-label text-md-start">{{ __('Nomor ') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control " readonly value="{{ $hdrdata->no_nonaktif_layanan }}" >

                        </div>
                        <div class="col-md-4">

                            <label  class="col-md-1 col-form-label text-md-end">{{ __('Tanggal ') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control " readonly value="{{ $hdrdata->tgl }}" >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <form method="post" action="/admin/trx_nonaktif/getsimpanupt" class="mb-5" enctype="multipart/form-data">
            @csrf
        <div class="panel panel-default">
            <div class="panel-heading">
                Data Pelanggan Non Aktif
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-4">
                            <label>{{ __('Nomer Transaksi') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control" readonly value="{{ $data->nomer_trx }}" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-4">
                            <label >{{ __('Nama Lengkap') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control" readonly value="{{ $data->nama_lengkap }}" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-4">
                            <label >{{ __('Periode Pemakaian') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control" readonly value="{{ $data->periode_pemakaian }}" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-4">
                            <label >{{ __('Outstanding') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control" readonly value="{{ number_format($data->gtot_tagihan) }}" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-4">
                            <label >{{ __('Jatuh Tempo') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input class="form-control" readonly value="{{ Carbon\Carbon::parse($data->tgl_jatuh_tempo)->format('d-m-Y')  }}" >
                        </div>
                    </div>
                </div>

            </div>
            <div class="panel-heading">
                Update Data Pelanggan
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-4">
                            <label  >{{ __('Request Off') }}</label>
                        </div>
                        <div class="col-md-4">
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <input type="hidden" name="tipe_trx" value="{{ $data->tipe_trx }}">
                            <input type="hidden" name="id_trx" value="{{ $data->id_trx }}">
                            <input class="form-control @error('tgl_reg_off') is-invalid  @enderror" type="text" readonly name="tgl_reg_off" id="tgl_reg_off" value="{{ date('d-m-Y', strtotime($data->tgl_req_off))  }}" >
                            @error('tgl_reg_off')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input class="form-control @error('tgl_reg_off_time') is-invalid  @enderror" type="time" readonly name="tgl_reg_off_time" id="tgl_reg_off_time" value="{{ Carbon\Carbon::parse($data->tgl_req_off)->format('H:i:s')  }}" >
                            @error('tgl_reg_off_time')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-4">
                            <label >{{ __('Action Off') }}</label>
                        </div>
                        <div class="col-md-4">
                            @if (isset($data->tgl_act_off))
                                <input class="form-control @error('tgl_act_off') is-invalid  @enderror"  name="tgl_act_off" id="tgl_act_off" value="{{ old('tgl_act_off',date('d-m-Y',strtotime($data->tgl_act_off))) }}">
                            @else
                                <input class="form-control @error('tgl_act_off') is-invalid  @enderror" type="date" name="tgl_act_off" id="tgl_act_off" >
                            @endif

                            @error('tgl_act_off')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            @if(isset($data->tgl_act_off))
                                <input class="form-control @error('tgl_act_off_time') is-invalid  @enderror" type="time" name="tgl_act_off_time" id="tgl_act_off_time" value="{{ old('tgl_act_off_time',date('H:s', strtotime($data->tgl_act_off))) }}">
                            @else
                                <input class="form-control @error('tgl_act_off_time') is-invalid  @enderror" type="time" name="tgl_act_off_time" id="tgl_act_off_time" >

                            @endif
                            @error('tgl_act_off_time')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-4">
                            <label >{{ __('Request On') }}</label>
                        </div>
                        <div class="col-md-4">
                            @if(isset($data->tgl_req_on))
                                <input class="form-control @error('tgl_reg_on') is-invalid  @enderror"  name="tgl_reg_on" id="tgl_reg_on" value="{{ old('tgl_reg_on',date('d-m-Y', strtotime($data->tgl_req_on))) }}">
                            @else
                                <input class="form-control @error('tgl_reg_on') is-invalid  @enderror" type="date" name="tgl_reg_on" id="tgl_reg_on" >

                            @endif

                            @error('tgl_reg_on')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            @if(isset($data->tgl_req_on))
                                <input class="form-control @error('tgl_reg_on_time') is-invalid  @enderror" type="time" name="tgl_reg_on_time" id="tgl_reg_on_time" value="{{ old('tgl_reg_on_time',date('H:i', strtotime($data->tgl_req_on))) }}">
                            @else
                                <input class="form-control @error('tgl_reg_on_time') is-invalid  @enderror" type="time" name="tgl_reg_on_time" id="tgl_reg_on_time" >

                            @endif

                            @error('tgl_reg_on_time')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-4">
                            <label>{{ __('Action On') }}</label>
                        </div>
                        <div class="col-md-4">
                            @if(isset($data->tgl_act_on))
                                <input class="form-control @error('tgl_act_on') is-invalid  @enderror" name="tgl_act_on" id="tgl_act_on" value="{{ old('tgl_act_on',date('d-m-Y', strtotime($data->tgl_act_on))) }}">
                            @else
                                <input class="form-control @error('tgl_act_on') is-invalid  @enderror" type="date" name="tgl_act_on" id="tgl_act_on" >

                            @endif

                            @error('tgl_act_on')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            @if(isset($data->tgl_act_on))
                                <input class="form-control @error('tgl_act_on_time') is-invalid  @enderror" type="time" name="tgl_act_on_time" id="tgl_act_on_time" value="{{ old('tgl_act_on_time',date('H:i', strtotime($data->tgl_act_on))) }}">
                            @else
                                <input class="form-control @error('tgl_act_on_time') is-invalid  @enderror" type="time" name="tgl_act_on_time" id="tgl_act_on_time" >

                            @endif


                            @error('tgl_act_on_time')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary" name="simpan" id="simpan">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>


@endsection
