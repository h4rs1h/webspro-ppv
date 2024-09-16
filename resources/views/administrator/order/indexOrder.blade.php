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
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="/admin/pelanggan/create" class="btn btn-primary mb-3">Tambah Pelanggan</a>
                    <a href="/admin/trx_order/create" class="btn btn-primary mb-3">Tambah Order</a>
                    <a href="/admin/trx_order/upgrade/create" class="btn btn-success mb-3">Upgrade Layanan</a>
                    <a href="/admin/trx_order/downgrade/create" class="btn btn-info mb-3">Downgrade Layanan</a>
                    <a href="/admin/trx_order/cuti/create" class="btn btn-warning mb-3">Cuti Layanan</a>
                    <a href="/admin/trx_order/stop/create" class="btn btn-danger mb-3">Stop Layanan</a>
                    <a href="/admin/trx_order/stop_lgn/create" class="btn btn-danger mb-3">Berhenti Berlanganan</a>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading ">
                    <strong>Order Layanan Pelanggan</strong>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="col-lg-12">
                            @foreach ($order as $dt )
                            <div class="col-lg-3 col-md-6">
                                 @if ($dt->tipe_order=="1")
                                    <div class="panel panel-primary">
                                @elseif ($dt->tipe_order=="2")
                                    <div class="panel panel-green">
                                @elseif ($dt->tipe_order=="3")
                                    <div class="panel panel-info">
                                @elseif ($dt->tipe_order=="4")
                                    <div class="panel panel-yellow">
                                @elseif ($dt->tipe_order=="5")
                                    <div class="panel panel-red">
                                @elseif ($dt->tipe_order=="6")
                                    <div class="panel panel-red">
                                @endif

                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                @if ($dt->tipe_order=="1")
                                                    <i class="fa fa-cubes fa-5x"></i>
                                                @elseif ($dt->tipe_order=="2")
                                                    <i class="fa fa-plus-square fa-5x"></i>
                                                @elseif ($dt->tipe_order=="3")
                                                    <i class="fa fa-minus-square fa-5x"></i>
                                                @elseif ($dt->tipe_order=="4")
                                                    <i class="fa fa-stop-circle fa-5x"></i>
                                                @elseif ($dt->tipe_order=="5")
                                                    <i class="fa fa-dot-circle-o fa-5x"></i>
                                                @elseif ($dt->tipe_order=="6")
                                                    <i class="fa fa-minus-circle fa-5x"></i>
                                                @endif


                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge">{{ $dt->jml }}</div>
                                                    <div>
                                                        @if ($dt->tipe_order=="1")
                                                            Registrasi Layanan
                                                        @elseif ($dt->tipe_order=="2")
                                                            Upgrade Layanan
                                                        @elseif ($dt->tipe_order=="3")
                                                            Downgrade Layanan
                                                        @elseif ($dt->tipe_order=="4")
                                                            Cuti Layanan
                                                        @elseif ($dt->tipe_order=="5")
                                                            Stop Layanan
                                                        @elseif ($dt->tipe_order=="6")
                                                            Berhenti Berlangganan
                                                        @endif
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($dt->tipe_order=="1")
                                        <a href="/admin/trx_order/get?action=show&tipe_order={{ $dt->tipe_order }}">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    @elseif ($dt->tipe_order=="2")
                                        <a href="/admin/trx_order/get?action=show&tipe_order={{ $dt->tipe_order }}">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    @elseif ($dt->tipe_order=="3")
                                        <a href="/admin/trx_order/get?action=show&tipe_order={{ $dt->tipe_order }}">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    @elseif ($dt->tipe_order=="4")
                                        <a href="/admin/trx_order/get?action=show&tipe_order={{ $dt->tipe_order }}">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    @elseif ($dt->tipe_order=="5")
                                        <a href="/admin/trx_order/get?action=show&tipe_order={{ $dt->tipe_order }}">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    @elseif ($dt->tipe_order=="6")
                                        <a href="/admin/trx_order/get?action=show&tipe_order={{ $dt->tipe_order }}">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    @endif

                                </div>
                            </div>
                            @endforeach
							<div class="col-lg-3 col-md-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-cubes fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge">{{ $termin }}</div>
                                                    <div>
                                                        Cicilan Pelanggan
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="/admin/trx_order/get?action=show_termin">
                                        <div class="panel-footer">
                                            <span class="pull-left">View Details</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@endsection
