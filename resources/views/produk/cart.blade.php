@extends('layouts.main')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3   border-bottom">
    <h1 class="h2">List Order </h1>
</div>
@if (session()->has('success'))
    <div class="alert alert-success col-lg-8" role="alert">
        {{ session('success') }}
    </div>
@endif
@if (session()->has('danger'))
    <div class="alert alert-danger col-lg-8" role="alert">
        {{ session('danger') }}
    </div>
@endif

<div class="table-responsive col-lg-8">
    @if (!$isEmpty)

    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th scope="col"># </th>
            <th scope="col">Jenis Layanan</th>
            <th scope="col">Jumlah</th>
            <th scope="col">Harga</th>
            <th scope="col">Total</th>
            <th scope="col">Aksi</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($cartItems as $item)
            <tr @if ($loop->iteration%2 != 0 )
                    class="success"
                @else
                    class="info"
                @endif >
                <td>{{ $loop->iteration }}</td>
                <td><a href="#"><p class="mb-2 md:ml-4">{{ $item->name  }}</p></a></td>
                <td >
                    <form action="{{ route('cart.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item->id}}" >
                    <input type="number" name="quantity" value="{{ $item->quantity }}" style='width:3em'  />
                    {{--  <button type="submit" class="px-2 pb-2 ml-2 text-white bg-blue-500">update</button>  --}}
                    <button type="submit" class="btn btn-info btn-circle"><i class="fa fa-edit"></i></button>
                    </form>
                </td>
                <td>
                    <span class="text-sm font-medium lg:text-base">
                    </span>
                    Rp. {{ number_format($item->price) }}
                </td>
                <td>
                    <span class="text-sm font-medium lg:text-base">
                    </span>
                    Rp. {{ number_format($item->price * $item->quantity) }}
                </td>
                <td>
                    <form action="{{ route('cart.remove') }}" method="POST">
                        @csrf

                    <button class="btn btn-danger btn-circle" onclick="return confirm('Are you sure?')"><i class="fa fa-times" ></i></button>
                    <input type="hidden" value="{{ $item->id }}" name="id">
                </td>
                </form>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" style="text-align:center"><strong>Subtotal</strong></td>
                <td><strong>Rp. {{ number_format(Cart::getTotal()) }}</strong></td>
                <td>&nbsp;</td>
            </tr>

        </tbody>
    </table>
    <table>
        <tbody>
            <tr>
                <td>

                    {{--  <a href="/layanan/Internet" class="btn btn-primary">Tambah Layanan Internet</a>&nbsp;  --}}
                    <a href="/layanan/tv" class="btn btn-primary">Tambah Layanan TV</a>&nbsp;
                    <a href="/layanan/telephony" class="btn btn-primary">Tambah Layanan Telephony</a>
                </td>
                <td>&nbsp;&nbsp;</td>
                <td>

                    <form action="{{ route('cart.clear') }}" method="POST" >
                    @csrf
                    <button class="btn btn-danger">Remove All Cart</button>&nbsp;&nbsp;
                    </form>

                </td>
                <td>
                    <form action="{{ route('cart.confirm') }}" method="POST">
                        @csrf
                        <button class="btn btn-primary">Pesanan Layanan</button>&nbsp;
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
    @else
        <div >
            <h4 class="h4">Silahkan pilih layanan yang sesuai untuk Anda pesan, klik menu pesan sebelah kanan</h4><br>
            <a href="/layanan/Internet" class="btn btn-primary">Tambah Layanan Internet</a>&nbsp;
            <a href="/layanan/tv" class="btn btn-primary">Tambah Layanan TV</a>&nbsp;
            <a href="/layanan/telephony" class="btn btn-primary">Tambah Layanan Telephony</a>
        </div>
    @endif
</div>
@endsection
