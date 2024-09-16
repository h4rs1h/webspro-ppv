@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $title }}</h1>
        </div>
    </div>

<form method="post" action="/admin/trx_nonaktif/getsimpanrequest" class="mb-5" enctype="multipart/form-data">
    @csrf
<div class="row">
    <div class="col-lg-12">

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
                            <input type="text" class="form-control " readonly value="{{ $nomerRequest }}" name="nomer">

                        </div>
                        <div class="col-md-4">

                            <label  class="col-md-1 col-form-label text-md-end">{{ __('Tanggal ') }}</label>
                        </div>
                        <div class="col-md-8">
                            <input type="datetime" class="form-control " readonly value="{{ date('d-m-Y H:i:s', strtotime(now())) }}" name="tgl">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Data Transaksi
            </div>

            <!-- /.panel-heading -->

            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nomor Trx</th>
                                <th scope="col">Nama</th>
                                <th scope="col">UnitId</th>
                                <th scope="col">Jatuh Tempo</th>
                                <th scope="col">Periode Pemakaian</th>
                                <th scope="col">Jumlah Hari Telat</th>
                                <th scope="col">Nilai Tagihan </th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $dt )
                                <tr>
                                    <td>{{ $loop->iteration }}

                                    </td>
                                    <td><input type="checkbox" name="cek[]" value="{{ $dt->no_invoice  }}" id="id-{{ $loop->iteration }}"> {{ 'Inv-'.$dt->no_invoice }} </td>
                                    <td>{{ $dt->nama_lengkap }}</td>
                                    <td>{{ $dt->unitid }}</td>
                                    <td>{{ date('d-m-Y', strtotime($dt->tgl_jatuh_tempo)) }}</td>
                                    <td>{{ $dt->periode_pemakaian }}</td>
                                    <td>{{ $dt->exp }}</td>
                                    <td>{{ $dt->outstanding }}</td>

                                </tr>
                               
                                    @if (isset($loop->iteration))
                                    <?php
                                        $no=$loop->iteration;
                                    ?>
                                @else
                                    <?php
                                        $no=0;
                                    ?>
                                @endif
                                
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="class="panel-body"">
                        <?php
						if(isset($no)) {
                            echo "<input type=radio name=pilih onclick=' for (i=1;i<=$no;i++){document.getElementById(\"id-\"+i).checked=true;}'> Check ALL";
                            echo "<input type=radio name=pilih onclick=' for (i=1;i<=$no;i++){document.getElementById(\"id-\"+i).checked=false;}'> Un Check ALL";
						}
                        ?>

                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="class="panel-body"">
                    <button type="submit" class="btn btn-primary" name="simpan" id="simpan">Submit</button>

                    </div>

                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
</form>

@endsection
