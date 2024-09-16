<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Layanan;
use App\Models\TrxOrder;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Models\TrxOrderDetail;
use App\Models\ViewTrxOrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class AdminTrxCutiLayananController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    public function index()
    {
      //  dd($request);
        $role = Auth::user()->role;
        return view('administrator.order.cuti_layanan.index',[
            'order' => TrxOrder::where('tipe_order','4')->get(),
            'level' => $role,
            'title' => 'Data Cuti Layanan Pelanggan',
        ]);

    }

    public function create()
    {
        $role = Auth::user()->role;

        $internet = Layanan::where('jenis_layanan','biaya')->where('id',15)->get();
        $tv = Layanan::wherein('jenis_layanan',['tv','tv-tambahan'])->get();
        $telepony = Layanan::where('jenis_layanan','telephony')->get();
        $pasang = Layanan::where('id','10')->get();
        $period = [
            ['id' =>'1','name' =>'Promo 6+2'],
            ['id' =>'2','name' =>'Promo 9+3'],
            ];
        $metode_bayar = [
            ['id' =>'1','name' =>'Lunas'],
            ['id' =>'2','name' =>'Bertahap (Cicilan)'],
            ];
        return view('administrator.order.cuti_layanan.create',[
          //  'order' => TrxOrder::all(),
            'level' => $role,
            'title' => 'Cuti Layanan Pelanggan',
            'no_formulir' => TrxOrder::getNomer('4'),
            'internet' => $internet,
            'tv' => $tv,
            'telepony' => $telepony,
            'biaya_pasang' => $pasang,
            'period' => $period,
            'metode_bayar' => $metode_bayar,
            'tipe_order' => '4',
        ]);
    }

    public function getTotal(Request $request){
      // dd($request);
        $role = Auth::user()->role;

        $validateData = $request->validate([
            'no_pelanggan' => 'required|max:20',
            'tgl_rencana_upgrade' => 'required|date',
            'tgl_rencana_upgrade2' => 'required|date',
            'no_formulir' => 'required',
            'tgl_formulir' => 'required',
            'lama_cuti' => 'required',


        ]);
      //  dd($request);
        $hdrTrxOrder = ([
             'no_order' => substr($request->no_formulir,0,4),
            'tipe_order' => '4',
            'no_formulir' => $request->no_formulir,
            'tgl_order' => $request->tgl_formulir,
            'pelanggan_id' => $request->pelanggan_id,
            'tgl_rencana_belangganan' =>$request->tgl_rencana_upgrade,
            'tgl_target_instalasi' => $request->tgl_rencana_upgrade2,
            'catatan_instalasi' => $request->catatan_instalasi,
            'catatan1' => $request->catatan_request_pelanggan,
            'termin_bayar' => $request->lama_cuti,
            'gtot_amount' => 0,
            'amount' => 0,
            'ppn_amount' => 0,
        ]);
        $hdrTrxOrder['user_id'] = auth()->user()->id;
        //Insert Header
        $no_order = substr($request->no_formulir,0,4);
        $cek = TrxOrder::where('no_order',$no_order)
                        ->where('tipe_order','4')
                        ->where('no_formulir',$request->no_formulir)
                        ->count();
        if($cek=='0'){
            $idOrder = TrxOrder::create($hdrTrxOrder)->id;
			DB::table('trx_order_detail')->insert([
                [
                    'trx_order_id' => $idOrder,
                    'no_order' =>  $no_order,
                    'line_no' => $request->line_no_1,
                    'layanan_id' => $request->id_biaya,
                    'amount' => $request->hrg_int,
                    'qty' => $request->qty_int,
                    'diskon' => 0,
                    'tax_amount' => $request->promo_int,
                    'sub_amount' => $request->subt_int,
                ]
            ]);
            DB::select('call updGtotAmount('.$idOrder.')');
        }
            $upg = TrxOrder::where('no_order',$no_order)
                    ->where('tipe_order','4')
                    ->where('no_formulir',$request->no_formulir)
                    ->first();
 			return redirect(url('admin/trx_order'))->with('success','Berhasil Simpan nomer Cuti '.$request->no_formulir.', data Layanan Cuti pelanggan ');
		/* return view('administrator.order.cuti_layanan.getSimpan',[

                'level' => $role,
                'title' => 'Cuti Layanan Pelanggan',
                'no_formulir' => $request->no_formulir,
                'tgl_formulir' => $request->tgl_formulir,
                'tgl_order' => $request->tgl_order,
                'no_pelanggan' => $request->no_pelanggan,
                'pelanggan_id' => $request->pelanggan_id,
                'nama_pelanggan' => $request->nama_pelanggan,
                'nomer_identitas' => $request->nomer_identitas,
                'no_unit' => $request->no_unit,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'no_hp2' => $request->no_hp2,
                'email' => $request->email,
                'catatan_request_pelanggan' => $request->catatan_request_pelanggan,
                'jenis_promo' => $request->jenis_promo,
                'tgl_rencana_upgrade' => $request->tgl_rencana_upgrade,
                'lama_cicilan' => $request->lama_cicilan,
                'catatan_instalasi' => $request->catatan_instalasi,
              ]); */
    }

    public function getSimpan(Request $request){

        $role = Auth::user()->role;


        $no_order = substr($request->no_formulir,0,4);
        $tipe_order = '4';

        // DB::select('call UpdPeriodeLayanan('.$no_order.','.$tipe_order.')');

        $upg = TrxOrder::where('no_order',$no_order)
                    ->where('tipe_order','4')
                    ->where('no_formulir',$request->no_formulir)
                    ->first();

        return view('administrator.order.cuti_layanan.getSimpan',[

            'level' => $role,
            'title' => 'Berhasil Simpan Order Cuti Layanan Pelanggan',
            'no_formulir' => $request->no_formulir,

            'tgl_formulir' => $request->tgl_formulir,
            'tgl_order' => $request->tgl_order,
            'no_pelanggan' => $request->no_pelanggan,
            'pelanggan_id' => $request->pelanggan_id,
            'nama_pelanggan' => $request->nama_pelanggan,
            'nomer_identitas' => $request->nomer_identitas,
            'no_unit' => $request->no_unit,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'no_hp2' => $request->no_hp2,
            'email' => $request->email,
            'catatan_request_pelanggan' => $request->catatan_request_pelanggan,
            'jenis_promo' => $request->jenis_promo,
            'tgl_rencana_upgrade' => $request->tgl_rencana_upgrade,
            'lama_cicilan' => $request->lama_cicilan,
            'catatan_instalasi' => $request->catatan_instalasi,

            'trx_order_id' => $upg->id,
          ]);
    }

    public function getAksi(Request $request)
    {

        $role = Auth::user()->role;

        if($request->aksi=="show"){
            $tagdtl = DB::table('vGetInvPendaftaran')
                ->where('id',$request->id)
                ->wherenull('kwitansi')->count();
            // dd($trxOrder,$trxOrder->id,ViewTrxOrderDetail::where('no_order',$trxOrder->id)->get());


            $pp = DB::table('vtrx_order_hdr_ppn')->where('id',$request->id)->first();

            return view('administrator.order.cuti_layanan.showupgrade',[
                'order' => TrxOrder::where('id',$request->id)->first(),
                'order_dtl' => ViewTrxOrderDetail::where('trx_order_id',$request->id)->get(),
                'order_ppn' => $pp,
                'level' => $role,
                'tunggakan' => $tagdtl,
                'title' => 'Cuti Layanan Pelanggan',
            ]);

        }
        if($request->aksi=="print"){

            $price = DB::table('vtrxorderdetail_new')
                        ->where('trx_order_id',$request->id)
                        ->whereNotIn('jenis_layanan',['deposit','telephony'])
                        ->sum('tax_amount');
            //dd($price);

            $data["order"] = TrxOrder::where('id',$request->id)->first();
            $data["order_dtl"] = ViewTrxOrderDetail::where('trx_order_id',$request->id)
                                                    ->whereNotIn('jenis_layanan',['deposit','telephony'])->get();
            $data["title"] = "Cuti Layanan Pelanggan";

            //return view('administrator.order.upgrade.invUpgrade1', $data);

             $pdf = PDF::loadView('administrator.order.cuti_layanan.invUpgrade1', $data);
             return $pdf->setPaper('A4','portrait')->stream();

        }
        if($request->akse="print-non-ppn"){

            $data["order"] = TrxOrder::where('id',$request->id)->first();
            $data["order_dtl"] = ViewTrxOrderDetail::where('trx_order_id',$request->id)
                                                    ->whereIn('jenis_layanan',['deposit','telephony'])->get();
            $data["title"] = "Cuti Layanan Pelanggan";

        //    / return view('administrator.order.upgrade.invUpgrade2', $data);

            $pdf = PDF::loadView('administrator.order.cuti_layanan.invUpgrade2', $data);

            return $pdf->setPaper('A4','portrait')->stream();
        }

    }
}
