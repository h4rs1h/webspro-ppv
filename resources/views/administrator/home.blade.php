@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h2 class="page-header">Welcome Back {{ Auth::user()->name }}, Dashboard Administrator </h2><br>
        </div>
    </div>
	<div class="row">
		<div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading ">
                   <strong>Reminder Proforma Invoice</strong>
                </div>
                <div class="panel-body">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12">
                                @if ($dtl_rm->jml_jt_tempo_1>0)
                                <div class="col-lg-3 col-md-6">
                                    <div class="panel panel-green">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-wifi fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div class="huge">{{ $dtl_rm->jml_jt_tempo_1 }}</div>
                                                    <div>2 Hari Lagi Jatuh Tempo </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="/admin/trx_invoice/get?action=remind&awal=-2&akhir=-1">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                                <div class="clearfix"></div>
                                            </div>
                                        </a>

                                    </div>
                                </div>

                                @endif
                                @if ($dtl_rm->jml_jt_tempo_2>0)
                                <div class="col-lg-3 col-md-6">
                                    <div class="panel panel-red">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-wifi fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div class="huge">{{ $dtl_rm->jml_jt_tempo_2 }}</div>
                                                    <div>Jatuh Tempo 0-10 Hari</div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="/admin/trx_invoice/get?action=remind&awal=0&akhir=10">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                @endif
                                @if ($dtl_rm->jml_jt_tempo_3>0)
                                <div class="col-lg-3 col-md-6">
                                    <div class="panel panel-red">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-television fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div class="huge">{{ $dtl_rm->jml_jt_tempo_3 }}</div>
                                                    <div>Jatuh Tempo 11-30 Hari</div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="/admin/trx_invoice/get?action=remind&awal=11&akhir=30">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                @endif
                                @if ($dtl_rm->jml_jt_tempo_4>0)
                                <div class="col-lg-3 col-md-6">
                                    <div class="panel panel-red">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-dollar fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div class="huge">{{ $dtl_rm->jml_jt_tempo_4 }}</div>
                                                    <div>Jatuh Tempo > 30 Hari</div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="/admin/trx_invoice/get?action=remind&awal=31&akhir=200">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading ">
					<strong>Pelanggan</strong>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="col-lg-12">
                            @foreach ($dtl_plg as $dt )
                            <div class="col-lg-3 col-md-6">
                                @if ($loop->iteration%2==1)
                                <div class="panel panel-primary">
                                @else
                                <div class="panel panel-green">
                                @endif

                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-users fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge">{{ $dt->jml }}</div>
                                                @if ($dt->status_layanan=='1')
                                                    <div>{{ $dt->ket_status_layanan }}</div>
                                                @elseif ($dt->status_layanan=='2')
                                                        <div>{{ $dt->ket_status_layanan }}</div>
                                                        {{--  <div>Suspend</div>  --}}
                                                @elseif ($dt->status_layanan=='3')
                                                        <div>{{ $dt->ket_status_layanan }}</div>
                                                        {{--  <div>Non Aktif</div>  --}}
                                                @elseif ($dt->status_layanan=='4')
                                                        <div>{{ $dt->ket_status_layanan }}</div>
                                                        {{--  <div>Berhenti Berlangganan</div>  --}}
                                                @elseif ($dt->status_layanan=='5')
                                                        <div>{{ $dt->ket_status_layanan }}</div>
                                                        {{--  <div>Status Batal Aktivasi</div>  --}}
                                                @else
                                                <div>{{ $dt->ket_status_layanan }}</div>
                                                @endif
                                                <div>  </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="/admin/pelanggan/get?action=status&layanan={{ $dt->status_layanan }}">
                                        <div class="panel-footer">
                                            <span class="pull-left">View Details</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            @endforeach

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading ">
                    <strong>Invoice Pelanggan</strong>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="col-lg-12">
                            @foreach ($dtl_inv as $dt )
                            <div class="col-lg-3 col-md-6">
                                @if ($loop->iteration%2==1)
                                <div class="panel panel-primary">
                                @else
                                <div class="panel panel-green">
                                @endif

                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-dollar fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge">{{ $dt->jml }}</div>
                                                    <div>{{ substr($dt->bulan,4,2).'-'.substr($dt->bulan,0,4) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="/admin/trx_invoice/get?action=show&periode={{ $dt->bulan }}">
                                        <div class="panel-footer">
                                            <span class="pull-left">View Details</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            @endforeach

                    </div>
                </div>
            </div>
        </div>
		<div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading ">
                    <strong>Data Aktivasi Pelanggan</strong>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="col-lg-12">	
						<div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-wifi fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">{{ $jum_router }}</div>
                                <div>Aktivasi Router</div>
                            </div>
                        </div>
                    </div>
                    <a href="/admin/trx_aktivasi">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
						<div class="col-lg-3 col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-television fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">{{ $jum_stb_free }}</div>
                                <div>Aktivasi STB Free</div>
                            </div>
                        </div>
                    </div>
                    <a href="/admin/trx_aktivasi">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-television fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">{{ $jum_stb }}</div>
                                <div>Aktivasi STB Berbayar</div>
                            </div>
                        </div>
                    </div>
                    <a href="/admin/trx_aktivasi">
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
	{{-- <div class="row">
        <div class="col-lg-12">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-users fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">{{ $jum_daftar }}</div>
                                <div>Pendaftaran</div>
                            </div>
                        </div>
                    </div>
                    <a href="/admin/trx_order">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div> 
            
			
             <div class="col-lg-3 col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-dollar fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">{{ $jum_tagihan }}</div>
                                <div>Invoice Bulan Ini</div>
                            </div>
                        </div>
                    </div>
                    <a href="/admin/trx_invoice">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>--}}
</div>
@endsection
