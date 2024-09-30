<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\TrxOrder;
use App\Models\TrxBayar;
use App\Models\Pelanggan;
use App\Models\TrxOutbox;
use Illuminate\Http\Request;
use App\Models\TrxOrderDetail;
use App\Models\ViewCekTrxOrderDtl;
use App\Models\ViewTrxOrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ViewPelanggan;
use App\Models\ViewTrxOrderPelanggan;
use Illuminate\Support\Facades\Crypt;
use PDF;

class AdminTrxOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is3ds');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = Auth::user()->role;
        //dd(TrxOrder::all());
        // return view('administrator.order.index',[
        //     'order' => TrxOrder::where('tipe_order','1')->get(),
        //     'level' => $role,
        //     'title' => 'Data Order Pelanggan',
        // ]);
        $order = TrxOrder::where('tipe_order', '1')->get();
        $con = DB::table('trx_order')
            ->select(DB::raw('count(*) as jml,tipe_order'))
            ->groupBy('tipe_order')
            ->orderBy('tipe_order')
            ->get();

        $termin = count(DB::select("call GetTerminPelanggan();"));
        // dd($con);
        return view('administrator.order.indexOrder', [
            'order' => $con,
            'level' => $role,
            'title' => 'Data Order Layanan Pelanggan',
            'termin' => $termin,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = Auth::user()->role;

        $internet = Layanan::where('jenis_layanan', 'Internet')
            ->where('aktif', '1')
            ->get();
        $tv = Layanan::where('jenis_layanan', 'tv')->get();
        $telepony = Layanan::where('jenis_layanan', 'telephony')->get();
        $pasang = Layanan::wherein('id', ['10', '18', '19', '20'])->get();
        $jenis_promo = DB::table('set_promo')->get(['id', 'name']);
        $period = [
            ['id' => '1', 'name' => 'Bulanan'],
            ['id' => '2', 'name' => 'Tahunan'],
        ];
        $metode_bayar = [
            ['id' => '1', 'name' => 'Lunas'],
            ['id' => '2', 'name' => 'Bertahap (Cicilan)'],
        ];

        return view('administrator.order.create', [
            //  'order' => TrxOrder::all(),
            'level' => $role,
            'title' => 'Tambah Order Pelanggan',
            'no_formulir' => TrxOrder::nomerformulir(),
            'internet' => $internet,
            'tv' => $tv,
            'telepony' => $telepony,
            'biaya_pasang' => $pasang,
            'period' => $period,
            'promo' => $jenis_promo,
            'metode_bayar' => $metode_bayar,
        ]);
    }

    public function check_nopelanggan(Request $request)
    {
        //dd($request);
        //$slug = ViewPelanggan::where('unitid', $request->no_pelanggan)->first();
        $slug = DB::table('vpelanggan2')->where('unitid', $request->no_pelanggan)->where('status_layanan', '<>', '4')->first();
        //dd(response()->json(['pelanggan' => $slug]));
        $data = array(
            'nama_lengkap' => $slug->nama_lengkap,
            'nomer_identitas' => $slug->nomer_identitas,
            'no_unit' => $slug->sub_tower . '/' . $slug->lantai . '/' . $slug->nomer_unit,
            'alamat' => $slug->alamat_identitas,
            'no_hp' => $slug->nomer_hp,
            'email' => $slug->email,
            'pelanggan_id' => $slug->id,
        );

        //dd(response()->json($data));
        return response()->json($data);
    }
    public function getLayanan(Request $request)
    {
        // dd($request);
        if (!$request->layanan) {
            $slug = Layanan::where('id', $request->id)->first();
        } else {
            $slug = Layanan::where('jenis_layanan', $request->layanan)
                ->where('id', $request->id)->first();
        }
        if (!$slug) {
            $data = array(
                'harga' => '0',
            );
        } else {
            $data = array(
                'harga' => $slug->harga,
            );
        }
        //  dd($data);
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //	dd($request);

        $validateData = $request->validate([
            'no_pelanggan' => 'required|max:20',
            'tgl_rencana_berlangganan' => 'required|date',
            'langganan_status' => 'required',
            'ly_internet' => 'required',
            'qty_int' => 'required|numeric',
            'biaya_pasang' => 'required|numeric',
            'subt_biaya_pasang' => 'required|numeric',
            'jenis_promo' => 'required',
            'metode_bayar' => 'required',
            'lama_cicilan' => 'required|numeric',
        ]);

        $hdrTrxOrder = ([
            'no_order' => substr($request->no_formulir, 0, 4),
            'tipe_order' => '1',
            'no_formulir' => $request->no_formulir,
            'tgl_order' => $request->tgl_formulir,
            'pelanggan_id' => $request->pelanggan_id,
            'tgl_rencana_belangganan' => $request->tgl_rencana_berlangganan,
            'langganan_status' => $request->langganan_status,
            'gtot_amount' => $request->gt_amount,
            'amount' => $request->amount,
            'ppn_amount' => $request->ppn_amount,
            'tgl_target_instalasi' => $request->tgl_target_instalasi,
            'catatan_instalasi' => $request->catatan_instalasi,
            'metode_bayar' => $request->metode_bayar,
            'termin_bayar' => $request->lama_cicilan,
            'jenis_promo' => $request->jenis_promo,
        ]);
        $hdrTrxOrder['user_id'] = auth()->user()->id;
        //Insert Header
        $idOrder = TrxOrder::create($hdrTrxOrder)->id;
        //   dd($idOrder);
        $no_order = substr($request->no_formulir, 0, 4);
        DB::table('trx_order_detail')->insertOrIgnore([
            [
                'trx_order_id' => $idOrder,
                'no_order' => $no_order,
                'line_no' => $request->line_no_1,
                'layanan_id' => $request->ly_internet,
                'amount' => $request->hrg_int,
                'qty' => $request->qty_int,
                'diskon' => $request->promo_int,
                'sub_amount' => $request->subt_int
            ],
            [
                'trx_order_id' => $idOrder,
                'no_order' => $no_order,
                'line_no' => $request->line_no_2,
                'layanan_id' => $request->ly_tv,
                'amount' => $request->hrg_tv,
                'qty' => $request->qty_tv,
                'diskon' => $request->promo_tv,
                'sub_amount' => $request->subt_tv
            ],
            [
                'trx_order_id' => $idOrder,
                'no_order' => $no_order,
                'line_no' => $request->line_no_3,
                'layanan_id' => $request->ly_telepony,
                'amount' => $request->hrg_telepony,
                'qty' => $request->qty_telepony,
                'diskon' => $request->promo_telepony,
                'sub_amount' => $request->subt_telepony
            ],
            [
                'trx_order_id' => $idOrder,
                'no_order' => $no_order,
                'line_no' => '4',
                'layanan_id' => $request->biaya_pasang,
                'amount' => $request->subt_biaya_pasang,
                'qty' => '1',
                'diskon' => null,
                'sub_amount' => $request->subt_biaya_pasang
            ],
            [
                'trx_order_id' => $idOrder,
                'no_order' => $no_order,
                'line_no' => '5',
                'layanan_id' => '11',
                'amount' => $request->deposit_amount,
                'qty' => '1',
                'diskon' => null,
                'sub_amount' => $request->deposit_amount
            ],

        ]);

        DB::select('call updtaxamt(' . $idOrder . ')');
        DB::select('call UpdPeriodeLayanan(' . $no_order . ',1)');
        return redirect('/admin/trx_order')->with('success', 'Berhasil menambahkan Order Pelanggan');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrxOrder  $trxOrder
     * @return \Illuminate\Http\Response
     */
    public function show(TrxOrder $trxOrder)
    {

        $tagdtl = DB::table('vGetInvPendaftaran')
            ->where('id', $trxOrder->id)
            ->where('outstanding', '>', 0)
            ->where('tipe_order', '1')
            //  ->wherenull('kwitansi')
            ->count();

        //  dd($trxOrder,$trxOrder->id,ViewTrxOrderDetail::where('no_order',$trxOrder->id)->get());
        $role = Auth::user()->role;
        $pp = DB::table('vtrx_order_hdr_ppn')->where('id', $trxOrder->id)->first();

        //	 DB::select('call updtaxamt('.$trxOrder->id.')');
        //dd($trxOrder,ViewTrxOrderDetail::where('no_order',$trxOrder->id)->get());
        //dd($role,ViewTrxOrderDetail::where('no_order',$trxOrder->id)->get());
        return view('administrator.order.show', [
            'order' => $trxOrder,
            'order_dtl' => ViewTrxOrderDetail::where('no_order', $trxOrder->no_order)->where('tipe_order', '1')->get(),
            'level' => $role,
            'tunggakan' => $tagdtl,
            'order_ppn' => $pp,
            'title' => 'Order Pelanggan1',
        ]);
    }

    public function download(TrxOrder $trxOrder)
    {
        $price = DB::table('vtrxorderdetail_new')
            ->where('no_order', $trxOrder->no_order)
            ->whereNotIn('jenis_layanan', ['deposit', 'telephony'])
            ->sum('tax_amount');
        //  dd($price);
        // dd($trxOrder->id,$trxOrder->no_order);
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
        $data["order"] = $trxOrder;
        $data["order_dtl"] = ViewTrxOrderDetail::where('no_order', $trxOrder->no_order)
            ->whereNotIn('jenis_layanan', ['deposit', 'telephony'])->get();
        $data["title"] = "Order Pelanggan";

        // return view('administrator.order.inv1', $data);

        $pdf = PDF::loadView('administrator.order.inv1', $data);

        return $pdf->setPaper('A4', 'portrait')->stream();
        // return $pdf->setPaper('A4','portrait')->download('order_'.$trxOrder->no_formulir.'.pdf');
        //   return $pdf->setPaper('A4','portrait')->stream('order_'.$trxOrder->no_formulir.'.pdf');
        //  return $pdf->download('invoice.pdf');

    }
    public function download_nppn(TrxOrder $trxOrder)
    {
        // dd($trxOrder->id,$trxOrder->no_order);
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
        $data["order"] = $trxOrder;
        $data["order_dtl"] = ViewTrxOrderDetail::where('no_order', $trxOrder->no_order)
            ->whereIn('jenis_layanan', ['deposit', 'telephony'])->get();
        $data["title"] = "Order Pelanggan";

        // return view('administrator.order.inv1', $data);

        $pdf = PDF::loadView('administrator.order.inv2', $data);

        return $pdf->setPaper('A4', 'portrait')->stream();
        // return $pdf->setPaper('A4','portrait')->download('order_'.$trxOrder->no_formulir.'.pdf');
        //   return $pdf->setPaper('A4','portrait')->stream('order_'.$trxOrder->no_formulir.'.pdf');
        //  return $pdf->download('invoice.pdf');

    }

    public function getTagihanDaftar(TrxOrder $trxOrder)
    {

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

        // dd($trxOrder->id,$trxOrder->no_formulir);
        $tag = ViewTrxOrderPelanggan::where('id', $trxOrder->id)->first();
        $tagdtl = DB::table('vGetInvPendaftaran')
            ->where('id', $trxOrder->id)
            ->where('outstanding', '>', 0)
            ->where('outstandingdtl', '>', 0);
        // ->wherenull('kwitansi');

        if ($tagdtl->count() > 0) {
            //dd($tagdtl->get());
            $subTagih = 0;
            $termin = 0;
            foreach ($tagdtl->get() as $td) {
                // $td->amount;
                if ($td->termin_bayar > 0) {
                    $subTagih = $subTagih + ($td->sub_amount / $td->termin_bayar);
                    // $tagihan = $td->Outstanding/$td->termin_bayar;
                    $tagihan = ($td->sub_amount / $td->termin_bayar);
                    $termin = $td->termin_bayar;
                } else {
                    $subTagih = $subTagih + $td->subtotal;
                    $tagihan = $td->Outstanding;
                }
                $tbilang = $td->terbilang;
            }
            //  dd($tag);
            $data["order"] = $tag;
            $data["order_dtl"] = $tagdtl->get();
            $data["title"] = "Invoice Pendaftarn Layanan Pelanggan";
            $data['subtagihan'] = $subTagih;
            $data['tagihan'] = $tagihan;
            $data['tbilang'] = $tbilang;
            $data['termin'] = $termin;
            //   return view('administrator.order.inv_tagihan_daftar', $data);

            $pdf = PDF::loadView('administrator.order.inv_tagihan_daftar', $data);

            return $pdf->setPaper('A4', 'portrait')->stream();
        } else {
            return redirect('/admin/trx_order/' . $trxOrder->id)->with('warning', 'Data Pelanggan ini sudah lunas');
        }
    }
    public function getTagihanDaftarfull(TrxOrder $trxOrder)
    {

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

        // dd($trxOrder->id,$trxOrder->no_formulir);
        $tag = ViewTrxOrderPelanggan::where('id', $trxOrder->id)->first();
        //dd($tag);
        $tagdtl = DB::table('vGetInvPendaftaran')
            ->where('id', $trxOrder->id)
            ->where('outstanding', '>', 0)
            ->where('outstandingdtl', '>', 0);
        // ->wherenull('kwitansi');

        if ($tagdtl->count() > 0) {
            // dd($tagdtl->get());
            $subTagih = 0;
            $termin = 0;
            foreach ($tagdtl->get() as $td) {
                // $td->amount;

                $subTagih = $subTagih + $td->sub_amount;
                $tagihan = $td->Outstanding;
                $tbilang = $td->terbilang;
            }
            //  dd($subTagih,$tagihan,$tbilang,$termin);
            $data["order"] = $tag;
            $data["order_dtl"] = $tagdtl->get();
            $data["title"] = "Invoice Pendaftarn Layanan Pelanggan full";
            $data['subtagihan'] = $subTagih;
            $data['tagihan'] = $tagihan;
            $data['tbilang'] = $tbilang;
            $data['termin'] = $termin;
            // return view('administrator.order.inv_tagihan_daftarfull', $data);

            $pdf = PDF::loadView('administrator.order.inv_tagihan_daftarfull', $data);

            return $pdf->setPaper('A4', 'portrait')->stream();
        } else {
            return redirect('/admin/trx_order/' . $trxOrder->id)->with('warning', 'Data Pelanggan ini sudah lunas');
        }
    }
    public function getTagihanTermin(Request $request)
    {
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

        $tag = DB::select("call GetInvTagihanTermin(" . $request->no_order . "," . $request->tipe_order . ")");
        //    dd($request->all(), $tag );
        $tagdtl = DB::select("call GetInvTagihanTermin(" . $request->no_order . "," . $request->tipe_order . ")");

        foreach ($tag as $hd) {
            $no_tagihan = $hd->no_formulir;
            $no_pelanggan = $hd->no_pelanggan;
            $no_unit = $hd->unitid;
            $tgl_tagih = $hd->tgl_tagih;
            $tgl_jt_tempo = $hd->tgl_jt_tempo;
            $tagihan = $hd->total_tagihan;
            $termin = $hd->jml_bayar + 1;
            $nama_lengkap = $hd->nama_lengkap;
            $alamat = $hd->alamat_identitas;
            $tbilang = $hd->terbilang;
            $sub_tower = $hd->sub_tower;
            $subTagih = $hd->amount_Tagihan;
            $ppn = $hd->ppn_tagihan;
        }
        // dd($ppn);

        $data["no_tagihan"] = $no_tagihan;
        $data["no_pelanggan"] = $no_pelanggan;
        $data["no_unit"] = $no_unit;
        $data["tgl_tagih"] = $tgl_tagih;
        $data["tgl_jt_tempo"] = $tgl_jt_tempo;
        $data["nama_lengkap"] = $nama_lengkap;
        $data["alamat"] = $alamat;
        $data["sub_tower"] = $sub_tower;
        $data["ppn_"] = $ppn;
        $data["order_dtl"] = $tagdtl;
        $data["title"] = "Invoice Pendaftarn Layanan Pelanggan";
        $data['subtagihan'] = $subTagih;
        $data['tagihan'] = $tagihan;
        $data['tbilang'] = $tbilang;
        $data['termin'] = $termin;
        // return view('administrator.order.inv_tagihan_termin', $data);

        $pdf = PDF::loadView('administrator.order.inv_tagihan_termin', $data);

        return $pdf->setPaper('A4', 'portrait')->stream();
    }
    public function getKirimWaTagihanTermin(Request $request)
    {
        if ($request->tipe_wa == 'termin') {
            // dd($request->all());
            $data = DB::select('call GetKirimNotifTermin(' . $request->no_order . ',' . $request->tipe_order . ')');
            foreach ($data as $d) {
                $pesan = $d->pesan_;
                $id = $d->id_;
                $no_wa = $d->no_wa_;
                $unitid = $d->unitid_;
                $no_formulir = $d->no_formulir_;
            }
            $link1 = 'viewtagihantermin&no_order=' . $request->no_order . '&tipe_order=' . $request->tipe_order;
            $link2 = Crypt::encrypt($link1);
            $link = url('/data/' . $link2);
            $psn = str_replace('[link]', ' ' . $link, $pesan);

            $pesan_outbox = ([
                'jenis_pesan' => 'trx_termin_tagihan',
                'id_source' => $id,
                'tgl_kirim' => now(),
                'no_wa' => $no_wa,
                'id_unit' => $unitid,
                'isi_pesan' => $psn,
                'status' => 'proses'
            ]);

            TrxOutbox::create($pesan_outbox);

            // $pesan = DB::select('call GetNewNotifInv('.$no_tagihan.')');

            return redirect('/admin/trx_order/get?action=show_termin')->with('success', 'Berhasil Mengirim Notifikasi Nomer Formulir=' . $no_formulir . ' ke Pelanggan');
        } elseif ($request->tipe_wa == 'upg') {
            $data = DB::select('call GetKirimNotifUpgrade(' . $request->no_order . ',' . $request->tipe_order . ')');
            foreach ($data as $d) {
                $pesan = $d->pesan;
                $id = $d->id;
                $no_wa = $d->nomer_hp;
                $unitid = $d->unitid;
                $no_formulir = $d->no_formulir;
            }
            $psn = $pesan;
            // dd($psn,$data);
            $pesan_outbox = ([
                'jenis_pesan' => 'trx_Upgrade_layanan',
                'id_source' => $id,
                'tgl_kirim' => now(),
                'no_wa' => 'PROBLEM HANDLING',
                'id_unit' => $unitid,
                'isi_pesan' => $psn,
                'status' => 'proses'
            ]);

            TrxOutbox::create($pesan_outbox);
            return redirect('/admin/trx_order')->with('success', 'Berhasil Mengirim Notifikasi Nomer Formulir=' . $no_formulir . ' ke Group Problem Handling');
        } elseif ($request->tipe_wa == 'dng') {
            $data = DB::select('call GetKirimNotifUpgrade(' . $request->no_order . ',' . $request->tipe_order . ')');
            foreach ($data as $d) {
                $pesan = $d->pesan;
                $id = $d->id;
                $no_wa = $d->nomer_hp;
                $unitid = $d->unitid;
                $no_formulir = $d->no_formulir;
            }
            $psn = $pesan;
            // dd($psn,$data);
            $pesan_outbox = ([
                'jenis_pesan' => 'trx_Downgrade_layanan',
                'id_source' => $id,
                'tgl_kirim' => now(),
                'no_wa' => 'PROBLEM HANDLING',
                'id_unit' => $unitid,
                'isi_pesan' => $psn,
                'status' => 'proses'
            ]);

            TrxOutbox::create($pesan_outbox);
            return redirect('/admin/trx_order')->with('success', 'Berhasil Mengirim Notifikasi Nomer Formulir=' . $no_formulir . ' ke Group Problem Handling');
        } elseif ($request->tipe_wa == 'berhenti' or $request->tipe_wa == 'stop') {
            $data = DB::select('call GetKirimNotifBerhenti(' . $request->no_order . ',' . $request->tipe_order . ')');
            foreach ($data as $d) {
                $pesan = $d->pesan;
                $id = $d->id;
                $no_wa = $d->nomer_hp;
                $unitid = $d->unitid;
                $no_formulir = $d->no_formulir;
            }
            $psn = $pesan;
            // dd($psn,$data);
            $pesan_outbox = ([
                'jenis_pesan' => 'trx_Upgrade_layanan',
                'id_source' => $id,
                'tgl_kirim' => now(),
                'no_wa' => 'PROBLEM HANDLING',
                'id_unit' => $unitid,
                'isi_pesan' => $psn,
                'status' => 'proses'
            ]);

            TrxOutbox::create($pesan_outbox);
            return redirect('/admin/trx_order')->with('success', 'Berhasil Mengirim Notifikasi Nomer Formulir=' . $no_formulir . ' ke Group Problem Handling');
        } elseif ($request->tipe_wa == 'cuti') {
            $data = DB::select('call GetKirimNotifCuti(' . $request->no_order . ',' . $request->tipe_order . ')');
            foreach ($data as $d) {
                $pesan = $d->pesan;
                $id = $d->id;
                $no_wa = $d->nomer_hp;
                $unitid = $d->unitid;
                $no_formulir = $d->no_formulir;
            }
            $psn = $pesan;
            // dd($psn,$data);
            $pesan_outbox = ([
                'jenis_pesan' => 'trx_Cuti_layanan',
                'id_source' => $id,
                'tgl_kirim' => now(),
                'no_wa' => 'PROBLEM HANDLING',
                'id_unit' => $unitid,
                'isi_pesan' => $psn,
                'status' => 'proses'
            ]);

            TrxOutbox::create($pesan_outbox);
            return redirect('/admin/trx_order')->with('success', 'Berhasil Mengirim Notifikasi Nomer Formulir=' . $no_formulir . ' ke Group Problem Handling');
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrxOrder  $trxOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(TrxOrder $trxOrder)
    {
        // dd($trxOrder->order_detail);
        $role = Auth::user()->role;

        $internet = Layanan::where('jenis_layanan', 'Internet')->get();
        $tv = Layanan::where('jenis_layanan', 'tv')->get();
        $telepony = Layanan::where('jenis_layanan', 'telephony')->get();
        $pasang = Layanan::wherein('id', ['10', '18', '19'])->get();

        $dtl_order1 = ViewTrxOrderDetail::where('trx_order_id', $trxOrder->id)
            ->get();
        $dtl_cekorder = ViewCekTrxOrderDtl::where('trx_order_id', $trxOrder->id)->first();

        // dd($dtl_order1);
        //	dd($dtl_cekorder);
        // if(!$dtl_order4 == true){
        //     $dtl_order4 = $pasang;

        // }
        //  dd($dtl_order1);
        $byr = TrxBayar::where('trx_order_id', $trxOrder->id)
            ->where('tipe_bayar', '1')
            ->select('no_bayar', 'tgl_bayar', 'status_bayar')
            ->first();
        if ($byr) {
            $stsbyr = $byr;
        } else {
            $stsbyr = 'kosong';
        }

        $period = [
            ['id' => '1', 'name' => 'Bulanan'],
            ['id' => '2', 'name' => 'Tahunan'],
        ];
        return view('administrator.order.edit', [
            'order' => $trxOrder,
            'order_dtl1' => $dtl_order1,
            'order_cekdtl' => $dtl_cekorder,
            // 'order_dtl3' => $dtl_order3,
            // 'order_dtl4' => $dtl_order4,
            // 'order_dtl5' => $dtl_order5,
            'level' => $role,
            'title' => 'Tambah Order Pelanggan',
            'no_formulir' => $trxOrder->no_formulir,
            'no_unit' => $trxOrder->pelanggan->sub_tower . '/' . $trxOrder->pelanggan->lantai . '/' . $trxOrder->pelanggan->nomer_unit,
            'internet' => $internet,
            'tv' => $tv,
            'telepony' => $telepony,
            'biaya_pasang' => $pasang,
            'period' => $period,
            'status_bayar' => $stsbyr
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrxOrder  $trxOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TrxOrder $trxOrder)
    {
        // dd($request);
        $rules = ([
            'no_pelanggan' => 'required|max:20',
            'tgl_rencana_berlangganan' => 'required|date',
            'langganan_status' => 'required',
            'ly_internet' => 'required',
            'qty_int' => 'required',
            'biaya_pasang' => 'required',
            'subt_biaya_pasang' => 'required',
        ]);

        $validateData = $request->validate($rules);
        // dd($rules);
        $hdrTrxOrder = ([
            'no_order' => substr($request->no_formulir, 0, 4),
            'no_formulir' => $request->no_formulir,
            'tgl_order' => $request->tgl_formulir,
            'pelanggan_id' => $request->pelanggan_id,
            'tgl_rencana_belangganan' => $request->tgl_rencana_berlangganan,
            'langganan_status' => $request->langganan_status,
            'gtot_amount' => $request->gt_amount,
            'amount' => $request->amount,
            'ppn_amount' => $request->ppn_amount,
            'langganan_status' => $request->langganan_status,
            'tgl_target_instalasi' => $request->tgl_target_instalasi,
            'catatan_instalasi' => $request->catatan_instalasi
        ]);
        $hdrTrxOrder['user_id'] = auth()->user()->id;
        //Update Headeer
        TrxOrder::where('id', $trxOrder->id)
            ->update($hdrTrxOrder);

        $idOrder = $trxOrder->id;
        $no_order = substr($request->no_formulir, 0, 4);
        // dd($request);
        if ($request->hrg_int > 0) {
            $data1 = [
                'trx_order_id' => $idOrder,
                'no_order' => $no_order,
                'line_no' => $request->line_no_1,
                'layanan_id' => $request->ly_internet,
                'amount' => $request->hrg_int,
                'qty' => $request->qty_int,
                'diskon' => $request->promo_int,
                'sub_amount' => $request->subt_int
            ];
            $dataInt = [
                'layanan_id' => $request->ly_internet,
                'amount' => $request->hrg_int,
                'qty' => $request->qty_int,
                'diskon' => $request->promo_int,
                'sub_amount' => $request->subt_int
            ];
            $cek1 = TrxOrderDetail::where('trx_order_id', $idOrder)
                ->where('no_order', $no_order)
                ->where('line_no', $request->line_no_1)
                ->select('line_no')->first();
            if ($cek1) {
                TrxOrderDetail::where('trx_order_id', $idOrder)
                    ->where('no_order', $no_order)
                    ->where('line_no', $request->line_no_1)
                    ->update($dataInt);
            } else {
                TrxOrderDetail::create($data1);
            }
            /*DB::table('trx_order_detail')->upsert([
                $data1,
            	],['trx_order_id','no_order','line_no','layanan_id'],['amount','qty','diskon','sub_amount']);*/
        }
        //  dd($data1);
        if ($request->hrg_tv > 0) {
            $data2 =  [
                'trx_order_id' => $idOrder,
                'no_order' => $no_order,
                'line_no' => $request->line_no_2,
                'layanan_id' => $request->ly_tv,
                'amount' => $request->hrg_tv,
                'qty' => $request->qty_tv,
                'diskon' => $request->promo_tv,
                'sub_amount' => $request->subt_tv
            ];
            $datatv = [
                'layanan_id' => $request->ly_tv,
                'amount' => $request->hrg_tv,
                'qty' => $request->qty_tv,
                'diskon' => $request->promo_tv,
                'sub_amount' => $request->subt_tv
            ];
            $cek1 = TrxOrderDetail::where('trx_order_id', $idOrder)
                ->where('no_order', $no_order)
                ->where('line_no', $request->line_no_2)
                ->select('line_no')->first();
            if ($cek1) {
                TrxOrderDetail::where('trx_order_id', $idOrder)
                    ->where('no_order', $no_order)
                    ->where('line_no', $request->line_no_2)
                    ->update($datatv);
            } else {
                TrxOrderDetail::create($data2);
            }
            /*
            DB::table('trx_order_detail')->upsert([
                $data2,
            ],['trx_order_id','no_order','line_no','layanan_id'],['amount','qty','diskon','sub_amount']);*/
        }
        if ($request->hrg_telepony > 0) {
            $data3 =  [
                'trx_order_id' => $idOrder,
                'no_order' => $no_order,
                'line_no' => $request->line_no_3,
                'layanan_id' => $request->ly_telepony,
                'amount' => $request->hrg_telepony,
                'qty' => $request->qty_telepony,
                'diskon' => $request->promo_telepony,
                'sub_amount' => $request->subt_telepony
            ];

            $datatelepony = [
                'layanan_id' => $request->ly_telepony,
                'amount' => $request->hrg_telepony,
                'qty' => $request->qty_telepony,
                'diskon' => $request->promo_telepony,
                'sub_amount' => $request->subt_telepony
            ];
            $cek1 = TrxOrderDetail::where('trx_order_id', $idOrder)
                ->where('no_order', $no_order)
                ->where('line_no', $request->line_no_3)
                ->select('line_no')->first();
            if ($cek1) {
                TrxOrderDetail::where('trx_order_id', $idOrder)
                    ->where('no_order', $no_order)
                    ->where('line_no', $request->line_no_3)
                    ->update($datatelepony);
            } else {
                TrxOrderDetail::create($data3);
            }
            /*
            DB::table('trx_order_detail')->upsert([
                $data3,
            ],['trx_order_id','no_order','line_no','layanan_id'],['amount','qty','diskon','sub_amount']);*/
        }
        // dd($request,$request->sub_biaya_pasang);
        if ($request->subt_biaya_pasang > 0) {
            $data4 =  [
                'trx_order_id' => $idOrder,
                'no_order' => $no_order,
                'line_no' => '4',
                'layanan_id' => $request->biaya_pasang,
                'amount' => $request->subt_biaya_pasang,
                'qty' => '1',
                'diskon' => null,
                'sub_amount' => $request->subt_biaya_pasang
            ];

            $databiaya = [
                'layanan_id' => $request->biaya_pasang,
                'amount' => $request->subt_biaya_pasang,
                'qty' => '1',
                'diskon' => null,
                'sub_amount' => $request->subt_biaya_pasang
            ];
            $cek1 = TrxOrderDetail::where('trx_order_id', $idOrder)
                ->where('no_order', $no_order)
                ->where('line_no', 4)
                ->select('line_no')->first();
            if ($cek1) {
                TrxOrderDetail::where('trx_order_id', $idOrder)
                    ->where('no_order', $no_order)
                    ->where('line_no', 4)
                    ->update($databiaya);
            } else {
                TrxOrderDetail::create($data4);
            }

            /* DB::table('trx_order_detail')->insertOrIgnore([
                    $data4,]);

                DB::table('trx_order_detail')->upsert([
                    $data4,
                ],['trx_order_id','no_order','line_no','layanan_id'],['amount','qty','diskon','sub_amount']);*/
        }
        if ($request->deposit > 0) {
            $data5 =   [
                'trx_order_id' => $idOrder,
                'no_order' => $no_order,
                'line_no' => '5',
                'layanan_id' => '11',
                'amount' => $request->deposit_amount,
                'qty' => '1',
                'diskon' => null,
                'sub_amount' => $request->deposit_amount
            ];

            $datadeposit = [
                'layanan_id' => '11',
                'amount' => $request->deposit_amount,
                'qty' => '1',
                'diskon' => null,
                'sub_amount' => $request->deposit_amount
            ];
            $cek1 = TrxOrderDetail::where('trx_order_id', $idOrder)
                ->where('no_order', $no_order)
                ->where('line_no', 5)
                ->select('line_no')->first();
            if ($cek1) {
                TrxOrderDetail::where('trx_order_id', $idOrder)
                    ->where('no_order', $no_order)
                    ->where('line_no', 5)
                    ->update($datadeposit);
            } else {
                TrxOrderDetail::create($data5);
            }

            /*
                DB::table('trx_order_detail')->upsert([
                    $data5,
                ],['trx_order_id','no_order','line_no','layanan_id'],['amount','qty','diskon','sub_amount']);*/
        }
        DB::select('call updtaxamt(' . $idOrder . ')');
        DB::select('call UpdPeriodeLayanan(' . $no_order . ',1)');
        return redirect('/admin/trx_order')->with('success', 'Berhasil menubah data Pesanan Pelanggan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrxOrder  $trxOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(TrxOrder $trxOrder)
    {
        // dd($trxOrder,TrxOrderDetail::where('no_order',$trxOrder->no_order)->get());
        DB::table('trx_order_detail')->where('no_order', $trxOrder->no_order)->delete();
        TrxOrder::destroy($trxOrder->id);
        // TrxOrderDetail::destroy($trxOrder->no_order);
        return redirect('/admin/trx_order')->with('success', 'Berhasil Menghapus Data Order Pelanggan');
    }

    public function getFormLayanan(Request $request)
    {
        $act = $request->action;
        $tipeOrder = $request->tipe_order;
        $role = Auth::user()->role;
        $user_id = Auth::user()->id;
        if ($tipeOrder == "1") {
            $title = 'Filter Data Registrasi Layanan';
            $subtitle = 'Registrasi Layanan';
        } elseif ($tipeOrder == "2") {
            $title = 'Filter Data Upgrade Layanan';
            $subtitle = 'Upgrade Layanan';
        } elseif ($tipeOrder == "3") {
            $title = 'Filter Data Downgrade Layanan';
            $subtitle = 'Downgrade Layanan';
        } elseif ($tipeOrder == "4") {
            $title = 'Filter Data Cuti Layanan';
            $subtitle = 'Cuti Layanan';
        } elseif ($tipeOrder == "5") {
            $title = 'Filter Data Berhenti Layanan';
            $subtitle = 'Berhenti Layanan';
        } elseif ($tipeOrder == "6") {
            $title = 'Filter Data Berhenti Langganan';
            $subtitle = 'Berhenti Langganan';
        }

        $statusTagihan = [
            ['id' => '0', 'name' => 'All'],
            ['id' => '1', 'name' => 'Lunas'],
            ['id' => '2', 'name' => 'Batal'],
            ['id' => '3', 'name' => 'Outstanding']
        ];
        $statusPelanggan = [
            ['id' => '0', 'name' => 'All'],
            ['id' => '1', 'name' => 'Nama'],
            ['id' => '2', 'name' => 'Unit'],
            ['id' => '3', 'name' => 'Nomer order'],
        ];

        if ($act == "show") {
            return view('administrator.order.formfilter', [
                'tipe_order' => $tipeOrder,
                'user_id' => $user_id,
                'level' => $role,
                'title' => $title,
                'subtitle' => $subtitle,
                'statustagihan' => $statusTagihan,
                'statuspelanggan' => $statusPelanggan,
            ]);
        } elseif ($act == "show_termin") {

            $termin = DB::select("call GetTerminPelanggan();");

            // dd($termin);
            return view('administrator.order.TerminPembayaran', [

                'level' => $role,
                'title' => 'Data Cicilan Pembayaran Pelanggan ',
                'termin' => $termin,
            ]);
        }
        if ($act == "create") {
            $internet = Layanan::where('jenis_layanan', 'Internet')->get();
            $tv = Layanan::wherein('jenis_layanan', ['tv', 'tv-tambahan'])->get();
            $telepony = Layanan::where('jenis_layanan', 'telephony')->get();
            $pasang = Layanan::where('id', '10')->get();

            if ($tipeOrder == "1") {

                $period = [
                    ['id' => '1', 'name' => 'Bulanan'],
                    ['id' => '2', 'name' => 'Tahunan'],
                ];
                return view('administrator.order.create', [
                    //  'order' => TrxOrder::all(),
                    'level' => $role,
                    'title' => 'Tambah Order Pelanggan',
                    'no_formulir' => TrxOrder::nomerformulir(),
                    'internet' => $internet,
                    'tv' => $tv,
                    'telepony' => $telepony,
                    'biaya_pasang' => $pasang,
                    'period' => $period,
                    'tipe_order' => $tipeOrder,
                ]);
            } elseif ($tipeOrder == "2") {
                // $internet = Layanan::where('jenis_layanan','Internet')->get();
                // $tv = Layanan::wherein('jenis_layanan',['tv','tv-tambahan'])->get();
                // $telepony = Layanan::where('jenis_layanan','telephony')->get();
                // $pasang = Layanan::where('id','10')->get();
                $period = [
                    ['id' => '1', 'name' => 'Promo 6+2'],
                    ['id' => '2', 'name' => 'Promo 9+3'],
                ];
                $metode_bayar = [
                    ['id' => '1', 'name' => 'Lunas'],
                    ['id' => '2', 'name' => 'Bertahap (Cicilan)'],
                ];
                return view('administrator.order.upgrade.create', [
                    //  'order' => TrxOrder::all(),
                    'level' => $role,
                    'title' => 'Order Upgrade Pelanggan',
                    'no_formulir' => TrxOrder::getNomer('2'),
                    'internet' => $internet,
                    'tv' => $tv,
                    'telepony' => $telepony,
                    'biaya_pasang' => $pasang,
                    'period' => $period,
                    'metode_bayar' => $metode_bayar,
                    'tipe_order' => $tipeOrder,
                ]);
            } elseif ($tipeOrder == "3") {
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
                    'title' => 'Order Downgrade Pelanggan',
                    'no_formulir' => TrxOrder::getNomer('3'),
                    'internet' => $internet,
                    'tv' => $tv,
                    'telepony' => $telepony,
                    'biaya_pasang' => $pasang,
                    'period' => $period,
                    'metode_bayar' => $metode_bayar,
                    'tipe_order' => $tipeOrder,
                ]);
            } elseif ($tipeOrder == "4") {
                $period = [
                    ['id' => '1', 'name' => 'Promo 6+2'],
                    ['id' => '2', 'name' => 'Promo 9+3'],
                ];
                $metode_bayar = [
                    ['id' => '1', 'name' => 'Lunas'],
                    ['id' => '2', 'name' => 'Bertahap (Cicilan)'],
                ];
                return view('administrator.order.cuti_layanan.create', [
                    //  'order' => TrxOrder::all(),
                    'level' => $role,
                    'title' => 'Order Cuti Langganan ',
                    'no_formulir' => TrxOrder::getNomer('4'),
                    'internet' => $internet,
                    'tv' => $tv,
                    'telepony' => $telepony,
                    'biaya_pasang' => $pasang,
                    'period' => $period,
                    'metode_bayar' => $metode_bayar,
                    'tipe_order' => $tipeOrder,
                ]);
            } elseif ($tipeOrder == "5") {
                $period = [
                    ['id' => '1', 'name' => 'Promo 6+2'],
                    ['id' => '2', 'name' => 'Promo 9+3'],
                ];
                $metode_bayar = [
                    ['id' => '1', 'name' => 'Lunas'],
                    ['id' => '2', 'name' => 'Bertahap (Cicilan)'],
                ];
                return view('administrator.order.stop_layanan.create', [
                    //  'order' => TrxOrder::all(),
                    'level' => $role,
                    'title' => 'Order Berhenti Langganan Pelanggan',
                    'no_formulir' => TrxOrder::getNomer('5'),
                    'internet' => $internet,
                    'tv' => $tv,
                    'telepony' => $telepony,
                    'biaya_pasang' => $pasang,
                    'period' => $period,
                    'metode_bayar' => $metode_bayar,
                    'tipe_order' => $tipeOrder,
                ]);
            } elseif ($tipeOrder == "6") {
                $period = [
                    ['id' => '1', 'name' => 'Promo 6+2'],
                    ['id' => '2', 'name' => 'Promo 9+3'],
                ];
                $metode_bayar = [
                    ['id' => '1', 'name' => 'Lunas'],
                    ['id' => '2', 'name' => 'Bertahap (Cicilan)'],
                ];
                return view('administrator.order.berhenti.create', [
                    //  'order' => TrxOrder::all(),
                    'level' => $role,
                    'title' => 'Order Berhenti Langganan Pelanggan',
                    'no_formulir' => TrxOrder::getNomer('6'),
                    'internet' => $internet,
                    'tv' => $tv,
                    'telepony' => $telepony,
                    'biaya_pasang' => $pasang,
                    'period' => $period,
                    'metode_bayar' => $metode_bayar,
                    'tipe_order' => $tipeOrder,
                ]);
            }
        }
    }

    public function getDataOrder(Request $request)
    {
        $role = Auth::user()->role;
        $tipeorder = $request->tipe_order;

        if ($tipeorder == "1") {
            $ttl = 'Data Registrasi Layanan';
            $subttl = 'Registrasi Layanan';
            $filter = '';
        } elseif ($tipeorder == "2") {
            $ttl = 'Data Upgrade Layanan';
            $subttl = 'Upgrade Layanan';
            $filter = '';
        } elseif ($tipeorder == "3") {
            $ttl = 'Data Downgrade Layanan';
            $subttl = 'Downgrade Layanan';
            $filter = '';
        } elseif ($tipeorder == "4") {
            $ttl = 'Data Cuti Layanan';
            $subttl = 'Cuti Layanan';
            $filter = '';
        } elseif ($tipeorder == "5") {
            $ttl = 'Data Berhenti Layanan';
            $subttl = 'Berhenti Layanan';
            $filter = '';
        } elseif ($tipeorder == "6") {
            $ttl = 'Data Berhenti Berlangganan';
            $subttl = 'Berhenti Berlangganan';
            $filter = '';
        }


        //   dd($request->all());
        if ($request->penel == "0") {
            if ($request->statuspelanggan == "0") {
                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->get(['trx_order.*']);
                $title = $ttl . ' Pelanggan';
                $subtitle = $subttl;
            }
            if ($request->statuspelanggan == "1") {
                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('pelanggan.nama_lengkap', 'like', '%' . $request->input_filter . '%')
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->get(['trx_order.*']);
                $title =  $ttl . ' Nama Pelanggan: ' . $request->input_filter;
                $subtitle = $subttl;
            }
            if ($request->statuspelanggan == "2") {
                // dd($request->input_filter,$sub_tower,$lantai,$nounit);
                $unit = explode('/', $request->input_filter);
                $sub_tower = $unit[0];
                $lantai = $unit[1];
                $nounit = $unit[2];

                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('pelanggan.sub_tower', $sub_tower)
                    ->where('pelanggan.lantai', $lantai)
                    ->where('pelanggan.nomer_unit', $nounit)
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->get(['trx_order.*']);
                $title =  $ttl . ' Unit ' . $request->input_filter;
                $subtitle = $subttl;
            }
            if ($request->statuspelanggan == "3") {
                $tagihan  = TrxOrder::where('no_order', $request->input_filter)
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->get(['trx_order.*']);
                $title =  $ttl;
                $subtitle = $subttl;
            }
        }
        //penel 1
        if ($request->penel == "1") {
            $validateData = $request->validate([
                'tgl_awal' => 'required',
                'tgl_akhir' => 'required',
                'statustagihan' => 'required',
                'statuspelanggan' => 'required'
            ]);


            $awal = $request->tgl_awal;
            $akhir = $request->tgl_akhir;
            $st = $request->statustagihan;
            $sp = $request->statuspelanggan;

            $unit = explode('/', $request->input_filter);
            $sub_tower = $unit[0];
            $lantai = $unit[1];
            $nounit = $unit[2];

            if ($st == "0" and $sp == "0") {
                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->wherebetween('tgl_order', [$awal, $akhir])
                    ->get(['trx_order.*']);
                $title = $ttl . ' Pelanggan';
                $subtitle = $subttl;
                $filter = ' Dari ' . date('d/m/Y', strtotime($awal)) . '-' . date('d/m/Y', strtotime($akhir));
            }
            if ($st == "0" and $sp == "1") {
                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->wherebetween('tgl_order', [$awal, $akhir])
                    ->where('pelanggan.nama_lengkap', 'like', '%' . $request->input_filter . '%')
                    ->get(['trx_order.*']);
                $title = $ttl . ' Pelanggan ' . $request->input_filter;
                $subtitle = $subttl;
                $filter = ' Dari ' . date('d/m/Y', strtotime($awal)) . '-' . date('d/m/Y', strtotime($akhir));
            }
            if ($st == "0" and $sp == "2") {

                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->wherebetween('tgl_order', [$awal, $akhir])
                    ->where('pelanggan.sub_tower', $sub_tower)
                    ->where('pelanggan.lantai', $lantai)
                    ->where('pelanggan.nomer_unit', $nounit)
                    ->get(['trx_order.*']);
                $title = $ttl . ' Pelanggan Unit ' . $request->input_filter;
                $subtitle = $subttl;
                $filter = ' Dari ' . date('d/m/Y', strtotime($awal)) . '-' . date('d/m/Y', strtotime($akhir));
            }
            if ($st == "0" and $sp == "3") {
                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->wherebetween('tgl_order', [$awal, $akhir])
                    ->where('no_order', $request->input_filter)
                    ->get(['trx_order.*']);
                $title = $ttl . ' Nomer Order ' . $request->input_filter;
                $subtitle = $subttl;
                $filter = ' Dari ' . date('d/m/Y', strtotime($awal)) . '-' . date('d/m/Y', strtotime($akhir));
            }
            if ($request->statustagihan == "1" and $request->statuspelanggan == "0") {
                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->wherebetween('tgl_order', [$awal, $akhir])
                    ->where('trx_order.payment_status', '2')
                    ->get(['trx_order.*']);
                $title = $ttl . ' All Pelanggan';
                $subtitle = $subttl;
                $filter = 'Dari ' . date('d/m/Y', strtotime($awal)) . '-' . date('d/m/Y', strtotime($akhir)) . ' Keterangan - Lunas';
            }
            if ($st == "1" and $sp == "1") {
                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->wherebetween('tgl_order', [$awal, $akhir])
                    ->where('pelanggan.nama_lengkap', 'like', '%' . $request->input_filter . '%')
                    ->where('trx_order.payment_status', '2')
                    ->get(['trx_order.*']);
                $title = $ttl . ' Data ' . $request->input_filter . ' Keterangan - Lunas';
                $subtitle = $subttl;
                $filter = ' Dari ' . date('d/m/Y', strtotime($awal)) . '-' . date('d/m/Y', strtotime($akhir));
            }
            if ($st == "1" and $sp == "2") {
                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->wherebetween('tgl_order', [$awal, $akhir])
                    ->where('pelanggan.sub_tower', $sub_tower)
                    ->where('pelanggan.lantai', $lantai)
                    ->where('pelanggan.nomer_unit', $nounit)
                    ->where('trx_order.payment_status', '2')
                    ->get(['trx_order.*']);
                $title = $ttl . ' Data Unit ' . $request->input_filter . ' Keterangan - Lunas';
                $subtitle = $subttl;
                $filter = ' Dari ' . date('d/m/Y', strtotime($awal)) . '-' . date('d/m/Y', strtotime($akhir));
            }
            if ($st == "1" and $sp == "3") {
                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->wherebetween('tgl_order', [$awal, $akhir])
                    ->where('no_order', $request->input_filter)
                    ->where('trx_order.payment_status', '2')
                    ->get(['trx_order.*']);
                $title = $ttl . ' Data Nomer Order ' . $request->input_filter . ' Keterangan - Lunas';
                $subtitle = $subttl;
                $filter = ' Dari ' . date('d/m/Y', strtotime($awal)) . '-' . date('d/m/Y', strtotime($akhir));
            }

            if ($request->statustagihan == "2" and $request->statuspelanggan == "0") {
                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->wherebetween('tgl_order', [$awal, $akhir])
                    ->where('trx_order.payment_status', '4')
                    ->get(['trx_order.*']);
                $title =  $ttl . ' Data All Pelanggan';
                $subtitle = $subttl;
                $filter = 'Dari ' . date('d/m/Y', strtotime($awal)) . '-' . date('d/m/Y', strtotime($akhir)) . ' Keterangan - Batal';
            }
            if ($st == "2" and $sp == "1") {
                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->wherebetween('tgl_order', [$awal, $akhir])
                    ->where('pelanggan.nama_lengkap', 'like', '%' . $request->input_filter . '%')
                    ->where('trx_order.payment_status', '4')
                    ->get(['trx_order.*']);
                $title = $ttl . ' Data ' . $request->input_filter . ' Keterangan - Batal';
                $subtitle = $subttl;
                $filter = ' Dari ' . date('d/m/Y', strtotime($awal)) . '-' . date('d/m/Y', strtotime($akhir));
            }
            if ($st == "2" and $sp == "2") {
                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->wherebetween('tgl_order', [$awal, $akhir])
                    ->where('pelanggan.sub_tower', $sub_tower)
                    ->where('pelanggan.lantai', $lantai)
                    ->where('pelanggan.nomer_unit', $nounit)
                    ->where('trx_order.payment_status', '4')
                    ->get(['trx_order.*']);

                $title = $ttl . ' Data Unit ' . $request->input_filter . ' Keterangan - Batal';
                $subtitle = $subttl;
                $filter = ' Dari ' . date('d/m/Y', strtotime($awal)) . '-' . date('d/m/Y', strtotime($akhir));
            }
            if ($st == "2" and $sp == "3") {
                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->wherebetween('tgl_order', [$awal, $akhir])
                    ->where('no_order', $request->input_filter)
                    ->where('trx_order.payment_status', '4')
                    ->get(['trx_order.*']);

                $title = $ttl . ' Data Nomer ' . $request->input_filter . ' Keterangan - Batal';
                $subtitle = $subttl;
                $filter = ' Dari ' . date('d/m/Y', strtotime($awal)) . '-' . date('d/m/Y', strtotime($akhir));
            }

            if ($request->statustagihan == "3" and $request->statuspelanggan == "0") {
                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->wherebetween('tgl_order', [$awal, $akhir])
                    ->whereNotIn('trx_order.payment_status', ['2'])
                    ->get(['trx_order.*']);
                $title = $ttl . ' Data All Pelanggan';
                $subtitle = $subttl;
                $filter = 'Dari ' . date('d/m/Y', strtotime($awal)) . '-' . date('d/m/Y', strtotime($akhir)) . ' Keterangan - Outstanding';
            }
            if ($st == "3" and $sp == "1") {
                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->wherebetween('tgl_order', [$awal, $akhir])
                    ->where('pelanggan.nama_lengkap', 'like', '%' . $request->input_filter . '%')
                    ->whereNotIn('trx_order.payment_status', ['2'])
                    ->get(['trx_order.*']);

                $title = $ttl . ' Data ' . $request->input_filter . ' Keterangan - Outstanding';
                $subtitle = $subttl;
                $filter = ' Dari ' . date('d/m/Y', strtotime($awal)) . '-' . date('d/m/Y', strtotime($akhir));
            }
            if ($st == "3" and $sp == "2") {
                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->wherebetween('tgl_order', [$awal, $akhir])
                    ->where('pelanggan.sub_tower', $sub_tower)
                    ->where('pelanggan.lantai', $lantai)
                    ->where('pelanggan.nomer_unit', $nounit)
                    ->whereNotIn('trx_order.payment_status', ['2'])
                    ->get(['trx_order.*']);

                $title = $ttl . ' Data Unit ' . $request->input_filter . ' Keterangan - Outstanding';
                $subtitle = $subttl;
                $filter = ' Dari ' . date('d/m/Y', strtotime($awal)) . '-' . date('d/m/Y', strtotime($akhir));
            }
            if ($st == "3" and $sp == "3") {
                $tagihan  = TrxOrder::join('pelanggan', 'trx_order.pelanggan_id', '=', 'pelanggan.id')
                    ->where('trx_order.tipe_order', $tipeorder)
                    ->wherebetween('tgl_order', [$awal, $akhir])
                    ->where('no_order', $request->input_filter)
                    ->whereNotIn('trx_order.payment_status', ['2'])
                    ->get(['trx_order.*']);

                $title = $ttl . ' Data Nomer  ' . $request->input_filter . ' Keterangan - Outstanding';
                $subtitle = $subttl;
                $filter = ' Dari ' . date('d/m/Y', strtotime($awal)) . '-' . date('d/m/Y', strtotime($akhir));
            }
        }
        //end penel 1
        //dd($tagihan);
        return view('administrator.order.index', [
            'order' => $tagihan,
            'level' => $role,
            'title' =>  $title,
            'subtitle' => $subtitle,
            'filter' => $filter,
            'tipe_order' => $tipeorder,
        ]);
    }
}
