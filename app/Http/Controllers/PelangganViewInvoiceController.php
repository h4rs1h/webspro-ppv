<?php

namespace App\Http\Controllers;

use PDF;
use Midtrans\Snap;
use App\Models\TrxBayar;
use App\Models\TrxOrder;
use App\Models\TrxTagihan;
use Midtrans\Notification;
use Illuminate\Http\Request;
use App\Models\TrxBayarDetail;
use App\Models\ViewTrxTagihan;
use App\Models\ViewTrxTagihanDtl;
use Illuminate\Support\Facades\DB;
use App\Models\ViewBayarOrderDetail;
use App\Models\ViewTagihanPelanggan;
use App\Models\ViewTrxOrderPelanggan;
use Illuminate\Support\Facades\Crypt;
use App\Models\ViewBayarOrderPelanggan;
use App\Models\TrxNonaktifLayananDetail;
use Illuminate\Support\Facades\Validator;

class PelangganViewInvoiceController extends Controller
{
	public function __construct()
    {
        // $this->middleware(['auth']);
        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is3ds');

    }
	
    //
    public function index(Request $request)
    {
        if($request->no_inv){

            $inv = $request->no_inv;
            $idPelanggan =$request->uid;
            $tg = TrxTagihan::where('no_tagihan',$inv)->first();
            $tBayar = TrxBayar::Where('no_tagihan',$tg->id)->first();
           // if($tBayar){
           //     if($tBayar->status_bayar=='1'){
           //         $status = 'Lunas';
           //     }elseif($tBayar->status_bayar=='2'){
           //         $status = 'Kurang Bayar';
           //     }elseif($tBayar->status_bayar=='3'){
           //         $status = 'Konfirmasi Finance';
           //     }
           // }else{
           //     $status = '';
           // }
			if($tBayar){
				if($tBayar->status_bayar=='1'){
					$status = 'Lunas';
					$page = 'pageinvlunas';
				}elseif($tBayar->status_bayar=='2'){
					$status = 'Kurang Bayar';
					$page = 'pageinvlunas';
				}elseif($tBayar->status_bayar=='3'){
					$status = 'Menunggu Validasi Finance';
					$page = 'pageinvlunas';
				}
			}else{
                $status = '';
                $page = 'pageinv';
            }

            $role=0;
            return view('beranda.'.$page,[
                'inv' => $tg,
                'tagihan' => ViewTagihanPelanggan::where('pelanggan_id',$idPelanggan )
                            ->where('no_bayar',$inv)->first(),
                'tagihandtl' => ViewTrxTagihanDtl::where('no_tagihan',$inv)->get(),
                'level' => $role,
                'status' => $status,
                'active' => ''
            ]);
        }else{
            //dd($request,$request->no_inv,$request->no_order);
            $no_order = $request->no_order;
            $idPelanggan =$request->uid;
            $tg = ViewTrxOrderPelanggan::where('no_order',$no_order)->first();
            //dd($tg);
            $tBayar = TrxBayar::Where('no_order',$no_order)
                    ->where('tipe_bayar','1')->first();
            if($tBayar){
                if($tBayar->status_bayar=='1'){
                    $status = 'Lunas';
                }elseif($tBayar->status_bayar=='2'){
                    $status = '';
                }elseif($tBayar->status_bayar=='3'){
                    $status = 'Konfirmasi Finance';
                }
            }else{
                $status = '';
            }
            $role=0;
            $tagdtl = DB::table('vGetInvPendaftaran')
                ->where('no_order',$no_order)
                ->wherenull('kwitansi')
                ->get();
               //  dd($tagdtl);
                $subTagih = 0;
                $ppn = 0;
                $tagihan = 0;
            foreach($tagdtl as $td){
                   // $td->amount;
                    $subTagih = $subTagih+$td->subtotal;
                    $tagihan = $td->Outstanding;
                    $tbilang = $td->terbilang;
                    $ppn = $ppn+$td->tax_amount;
            }

            return view('beranda.pageReg',[
                'inv' => $tg,
                'tagihan' => ViewTagihanPelanggan::where('pelanggan_id',$idPelanggan )
                            ->where('ket','reg')->first(),
                 'tagihandtl' => $tagdtl,
                 'subTagih' => $subTagih,
                 'ppn' => $ppn,
                'level' => $role,
                'status' => $status,
                'gttagihan' => $tagihan,
                'active' => ''
            ]);
            //dd($tg);
        }
    }
	public function inv(Request $request)
    {
        $inv = $request->no_inv;
        $idPelanggan =$request->uid;
        $tg = TrxTagihan::where('no_tagihan',$inv)->first();

       
        $role=0;
        return view('beranda.pageinv2',[
            'inv' => $tg,
            'tagihan' => ViewTagihanPelanggan::where('pelanggan_id',$idPelanggan )
                        ->where('no_bayar',$inv)->first(),
            'tagihandtl' => ViewTrxTagihanDtl::where('no_tagihan',$inv)->get(),
            'level' => $role,
            'active' => ''
        ]);
    }
    public function getInvoicePdf(Request $request){
		
		$path = base_path().'/../httpdocs/'.env('FOLDER_IN_PUBLIC_HTML').'/storage/LOGO1.png';
		$type = pathinfo($path,PATHINFO_EXTENSION);
		$dataimg = file_get_contents($path);
		$pic1 = 'data:image/'.$type.';base64,'.base64_encode($dataimg);
		$data["baseurl"] = $pic1;
		$path2 = base_path().'/../httpdocs/'.env('FOLDER_IN_PUBLIC_HTML').'/storage/LOGO2.png';
		$type2 = pathinfo($path2,PATHINFO_EXTENSION);
		$dataimg2 = file_get_contents($path2);
		$pic2 = 'data:image/'.$type2.';base64,'.base64_encode($dataimg2);
		$data["baseurl2"] = $pic2;
		$path3 = base_path().'/../httpdocs/'.env('FOLDER_IN_PUBLIC_HTML').'/storage/unpaid.png';
		$type3 = pathinfo($path3,PATHINFO_EXTENSION);
		$dataimg3 = file_get_contents($path3);
		$pic3 = 'data:image/'.$type3.';base64,'.base64_encode($dataimg3);
		$data["baseurl3"] = $pic3;
		$path4 = base_path().'/../httpdocs/'.env('FOLDER_IN_PUBLIC_HTML').'/storage/paid.png';
		$type4 = pathinfo($path4,PATHINFO_EXTENSION);
		$dataimg4 = file_get_contents($path4);
		$pic4 = 'data:image/'.$type4.';base64,'.base64_encode($dataimg4);
		$data["baseurl4"] = $pic4;
		
		if($request->no_inv){		
		
        $data["tagihan"] = ViewTrxTagihan::where('no_tagihan',$request->no_inv)->first();
        $data['tagihandtl'] = ViewTrxTagihanDtl::where('no_tagihan',$request->no_inv)->get();
        $data["title"] = "Invoice Tagihan ".$request->no_tagihan;

        // return view('administrator.tagihan.invoice', $data);

       $pdf = PDF::loadView('administrator.tagihan.invoice', $data);

      // return $pdf->setPaper('A4','portrait')->stream();
      return $pdf->setPaper('A4','portrait')->download('inv_'.$request->no_inv.'.pdf');
		}
		else{
            $idOrder = TrxOrder::where('no_order',$request->no_order)->first()->id;
           // dd($idOrder);
            $tag = ViewTrxOrderPelanggan::where('id',$idOrder)->first();
            $tagdtl = DB::table('vGetInvPendaftaran')
                    ->where('id',$idOrder)
                    ->wherenull('kwitansi');

            if($tagdtl->count()>0){
                //dd($tagdtl->get());
                $subTagih = 0;
                foreach($tagdtl->get() as $td){
                    // $td->amount;
                        $subTagih = $subTagih+$td->subtotal;
                        $tagihan = $td->Outstanding;
                        $tbilang = $td->terbilang;
                }
            //  dd($tag);
                $data["order"] = $tag;
                $data["order_dtl"] = $tagdtl->get();
                $data["title"] = "Invoice Pendaftarn Layanan Pelanggan";
                $data['subtagihan'] = $subTagih;
                $data['tagihan'] = $tagihan;
                $data['tbilang'] = $tbilang;

            //   return view('administrator.order.inv_tagihan_daftar', $data);

            $pdf = PDF::loadView('administrator.order.inv_tagihan_daftar', $data);

            return $pdf->setPaper('A4','portrait')->stream();
            }else{
                return redirect('/viewinvoice?no_order='.$request->no_order.'&uid='.$request->uid);
            }
        }
		

    }
	
	public function store_bukti_bayar(Request $request){
       // dd($request);
		
        $request->validate([
           'tgl_bayar' => 'required|date',
            'nominal_bayar' => 'required|numeric|min:50000',
            'image' => 'required|mimes:jpeg,png,jpg|image|file|max:1024',
        ]);
        $tgl = DB::select("call getNoBayar('".$request->tgl_bayar."')");
        foreach($tgl as $t){
            $no = $t->hasil;
        }
		$idOrder =TrxTagihan::where('id',$request->trx_tagihan_id)->first('trx_order_id');
		$noOrder =  TrxOrder::Where('id',$idOrder->trx_order_id)->first('no_order');
        $hdrTrxBayar = ([
            'no_bayar' => $no,
            'tgl_bayar' => $request->tgl_bayar,
            'tipe_bayar' => '2',
            'no_order' => $noOrder->no_order,
            'no_tagihan'=> $request->trx_tagihan_id,
            'user_id' => $request->pelanggan_id,
            'pelanggan_id' => $request->pelanggan_id,
            'metode_bayar' => '4',
            'amount' => $request->nominal_bayar,
            'status_bayar' => '3',
            'catatan' => 'Bukti Transfer Upload Pelanggan',
            'tgl_bukti_bayar' => $request->tgl_bayar
        ]);

        if($request->file('image')){
            $hdrTrxBayar['bukti_bayar'] = $request->file('image')->store('bukti_bayar_images');
        }

        //simpan ke tabel trx_bayar
        $idBayar = TrxBayar::create($hdrTrxBayar)->id;
		// DB::select("call GetUpdExpDateFromBayar('".$idBayar."','2')");
		
        $dtlInv = ViewTrxTagihanDtl::where('trx_tagihan_id',$request->trx_tagihan_id)->get();
        foreach($dtlInv as $dt){
            $dtlBayar =([
                'trx_bayar_id' => $idBayar,
                'line_no' => $dt->line_no,
                'layanan_id' => $dt->layanan_id,
                'amount' => $dt->amount_tagihan,
                'qty' => 1,
                'diskon' => null,
                'sub_amount' => $dt->amount_tagihan,
                'ppn_amount' => $dt->amount_tagihan * 0.11,
                'total_amount' => ($dt->amount_tagihan * 0.11)+$dt->amount_tagihan,
                'payment_amount' => ($dt->amount_tagihan * 0.11)+$dt->amount_tagihan,
            ]);
            TrxBayarDetail::create($dtlBayar);
        }

        $link = "/viewinvoice?no_inv=".$request->nomer_tagihan."&uid=".$request->pelanggan_id;
        //simpan/update ke tabel trx_bayar_detail copy dari trx_tagihan_detail
        return redirect($link)->with('success','Berhasil Upload Bukti pembayaran');
    }
	public function store_bukti_bayar_daftar(Request $request){
        //dd($request);
        $request->validate([
            'tgl_bayar' => 'required|date',
            'nominal_bayar' => 'required|numeric|min:50000',
            'image' => 'image|file|max:1024',
        ]);
        $tgl = DB::select("call getNoBayar('".$request->tgl_bayar."')");
        foreach($tgl as $t){
            $no = $t->hasil;
        }

        $hdrTrxBayar = ([
            'no_bayar' => $no,
            'tgl_bayar' => $request->tgl_bayar,
            'tipe_bayar' => '1',
            'no_order' => $request->no_order,
            'trx_order_id'=> $request->trx_order_id,
            'user_id' => $request->pelanggan_id,
            'pelanggan_id' => $request->pelanggan_id,
            'metode_bayar' => '4',
            'amount' => $request->nominal_bayar,
            'status_bayar' => '3',
            'catatan' => 'Bukti Transfer Upload Pelanggan',
            'tgl_bukti_bayar' => $request->tgl_bayar
        ]);

        if($request->file('image')){
            $hdrTrxBayar['bukti_bayar'] = $request->file('image')->store('bukti_bayar-images');
        }

        //simpan ke tabel trx_bayar
        $cek = TrxBayar::where('no_order',$request->no_order)
                        ->where('trx_order_id',$request->trx_order_id)
                        ->where('pelanggan_id',$request->pelanggan_id)
                        ->where('amount',$request->nominal_bayar)->first();
        if(!$cek){
            // dd($cek);
            $idBayar = TrxBayar::create($hdrTrxBayar)->id;

            // $dtlInv = ViewTrxTagihanDtl::where('trx_tagihan_id',$request->trx_order_id)->get();
            $dtlInv = DB::table('vGetInvPendaftaran')
                        ->where('id',$request->trx_order_id)
                        ->wherenull('kwitansi')
                        ->get();
            foreach($dtlInv as $dt){
                $dtlBayar =([
                    'trx_bayar_id' => $idBayar,
                    'line_no' => $dt->line_no,
                    'layanan_id' => $dt->layanan_id,
                    'amount' => $dt->amount,
                    'qty' => 1,
                    'diskon' => null,
                    'sub_amount' => $dt->subtotal,
                    'ppn_amount' => $dt->tax_amount,
                    'total_amount' => $dt->sub_amount,
                    'payment_amount' => $dt->sub_amount,
                ]);
                TrxBayarDetail::create($dtlBayar);
            }

            $link = "/viewinvoice?no_order=".$request->no_order."&uid=".$request->pelanggan_id;
            //simpan/update ke tabel trx_bayar_detail copy dari trx_tagihan_detail
            return redirect($link)->with('success','Berhasil Upload Bukti pembayaran');
        }
    }
	public function confim_bayar(Request $request){
        //dd($request);
        $inv = $request->no_inv;
        $idPelanggan =$request->uid;
        $tg = TrxTagihan::where('no_tagihan',$inv)->first();
		
		$TagihanDtl = DB::table('vtrx_tagihan_layanan_detail')
                            ->select('layanan_id','sub_amount','pemakaian')
                            ->where('id',$tg->id)->get();
        $TagihanDataPelanggan = DB::table('vtrx_tagihan_pelanggan')
                            ->select('nama_lengkap','nomer_hp','email')
                            ->where('id',$tg->id)->first();
        $biaya = 6000;

        $tg->biaya_transaksi_midtrans = $biaya;
        $tg->gtot_bayar_midtrans = $biaya+$tg->gtot_tagihan;

        $tg ->save();

        $snapToken = $tg->snap_token;
		
		if (empty($snapToken)) {

            foreach ($TagihanDtl as $item){
               $res[] = [
                    'id' => $item->layanan_id,
                    'price' => $item->sub_amount,
                    'quantity' => 1,
                    'name' => $item->pemakaian,
               ];
            }
            $res[] = [
                'id' => 10,
                'price' => $biaya,
                'quantity' => 1,
                'name' => 'Biaya Transfer',
            ];
           // dd($res);
            $params = [
                'transaction_details' => [
                    'order_id' => 'Inv-'.$tg->no_tagihan,
                    'gross_amount' => $tg->gtot_bayar_midtrans,
                ],
                'item_details' => $res,
                'customer_details' => [
                    'first_name' => $TagihanDataPelanggan->nama_lengkap,
                    'email' => $TagihanDataPelanggan->email ,
                    'phone' => $TagihanDataPelanggan->nomer_hp,
                ]
            ];
           // dd($params);
            $snapToken = Snap::getSnapToken($params);

            // $midtrans = new CreateSnapTokenService($order);
            // $snapToken = $midtrans->getSnapToken();

            $tg ->snap_token = $snapToken;
            $tg ->save();
        }
		
		//dd($request,$snapToken);
        $role=0;
        return view('beranda.pageinvmidtrans',[
            'inv' => $tg,
            'tagihan' => ViewTagihanPelanggan::where('pelanggan_id',$idPelanggan )
                        ->where('no_bayar',$inv)->first(),
            'tagihandtl' => ViewTrxTagihanDtl::where('no_tagihan',$inv)->get(),
            'level' => $role,

            'active' => '',
            'snapToken' => $snapToken
        ]);
    }
    public function store_midtras_bayar(Request $request){

	 //dd($request);
        $TagihanHdr = TrxTagihan::where('id',$request->trx_tagihan_id)->first();
        $TagihanDtl = DB::table('vtrx_tagihan_layanan_detail')
                            ->select('layanan_id','sub_amount','pemakaian')
                            ->where('id',$request->trx_tagihan_id)->get();
        $TagihanDataPelanggan = DB::table('vtrx_tagihan_pelanggan')
                            ->select('nama_lengkap','nomer_hp','email')
                            ->where('id',$request->trx_tagihan_id)->first();
     //   dd($request,$TagihanDtl);

        $snapToken = $TagihanHdr->snap_token;
      
        if (empty($snapToken)) {

            foreach ($TagihanDtl as $item){
               $res[] = [
                    'id' => $item->layanan_id,
                    'price' => $item->sub_amount,
                    'quantity' => 1,
                    'name' => $item->pemakaian,
               ];
            }
            $res[] = [
                'id' => 10,
                'price' => $request->in_biaya,
                'quantity' => 1,
                'name' => 'Biaya Transfer',
            ];
           // dd($res);
            $params = [
                'transaction_details' => [
                    'order_id' => 'Inv-'.$request->nomer_tagihan,
                    'gross_amount' => $request->in_gtot_bayar,
                ],
                'item_details' => $res,
                'customer_details' => [
                    'first_name' => $TagihanDataPelanggan->nama_lengkap,
                    'email' => $TagihanDataPelanggan->email ,
                    'phone' => $TagihanDataPelanggan->nomer_hp,
                ]
            ];
            //dd($params);
            $snapToken = Snap::getSnapToken($params);

            // $midtrans = new CreateSnapTokenService($order);
            // $snapToken = $midtrans->getSnapToken();

            $TagihanHdr->snap_token = $snapToken;
            $TagihanHdr->save();
        }
		//  dd($request,$snapToken, $TagihanDataPelanggan,$TagihanDtl);
        $role=0;
        $status = '';
        return view('beranda.pageinvmidtrans',[
            'inv' => $TagihanHdr,
            'tagihan' => ViewTagihanPelanggan::where('pelanggan_id',$request->pelanggan_id)
                        ->where('no_bayar',$TagihanHdr->no_tagihan)->first(),
            'tagihandtl' => ViewTrxTagihanDtl::where('no_tagihan',$TagihanHdr->no_tagihan)->get(),
            'level' => $role,
            'status' => $status,
            'active' => '',
            'snapToken' => $snapToken
        ]);
    }

    public function notifications(Request $request)
    {
        $notif = new Notification();

        DB::transaction(function() use($notif) {

            $transaction = $notif->transaction_status;
            $tgl = $notif->transaction_time;
            $type = $notif->payment_type;
            $orderId = explode("-",$notif->order_id);
            $fraud = $notif->fraud_status;
            $donation = TrxTagihan::where('no_tagihan', $orderId[1])->first();
          //  dd($donation);
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {

                if($fraud == 'challenge') {
                    $donation->setStatusPending();
                } else {
                    $donation->setStatusSuccess();
					// buat transaksi bayar jika satlemen berhasil
        $tgl_bayar = DB::select("call getNoBayar('".$notif->transaction_time."')");
        foreach($tgl_bayar as $t){
            $no = $t->hasil;
        }

        $trxtagih = DB::table('vtagihanhdr')->where('no_tagihan',$orderId[1])->first();
      //  dd($no,$trxtagih);

        $hdrTrxBayar = ([
            'no_bayar' => $no,
            'tgl_bayar' => $notif->transaction_time,
            'tipe_bayar' => '2',
            'no_order' => $trxtagih->no_order,
            'no_tagihan'=> $trxtagih->id,
            'user_id' => $trxtagih->pelanggan_id,
            'pelanggan_id' => $trxtagih->pelanggan_id,
            'metode_bayar' => '5',
            'amount' => $trxtagih->gtot_tagihan,
            'status_bayar' => '3',
            'catatan' => 'Payment Gateway Midtrans',
            'tgl_bukti_bayar' => $notif->transaction_time
        ]);

        //dd( $hdrTrxBayar);
        //simpan ke tabel trx_bayar
        //simpan ke tabel trx_bayar
        $cek = TrxBayar::where('no_order',$trxtagih->no_order)
                        ->where('no_tagihan',$trxtagih->id)
                        ->where('pelanggan_id',$trxtagih->pelanggan_id)
                        ->where('amount',$trxtagih->gtot_tagihan)->first();
        if(!$cek){
          //  dd($cek);
            $idBayar = TrxBayar::create($hdrTrxBayar)->id;

            $dtlInv = ViewTrxTagihanDtl::where('trx_tagihan_id',$trxtagih->id)->get();
            foreach($dtlInv as $dt){
                $dtlBayar =([
                    'trx_bayar_id' => $idBayar,
                    'line_no' => $dt->line_no,
                    'layanan_id' => $dt->layanan_id,
                    'amount' => $dt->amount_tagihan,
                    'qty' => 1,
                    'diskon' => null,
                    'sub_amount' => $dt->amount_tagihan,
                    'ppn_amount' => $dt->ppn_amount,
                    'total_amount' => $dt->total_amount,
                    'payment_amount' => $dt->total_amount,
                ]);
                TrxBayarDetail::create($dtlBayar);
            }
        }

        // end transksi satlemen
                }

            }
            } elseif ($transaction == 'settlement') {
                $donation->tgl_bayar_midtrans = $tgl;
                $donation->save();
                $donation->setStatusSuccess();
				
				// buat transaksi bayar jika satlemen berhasil
        $tgl_bayar = DB::select("call getNoBayar('".$notif->transaction_time."')");
        foreach($tgl_bayar as $t){
            $no = $t->hasil;
        }

        $trxtagih = DB::table('vtagihanhdr')->where('no_tagihan',$orderId[1])->first();
      //  dd($no,$trxtagih);

        $hdrTrxBayar = ([
            'no_bayar' => $no,
            'tgl_bayar' => $notif->transaction_time,
            'tipe_bayar' => '2',
            'no_order' => $trxtagih->no_order,
            'no_tagihan'=> $trxtagih->id,
            'user_id' => $trxtagih->pelanggan_id,
            'pelanggan_id' => $trxtagih->pelanggan_id,
            'metode_bayar' => '5',
            'amount' => $trxtagih->gtot_tagihan,
            'status_bayar' => '3',
            'catatan' => 'Payment Gateway Midtrans',
            'tgl_bukti_bayar' => $notif->transaction_time
        ]);

        //dd( $hdrTrxBayar);
        //simpan ke tabel trx_bayar
        //simpan ke tabel trx_bayar
        $cek = TrxBayar::where('no_order',$trxtagih->no_order)
                        ->where('no_tagihan',$trxtagih->id)
                        ->where('pelanggan_id',$trxtagih->pelanggan_id)
                        ->where('amount',$trxtagih->gtot_tagihan)->first();
        if(!$cek){
          //  dd($cek);
            $idBayar = TrxBayar::create($hdrTrxBayar)->id;
			// DB::select("call GetUpdExpDateFromBayar('".$idBayar."','2')");
            $dtlInv = ViewTrxTagihanDtl::where('trx_tagihan_id',$trxtagih->id)->get();
            foreach($dtlInv as $dt){
                $dtlBayar =([
                    'trx_bayar_id' => $idBayar,
                    'line_no' => $dt->line_no,
                    'layanan_id' => $dt->layanan_id,
                    'amount' => $dt->amount_tagihan,
                    'qty' => 1,
                    'diskon' => null,
                    'sub_amount' => $dt->amount_tagihan,
                    'ppn_amount' => $dt->ppn_amount,
                    'total_amount' => $dt->total_amount,
                    'payment_amount' => $dt->total_amount,
                ]);
                TrxBayarDetail::create($dtlBayar);
            }
        }

        // end transksi satlemen

            } elseif($transaction == 'pending'){

                $donation->setStatusPending();

            } elseif ($transaction == 'deny') {

                $donation->setStatusFailed();

            } elseif ($transaction == 'expire') {

                $donation->setStatusExpired();

            } elseif ($transaction == 'cancel') {

                $donation->setStatusFailed();

            }

            

        });


        return;
    }
	
	public function getViewKwitansi($data_rahasia)
    {

        $data = explode('&',Crypt::decrypt($data_rahasia)) ;
        $tipe = $data[0];
        $id = explode('=',$data[1]);
		if($tipe=="viewtagihantermin"){
			$tipe_order = explode('=',$data[2]);
		}
      //  dd($request->all(),$request->show,$data,$tipe,$id[1]);
        $dtl = ViewBayarOrderDetail::where('id',$id[1])->get();
        $hdr = ViewBayarOrderPelanggan::where('id',$id[1])->first();
 		$tagihan = ViewTrxTagihan::where('id',$hdr->id_order_or_tagihan)->first();
		// dd($trxOrder->id,$trxOrder->no_order);
		$path = base_path().'/../httpdocs/'.env('FOLDER_IN_PUBLIC_HTML').'/storage/LOGO1.png';
		$type = pathinfo($path,PATHINFO_EXTENSION);
		$dataimg = file_get_contents($path);
		$pic1 = 'data:image/'.$type.';base64,'.base64_encode($dataimg);
		$data["baseurl"] = $pic1;
		$path2 = base_path().'/../httpdocs/'.env('FOLDER_IN_PUBLIC_HTML').'/storage/LOGO2.png';
		$type2 = pathinfo($path2,PATHINFO_EXTENSION);
		$dataimg2 = file_get_contents($path2);
		$pic2 = 'data:image/'.$type2.';base64,'.base64_encode($dataimg2);
		$data["baseurl2"] = $pic2;
		
		
		
        if($tipe=='kwitansi'){
			
			$periode = DB::select("call GetPeriodePemakaianFromOrder('".$id[1]."')");
            $data["bayar"] = $hdr;
            $data["order_dtl"] = $dtl;
			$data["tagihan"] =  $tagihan;
			$data["periode"] =  $periode;
            $data["title"] = "Kwitansi Pembayaran";
            $pdf = PDF::loadView('administrator.bayar.kwitansi', $data);
			return $pdf->setPaper('A4','portrait')->stream();
        }
        elseif($tipe=='ttbayar'){
			$periode = DB::select("call GetPeriodePemakaianFromOrder('".$id[1]."')");
			
            $data["bayar"] = $hdr;
            $data["order_dtl"] = $dtl;
			 $data["tagihan"] =  $tagihan;
			$data["periode"] =  $periode;
            $data["title"] = "Kwitansi Pembayaran";
            $pdf = PDF::loadView('administrator.bayar.ttbayar', $data);
			return $pdf->setPaper('A4','portrait')->stream();
        }
		elseif($tipe=="viewnonaktif"){
            $role=0;
            $data = TrxNonaktifLayananDetail::join('pelanggan','pelanggan.id','=','trx_nonaktif_layanan_detail.pelanggan_id')
                                            ->where('trx_nonaktif_layanan_detail.id',$id[1])
											->distinct()
											->get(['pelanggan.nama_lengkap','pelanggan.sub_tower','pelanggan.lantai','pelanggan.nomer_unit','pelanggan.cid']);
            //dd($data);
            return view('beranda.pageListNonAktif',[
                'isi_data' => $data,
                'level' => $role,
                'active' => ''
            ]);

        }elseif($tipe=="viewtagihantermin"){
            $role=0;
            $tag = DB::select("call GetInvTagihanTermin(". $id[1].",".$tipe_order[1].")");
    //    dd($request->all(), $tag );
        $tagdtl = DB::select("call GetInvTagihanTermin(".$id[1].",".$tipe_order[1].")");

        foreach($tag as $hd){
            $no_tagihan = $hd->no_formulir;
            $no_pelanggan = $hd->no_pelanggan;
            $no_unit = $hd->unitid;
            $tgl_tagih = $hd->tgl_tagih;
            $tgl_jt_tempo = $hd->tgl_jt_tempo;
            $tagihan = $hd->total_tagihan;
            $termin = $hd->jml_bayar+1;
            $nama_lengkap = $hd->nama_lengkap;
            $alamat = $hd->alamat_identitas;
            $tbilang = $hd->terbilang;
            $sub_tower= $hd->sub_tower;
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

        return $pdf->setPaper('A4','portrait')->stream();
        }
        
    }
}
