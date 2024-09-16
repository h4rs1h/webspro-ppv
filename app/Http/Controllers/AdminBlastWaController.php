<?php

namespace App\Http\Controllers;

use App\Models\Tower;
use App\Models\Lantai;
use App\Models\SubTower;
use App\Models\TrxSending;
use App\Models\TrxOutbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AdminBlastWaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function getSetting(Request $request){

        $cek = $request->wablast;
        $url = env('WOOWA_URL_PARTNER');
        $license = env('WOOWA_LICENSE');
		$key = env('WOOWA_KEY');
		$url_sender = env('WOOWA_URL_SEND');
		
        $data = array(
            'key' => $key,
        );
		$data_string = json_encode($data);

      //  $data_string = http_build_query($data);
	// dd($data_string);
        if($cek=="statusqr"){
           // $ch = curl_init($url_sender.'qr_status');
			//dd($data_string);
              //  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
           //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            //    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //    curl_setopt($ch, CURLOPT_VERBOSE, 0);
           //     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
           //     curl_setopt($ch, CURLOPT_TIMEOUT, 360);
           //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
           //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			//	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  			//			'Content-Type: application/json',
  		//				'Content-Length: ' . strlen($data_string)));
                
          //  $res = json_decode(curl_exec($ch));
	  		try {
                $response = Http::post($url_sender . 'qr_status', $data);
             //   dd($response->body());
                // Lakukan sesuatu dengan respon
                // return $response;
            } catch (\Exception $e) {
                // Tangani kesalahan
                return 'Error: ' . $e->getMessage();
            }
			//dd($response->body());
			//$res = json_decode($response);
            $role = Auth::user()->role;
            return view('administrator.wa.setting.statusqr',[
                'key' => $response->body(),
                'msg' => $response->body(),
                'st' => $response->body(),
                'level' => $role,
                'title' => 'Setting Koneksi WA',
            ]);
        }
        elseif($cek=="scanqr"){
            $ch = curl_init($url_sender.'generate_qr');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_VERBOSE, 0);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
				curl_setopt($ch, CURLOPT_TIMEOUT, 360);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					  'Content-Type: application/json',
							  'Content-Length: ' . strlen($data_string))
							);
	//dd($ch,$data_string,$url_sender,curl_exec($ch));
                //$res = json_decode(curl_exec($ch));
			$res = curl_exec($ch);
               // dd($res);
                $role = Auth::user()->role;
            return view('administrator.wa.setting.statusqr2',[
                'img' => $res,
                'level' => $role,
                'title' => 'Scand QR Code',
            ]);
        }
    }
	public function getSending(Request $request){
        $cek = $request->getdata;

        $role = Auth::user()->role;

        if($cek=="all"){
            return view('administrator.wa.sending.index',[
                'sending' => TrxSending::orderBy('id','DESC')->limit(500)->get(),
                'level' => $role,
                'title' => 'Data Pengiriman WA Blast',
            ]);
        }
    }

    public function getOutbox()
    {
        $role = Auth::user()->role;


            return view('administrator.wa.outbox.index',[
                'sending' => TrxOutbox::all(),
                'level' => $role,
                'title' => 'Data Proses Outbox WA Blast',
            ]);

    }
	public function getResend()
    {
        $role = Auth::user()->role;
		$upd = ([
				'tgl_terkirim' => null,
				'status' => 'Proses'
				]);
		TrxOutbox::whereNotNull('tgl_terkirim')->update($upd);
		
		return redirect('/admin/wa/outbox')->with('success','Berhasil mengirim ulang');
    }
	public function getblast()
    {

        $role = Auth::user()->role;
            return view('administrator.wa.form_filter_blast',[
                'sending' => TrxOutbox::all(),
                'level' => $role,
                'title' => 'Proses Kirim WA Blast',
                'tower' => Tower::all(),
                'subtower' => SubTower::all()->sortBy('name',SORT_NATURAL),
                'lantai' => Lantai::all()->sortBy('name',SORT_NATURAL),
            ]);

    }
    public function actKirimBlast(Request $request){

        $validateData = $request->validate([
            'isi_pesan' => 'required',
            'tower' => 'required',
            'sub_tower' => 'required',
            'lantai1' => 'required',
            'lantai2' => 'required',

        ]);
        $pesan = $request->isi_pesan;

        if($request->tower=='0' and $request->sub_tower=='0' and $request->lantai1=='0' and $request->lantai2=='0'){
            $data = DB::table('vpelanggan')
                        ->wherein('status_layanan',['0','1','2','3'])
                        ->where('tipe_order','1')
                        ->whereRaw('LENGTH(nomer_hp) > 5')
                        ->get();
        }elseif($request->tower<>'0' and $request->sub_tower=='0' and $request->lantai1=='0' and $request->lantai2=='0'){
            $data = DB::table('vpelanggan')
                        ->wherein('status_layanan',['0','1','2','3'])
                        ->where('tipe_order','1')
                        ->whereRaw('LENGTH(nomer_hp) > 5')
                        ->where('tower',$request->tower)
                        ->get();
        }elseif($request->tower<>'0' and $request->sub_tower<>'0' and $request->lantai1=='0' and $request->lantai2=='0'){
            $data = DB::table('vpelanggan')
                        ->wherein('status_layanan',['0','1','2','3'])
                        ->where('tipe_order','1')
                        ->whereRaw('LENGTH(nomer_hp) > 5')
                        ->where('tower',$request->tower)
                        ->where('sub_tower',$request->sub_tower)
                        ->get();
        }elseif($request->tower<>'0' and $request->sub_tower<>'0' and $request->lantai1<>'0' and $request->lantai2<>'0'){
            $data = DB::table('vpelanggan')
                        ->wherein('status_layanan',['0','1','2','3'])
                        ->where('tipe_order','1')
                        ->whereRaw('LENGTH(nomer_hp) > 5')
                        ->where('tower',$request->tower)
                        ->where('sub_tower',$request->sub_tower)
                        ->whereBetween('lantai',[$request->lantai1,$request->lantai2])
                        ->get();
        }

        foreach($data as $dt ){

            $psn_nama = str_replace('[nama]',' '.$dt->nama_lengkap,$pesan);
            $psn_unit = str_replace('[unit]',' '.$dt->unitid,$psn_nama);

            $pesan_outbox =([
                'jenis_pesan' => 'Blat_info',
                'id_source' => $dt->trx_order_id,
                'tgl_kirim' => now(),
                'no_wa' => $dt->nomer_hp,
                'id_unit' => $dt->unitid ,
                'isi_pesan' => $psn_unit,
                'status' => 'proses'
            ]);

            TrxOutbox::create($pesan_outbox);
        }

        return redirect('/admin/wa/outbox')->with('success','Berhasil Mengirim Pesan Blast ');
        // dd($request->all(),$data);
    }
}
