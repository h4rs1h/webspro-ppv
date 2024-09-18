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


class AdminTrxDwnLayananController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    public function index()
    {
        //  dd($request);
        $role = Auth::user()->role;
        return view('administrator.order.downgrade.index', [
            'order' => TrxOrder::where('tipe_order', '3')->get(),
            'level' => $role,
            'title' => 'Data Downgrade Pelanggan',
        ]);
    }

    public function create()
    {
        $role = Auth::user()->role;

        $internet = Layanan::where('jenis_layanan', 'Internet')
            ->where('aktif', '1')
            ->get();
        $tv = Layanan::wherein('jenis_layanan', ['tv', 'tv-tambahan'])->get();
        $telepony = Layanan::where('jenis_layanan', 'telephony')->get();
        $pasang = Layanan::where('id', '10')->get();
        $period = [
            ['id' => '1', 'name' => 'Promo 6+2'],
            ['id' => '2', 'name' => 'Promo 9+3'],
        ];
        $metode_bayar = [
            ['id' => '1', 'name' => 'Lunas'],
            ['id' => '2', 'name' => 'Bertahap (Cicilan)'],
        ];
        return view('administrator.order.downgrade.create', [
            //  'order' => TrxOrder::all(),
            'level' => $role,
            'title' => 'Tambah Order Pelanggan',
            'no_formulir' => TrxOrder::getNomer('3'),
            'internet' => $internet,
            'tv' => $tv,
            'telepony' => $telepony,
            'biaya_pasang' => $pasang,
            'period' => $period,
            'metode_bayar' => $metode_bayar,
        ]);
    }

    public function getTotal(Request $request)
    {
        // dd($request);
        $role = Auth::user()->role;
        $internet = Layanan::where('jenis_layanan', 'Internet')->get();
        $tv = Layanan::wherein('jenis_layanan', ['tv', 'tv-tambahan'])->get();
        $telepony = Layanan::where('jenis_layanan', 'telephony')->get();


        $metode_bayar = [
            ['id' => '1', 'name' => 'Lunas'],
            ['id' => '2', 'name' => 'Bertahap (Cicilan)'],
        ];

        $validateData = $request->validate([
            'no_pelanggan' => 'required|max:20',
            'tgl_rencana_upgrade' => 'required|date',
            'metode_bayar' => 'required',
            'ly_internet' => 'required',
            'qty_int' => 'required',
            'no_formulir' => 'required',
            'tgl_formulir' => 'required',


        ]);
        //  dd($request->tgl_formulir);
        $hdrTrxOrder = ([
            'no_order' => substr($request->no_formulir, 0, 4),
            'tipe_order' => '3',
            'no_formulir' => $request->no_formulir,
            'tgl_order' => $request->tgl_formulir,
            'pelanggan_id' => $request->pelanggan_id,
            'tgl_rencana_belangganan' => $request->tgl_rencana_ugrade,
            'tgl_target_instalasi' => $request->tgl_target_instalasi,
            'catatan_instalasi' => $request->catatan_instalasi,
            'metode_bayar' => $request->metode_bayar,
            'termin_bayar' => $request->lama_cicilan,
            'gtot_amount' => 0,
            'amount' => 0,
            'ppn_amount' => 0,
            'upg_layanan' => '1',
        ]);
        $hdrTrxOrder['user_id'] = auth()->user()->id;
        //Insert Header
        $no_order = substr($request->no_formulir, 0, 4);
        $cek = TrxOrder::where('no_order', $no_order)
            ->where('tipe_order', '3')
            ->where('no_formulir', $request->no_formulir)
            ->count();
        if ($cek == '0') {
            $idOrder = TrxOrder::create($hdrTrxOrder)->id;
            if (isset($request->ly_internet)) {
                DB::table('trx_order_detail')->insert([
                    [
                        'trx_order_id' => $idOrder,
                        'no_order' =>  $no_order,
                        'line_no' => $request->line_no_1,
                        'layanan_id' => $request->ly_internet,
                        'amount' => $request->hrg_int,
                        'qty' => $request->qty_int,
                        'diskon' => $request->promo_int,
                        'tax_amount' => (($request->hrg_int * $request->qty_int) - $request->promo_int) * 0.11,
                        'sub_amount' => $request->subt_int + ((($request->hrg_int * $request->qty_int) - $request->promo_int) * 0.11)
                    ]
                ]);
            }
            if (isset($request->ly_tv)) {
                DB::table('trx_order_detail')->insert([
                    [
                        'trx_order_id' => $idOrder,
                        'no_order' =>  $no_order,
                        'line_no' => $request->line_no_2,
                        'layanan_id' => $request->ly_tv,
                        'amount' => $request->hrg_tv,
                        'qty' => $request->qty_tv,
                        'diskon' => $request->promo_tv,
                        'tax_amount' => (($request->hrg_tv * $request->qty_tv) - $request->promo_tv) * 0.11,
                        'sub_amount' => $request->subt_tv + ((($request->hrg_tv * $request->qty_tv) - $request->promo_tv) * 0.11)
                    ]
                ]);
            }
            $deposit = $request->hrg_int;
            $tax_amount_deposit = $request->hrg_int * 0.11;
            $sub_amount_deposit = $deposit + $tax_amount_deposit;
            $saldo_deposit = Pelanggan::where('id', $request->pelanggan_id)->first()->nilai_deposit;
            $kurang_deposit = $saldo_deposit - $sub_amount_deposit;


            DB::table('trx_order_detail')->insert([
                [
                    'trx_order_id' => $idOrder,
                    'no_order' =>  $no_order,
                    'line_no' => '5',
                    'layanan_id' => '11',
                    'amount' => $kurang_deposit,
                    'qty' => '0',
                    'diskon' => '0',
                    'tax_amount' => '0',
                    // 'sub_amount' => -1*$kurang_deposit
                    'sub_amount' => 0
                ]
            ]);

            DB::select('call updGtotAmount(' . $idOrder . ')');
        }


        $upg = TrxOrder::where('no_order', $no_order)
            ->where('tipe_order', '3')
            ->where('no_formulir', $request->no_formulir)
            ->first();
        $upgdtl = TrxOrderDetail::where('no_order', $upg->no_order)
            ->where('trx_order_id', $upg->id)
            ->where('layanan_id', '11')
            ->first()->sub_amount;
        return view('administrator.order.downgrade.getTotal', [

            'level' => $role,
            'title' => 'Downgrade Layanan Pelanggan',
            'no_formulir' => $request->no_formulir,
            'internet' => $internet,
            'tv' => $tv,
            'telepony' => $telepony,

            'metode_bayar' => $metode_bayar,
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
            'line_no_1' => $request->line_no_1,
            'ly_internet' => $request->ly_internet,
            'hrg_int' => $request->hrg_int,
            'qty_int' => $request->qty_int,
            'promo_int' => $request->promo_int,
            'subt_int' => $request->subt_int,
            'line_no_2' => $request->line_no_2,
            'ly_tv' => $request->ly_tv,
            'hrg_tv' => $request->hrg_tv,
            'qty_tv' => $request->qty_tv,
            'promo_tv' => $request->promo_tv,
            'subt_tv' => $request->subt_tv,
            'ppn_amount' => $upg->ppn_amount,
            'gtot_amount' => $upg->gtot_amount,
            'deposit' => $upgdtl
        ]);
    }

    public function getSimpan(Request $request)
    {

        $role = Auth::user()->role;
        $internet = Layanan::where('jenis_layanan', 'Internet')->get();
        $tv = Layanan::wherein('jenis_layanan', ['tv', 'tv-tambahan'])->get();
        $telepony = Layanan::where('jenis_layanan', 'telephony')->get();
        $pasang = Layanan::where('id', '10')->get();
        $period = [
            ['id' => '1', 'name' => 'Promo 6+2'],
            ['id' => '2', 'name' => 'Promo 9+3'],
        ];
        $metode_bayar = [
            ['id' => '1', 'name' => 'Lunas'],
            ['id' => '2', 'name' => 'Bertahap (Cicilan)'],
        ];

        $no_order = substr($request->no_formulir, 0, 4);
        $tipe_order = '3';

        DB::select('call UpdPeriodeLayanan(' . $no_order . ',' . $tipe_order . ')');

        $upg = TrxOrder::where('no_order', $no_order)
            ->where('tipe_order', '3')
            ->where('no_formulir', $request->no_formulir)
            ->first();
        $upgdtl = TrxOrderDetail::where('no_order', $upg->no_order)
            ->where('trx_order_id', $upg->id)
            ->where('layanan_id', '11')
            ->first()->sub_amount;

        return view('administrator.order.downgrade.getSimpan', [

            'level' => $role,
            'title' => 'Berhasil Simpan Downgrade Layanan Pelanggan',
            'no_formulir' => $request->no_formulir,
            'internet' => $internet,
            'tv' => $tv,
            'telepony' => $telepony,
            'biaya_pasang' => $pasang,
            'period' => $period,
            'metode_bayar' => $metode_bayar,
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
            'line_no_1' => $request->line_no_1,
            'ly_internet' => $request->ly_internet,
            'hrg_int' => $request->hrg_int,
            'qty_int' => $request->qty_int,
            'promo_int' => $request->promo_int,
            'subt_int' => $request->subt_int,
            'line_no_2' => $request->line_no_2,
            'ly_tv' => $request->ly_tv,
            'hrg_tv' => $request->hrg_tv,
            'qty_tv' => $request->qty_tv,
            'promo_tv' => $request->promo_tv,
            'subt_tv' => $request->subt_tv,
            'ppn_amount' => $upg->ppn_amount,
            'gtot_amount' => $upg->gtot_amount,
            'deposit' => $upgdtl,
            'trx_order_id' => $upg->id,
        ]);
    }

    public function getAksi(Request $request)
    {

        $role = Auth::user()->role;
        $path = base_path() . '/../httpdocs/' . env('FOLDER_IN_PUBLIC_HTML') . '/storage/LOGO1.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $dataimg = file_get_contents($path);
        $pic1 = 'data:image/' . $type . ';base64,' . base64_encode($dataimg);
        $data["baseurl"] = $pic1;
        $path2 = base_path() . '/../httpdocs/' . env('FOLDER_IN_PUBLIC_HTML') . '/storage/LOGO2.png';
        $type2 = pathinfo($path2, PATHINFO_EXTENSION);
        $dataimg2 = file_get_contents($path2);
        $pic2 = 'data:image/' . $type2 . ';base64,' . base64_encode($dataimg2);
        $data["baseurl2"] = $pic2;

        if ($request->aksi == "show") {
            $tagdtl = DB::table('vGetInvPendaftaran')
                ->where('id', $request->id)
                ->wherenull('kwitansi')->count();
            // dd($trxOrder,$trxOrder->id,ViewTrxOrderDetail::where('no_order',$trxOrder->id)->get());


            $pp = DB::table('vtrx_order_hdr_ppn')->where('id', $request->id)->first();

            return view('administrator.order.downgrade.showdowngrade', [
                'order' => TrxOrder::where('id', $request->id)->first(),
                'order_dtl' => ViewTrxOrderDetail::where('trx_order_id', $request->id)->get(),
                'order_ppn' => $pp,
                'level' => $role,
                'tunggakan' => $tagdtl,
                'title' => 'Downgrade Layanan Pelanggan',
            ]);
        }
        if ($request->aksi == "print") {

            $price = DB::table('vtrxorderdetail_new')
                ->where('trx_order_id', $request->id)
                ->whereNotIn('jenis_layanan', ['deposit', 'telephony'])
                ->sum('tax_amount');
            //dd($price);

            $data["order"] = TrxOrder::where('id', $request->id)->first();
            $data["order_dtl"] = ViewTrxOrderDetail::where('trx_order_id', $request->id)
                ->whereNotIn('jenis_layanan', ['deposit', 'telephony'])->get();
            $data["title"] = "Formulir Downgrade Layanan Pelanggan";

            //return view('administrator.order.upgrade.invUpgrade1', $data);

            $pdf = PDF::loadView('administrator.order.upgrade.invUpgrade1', $data);
            return $pdf->setPaper('A4', 'portrait')->stream();
        }
        if ($request->akse = "print-non-ppn") {

            $data["order"] = TrxOrder::where('id', $request->id)->first();
            $data["order_dtl"] = ViewTrxOrderDetail::where('trx_order_id', $request->id)
                ->whereIn('jenis_layanan', ['deposit', 'telephony'])->get();
            $data["title"] = "Formulir Downgrade Layanan Pelanggan";

            //    / return view('administrator.order.upgrade.invUpgrade2', $data);

            $pdf = PDF::loadView('administrator.order.upgrade.invUpgrade2', $data);

            return $pdf->setPaper('A4', 'portrait')->stream();
        }
    }
}
