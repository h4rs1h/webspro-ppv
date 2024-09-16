@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Harga Layanan {{ $heading }}</h1>
        </div>
    </div>
    <div class="row">
        @foreach ($produk as $data)
        <div class="col-lg-4">
            <div class="panel panel-primary">
                <div class="panel-heading text-center">
                    <h3>{{ $data->title }}</h3>
                </div>
                <div class="panel-body">
                    <div class="text-center"><i>Spead Up To</i></div>

                    <h1 class="text-center"><strong>{{ $data->spead }}</strong></h1>
                    <?=$data->benefit?>
                    <table style="margin-left: auto; margin-right: auto;">
                        <tbody>
                            <tr>
                                <td rowspan="2"><h1 class="text-center"><strong><sup>Rp </sup>{{ substr($data->harga,0,3) }}</strong></h1></td>
                                <td style="vertical-align: bottom;" ><strong>.{{ substr($data->harga,3,3) }}</strong></td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;"><strong>/bulan</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="panel-footer text-center ">
                    <form action="{{ route('cart.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{ $data->id }}" name="id">
                        <input type="hidden" value="{{ $data->title }}" name="name">
                        <input type="hidden" value="{{ $data->harga }}" name="price">
                        <input type="hidden" value="{{ $data->jenis_layanan }}" name="layanan">
                        {{--  <button type="submit" class="btn btn-default" name="quantity" value="3">Order 3 bln</button>
                        <button type="submit" class="btn btn-primary" name="quantity" value="6">Order 6 bln</button>
                        <button type="submit" class="btn btn-warning" name="quantity" value="12">Order 12 bln</button>  --}}

                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection
