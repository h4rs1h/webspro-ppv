@extends('layouts.main')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $title }}</h1>
        </div>
    </div>

<div class="row">
    <div class="col-lg-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                Rekap Data Non Aktif
            </div>

            <!-- /.panel-heading -->

            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Jumlah Unit Permintaan Non Aktif</th>
                                <th scope="col">Jumlah Non Aktif</th>
                                <th scope="col">Jumlah Unit Diaktifkan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $dt )
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ Carbon\Carbon::parse($dt->tanggal)->format('d-m-Y')  }}
                                    </td>
                                    <td class="text-center">
                                       {{ $dt->ReqNonaktif }}
                                    </td>
                                    <td class="text-center">
                                       {{ $dt->Nonaktif }}
                                    </td>
                                    <td class="text-center">
                                       {{ $dt->aktif }}
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>


@endsection
