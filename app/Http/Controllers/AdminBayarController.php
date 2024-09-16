<?php

namespace App\Http\Controllers;

use App\Models\TrxBayar;
use App\Models\TrxOrder;
use App\Models\Pelanggan;
// use Barryvdh\DomPDF\PDF;
use App\Models\TrxOutbox;
use App\Models\TrxTagihan;
use Illuminate\Http\Request;
use App\Models\TrxBayarDetail;
use App\Models\ViewTrxTagihan;
use App\Models\ViewOutstdTagihan;
use App\Models\ViewTrxTagihanDtl;
use Illuminate\Support\Facades\DB;
use App\Models\ViewBayarOrderDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\ViewOutstdPendaftaran;
use App\Models\ViewTrxOrderPelanggan;
use Illuminate\Support\Facades\Crypt;
use App\Models\ViewBayarOrderPelanggan;
use App\Models\ViewTrxTagihanPelanggan;
use Illuminate\Support\Facades\Storage;
use App\Models\ViewTrxOrderLayananDetail;
use App\Models\ViewTrxTagihanLayananDetail;
Use PDF;

class AdminBayarController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //  $bayar = ViewBayarOrderPelanggan::select("*")->get();//->toArray();
		$statusTagihan = [
            ['id' =>'0','name' =>'All'],
            ['id' =>'1','name' =>'Lunas'],
            ['id' =>'2','name' =>'Batal'],
            ['id' =>'3','name' =>'Outstanding']
            ];
		$statusPelanggan = [
                ['id' =>'0','name' =>'All'],
                ['id' =>'1','name' =>'Nama'],
                ['id' =>'2','name' =>'Unit'],
                ['id' =>'3','name' =>'Tanggal Bayar'],
				['id' =>'4','name' =>'Tanggal Mutasi Bank'],
                // ['id' =>'3','name' =>'Nomer Tagihan'],
                // ['id' =>'4','name' =>'Nomer Pembayaran'],
                // ['id' =>'5','name' =>'Tanggal Pembayaran'],
                ];
        //dd($bayar);
        $role = Auth::user()->role;
		$id_user = Auth::user()->id;
        //return view('administrator.bayar.index',[
		return view('administrator.bayar.formfilter',[
           // 'order' => $bayar,
            'level' => $role,
			'id_user' =>$id_user,
            'statustagihan' => $statusTagihan,
            'statuspelanggan' => $statusPelanggan,
            'title' => 'Pembayaran Pelanggan',
        ]);
    }
 public function getOut(Request $request)
    {
        //dd($request->aksi);
        $role = Auth::user()->role;

        if($request->aksi=='daftar'){
            $bayar = ViewOutstdPendaftaran::where('outstanding','<>','0')
                    ->whereIn('tipe_order',['1'])->get();//->toArray();
            //dd($bayar);
            return view('administrator.bayar.outstd',[
                'outstd' => $bayar,
                'level' => $role,
                'title' => 'Data Outstanding Pembayaran Order Layanan',
            ]);
        }
        if($request->aksi=='upgrade'){
            $bayar = ViewOutstdPendaftaran::where('outstanding','<>','0')
				->where('payment_status','<>','4')
                    ->where('tipe_order','2')->get();//->toArray();
            //dd($bayar);
            return view('administrator.bayar.outstd',[
                'outstd' => $bayar,
                'level' => $role,
                'title' => 'Data Outstanding Pembayaran Upgrade',
            ]);
        }
        if($request->aksi=='tagihan'){
            $bayar = ViewOutstdTagihan::where('outstanding','>','0')->get();//->toArray();

            //dd($bayar);
            return view('administrator.bayar.outstdtagihan',[
                'outstd' => $bayar,
                'level' => $role,
                'title' => 'Data Outstanding Pembayaran Tagihan',
            ]);
        }

    }
	public function getoutdaftar()
    {
        $bayar = ViewOutstdPendaftaran::where('outstanding','<>','0')->where('payment_status','<>','4')->whereIn('tipe_order',['1','3','4','5','6'])->get();//->toArray();

        //dd($bayar);
        $role = Auth::user()->role;
        return view('administrator.bayar.outstd',[
            'outstd' => $bayar,
            'level' => $role,
            'title' => 'Data Outstanding Pembayaran  Order Layanan',
        ]);
    }
	public function getouttagihan()
    {
        $bayar = ViewOutstdTagihan::where('outstanding','>','0')->get();//->toArray();

        //dd($bayar);
        $role = Auth::user()->role;
        return view('administrator.bayar.outstdtagihan',[
            'outstd' => $bayar,
            'level' => $role,
            'title' => 'Data Outstanding Pembayaran Tagihan',
        ]);
    }
	public function getcheckno_bayar(Request $request)
    {

        $tgl = DB::select("call getNoBayar('".$request->tgl_bayar."')");
        foreach($tgl as $t){
            $no = $t->hasil;
        }
        $data = array(
            'no_bayar' => $no
        );

        //dd(response()->json($data));
        return response()->json($data);

    }
	public function getApproved(Request $request){
        $id_user = Auth::user()->id;
        if($id_user=='1' or $id_user=='14' or  $id_user=='20' or  $id_user=='37'){
            $valid = TrxBayar::where('no_bayar',$request->no_bayar)->wherenull('tgl_mutasi_bank')->select('tgl_mutasi_bank')->exists();
          //  dd($valid);
            if($valid)
            {
                return redirect(url('admin/trx_bayar'))->with('danger','Approved Gagal, Tanggal mutasi bank masih kosong');

            }else{
                $hdrTrxBayar['approved_by'] = $id_user;
                TrxBayar::where('no_bayar',$request->no_bayar)
                            ->update($hdrTrxBayar);

                return redirect(url('admin/trx_bayar'))->with('success','Berhasil Approved Pembayaran');

            }
        }
        else {
            return redirect(url('admin/trx_bayar'))->with('danger','Approved Gagal, User tidak memiliki akses');
        }

    }
    public function getBayar(Request $request){
        $metode_bayar = [
            ['id' =>'1','name' =>'QRIS'],
            ['id' =>'2','name' =>'Kartu Kredit'],
            ['id' =>'3','name' =>'Debit'],
            ['id' =>'4','name' =>'Transfer'],
            ['id' =>'5','name' =>'Payment Gateway'],
			['id' =>'6','name' =>'Deposit'],
            ];
        $status_bayar = [
                ['id' =>'1','name' =>'Lunas'],
                ['id' =>'2','name' =>'Bertahap (Kurang Bayar)'],
			 ['id' =>'3','name' =>'Pelanggan Upload Bukti Bayar'],
                ];
		 $list_user = [
            ['id' => '1','name' =>'Administrator'],
            ['id' => '14','name' =>'Yanty Salim' ],
            ['id' => '20','name' =>'Sere Puspa' ],
        ];

        $role = Auth::user()->role;
        $id_user = Auth::user()->id;
        
if($request->no_order)
        {
        return view('administrator.bayar.create_bayar',[
            'orderhdr' => ViewTrxOrderPelanggan::where('no_order',$request->no_order)->where('tipe_order',$request->tipe_order)->first(),
            'orderdtl' => ViewTrxOrderLayananDetail::where('no_order',$request->no_order)->where('tipe_order',$request->tipe_order)->get(),
            'level' => $role,
            'metode_bayar' => $metode_bayar,
            'status_bayar' => $status_bayar,
            'title' => 'Pembayaran Pendaftaran',
        ]);
	}
        elseif($request->no_tagihan){
            return view('administrator.bayar.create_bayar_tagihan',[
                'orderhdr' => ViewTrxTagihanPelanggan::where('id',$request->no_tagihan)->first(),
                'orderdtl' => ViewTrxTagihanLayananDetail::where('id',$request->no_tagihan)->get(),
                'level' => $role,
                'metode_bayar' => $metode_bayar,
                'status_bayar' => $status_bayar,
                'title' => 'Pembayaran Pendaftaran',
            ]);

        }else{
            $dthdr = ViewBayarOrderPelanggan::where('id',$request->no_bayar)->first();
           // dd(ViewTrxTagihan::where('id',$dthdr->id_order_or_tagihan)->first());
            return view('administrator.bayar.edit_bayar_tagihan',[
                'orderhdr' => ViewBayarOrderPelanggan::where('id',$request->no_bayar)->first(),
                'orderdtl' => ViewBayarOrderDetail::where('id',$request->no_bayar)->get(),
                'tagihan' => ViewTrxTagihan::where('id',$dthdr->id_order_or_tagihan)->first(),
                'level' => $role,
                'metode_bayar' => $metode_bayar,
                'status_bayar' => $status_bayar,
                'title' => 'Edit Pembayaran',
				'approved' => $list_user,
                'id_user' => $id_user,
            ]);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$nopelanggan = DB::select("call getNoPelanggan('".$request->no_unit."')");
        foreach($nopelanggan as $t){
            $no = $t->kodebaru;
        }
        $dataPelanggan = (['no_pelanggan'=> $no]);
        Pelanggan::where('id', $request->pelanggan_id)
                  ->where('no_pelanggan',NULL)
                ->update($dataPelanggan);
		
        // dd($request,$request->no_bayar);
        $hdrTrxBayar = $request->validate([
            'no_bayar' => 'required|max:10',
            'tgl_bayar' => 'required|date',
            'amount' => 'required',
            'metode_bayar' => 'required',
            'status_bayar' => 'required',
			'catatan' => 'required',
        ]);
		$idTrxOrder = DB::table('trx_order')->select('id')->where('no_order',$request->no_order)->where('tipe_order',$request->tipe_order)->first();
        $hdrTrxBayar['user_id'] = auth()->user()->id;
        $hdrTrxBayar['pelanggan_id'] = $request->pelanggan_id;
        $hdrTrxBayar['no_order'] = $request->no_order;
		$hdrTrxBayar['trx_order_id'] = $idTrxOrder->id;
		$hdrTrxBayar['tipe_bayar'] = '1';
		$hdrTrxBayar['biaya_transaksi'] = $request->biaya_transaksi;
		$hdrTrxBayar['tgl_bukti_bayar'] = $request->tgl_bayar;
		
		if($request->file('image')){
            $hdrTrxBayar['bukti_bayar'] = $request->file('image')->store('bukti_bayar-images');
        }
        $idBayar = TrxBayar::create($hdrTrxBayar)->id;
		
		//update status pembayaran di trx_order_id
        if($request->status_bayar=='1'){
            $updOrder = (['payment_status' => '2']);
			
        }else{
            $updOrder = (['payment_status' => '5']);
		
        }
        TrxOrder::where('id',$idTrxOrder->id)->update($updOrder);
        
		 DB::select("call GetUpdExpDateFromBayar('".$idBayar."','1')");
		
        if($request->nominal_bayar_Internet){
            $data=([
                'line_no' => $request->line_no_Internet,
                'layanan_id' => $request->layanan_id_Internet,
                'amount' => $request->amount_Internet,
                'qty' => $request->qty_Internet,
                'diskon' => $request->diskon_Internet,
                'sub_amount' => $request->sub_amount_Internet-$request->ppn_amount_Internet,
                'ppn_amount' => $request->ppn_amount_Internet,
                'total_amount' => $request->sub_amount_Internet,
                'payment_amount' => $request->nominal_bayar_Internet
            ]);
            $data['trx_bayar_id'] = $idBayar;
            DB::table('trx_bayar_detail')->insertOrIgnore($data);
        }
        if($request->nominal_bayar_tv){
            $data=([
                'line_no' => $request->line_no_tv,
                'layanan_id' => $request->layanan_id_tv,
                'amount' => $request->amount_tv,
                'qty' => $request->qty_tv,
                'diskon' => $request->diskon_tv,
                'sub_amount' => $request->sub_amount_tv-$request->ppn_amount_tv,
                'ppn_amount' => $request->ppn_amount_tv,
                'total_amount' => $request->sub_amount_tv,
                'payment_amount' => $request->nominal_bayar_tv
            ]);
            $data['trx_bayar_id'] = $idBayar;
            DB::table('trx_bayar_detail')->insertOrIgnore($data);
        }
        if($request->nominal_bayar_telephony){
            $data=([
                'line_no' => $request->line_no_telephony,
                'layanan_id' => $request->layanan_id_telephony,
                'amount' => $request->amount_telephony,
                'qty' => $request->qty_telephony,
                'diskon' => $request->diskon_telephony,
                'sub_amount' => $request->sub_amount_telephony-$request->ppn_amount_telephony,
                'ppn_amount' => $request->ppn_amount_telephony,
                'total_amount' => $request->sub_amount_telephony,
                'payment_amount' => $request->nominal_bayar_telephony
            ]);
            $data['trx_bayar_id'] = $idBayar;
            DB::table('trx_bayar_detail')->insertOrIgnore($data);
        }
        if($request->nominal_bayar_deposit){
            $data=([
                'line_no' => $request->line_no_deposit,
                'layanan_id' => $request->layanan_id_deposit,
                'amount' => $request->amount_deposit,
                'qty' => $request->qty_deposit,
                'diskon' => $request->diskon_deposit,
                'sub_amount' => $request->sub_amount_deposit-$request->ppn_amount_deposit,
                'ppn_amount' => $request->ppn_amount_deposit,
                'total_amount' => $request->sub_amount_deposit,
                'payment_amount' => $request->nominal_bayar_deposit
            ]);
            $data['trx_bayar_id'] = $idBayar;
            DB::table('trx_bayar_detail')->insertOrIgnore($data);
			
			$nilaiDeposit = Pelanggan::where('id',$request->pelanggan_id)->first()->nilai_deposit;
			$dep['nilai_deposit'] = $nilaiDeposit+$request->nominal_bayar_deposit;
			Pelanggan::where('id',$request->pelanggan_id)
					->update($dep);
        }
        if($request->nominal_bayar_biaya){
            $data=([
                'line_no' => $request->line_no_biaya,
                'layanan_id' => $request->layanan_id_biaya,
                'amount' => $request->amount_biaya,
                'qty' => $request->qty_biaya,
                'diskon' => $request->diskon_biaya,
                'sub_amount' => $request->sub_amount_biaya-$request->ppn_amount_biaya,
                'ppn_amount' => $request->ppn_amount_biaya,
                'total_amount' => $request->sub_amount_biaya,
                'payment_amount' => $request->nominal_bayar_biaya
            ]);
            $data['trx_bayar_id'] = $idBayar;
            DB::table('trx_bayar_detail')->insertOrIgnore($data);
        }
        //dd($hdrTrxBayar,$request->nominal_bayar_tv,$request->nominal_bayar_biaya,$data,$request);
        return redirect('/admin/trx_bayar')->with('success','Berhasil menambahkan pembayaran Pelanggan '.$request->pelanggan_id.' - '.$request->no_unit);
    }

	public function bayar_tagihan(Request $request)
    {
       // dd($request,$request->no_bayar);
        $hdrTrxBayar = $request->validate([
            'no_bayar' => 'required|max:10',
            'tgl_bayar' => 'required|date',
            'amount' => 'required',
            'metode_bayar' => 'required',
            'status_bayar' => 'required',
			'biaya_transaksi' => 'required|numeric',
			 'image' => 'image|file|max:1024',
			'catatan' => 'required',
        ]);
        $hdrTrxBayar['user_id'] = auth()->user()->id;
        $hdrTrxBayar['pelanggan_id'] = $request->pelanggan_id;
        $hdrTrxBayar['no_order'] = $request->no_order;
        $hdrTrxBayar['no_tagihan'] = $request->trx_tagihan_id;
        $hdrTrxBayar['tipe_bayar'] = '2';
		// $hdrTrxBayar['biaya_transaksi'] = $request->biaya_transaksi;
		$hdrTrxBayar['tgl_bukti_bayar'] = $request->tgl_bayar;
		
		if($request->file('image')){
            $hdrTrxBayar['bukti_bayar'] = $request->file('image')->store('bukti_bayar-images');
        }
		
        $idBayar = TrxBayar::create($hdrTrxBayar)->id;
		
		// if($request->status_bayar=='1'){
            $updTagihan = ([
				'tipe_tagihan' => '2',
				'tgl_tagihan' => $request->tgl_bayar,
				//'tgl_tagihan' => $request->tgl_bayar,
			]);
        // }
        //dd($updOrder);
        TrxTagihan::where('id',$request->trx_tagihan_id)->update($updTagihan);
		 DB::select("call GetUpdExpDateFromBayar('".$idBayar."','2')");
		// Update Deposit
        if($request->metode_bayar=='6'){
            $deposit = Pelanggan::where('id',$request->pelanggan_id)->first('nilai_deposit');
            $saldo = $deposit->nilai_deposit - $request->amount;
            $updDeposit = (['nilai_deposit' => $saldo]);
            Pelanggan::where('id',$request->pelanggan_id)->update($updDeposit);
        }
       // dd($request,$request->no_bayar,$idBayar);
        if($request->nominal_bayar_Internet){
            $data=([
                'line_no' => $request->line_no_Internet,
                'layanan_id' => $request->layanan_id_Internet,
                'amount' => $request->amount_Internet,
                'qty' => $request->qty_Internet,
                'diskon' => $request->diskon_Internet,
                'sub_amount' => $request->sub_amount_Internet-$request->ppn_amount_Internet,
                'ppn_amount' => $request->ppn_amount_Internet,
                'total_amount' => $request->sub_amount_Internet,
                'payment_amount' => $request->nominal_bayar_Internet
            ]);
           // dd($data);
            $data['trx_bayar_id'] = $idBayar;
            DB::table('trx_bayar_detail')->insertOrIgnore($data);
        }
        if($request->nominal_bayar_tv){
            $data=([
                'line_no' => $request->line_no_tv,
                'layanan_id' => $request->layanan_id_tv,
                'amount' => $request->amount_tv,
                'qty' => $request->qty_tv,
                'diskon' => $request->diskon_tv,
                'sub_amount' => $request->sub_amount_tv-$request->ppn_amount_tv,
                'ppn_amount' => $request->ppn_amount_tv,
                'total_amount' => $request->sub_amount_tv,
                'payment_amount' => $request->nominal_bayar_tv
            ]);
            $data['trx_bayar_id'] = $idBayar;
            DB::table('trx_bayar_detail')->insertOrIgnore($data);
        }
        if($request->nominal_bayar_telephony){
            $data=([
                'line_no' => $request->line_no_telephony,
                'layanan_id' => $request->layanan_id_telephony,
                'amount' => $request->amount_telephony,
                'qty' => $request->qty_telephony,
                'diskon' => $request->diskon_telephony,
                'sub_amount' => $request->sub_amount_telephony-$request->ppn_amount_telephony,
                'ppn_amount' => $request->ppn_amount_telephony,
                'total_amount' => $request->sub_amount_telephony,
                'payment_amount' => $request->nominal_bayar_telephony
            ]);
            $data['trx_bayar_id'] = $idBayar;
            DB::table('trx_bayar_detail')->insertOrIgnore($data);
        }
        if($request->nominal_bayar_deposit){
            $data=([
                'line_no' => $request->line_no_deposit,
                'layanan_id' => $request->layanan_id_deposit,
                'amount' => $request->amount_deposit,
                'qty' => $request->qty_deposit,
                'diskon' => $request->diskon_deposit,
                'sub_amount' => $request->sub_amount_deposit-$request->ppn_amount_deposit,
                'ppn_amount' => $request->ppn_amount_deposit,
                'total_amount' => $request->sub_amount_deposit,
                'payment_amount' => $request->nominal_bayar_deposit
            ]);
            $data['trx_bayar_id'] = $idBayar;
            DB::table('trx_bayar_detail')->insertOrIgnore($data);
        }
        if($request->nominal_bayar_biaya){
            $data=([
                'line_no' => $request->line_no_biaya,
                'layanan_id' => $request->layanan_id_biaya,
                'amount' => $request->amount_biaya,
                'qty' => $request->qty_biaya,
                'diskon' => $request->diskon_biaya,
                'sub_amount' => $request->sub_amount_biaya-$request->ppn_amount_biaya,
                'ppn_amount' => $request->ppn_amount_biaya,
                'total_amount' => $request->sub_amount_biaya,
                'payment_amount' => $request->nominal_bayar_biaya
            ]);
            $data['trx_bayar_id'] = $idBayar;
            DB::table('trx_bayar_detail')->insertOrIgnore($data);
        }
        //dd($hdrTrxBayar,$request->nominal_bayar_tv,$request->nominal_bayar_biaya,$data,$request);
        return redirect('/admin/trx_bayar')->with('success','Berhasil menambahkan pembayaran Pelanggan');
    }
	
	public function update_bayar_tagihan(Request $request)
    {
       // dd($request,$request->no_bayar);
		if($request->approved == 'approved'){
            $id_user = Auth::user()->id;
			$hdrTrxBayar = $request->validate([
            'no_bayar' => 'required|max:10',
            'tgl_bayar' => 'required|date',
            'amount' => 'required',
            'metode_bayar' => 'required',
            'status_bayar' => 'required',
			'catatan' => 'required',
			'tgl_mutasi_bank' => 'required|date',
			// 'image' => 'image|file|max:1024',
        ]);
			$hdrTrxBayar['approved_by'] = $id_user;
		}else{
			
        $hdrTrxBayar = $request->validate([
            'no_bayar' => 'required|max:10',
            'tgl_bayar' => 'required|date',
            'amount' => 'required',
            'metode_bayar' => 'required',
            'status_bayar' => 'required',
			'catatan' => 'required',
			// 'image' => 'image|file|max:1024',
        ]);
		}
		
      //  $hdrTrxBayar['user_id'] = auth()->user()->id;
      //  $hdrTrxBayar['pelanggan_id'] = $request->pelanggan_id;
      //  $hdrTrxBayar['no_order'] = $request->no_order;
      //  $hdrTrxBayar['no_tagihan'] = $request->trx_tagihan_id;
       // $hdrTrxBayar['tipe_bayar'] = '2';
		 $hdrTrxBayar['tipe_bayar'] = $request->tipe_bayar;
		$hdrTrxBayar['biaya_transaksi'] = $request->biaya_transaksi;
        $hdrTrxBayar['tgl_mutasi_bank'] = $request->tgl_mutasi_bank;
		$hdrTrxBayar['tgl_bukti_bayar'] = $request->tgl_bayar;
        $idBayar = $request->id_bayar;
		
		if($request->file('image')){
            $hdrTrxBayar['bukti_bayar'] = $request->file('image')->store('bukti_bayar-images');
        }
		
        TrxBayar::where('id', $idBayar)
                ->update($hdrTrxBayar);

		 if($request->tipe_bayar=='2'){
            $updTagihan = ([
				'tipe_tagihan' => '2',
				'tgl_tagihan' => $request->tgl_bayar,
				//'tgl_tagihan' => $request->tgl_bayar,
			]);
			 //  dd($request->status_bayar,$request->trx_tagihan_id,$request->tgl_bayar);
        TrxTagihan::where('id',$request->trx_tagihan_id)->update($updTagihan);
			// DB::select("call GetUpdExpDateFromBayar('".$idBayar."')");
        }
     
		DB::select("call GetUpdExpDateFromBayar('".$idBayar."','".$request->tipe_bayar."')");
       // dd($request,$request->no_bayar,$idBayar);
        if($request->nominal_bayar_Internet){
            $data=([
               /* 'amount' => $request->amount_Internet,
                'qty' => $request->qty_Internet,
                'diskon' => $request->diskon_Internet,
                'sub_amount' => $request->sub_amount_Internet,
                'ppn_amount' => $request->ppn_amount_Internet,
                'total_amount' => $request->sub_amount_Internet,*/
                'payment_amount' => $request->nominal_bayar_Internet
            ]);
           // dd($data);
           // $data['trx_bayar_id'] = $idBayar;
            DB::table('trx_bayar_detail')
                ->where('trx_bayar_id', $idBayar)
                ->where('line_no', $request->line_no_Internet)
                ->where('layanan_id',$request->layanan_id_Internet)
                ->update($data);
        }
        if($request->nominal_bayar_tv){
            $data=([

             /*   'amount' => $request->amount_tv,
                'qty' => $request->qty_tv,
                'diskon' => $request->diskon_tv,
                'sub_amount' => $request->sub_amount_tv,
                'ppn_amount' => $request->ppn_amount_tv,
                'total_amount' => $request->sub_amount_tv,*/
                'payment_amount' => $request->nominal_bayar_tv
            ]);
            DB::table('trx_bayar_detail')
                ->where('trx_bayar_id', $idBayar)
                ->where('line_no', $request->line_no_tv)
                ->where('layanan_id',$request->layanan_id_tv)
                ->update($data);
        }
        if($request->nominal_bayar_telephony){
            $data=([

              /*  'amount' => $request->amount_telephony,
                'qty' => $request->qty_telephony,
                'diskon' => $request->diskon_telephony,
                'sub_amount' => $request->sub_amount_telephony,
                'ppn_amount' => $request->ppn_amount_telephony,
                'total_amount' => $request->sub_amount_telephony,*/
                'payment_amount' => $request->nominal_bayar_telephony
            ]);
            DB::table('trx_bayar_detail')
            ->where('trx_bayar_id', $idBayar)
            ->where('line_no', $request->line_no_telephony)
            ->where('layanan_id',$request->layanan_id_telephony)
            ->update($data);

        }
		if($request->nominal_bayar_deposit){
            $data=([

          /*      'amount' => $request->amount_deposit,
                'qty' => $request->qty_deposit,
                'diskon' => $request->diskon_deposit,
                'sub_amount' => $request->sub_amount_deposit,
                'ppn_amount' => $request->ppn_amount_deposit,
                'total_amount' => $request->sub_amount_deposit,*/
                'payment_amount' => $request->nominal_bayar_deposit
            ]);
            DB::table('trx_bayar_detail')
            ->where('trx_bayar_id', $idBayar)
            ->where('line_no', $request->line_no_deposit)
            ->where('layanan_id',$request->layanan_id_deposit)
            ->update($data);

        }
		if($request->nominal_bayar_biaya){
            $data=([

              /*  'amount' => $request->amount_biaya,
                'qty' => $request->qty_biaya,
                'diskon' => $request->diskon_biaya,
                'sub_amount' => $request->sub_amount_biaya,
                'ppn_amount' => $request->ppn_amount_biaya,
                'total_amount' => $request->sub_amount_biaya,*/
                'payment_amount' => $request->nominal_bayar_biaya
            ]);
            DB::table('trx_bayar_detail')
            ->where('trx_bayar_id', $idBayar)
            ->where('line_no', $request->line_no_biaya)
            ->where('layanan_id',$request->layanan_id_biaya)
            ->update($data);

        }
        //dd($hdrTrxBayar,$request->nominal_bayar_tv,$request->nominal_bayar_biaya,$data,$request);
        return redirect('/admin/trx_bayar')->with('success','Berhasil Update pembayaran Pelanggan');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrxBayar  $trxBayar
     * @return \Illuminate\Http\Response
     */
    public function show(TrxBayar $trxBayar)
    {
        //
    }

    public function getKwitansi(TrxBayar $trxBayar)
    {
        $dtl = ViewBayarOrderDetail::where('id',$trxBayar->id)->get();
        $hdr = ViewBayarOrderPelanggan::where('id',$trxBayar->id)->first();
		$tagihan = ViewTrxTagihan::where('id',$hdr->id_order_or_tagihan)->first();
		$periode = DB::select("call GetPeriodePemakaianFromOrder('".$trxBayar->id."')");
		
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
		
        $data["bayar"] = $hdr;
        $data["order_dtl"] = $dtl;
		$data["tagihan"] =  $tagihan;
		$data["periode"] =  $periode;
        $data["title"] = "Kwitansi Pembayaran";

        // return view('administrator.bayar.kwitansi', $data);

        $pdf = PDF::loadView('administrator.bayar.kwitansi', $data);

		return $pdf->setPaper('A4','portrait')->stream();
        //return $pdf->setPaper('A4','portrait')->download('kwitansi_'.$hdr->nomer_bayar.'.pdf');

    }
	public function getTandaTerimaBayar(TrxBayar $trxBayar)
    {

        $dtl = ViewBayarOrderDetail::where('id',$trxBayar->id)->get();
        $hdr = ViewBayarOrderPelanggan::where('id',$trxBayar->id)->first();
		$tagihan = ViewTrxTagihan::where('id',$hdr->id_order_or_tagihan)->first();
		$periode = DB::select("call GetPeriodePemakaianFromOrder('".$trxBayar->id."')");
        //  dd($hdr);
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
		
        $data["bayar"] = $hdr;
        $data["order_dtl"] = $dtl;
		 $data["tagihan"] =  $tagihan;
		$data["periode"] =  $periode;
        $data["title"] = "Kwitansi Pembayaran";

        // return view('administrator.bayar.kwitansi', $data);

        $pdf = PDF::loadView('administrator.bayar.ttbayar', $data);

        return $pdf->setPaper('A4','portrait')->stream();
        //return $pdf->setPaper('A4','portrait')->download('kwitansi_'.$hdr->nomer_bayar.'.pdf');
       // return $pdf->setPaper('A4','portrait')->download('kwitansi_'.$hdr->nomer_bayar.'.pdf');

    }
	
	public function getKirimWAKwitansi(Request $request){

        $hdr = ViewBayarOrderPelanggan::join('pelanggan','pelanggan.id','=','vbayar_order_pelanggan.pelanggan_id')
                                    ->where('vbayar_order_pelanggan.id',$request->id)->first(['vbayar_order_pelanggan.nama_lengkap','vbayar_order_pelanggan.unitid','pelanggan.nomer_hp']);
        if($request->kirimwa=="kwitansi"){
            $ket = "Kwitansi Pembayaran";
            $link1 ="kwitansi&id=".$request->id;
        }
        if($request->kirimwa=="ttbayar"){
            $ket = "Tanda Terima Pembayaran";
            $link1 ="ttbayar&id=".$request->id;
        }
        $link2 = Crypt::encrypt($link1);
        $link=url('/data/'.$link2);
		// dd($link);
        $psn = DB::table('set_tmp_pesan')->where('kode_pesan','PS012')->first('pesan')->pesan;
        $psn = str_replace('[link]',$link,$psn);
        $psn = str_replace('[ket]',$ket,$psn);
        $psn = str_replace('[nama_lengkap]',$hdr->nama_lengkap,$psn);
        $psn = str_replace('[UnitID]',$hdr->unitid,$psn);

        $pesan_outbox =([
            'jenis_pesan' => 'notif '.$request->kirimwa,
            'id_source' => $request->id,
            'tgl_kirim' => now(),
            'no_wa' => $hdr->nomer_hp,
            'id_unit' => $hdr->unitid,
            'isi_pesan' => $psn,
            'status' => 'proses'
        ]);

        TrxOutbox::create($pesan_outbox);
        return redirect('/admin/trx_bayar')->with('success','Berhasil mengirim '.$ket.' unitid '.$hdr->unitid.' Atas nama '.$hdr->nama_lengkap);
        //dd($request->all(),$ket,$psn,$hdr,$pesan_outbox);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrxBayar  $trxBayar
     * @return \Illuminate\Http\Response
     */
    public function edit(TrxBayar $trxBayar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrxBayar  $trxBayar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TrxBayar $trxBayar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrxBayar  $trxBayar
     * @return \Illuminate\Http\Response
     */
    public function destroy(TrxBayar $trxBayar)
    {
        //dd($trxBayar);
        $no_bayar = $trxBayar->no_bayar;

        if($trxBayar->bukti_bayar){
            Storage::delete($trxBayar->bukti_bayar);
        }

         TrxBayar::destroy($trxBayar->id);
        // TrxOrderDetail::destroy($trxOrder->no_order);
         return redirect('/admin/trx_bayar')->with('success','Berhasil Menghapus Data Pembayaran no '.$no_bayar.' Pelanggan');
    }
	public function getHapusDetail(Request $request)
    {

        // dd($request->all());

        $delete = TrxBayarDetail::where('trx_bayar_id',$request->id)
                        ->where('line_no',$request->line_no)
                        ->where('layanan_id',$request->layanan_id)
                        ->delete();
        // dd($delete);
          return redirect('/admin/trx_bayar/bayar?no_bayar='.$request->id)->with('success','Berhasil Menghapus Item ');
    }
	
	public function getDataPembayaran(Request $request){
        $role = Auth::user()->role;
        // dd($request->all());
        if($request->penel=="0")
        {
            if($request->statuspelanggan=="0"){

                $tagihan  = ViewBayarOrderPelanggan::orderByDesc('id')->limit(100)->get();
                $title = 'Data Pembayaran All Pelanggan';
                $subtitle ='';
            }
            if($request->statuspelanggan=="1"){
                $tagihan  = ViewBayarOrderPelanggan::where('nama_lengkap','like','%'.$request->input_filter.'%')->orderByDesc('id')->get();
                $title = 'Data Pembayaran Nama Pelanggan: '.$request->input_filter;
                $subtitle ='';
            }
            if($request->statuspelanggan=="2"){
                $tagihan  = ViewBayarOrderPelanggan::where('unitid',$request->input_filter)->orderByDesc('id')->get();
                $title = 'Data Pembayaran Unit '.$request->input_filter;
                $subtitle ='';
            }
            if($request->statuspelanggan=="3"){
                $tagihan  = ViewBayarOrderPelanggan::where('tgl_bayar',$request->input_filter)->orderByDesc('id')->get();
                $title = 'Data Pembayaran Tanggal :'.$request->input_filter;
                $subtitle ='';
            }
			 if($request->statuspelanggan=="4"){
                $tagihan  = ViewBayarOrderPelanggan::where('tgl_mutasi_bank',$request->input_filter)->orderByDesc('id')->get();
                $title = 'Data Pembayaran Tanggal Mutasi Bank :'.$request->input_filter;
                $subtitle ='';
            }

        }
        if($request->penel=="1"){
            $validateData = $request->validate([
                'tgl_awal' => 'required',
                'tgl_akhir' => 'required',
                'statustagihan' => 'required',
                'statuspelanggan' => 'required']);


            $awal = $request->tgl_awal;
            $akhir = $request->tgl_akhir;
            $st = $request->statustagihan ;
            $sp = $request->statuspelanggan;

            if($st=="0" and $sp=="0"){
                $tagihan  = ViewBayarOrderPelanggan::wherebetween('tgl_bayar',[$awal,$akhir])->orderByDesc('id')->get();
                $title = 'Data Pembayaran All Pelanggan';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).' - '.date('d/m/Y', strtotime($akhir));

            }
            if($st=="0" and $sp=="1"){
                $tagihan  = ViewBayarOrderPelanggan::wherebetween('tgl_bayar',[$awal,$akhir])->where('nama_lengkap','like','%'.$request->input_filter.'%')->orderByDesc('id')->get();
                $title = 'Data Pembayaran '.$request->input_filter;
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            if($st=="0" and $sp=="2"){
                $tagihan  = ViewBayarOrderPelanggan::wherebetween('tgl_bayar',[$awal,$akhir])->where('unitid',$request->input_filter)->orderByDesc('id')->get();
                $title = 'Data Pembayaran Unit '.$request->input_filter;
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            if($st=="0" and $sp=="3"){
                $tagihan  = ViewBayarOrderPelanggan::wherebetween('tgl_bayar',[$awal,$akhir])->where('no_tagihan',$request->input_filter)->orderByDesc('id')->get();
                $title = 'Data Pembayaran Nomer Invoice '.$request->input_filter;
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            if($request->statustagihan=="1" and $request->statuspelanggan=="0"){
                $tagihan  = ViewBayarOrderPelanggan::wherebetween('tgl_bayar',[$awal,$akhir])->where('status_bayar','1')->orderByDesc('id')->get();
                $title = 'Data Pembayaran All Pelanggan';
                $subtitle = 'Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir)) .' Keterangan - Lunas';
            }
            if($st=="1" and $sp=="1"){
                $tagihan  = ViewBayarOrderPelanggan::wherebetween('tgl_bayar',[$awal,$akhir])->where('status_bayar','1')->where('nama_lengkap','like','%'.$request->input_filter.'%')->orderByDesc('id')->get();
                $title = 'Data Pembayaran '.$request->input_filter.' Keterangan - Lunas';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            if($st=="1" and $sp=="2"){
                $tagihan  = ViewBayarOrderPelanggan::wherebetween('tgl_bayar',[$awal,$akhir])->where('status_bayar','1')->where('unitid',$request->input_filter)->orderByDesc('id')->get();
                $title = 'Data Pembayaran Unit '.$request->input_filter.' Keterangan - Lunas';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            if($st=="1" and $sp=="3"){
                $tagihan  = ViewBayarOrderPelanggan::wherebetween('tgl_bayar',[$awal,$akhir])->where('status_bayar','1')->where('no_tagihan',$request->input_filter)->orderByDesc('id')->get();
                $title = 'Data Pembayaran Nomer Invoice '.$request->input_filter.' Keterangan - Lunas';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }

            if($request->statustagihan=="2" and $request->statuspelanggan=="0"){
                $tagihan  = ViewBayarOrderPelanggan::wherebetween('tgl_bayar',[$awal,$akhir])->where('status_tagihan','4')->orderByDesc('id')->get();
                $title = 'Data Pembayaran All Pelanggan';
                $subtitle = 'Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir)) .' Keterangan - Batal';
            }
            if($st=="2" and $sp=="1"){
                $tagihan  = ViewBayarOrderPelanggan::wherebetween('tgl_bayar',[$awal,$akhir])->where('status_tagihan','4')->where('nama_lengkap','like','%'.$request->input_filter.'%')->orderByDesc('id')->get();
                $title = 'Data Pembayaran '.$request->input_filter.' Keterangan - Batal';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            if($st=="2" and $sp=="2"){
                $tagihan  = ViewBayarOrderPelanggan::wherebetween('tgl_bayar',[$awal,$akhir])->where('status_tagihan','4')->where('unitid',$request->input_filter)->orderByDesc('id')->get();
                $title = 'Data Pembayaran Unit '.$request->input_filter.' Keterangan - Batal';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            if($st=="2" and $sp=="3"){
                $tagihan  = ViewBayarOrderPelanggan::wherebetween('tgl_bayar',[$awal,$akhir])->where('status_tagihan','4')->where('no_tagihan',$request->input_filter)->orderByDesc('id')->get();
                $title = 'Data Pembayaran Nomer Invoice '.$request->input_filter.' Keterangan - Batal';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }

            if($request->statustagihan=="3" and $request->statuspelanggan=="0"){
                $tagihan  = ViewBayarOrderPelanggan::wherebetween('tgl_bayar',[$awal,$akhir])->where('status_byr','0')->whereNotIn('status_tagihan',['4'])->orderByDesc('id')->get();
                $title = 'Data Pembayaran All Pelanggan';
                $subtitle = 'Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir)) .' Keterangan - Outstanding';
            }
            if($st=="3" and $sp=="1"){
                $tagihan  = ViewBayarOrderPelanggan::wherebetween('tgl_bayar',[$awal,$akhir])->where('status_byr','0')->whereNotIn('status_tagihan',['4'])->where('nama_lengkap','like','%'.$request->input_filter.'%')->orderByDesc('id')->get();
                $title = 'Data Pembayaran '.$request->input_filter.' Keterangan - Outstanding';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            if($st=="3" and $sp=="2"){
                $tagihan  = ViewBayarOrderPelanggan::wherebetween('tgl_bayar',[$awal,$akhir])->where('status_byr','0')->whereNotIn('status_tagihan',['4'])->where('unitid',$request->input_filter)->orderByDesc('id')->get();
                $title = 'Data Pembayaran Unit '.$request->input_filter.' Keterangan - Outstanding';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            if($st=="3" and $sp=="3"){
                $tagihan  = ViewBayarOrderPelanggan::wherebetween('tgl_bayar',[$awal,$akhir])->where('status_byr','0')->whereNotIn('status_tagihan',['4'])->where('no_tagihan',$request->input_filter)->orderByDesc('id')->get();
                $title = 'Data Pembayaran Nomer Invoice '.$request->input_filter.' Keterangan - Outstanding';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }

        }
        //dd($tagihan);
        $id_user = Auth::user()->id;
		if($request->statuspelanggan=="0" and $request->penel=="0"){
            return view('administrator.bayar.index',[
                'order' => $tagihan,
                'id_user' =>$id_user,
                'level' => $role,
                'title' =>  $title,
                'subtitle' => $subtitle,
            ])->with('successs','Data ditampilkan hanya 100 transaksi terakhir untuk mempercepat proses loading, pilih filter yang sesuai untuk menampilkan data pembayaran');

        }else{
        return view('administrator.bayar.index',[
            'order' => $tagihan,
            'id_user' =>$id_user,
            'level' => $role,
            'title' =>  $title,
            'subtitle' => $subtitle,
        ]);
		}
    }
	
}
