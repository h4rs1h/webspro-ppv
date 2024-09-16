<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ViewOutstdPendaftaran;

class AdminLaporanAkuntingController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){

        $a = DB::table('vGetInvPendaftaran_dict')->select('no_formulir')->count();
		$f = DB::table('vGetRptNonAktifLayanan')->count();
        $b = DB::table('trx_order')->whereDate('tgl_order','=',Carbon::yesterday()->toDateString())->where('tipe_order','1')->count();
        $c = DB::table('vGetOuststandingDaftar')->where('tipe_order','1')->whereNotIn('statuslayanan',['4','5','6'])->count();
        $d = ViewOutstdPendaftaran::where('tgl_order',Carbon::today()->toDateString())->count();
		$e = DB::table('vGetInvTagihan')->select('no_invoice')->count();
        $role = Auth::user()->role;
        return view('administrator.laporan.akunting.index',[
            'jum_daftar' => $a,
            'jum_hari_ini' => $b,
            'jum_out_daftar' => $c,
            'jum_bayar_daftar' => $d,
			'jum_daftar_inv' => $e,
			'jum_non_aktif' => $f,
            'level' => $role,
            'title' => 'Laporan Billing Akunting',
        ]);
    }

    public function getPelanggan (Request $request){
         $role = Auth::user()->role;
		$status_out ='';
        if($request->id=="pelanggan"){
            $title = "Laporan Tagihan Pendaftaran Pelanggan";
			 $show_rpt = [
                ['id' =>'1','name' =>'Summary'],
                ['id' =>'2','name' =>'Detail'],
                ];
            $status_out = [
                ['id' =>'0','name' =>'All'],
                ['id' =>'1','name' =>'Aktif'],
                ['id' =>'2','name' =>'Suspend'],
                ['id' =>'3','name' =>'Non Aktif'],
                ['id' =>'4','name' =>'Berhenti Langganan'],
                ['id' =>'5','name' =>'Batal Aktivasi'],
                ['id' =>'6','name' =>'Belum Aktivasi'],
                ];
        }
        elseif($request->id=="invoice"){
            $title = "Laporan Tagihan Invoice Bulanan";
			$show_rpt = [
                ['id' =>'1','name' =>'Summary'],
                ['id' =>'2','name' =>'Detail'],
                ];
            $status_out = [
                ['id' =>'0','name' =>'All'],
                ['id' =>'1','name' =>'Aktif'],
                ['id' =>'2','name' =>'Suspend'],
                ['id' =>'3','name' =>'Non Aktif'],
                ['id' =>'4','name' =>'Berhenti Langganan'],
                ['id' =>'5','name' =>'Batal Aktivasi'],
                ['id' =>'6','name' =>'Belum Aktivasi'],
                ];
        }
        elseif($request->id=="salesday"){
            $title = "Sales Report Per Day";
			$status_out= '';
            $show_rpt = '';
        }
		elseif($request->id=="out-daftar"){
			 $a = DB::table('vGetOuststandingDaftar')->where('tipe_order','1')->whereNotIn('statuslayanan',['4','5','6'])->get();
           //  $title = "Laporan Outstanding Pendaftaran Pelanggan";
             $status_out = [
                ['id' =>'1','name' =>'Belum Ada Pembayaran'],
                ['id' =>'2','name' =>'Pembayaran Masih Kurang'],
                ['id' =>'3','name' =>'All Outstanding'],
                ];
			 $show_rpt = '';
			return view('administrator.laporan.akunting.lap_getoutstd_daftar2',[
                'level' => $role,
                'title' => 'Laporan Outstanding Pendaftaran Pelanggan',
                'sub_title' => '',
                'msg' => '',
                'pelanggan' => $a
            ]);
        }elseif($request->id=="nonaktif"){
            $title = "Laporan Non Aktif Pelanggan";

            $status_out = [
                ['id' =>'1','name' =>'All'],
                ['id' =>'2','name' =>'Nama pelanggan'],
                ['id' =>'3','name' =>'Unit ID'],
				['id' =>'4','name' =>'Pelanggan Masih Suspend'],
                ];
            $show_rpt = [];
        }elseif($request->id=="kwitansi"){
            $title = "Laporan Pembayaran Pelanggan";

            $status_out = [
                ['id' =>'1','name' =>'All'],
                ['id' =>'2','name' =>'Nama pelanggan'],
                ['id' =>'3','name' =>'Unit ID'],
                ['id' =>'4','name' =>'Tanggal Mutasi Bank'],
                ];
            $show_rpt = [];
        }
        return view('administrator.laporan.akunting.filterpelanggan',[
            'level' => $role,
            'rpt' => $request->id,
            'title' => $title,
			'opt_status' =>  $status_out,
			 'opt_show_rpt' => $show_rpt
        ]);
    }
    public function getPelanggandata (Request $request){

        // dd($request);
        $awal = $request->tgl_awal;
        $akhir = $request->tgl_akhir;
		 $msg ='';
        $role = Auth::user()->role;
  $idUser = Auth::user()->id;
        //dd($a);
        if($request->rpt=='pelanggan'){
            if($request->status_rpt=="1" and $request->status_out=="0"){
                $a = DB::table('vGetInvPendaftaran_summary2')->whereBetween('tgl_daftar',[$awal,$akhir])->orderBy('pelanggan_id')->get();
                $b = 'lap_getinvpelanggan_sumary';
                $title = 'Summary Laporan Tagihan Order Layanan Pelanggan';
            }elseif($request->status_rpt=="1" and $request->status_out=="1"){
                $a = DB::table('vGetInvPendaftaran_summary2')
                        ->whereBetween('tgl_daftar',[$awal,$akhir])
                        ->where('statuslayanan','1')
                        ->orderBy('pelanggan_id')->get();
                $b = 'lap_getinvpelanggan_sumary';
                $title = 'Summary Laporan Tagihan Order Layanan Pelanggan Aktif';
            }elseif($request->status_rpt=="1" and $request->status_out=="2"){
                $a = DB::table('vGetInvPendaftaran_summary2')
                        ->whereBetween('tgl_daftar',[$awal,$akhir])
                        ->where('statuslayanan','2')
                        ->orderBy('pelanggan_id')->get();
                $b = 'lap_getinvpelanggan_sumary';
                $title = 'Summary Laporan Tagihan Order Layanan Pelanggan Suspend';
            }elseif($request->status_rpt=="1" and $request->status_out=="3"){
                $a = DB::table('vGetInvPendaftaran_summary2')
                        ->whereBetween('tgl_daftar',[$awal,$akhir])
                        ->where('statuslayanan','3')
                        ->orderBy('pelanggan_id')->get();
                $b = 'lap_getinvpelanggan_sumary';
                $title = 'Summary Laporan Tagihan Order Layanan Pelanggan Non Aktif';
            }elseif($request->status_rpt=="1" and $request->status_out=="4"){
                $a = DB::table('vGetInvPendaftaran_summary2')
                        ->whereBetween('tgl_daftar',[$awal,$akhir])
                        ->where('statuslayanan','4')
                        // ->orderBy('nama_lengkap','asc')
                        ->get();
                $b = 'lap_getinvpelanggan_sumary';
                $title = 'Summary Laporan Tagihan Order Layanan Pelanggan Berhenti Langganan';
            }elseif($request->status_rpt=="1" and $request->status_out=="5"){
                $a = DB::table('vGetInvPendaftaran_summary2')
                        ->whereBetween('tgl_daftar',[$awal,$akhir])
                        ->where('statuslayanan','5')
                        ->orderBy('pelanggan_id')->get();
                $b = 'lap_getinvpelanggan_sumary';
                $title = 'Summary Laporan Tagihan Order Layanan Pelanggan Batal Aktivasi';
            }elseif($request->status_rpt=="1" and $request->status_out=="6"){
                $a = DB::table('vGetInvPendaftaran_summary2')
                        ->whereBetween('tgl_daftar',[$awal,$akhir])
                        ->where('statuslayanan','6')
                        ->orderBy('pelanggan_id')->get();
                $b = 'lap_getinvpelanggan_sumary';
                $title = 'Summary Laporan Tagihan Order Layanan Pelanggan Belum Aktivasi';
            }
            elseif($request->status_rpt=="2" and $request->status_out=="0"){
                $a = DB::table('vGetInvPendaftaran_sumdetail')->whereBetween('tgl_daftar',[$awal,$akhir])->get();
                $b = 'lap_getinvpelanggan';
                $title = 'Detail Laporan Tagihan Order Layanan Pelanggan';
            }elseif($request->status_rpt=="2" and $request->status_out=="1"){
                $a = DB::table('vGetInvPendaftaran_sumdetail')
                        ->whereBetween('tgl_daftar',[$awal,$akhir])
                        ->where('statuslayanan','1')
                        ->get();
                $b = 'lap_getinvpelanggan';
                $title = 'Detail Laporan Tagihan Order Layanan Pelanggan Aktif';
            }elseif($request->status_rpt=="2" and $request->status_out=="2"){
                $a = DB::table('vGetInvPendaftaran_sumdetail')
                        ->whereBetween('tgl_daftar',[$awal,$akhir])
                        ->where('statuslayanan','2')
                        ->get();
                $b = 'lap_getinvpelanggan';
                $title = 'Detail Laporan Tagihan Order Layanan Pelanggan Suspend';
            }elseif($request->status_rpt=="2" and $request->status_out=="3"){
                $a = DB::table('vGetInvPendaftaran_sumdetail')
                        ->whereBetween('tgl_daftar',[$awal,$akhir])
                        ->where('statuslayanan','3')
                        ->get();
                $b = 'lap_getinvpelanggan';
                $title = 'Detail Laporan Tagihan Order Layanan Pelanggan Non Aktif';
            }elseif($request->status_rpt=="2" and $request->status_out=="4"){
                $a = DB::table('vGetInvPendaftaran_sumdetail')
                        ->whereBetween('tgl_daftar',[$awal,$akhir])
                        ->where('statuslayanan','4')
                        ->get();
                $b = 'lap_getinvpelanggan';
                $title = 'Detail Laporan Tagihan Order Layanan Pelanggan Berhenti Langganan';
            }elseif($request->status_rpt=="2" and $request->status_out=="5"){
                $a = DB::table('vGetInvPendaftaran_sumdetail')
                        ->whereBetween('tgl_daftar',[$awal,$akhir])
                        ->where('statuslayanan','5')
                        ->get();
                $b = 'lap_getinvpelanggan';
                $title = 'Detail Laporan Tagihan Order Layanan Pelanggan Batal Aktivasi';
            }elseif($request->status_rpt=="2" and $request->status_out=="6"){
                $a = DB::table('vGetInvPendaftaran_sumdetail')
                        ->whereBetween('tgl_daftar',[$awal,$akhir])
                        ->where('statuslayanan','6')
                        ->get();
                $b = 'lap_getinvpelanggan';
                $title = 'Detail Laporan Tagihan Order Layanan Pelanggan Belum Aktivasi';
            }
            return view('administrator.laporan.akunting.'.$b,[
                'level' => $role,
                'title' => $title, // 'Laporan Tagihan Pendaftaran Pelanggan',
                'sub_title' => 'Dari Tanggal: '.$awal.' S/d Tanggal: '.$akhir,
                'pelanggan' => $a
            ]);
        }
        elseif($request->rpt=='invoice'){
            if($request->status_rpt=="1" and $request->status_out=="0"){
                $a = DB::table('vGetInvTagihan_summary')
                        ->whereBetween('tgl_invoice',[$awal,$akhir])
					//	->whereBetween('tgl_jatuh_tempo',[$awal,$akhir])
                        ->orderBy('pelanggan_id')->get();
                $b = 'lap_getinvoice_summary';
                $title = 'Summary Laporan Tagihan Order Layanan Pelanggan';
            }elseif($request->status_rpt=="1" and $request->status_out=="1"){
                $a = DB::table('vGetInvTagihan_summary')
                          ->whereBetween('tgl_invoice',[$awal,$akhir])
					 //	->whereBetween('tgl_jatuh_tempo',[$awal,$akhir])
                        ->where('status_layanan','1')
                        ->orderBy('pelanggan_id')->get();
                $b = 'lap_getinvoice_summary';
                $title = 'Summary Laporan Tagihan Order Layanan Pelanggan Aktif';
            }elseif($request->status_rpt=="1" and $request->status_out=="2"){
                $a = DB::table('vGetInvTagihan_summary')
                           ->whereBetween('tgl_invoice',[$awal,$akhir])
						// ->whereBetween('tgl_jatuh_tempo',[$awal,$akhir])
                        ->where('status_layanan','2')
                        ->orderBy('pelanggan_id')->get();
                $b = 'lap_getinvoice_summary';
                $title = 'Summary Laporan Tagihan Order Layanan Pelanggan Suspend';
            }elseif($request->status_rpt=="1" and $request->status_out=="3"){
                $a = DB::table('vGetInvTagihan_summary')
                           ->whereBetween('tgl_invoice',[$awal,$akhir])
						//->whereBetween('tgl_jatuh_tempo',[$awal,$akhir])
                        ->where('status_layanan','3')
                        ->orderBy('pelanggan_id')->get();
                $b = 'lap_getinvoice_summary';
                $title = 'Summary Laporan Tagihan Order Layanan Pelanggan Non Aktif';
            }elseif($request->status_rpt=="1" and $request->status_out=="4"){
                $a = DB::table('vGetInvTagihan_summary')
                           ->whereBetween('tgl_invoice',[$awal,$akhir])
						//->whereBetween('tgl_jatuh_tempo',[$awal,$akhir])
                        ->where('status_layanan','4')
                        // ->orderBy('nama_lengkap','asc')
                        ->get();
                $b = 'lap_getinvoice_summary';
                $title = 'Summary Laporan Tagihan Order Layanan Pelanggan Berhenti Langganan';
            }elseif($request->status_rpt=="1" and $request->status_out=="5"){
                $a = DB::table('vGetInvTagihan_summary')
                           ->whereBetween('tgl_invoice',[$awal,$akhir])
						//->whereBetween('tgl_jatuh_tempo',[$awal,$akhir])
                        ->where('status_layanan','5')
                        ->orderBy('pelanggan_id')->get();
                $b = 'lap_getinvoice_summary';
                $title = 'Summary Laporan Tagihan Order Layanan Pelanggan Batal Aktivasi';
            }elseif($request->status_rpt=="1" and $request->status_out=="6"){
                $a = DB::table('vGetInvTagihan_summary')
                           ->whereBetween('tgl_invoice',[$awal,$akhir])
						//->whereBetween('tgl_jatuh_tempo',[$awal,$akhir])
                        ->where('status_layanan','6')
                        ->orderBy('pelanggan_id')->get();
                $b = 'lap_getinvoice_summary';
                $title = 'Summary Laporan Tagihan Order Layanan Pelanggan Belum Aktivasi';
            }
            elseif($request->status_rpt=="2" and $request->status_out=="0"){
                $a = DB::table('vGetInvTagihan')
					   ->whereBetween('tgl_invoice',[$awal,$akhir])
						->where('tipe_tagihan','2')
						//->whereBetween('tgl_jatuh_tempo',[$awal,$akhir])
					->get();
                $b = 'lap_getinvoice';
                $title = 'Detail Laporan Tagihan Order Layanan Pelanggan';
            }elseif($request->status_rpt=="2" and $request->status_out=="1"){
                $a = DB::table('vGetInvTagihan')
                           ->whereBetween('tgl_invoice',[$awal,$akhir])
							->where('tipe_tagihan','2')
						//->whereBetween('tgl_jatuh_tempo',[$awal,$akhir])
                        ->where('status_layanan','1')
                        ->get();
                $b = 'lap_getinvoice';
                $title = 'Detail Laporan Tagihan Order Layanan Pelanggan Aktif';
            }elseif($request->status_rpt=="2" and $request->status_out=="2"){
                $a = DB::table('vGetInvTagihan')
                           ->whereBetween('tgl_invoice',[$awal,$akhir])
							->where('tipe_tagihan','2')
						//->whereBetween('tgl_jatuh_tempo',[$awal,$akhir])
                        ->where('status_layanan','2')
                        ->get();
                $b = 'lap_getinvoice';
                $title = 'Detail Laporan Tagihan Order Layanan Pelanggan Suspend';
            }elseif($request->status_rpt=="2" and $request->status_out=="3"){
                $a = DB::table('vGetInvTagihan')
                           ->whereBetween('tgl_invoice',[$awal,$akhir])
							->where('tipe_tagihan','2')
						//->whereBetween('tgl_jatuh_tempo',[$awal,$akhir])
                        ->where('status_layanan','3')
                        ->get();
                $b = 'lap_getinvoice';
                $title = 'Detail Laporan Tagihan Order Layanan Pelanggan Non Aktif';
            }elseif($request->status_rpt=="2" and $request->status_out=="4"){
                $a = DB::table('vGetInvTagihan')
                           ->whereBetween('tgl_invoice',[$awal,$akhir])
							->where('tipe_tagihan','2')
						//->whereBetween('tgl_jatuh_tempo',[$awal,$akhir])
                        ->where('status_layanan','4')
                        ->get();
                $b = 'lap_getinvoice';
                $title = 'Detail Laporan Tagihan Order Layanan Pelanggan Berhenti Langganan';
            }elseif($request->status_rpt=="2" and $request->status_out=="5"){
                $a = DB::table('vGetInvTagihan')
                           ->whereBetween('tgl_invoice',[$awal,$akhir])
							->where('tipe_tagihan','2')
					//	->whereBetween('tgl_jatuh_tempo',[$awal,$akhir])
                        ->where('status_layanan','5')
                        ->get();
                $b = 'lap_getinvoice';
                $title = 'Detail Laporan Tagihan Order Layanan Pelanggan Batal Aktivasi';
            }elseif($request->status_rpt=="2" and $request->status_out=="6"){
                $a = DB::table('vGetInvTagihan')
                           ->whereBetween('tgl_invoice',[$awal,$akhir])
							->where('tipe_tagihan','2')
						//->whereBetween('tgl_jatuh_tempo',[$awal,$akhir])
                        ->where('status_layanan','6')
                        ->get();
                $b = 'lap_getinvoice';
                $title = 'Detail Laporan Tagihan Order Layanan Pelanggan Belum Aktivasi';
            }

            return view('administrator.laporan.akunting.'.$b,[
                'level' => $role,
                'title' => $title, // 'Laporan Tagihan Pendaftaran Pelanggan',
                'sub_title' => 'Dari Tanggal: '.$awal.' S/d Tanggal: '.$akhir,
                'pelanggan' => $a
            ]);


            $a = DB::table('vGetInvTagihan')->whereBetween('tgl_invoice',[$awal,$akhir])->get();


        }
		elseif($request->rpt=='nonaktif'){
          //  dd($request->all());
            $filterby = $request->status_out;
            $inputfilter = $request->input_filter;
            if($filterby=='1'){
                $a = DB::table('vGetRptNonAktifLayanan')->whereBetween('tgl_nonaktif_layanan',[$awal,$akhir])->get();
                $title = 'Laporan Non Aktif Pelanggan';
            }
            elseif($filterby=='2'){
                $a = DB::table('vGetRptNonAktifLayanan')->whereBetween('tgl_nonaktif_layanan',[$awal,$akhir])
                        ->where('nama_lengkap','like','%'.$inputfilter.'%')->get();
                $title = 'Laporan Non Aktif Pelanggan Nama: '.$inputfilter;
            }elseif($filterby=='3'){
                $a = DB::table('vGetRptNonAktifLayanan')->whereBetween('tgl_nonaktif_layanan',[$awal,$akhir])
                        ->where('unitid',$inputfilter)->get();
                $title = 'Laporan Non Aktif Pelanggan Unit: '.$inputfilter;
            }elseif($filterby=='4'){
                $a = DB::table('vGetRptNonAktifLayanan')->whereBetween('tgl_nonaktif_layanan',[$awal,$akhir])
                        ->whereNull('tgl_req_on')->get();
                $title = 'Laporan Non Aktif Pelanggan Status Masih Suspend';
            }
            return view('administrator.laporan.akunting.lap_getnonaktif',[
                'level' => $role,
                'title' => $title,
                'sub_title' => 'Dari Tanggal: '.$awal.' S/d Tanggal: '.$akhir,
                'pelanggan' => $a,
                'user_id' => $idUser,
            ]);

        }
		elseif($request->rpt=='kwitansi'){
          //  dd($request->all());
            $filterby = $request->status_out;
            $inputfilter = $request->input_filter;
            if($filterby=='1'){
                $a = DB::table('vGetKwitansi')->whereBetween('tgl_kwitansi',[$awal,$akhir])->get();
                $title = 'Laporan Pembayaran Pelanggan';
            }
            elseif($filterby=='2'){
                $a = DB::table('vGetKwitansi')->whereBetween('tgl_kwitansi',[$awal,$akhir])
                        ->where('nama_lengkap','like','%'.$inputfilter.'%')->get();
                $title = 'Laporan Pembayaran Pelanggan Nama: '.$inputfilter;
            }elseif($filterby=='3'){
                $a = DB::table('vGetKwitansi')->whereBetween('tgl_kwitansi',[$awal,$akhir])
                        ->where('unitid',$inputfilter)->get();
                $title = 'Laporan Pembayaran Pelanggan Unit: '.$inputfilter;
            }elseif($filterby=='4'){
                $a = DB::table('vGetKwitansi')->whereBetween('tgl_kwitansi',[$awal,$akhir])
                        ->where('tgl_mutasi_bank',$inputfilter)->get();
                $title = 'Laporan Pembayaran Pelanggan Tanggal Mutasi Bank: '.$inputfilter;
            }
            return view('administrator.laporan.akunting.lap_getkwitansi',[
                'level' => $role,
                'title' => $title,
                'sub_title' => 'Dari Tanggal: '.$awal.' S/d Tanggal: '.$akhir,
                'pelanggan' => $a,
                'user_id' => $idUser,
            ]);

        }
        elseif($request->rpt=='salesday'){
            $a = DB::table('vGetInvPendaftaran')
                    ->select('id','no_formulir','tgl_daftar','tgl_aktivasi','nama_lengkap','unitid','gtot_amount','kwitansi','tgl_kwitansi','StatusPembayaran','Ket_metode_bayar','payment_gtotal','outstanding')
                    ->distinct()
                    ->whereBetween('tgl_daftar',[$awal,$akhir])
					->whereNotNull ('kwitansi')->get();
            return view('administrator.laporan.akunting.lap_getsalesday',[
                'level' => $role,
                'title' => 'Laporan Sales Pendaftaran Pelanggan',
                'sub_title' => 'Dari Tanggal: '.$awal.' S/d Tanggal: '.$akhir,
                'pelanggan' => $a
            ]);

        }
		elseif($request->rpt=='out-daftar'){
            $status_out = $request->status_out;
            if($status_out=='3'){
                $a = DB::table('vGetInvPendaftaran')
                        ->select('id','no_formulir','tgl_daftar','tgl_aktivasi','nama_lengkap','unitid','gtot_amount','kwitansi','tgl_kwitansi','StatusPembayaran','Ket_metode_bayar','payment_gtotal','outstanding')
                        ->distinct()
                        ->whereBetween('tgl_daftar',[$awal,$akhir])
                        ->whereNull('kwitansi')
                        ->where('outstanding','<>',0)
                        ->get();
                        $msg = "Status All Data";
                    }
            elseif($status_out=='2'){
                $a = DB::table('vGetInvPendaftaran')
                        ->select('id','no_formulir','tgl_daftar','tgl_aktivasi','nama_lengkap','unitid','gtot_amount','kwitansi','tgl_kwitansi','StatusPembayaran','Ket_metode_bayar','payment_gtotal','outstanding')
                        ->distinct()
                        ->whereBetween('tgl_daftar',[$awal,$akhir])
                        ->whereNotNull('kwitansi')
                        ->where('outstanding','<>',0)
                        ->get();
                        $msg = "Status Data Pembayaran Masih Kurang (Belum Lunas)";

            }elseif($status_out=='1'){
                $a = DB::table('vGetOustStdNoBayar')
                        ->select('id','no_formulir','tgl_daftar','nama_lengkap','unitid','gtot_amount','kwitansi','tgl_kwitansi','StatusPembayaran','Ket_metode_bayar','payment_gtotal','Outstanding')
                        ->distinct()
                        ->whereBetween('tgl_daftar',[$awal,$akhir])
                      //  ->whereNull('kwitansi')
                       // ->where('gtot_amount','=','outstanding')
                        ->get();
                        $msg = "Status Data Pembayaran Belum ada Transaksi Pembayaran";

            }
            return view('administrator.laporan.akunting.lap_getoutstd_daftar',[
                'level' => $role,
                'title' => 'Laporan Outstanding Pendaftaran Pelanggan',
                'sub_title' => 'Dari Tanggal: '.$awal.' S/d Tanggal: '.$akhir,
                'msg' => $msg,
                'pelanggan' => $a
            ]);

        }
    }
}
