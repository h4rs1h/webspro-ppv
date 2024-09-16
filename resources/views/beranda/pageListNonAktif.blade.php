@extends('layouts.app')

@section('content')
{{--  carousel  --}}
<div class="container">
    @if (session()->has('success'))
        <div class="alert alert-success col-lg-12" role="alert">
        {{ session('success') }}
        </div>
    @endif
    @if (session()->has('danger'))
        <div class="alert alert-danger col-lg-12" role="alert">
        {{ session('danger') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Data Pelanggan Request Non Aktif
                </div>
                <div class="row">
                    <div class="col-md-2 ">
                        <select name="export_btn" id="export_btn" class="form-control btn-primary">
                            <option value="0"><strong>Download</strong></option>
                            <option value="1">CSV</option>
                            <option value="2">Excel</option>
                            <option value="3">PDF</option>
                            <option value="4">Print</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">CID</th>
                                    <th scope="col">Nama Lengkap</th>
                                    <th scope="col">Unit ID</th>
                                    <th scope="col">Request</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($isi_data as $data )
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $data->cid }}</td>
                                        <td>{{ $data->nama_lengkap }}</td>
                                        <td>{{ $data->sub_tower."/".$data->lantai."/".$data->nomer_unit }}</td>
                                        <td>Penghentian layanan internet sementara tolong dibantu TT</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
