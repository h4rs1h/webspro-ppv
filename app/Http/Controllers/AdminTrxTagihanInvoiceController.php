<?php

namespace App\Http\Controllers;

use App\Models\TrxTagihan;
use Illuminate\Http\Request;
use App\Models\ViewTrxTagihan;
use App\Models\ViewTrxTagihanDtl;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PDF;
// use Barryvdh\DomPDF\PDF;

class AdminTrxTagihanInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['auth']);

    }
    public function index()
    {
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
                ['id' =>'3','name' =>'Nomer Invoice'],
			 	['id' =>'4','name' =>'Tgl Jatuh Tempo'],
                ];
		
        $role = Auth::user()->role;
        // return view('administrator.tagihan.index',[
        return view('administrator.tagihan.formfilter',[
            'order' => [], // ViewTrxTagihan::all(),
            'level' => $role,
			 'statustagihan' => $statusTagihan,
            'statuspelanggan' => $statusPelanggan,
            'title' => 'Data Tagihan Pelanggan',
        ]);
    }

    public function getInvoicePdf(Request $request){

        // dd($request->no_tagihan,ViewTrxTagihanDtl::where('no_tagihan',$request->no_tagihan)->get());

        $data["tagihan"] = ViewTrxTagihan::where('no_tagihan',$request->no_tagihan)->first();
        $data['tagihandtl'] = ViewTrxTagihanDtl::where('no_tagihan',$request->no_tagihan)->get();
        $data["title"] = "Invoice Tagihan ".$request->no_tagihan;
		
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
		
        // return view('administrator.tagihan.invoice', $data);

       $pdf = PDF::loadView('administrator.tagihan.invoice', $data);

       return $pdf->setPaper('A4','portrait')->stream();
      //return $pdf->setPaper('A4','portrait')->download('order_'.$trxOrder->no_formulir.'.pdf');

    }
	
	public function getKirimWa(Request $request){
        $no_tagihan = $request->no_tagihan;
        $pesan = DB::select('call GetNewNotifInv('.$no_tagihan.')');

        return redirect('/admin/trx_invoice')->with('success','Berhasil Mengirim Notifikasi Inv='.$no_tagihan.' ke Pelanggan');
    }
	
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = Auth::user()->role;
        return view('administrator.tagihan.create',[
           // 'order' => Pelanggan::Where(),
            'level' => $role,
            'title' => 'Proses Tagihan Pelanggan ',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
               $validateData = $request->validate([
            'unitid' => 'required',
           // 'tgl_jtempo' => 'required'
        ]);

        $id_user = Auth::user()->id;
        $no_unit = $request->unitid;
        $period = substr($request->tgl_jtempo,0,4).substr($request->tgl_jtempo,5,2);
        // dd($request->all(),$period);
        $proses = DB::select("call getnewinvoiceunitpermintaan3('".$no_unit."')");

        return redirect('/admin/trx_invoice')->with('success','Berhasil Membuat Inv='.$no_unit.' ');
    }

	public function getTagihan (Request $request)
    {
        $role = Auth::user()->role;
       // dd($request->all());
        if($request->action=="show")
        {
            $tagihan  = ViewTrxTagihan::where('periodeinv',$request->periode)->get();

            return view('administrator.tagihan.index',[
                'order' => $tagihan,
                'level' => $role,
                'title' => 'Data Tagihan Pelanggan Periode '.substr($request->periode,4,2).'-'.substr($request->periode,0,4) ,
            ]);
        }elseif($request->action=="remind")
        {
            $awal = $request->awal;
            $akhir = $request->akhir;

            $tagihan  = DB::select('call getReminTagihan('.$awal.','.$akhir.')');
            $subtitle ='';
            return view('administrator.tagihan.index',[
                'order' => $tagihan,
                'level' => $role,
                'title' => 'Data Tagihan Pelanggan ' ,
                'subtitle' => $subtitle,
            ]);
        }
    }
	public function getexpdate(Request $request){
        $role = Auth::user()->role;
        $validateData = $request->validate([
            'unitid' => 'required',
        ]);
        // dd($request->all());
        $no_unit = $request->unitid;
        $data = DB::table('vGetExpDate')
                    ->where('unitid',$no_unit)
                    ->get();

        return view('administrator.tagihan.showexpdate',[
            'order' => $data,
            'level' => $role,
            'title' => 'Data Exp Date Layanan Pelanggan ' ,
        ]);
    }
	 public function getupdInvBatal(Request $request){

        $role = Auth::user()->role;
        $validateData = $request->validate([
            'no_tagihan' => 'required',
            'ket' => 'required',
        ]);
        $id = TrxTagihan::where('no_tagihan',$request->no_tagihan)->first()->id;
        if(isset($id)){
            $upd = ([
                'ket' => $request->ket,
                'status_tagihan' => '4'
            ]);
            TrxTagihan::where('id',$id)->update($upd);
        }

        return redirect('/admin/trx_invoice')->with('success','Berhasil Update '.$request->no_tagihan.', ke Invoice Batal');
    }
	public function getupdExpDateInv(Request $request){

        $role = Auth::user()->role;
        $validateData = $request->validate([
            'no_tagihan' => 'required',
            'tgl_jtempo' => 'required|date',
            'periode_pemakaian' => 'required',
        ]);

        DB::select("call GetUpdJtExpDateDtl('".$request->no_tagihan."','".$request->tgl_jtempo."','".$request->periode_pemakaian."')");

        return redirect('/admin/trx_invoice')->with('success','Berhasil Update Exp Date Invoice: '.$request->no_tagihan.' ');
    }
    public function getupdexpdate(Request $request){
        $no_unit = $request->id;
		$layanan_id = $request->layanan_id;
       // $proses = DB::select("call GetUpdExpDate('".$no_unit."')");
 		$proses = DB::select("call GetUpdExpDate('".$no_unit."','".$layanan_id."')");
        $data = DB::table('vGetExpDate')
                    ->where('pelanggan_id',$no_unit)
                    ->first();

        return redirect('/admin/trx_invoice')->with('success','Berhasil Update data Pelanggan '.$data->unitid);

    }
	public function getDataTagihan(Request $request){
        $role = Auth::user()->role;
        // dd($request->all());
        if($request->penel=="0")
        {
            if($request->statuspelanggan=="0"){
               // $tagihan  = ViewTrxTagihan::all();
				$tagihan  = DB::table('vtagihanhdr2')->orderBy('id','desc')->limit(1000)->get();
                $title = 'Data Tagihan All Pelanggan';
                $subtitle ='';
            }
            elseif($request->statuspelanggan=="1"){
             //   $tagihan  = ViewTrxTagihan::where('nama_lengkap','like','%'.$request->input_filter.'%')->get();
				$tagihan  = DB::table('vtagihanhdr2')->where('nama_lengkap','like','%'.$request->input_filter.'%')
					->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan Nama Pelanggan: '.$request->input_filter;
                $subtitle ='';
            }
            elseif($request->statuspelanggan=="2"){
                //$tagihan  = ViewTrxTagihan::where('unitid',$request->input_filter)->get();
				$tagihan  = DB::table('vtagihanhdr2')->where('unitid',$request->input_filter)
					->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan Unit '.$request->input_filter;
                $subtitle ='';
            }
            elseif($request->statuspelanggan=="3"){
              //  $tagihan  = ViewTrxTagihan::where('no_tagihan',$request->input_filter)->get();
				$tagihan  = DB::table('vtagihanhdr2')->where('no_tagihan',$request->input_filter)
					->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan Nomer Inv-'.$request->input_filter;
                $subtitle ='';
            }elseif($request->statuspelanggan=="4"){
                //$tagihan  = ViewTrxTagihan::where('tgl_jatuh_tempo',$request->input_filter)->get();
				$tagihan  = DB::table('vtagihanhdr2')->where('tgl_jatuh_tempo',$request->input_filter)
					->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan Invoice Jatuh Tempo Tanggal:'.$request->input_filter;
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
              //  $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])->get();
					$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])
					->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan All Pelanggan';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            elseif($st=="0" and $sp=="1"){
                //$tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])->where('nama_lengkap','like','%'.$request->input_filter.'%')->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('nama_lengkap','like','%'.$request->input_filter.'%')->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan '.$request->input_filter;
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            elseif($st=="0" and $sp=="2"){
               // $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])->where('unitid',$request->input_filter)->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('unitid',$request->input_filter)->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan Unit '.$request->input_filter;
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            elseif($st=="0" and $sp=="3"){
               // $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])->where('no_tagihan',$request->input_filter)->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('no_tagihan',$request->input_filter)->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan Nomer Invoice '.$request->input_filter;
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }elseif($st=="0" and $sp=="4"){
               // $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])
									//	->where('tgl_jatuh_tempo',$request->input_filter)
									//	->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('tgl_jatuh_tempo',$request->input_filter)->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan Invoice Jatuh Tempo Tanggal '.$request->input_filter;
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            elseif($request->statustagihan=="1" and $request->statuspelanggan=="0"){
              //  $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','1')->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','1')->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan All Pelanggan';
                $subtitle = 'Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir)) .' Keterangan - Lunas';
            }
            elseif($st=="1" and $sp=="1"){
              //  $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','1')->where('nama_lengkap','like','%'.$request->input_filter.'%')->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','1')->where('nama_lengkap','like','%'.$request->input_filter.'%')->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan '.$request->input_filter.' Keterangan - Lunas';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            elseif($st=="1" and $sp=="2"){
              //  $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','1')->where('unitid',$request->input_filter)->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','1')->where('unitid',$request->input_filter)->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan Unit '.$request->input_filter.' Keterangan - Lunas';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            elseif($st=="1" and $sp=="3"){
               // $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','1')->where('no_tagihan',$request->input_filter)->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','1')->where('no_tagihan',$request->input_filter)->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan Nomer Invoice '.$request->input_filter.' Keterangan - Lunas';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }elseif($st=="1" and $sp=="4"){
             //   $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','1')
				//	->where('tgl_jatuh_tempo',$request->input_filter)->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','1')->where('tgl_jatuh_tempo',$request->input_filter)->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan Invoice Jatuh Tempo Tanggal '.$request->input_filter.' Keterangan - Lunas';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }

            elseif($request->statustagihan=="2" and $request->statuspelanggan=="0"){
              //  $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_tagihan','4')->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_tagihan','4')->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan All Pelanggan';
                $subtitle = 'Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir)) .' Keterangan - Batal';
            }
            elseif($st=="2" and $sp=="1"){
               // $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_tagihan','4')->where('nama_lengkap','like','%'.$request->input_filter.'%')->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_tagihan','4')->where('nama_lengkap','like','%'.$request->input_filter.'%')->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan '.$request->input_filter.' Keterangan - Batal';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            elseif($st=="2" and $sp=="2"){
              //  $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_tagihan','4')->where('unitid',$request->input_filter)->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_tagihan','4')->where('unitid',$request->input_filter)->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan Unit '.$request->input_filter.' Keterangan - Batal';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            elseif($st=="2" and $sp=="3"){
               // $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_tagihan','4')->where('no_tagihan',$request->input_filter)->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_tagihan','4')->where('no_tagihan',$request->input_filter)->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan Nomer Invoice '.$request->input_filter.' Keterangan - Batal';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }elseif($st=="2" and $sp=="4"){
              //  $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_tagihan','4')
				//							->where('tgl_jatuh_tempo',$request->input_filter)->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_tagihan','4')->where('tgl_jatuh_tempo',$request->input_filter)->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan Invoice Jatuh Tempo Tanggal '.$request->input_filter.' Keterangan - Batal';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }

            elseif($request->statustagihan=="3" and $request->statuspelanggan=="0"){
              //  $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','0')->whereNotIn('status_tagihan',['4'])->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','0')->whereNotIn('status_tagihan',['4'])->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan All Pelanggan';
                $subtitle = 'Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir)) .' Keterangan - Outstanding';
            }
            elseif($st=="3" and $sp=="1"){
              //  $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','0')->whereNotIn('status_tagihan',['4'])->where('nama_lengkap','like','%'.$request->input_filter.'%')->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','0')->whereNotIn('status_tagihan',['4'])->where('nama_lengkap','like','%'.$request->input_filter.'%')->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan '.$request->input_filter.' Keterangan - Outstanding';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            elseif($st=="3" and $sp=="2"){
               // $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','0')->whereNotIn('status_tagihan',['4'])->where('unitid',$request->input_filter)->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','0')->whereNotIn('status_tagihan',['4'])->where('unitid',$request->input_filter)->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan Unit '.$request->input_filter.' Keterangan - Outstanding';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }
            elseif($st=="3" and $sp=="3"){
             //   $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','0')->whereNotIn('status_tagihan',['4'])->where('no_tagihan',$request->input_filter)->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','0')->whereNotIn('status_tagihan',['4'])->where('no_tagihan',$request->input_filter)->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan Nomer Invoice '.$request->input_filter.' Keterangan - Outstanding';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }elseif($st=="3" and $sp=="4"){
              //  $tagihan  = ViewTrxTagihan::wherebetween('tgl_tagihan',[$awal,$akhir])
				//							->where('status_byr','0')
				//							->whereNull('status_tagihan')
				//							->where('tgl_jatuh_tempo',$request->input_filter)->get();
				$tagihan  = DB::table('vtagihanhdr2')->wherebetween('tgl_tagihan',[$awal,$akhir])->where('status_byr','0')->where('status_tagihan','0')->where('tgl_jatuh_tempo',$request->input_filter)->orderBy('id','desc')->limit(200)->get();
                $title = 'Data Tagihan Invoice Jatuh Tempo Tanggal '.$request->input_filter.' Keterangan - Outstanding';
                $subtitle = ' Dari '.date('d/m/Y', strtotime($awal)).'-'.date('d/m/Y', strtotime($akhir));

            }

        }
        //dd($tagihan);
        return view('administrator.tagihan.index',[
            'order' => $tagihan,
            'level' => $role,
            'title' =>  $title,
            'subtitle' => $subtitle,
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrxTagihan  $trxTagihan
     * @return \Illuminate\Http\Response
     */
    public function show(TrxTagihan $trxTagihan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrxTagihan  $trxTagihan
     * @return \Illuminate\Http\Response
     */
    public function edit(TrxTagihan $trxTagihan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrxTagihan  $trxTagihan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TrxTagihan $trxTagihan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrxTagihan  $trxTagihan
     * @return \Illuminate\Http\Response
     */
    public function destroy(TrxTagihan $trxTagihan)
    {
        //
    }
}
