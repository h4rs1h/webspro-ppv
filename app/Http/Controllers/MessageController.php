<?php

namespace App\Http\Controllers;

use App\Models\Outbox;
use App\Models\TrxOutbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\TrxSending;

class MessageController extends Controller
{
	public $key = "7102f062dcec2541d848cc70a215dc6bd78bfa8fe9b30d4f";
		//env('WOOWA_KEY');
	// 
    //public $key = "120b2f8fc284ffd6c985e398ce9930fe0a7c585cf3790cae";

    public function index()
    {
        return view('send-msg.index');

    }
    public function sendMessage(Request $request)
    {
       // dd($request);
        $msg = "Haloo $request->name, ini pesan kamu ya: $request->msg";

       // $cek_numb = $this->cek_number($request->no_wa);

       // dd($cek_numb);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->withOptions([
            'debug' => false,
            'connect_timeout' =>false,
            'timeout' => false,
            'verify' => false,
        ])->post('http://116.203.191.58/api/send_message',[
            'phone_no' => $request->no_wa,
            'message' => $msg,
            'key' => $this->key,
            'skip_link' => true
        ]);

        dd($response,$msg,$request->no_wa,$response->successful(),$response->body());
        if($response->successful()){
            return redirect(url('send-message'))->with('status','success');
        }else{

            return redirect(url('send-message'))->with('status','warning');
        }
    }
	public function getOutbox(){

        $data = TrxOutbox::where('tglsending',null)->first();
		
		
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->withOptions([
            'debug' => false,
            'connect_timeout' =>false,
            'timeout' => false,
            'verify' => false,
        ])->post('http://116.203.191.58/api/send_message',[
            'phone_no' => $data->wa,
            'message' => $data->pesan,
            'key' => $this->key,
            'skip_link' => true
        ]);

        if($response->successful()){
            $upd = ([
                'tglsending' => now(),
                'status' => 'success'
            ]);
            Outbox::where('id',$data->id)
                    ->update($upd);

            return redirect(url('send-message'))->with('status','success');
        }else{

            return redirect(url('send-message'))->with('status','warning');
        }

    }
	public function getNotifInv(){
		$url = env('WOOWA_URL_SEND').'send_image_url';
        $license = env('WOOWA_LICENSE');
        $keys = env('WOOWA_KEY');
        $data = TrxOutbox::where('tgl_terkirim',null)->whereNotIn('no_wa',['PROBLEM HANDLING'])->whereNull('file')->limit(4)->get();
		
		$cek = $this->getStatusQr();
		//dd($cek);
		if($cek=="online"){
			foreach($data as $dt){

				$response = Http::withHeaders([
					'Content-Type' => 'application/json'
				])->withOptions([
					'debug' => false,
					'connect_timeout' =>false,
					'timeout' => false,
					'verify' => false,
				])->post(env('WOOWA_URL_SEND').'send_message',[
					'phone_no' => $dt->no_wa,
					'message' => $dt->isi_pesan,
					'key' =>   $keys,
					'skip_link' => true
				]);
				//dd($response->successful());
				if($response->successful()){
					$tkirim = now();
					$upd = ([
						'tgl_terkirim' => $tkirim,
						'status' => 'success'
					]);
					TrxOutbox::where('id',$dt->id)
							->update($upd);
					$sending =([
						'id' => $dt->id,
						'jenis_pesan' => $dt->jenis_pesan,
						'id_source' => $dt->id_source,
						'tgl_kirim' => $dt->tgl_kirim,
						'tgl_terkirim' => $tkirim,
						'id_unit' => $dt->id_unit,
						'no_wa' => $dt->no_wa,
						'isi_pesan' => $dt->isi_pesan,
						'status' => 'success',
					]);
					TrxSending::create($sending);

					TrxOutbox::destroy($dt->id);
					//return redirect(url('send-message'))->with('status','success');
				}else{
					$tkirim = now();
					$upd = ([
						'tgl_terkirim' => $tkirim,
						'status' => 'failed'
					]);
					$sending =([
						'id' => $dt->id,
						'jenis_pesan' => $dt->jenis_pesan,
						'id_source' => $dt->id_source,
						'tgl_kirim' => $dt->tgl_kirim,
						'tgl_terkirim' => $tkirim,
						'id_unit' => $dt->id_unit,
						'no_wa' => $dt->no_wa,
						'isi_pesan' => $dt->isi_pesan,
						'status' => 'failed'
					]);
					TrxSending::create($sending);

					TrxOutbox::where('id',$dt->id)
							->update($upd);

					//return redirect(url('send-message'))->with('status','warning');
				}
			}

        	return redirect(url('send-message'))->with('status selesai','succes');
		}
    }
	
	public function getSendImg(){

        $data = TrxOutbox::where('tgl_terkirim',null)->whereNotNull('file')->limit(2)->get();

        $url = env('WOOWA_URL_SEND').'send_image_url';
        $license = env('WOOWA_LICENSE');
        $keys = env('WOOWA_KEY');
       // $group_id =  'BytwcwUWGjfBdXdypAKa0f'; //Group PROBLEM HANDLING MORA-PPV
        // $group_id =  'CL3wO83g9ZXKEhNp45V41a'; //Group Dummy

        $cek = $this->getStatusQr();
       // dd($cek,$data);
        if($cek->status=="phone_online"){
           // dd($data);
            foreach($data as $dt){

                $data = array(
                    "phone_no" => $dt->no_wa,
                    "key"         => $keys,
                    "url"  => $dt->file,
                    "message"     => $dt->isi_pesan
                );

                $data_string = json_encode($data);
                //dd($data_string);
                $ch = curl_init($url);
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
               // dd(curl_exec($ch));
                $res=curl_exec($ch);
                 curl_close($ch);
                //dd($res);
                if($res=='success'){
                    $tkirim = now();
                    $upd = ([
                        'tgl_terkirim' => $tkirim,
                        'status' => 'success'
                    ]);
                    TrxOutbox::where('id',$dt->id)
                            ->update($upd);
                    $sending =([
                        'id' => $dt->id,
                        'jenis_pesan' => $dt->jenis_pesan,
                        'id_source' => $dt->id_source,
                        'tgl_kirim' => $dt->tgl_kirim,
                        'tgl_terkirim' => $tkirim,
                        'id_unit' => $dt->id_unit,
                        'no_wa' => $dt->no_wa,
                        'isi_pesan' => $dt->isi_pesan,
                        'file' => $dt->file,
                        'status' => $dt->status,
                    ]);
                    TrxSending::create($sending);

                    TrxOutbox::destroy($dt->id);
                    //return redirect(url('send-message'))->with('status','success');
                }else{
                    $tkirim = now();
                    $upd = ([
                        'tgl_terkirim' => $tkirim,
                        'status' => 'failed'
                    ]);
                    $sending =([
                        'id' => $dt->id,
                        'jenis_pesan' => $dt->jenis_pesan,
                        'id_source' => $dt->id_source,
                        'tgl_kirim' => $dt->tgl_kirim,
                        'tgl_terkirim' => $tkirim,
                        'id_unit' => $dt->id_unit,
                        'no_wa' => $dt->no_wa,
                        'isi_pesan' => $dt->isi_pesan,
                        'file' => $dt->file,
                        'status' => $dt->status,
                    ]);
                    TrxSending::create($sending);

                    TrxOutbox::where('id',$dt->id)
                            ->update($upd);

                    //return redirect(url('send-message'))->with('status','warning');
                }
            }
            return ("Berhasil Selesai");

        }

    }
	public function getNotifgroupBilling(){

        $data = TrxOutbox::where('tgl_terkirim',null)->whereIn('no_wa',['Billing MPJ'])->get();

        //$url = env('WOOWA_URL_SEND').'send_message_group_id';
		  $url = env('WOOWA_URL_SEND').'send_message_group_id'; //sync
		// $url = env('WOOWA_URL_SEND').'async_send_message_group_id';  //async_send_message_group_id
        $license = env('WOOWA_LICENSE');
        $keys = env('WOOWA_KEY');
		
        $group_id =  'EeQPNBrxxFWCck7nPGxmkU'; //Group Billing MPJ 
		//$group_id =  'BytwcwUWGjfBdXdypAKa0f'; //Group PROBLEM HANDLING MORA-PPV
       // $group_id =  'CL3wO83g9ZXKEhNp45V41a'; //Group Dummy

        $cek = $this->getStatusQr();
       // dd($cek,$data);
      //  if($cek->status=="phone_online"){
           // dd($data);
            foreach($data as $dt){

                $data = array(
                    // "group_name" =>'Mentoring Decoding',
                    "group_id"  => $group_id,
                    "key"         => $keys,
                    "message"     => $dt->isi_pesan
                );

                $data_string = json_encode($data);
                //dd($data_string);
                $ch = curl_init($url);
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
               // dd(curl_exec($ch));
                $res=curl_exec($ch);
                 curl_close($ch);
				 $this->getNotifSoftBandFile($dt->file);
                // dd($res);
                if($res=='success'){
                    $tkirim = now();
                    $upd = ([
                        'tgl_terkirim' => $tkirim,
                        'status' => 'success'
                    ]);
                    TrxOutbox::where('id',$dt->id)
                            ->update($upd);
                    $sending =([
                        'id' => $dt->id,
                        'jenis_pesan' => $dt->jenis_pesan,
                        'id_source' => $dt->id_source,
                        'tgl_kirim' => $dt->tgl_kirim,
                        'tgl_terkirim' => $tkirim,
                        'id_unit' => $dt->id_unit,
                        'no_wa' => $dt->no_wa,
                        'isi_pesan' => $dt->isi_pesan,
						'file' => $dt->file,
                        'status' => $dt->status,
                    ]);
                    TrxSending::create($sending);

                    TrxOutbox::destroy($dt->id);
                    //return redirect(url('send-message'))->with('status','success');
                }else{
                    $tkirim = now();
                    $upd = ([
                        'tgl_terkirim' => $tkirim,
                        'status' => 'failed'
                    ]);
                    $sending =([
                        'id' => $dt->id,
                        'jenis_pesan' => $dt->jenis_pesan,
                        'id_source' => $dt->id_source,
                        'tgl_kirim' => $dt->tgl_kirim,
                        'tgl_terkirim' => $tkirim,
                        'id_unit' => $dt->id_unit,
                        'no_wa' => $dt->no_wa,
                        'isi_pesan' => $dt->isi_pesan,
						'file' => $dt->file,
                        'status' => $dt->status,
                    ]);
                    TrxSending::create($sending);

                    TrxOutbox::where('id',$dt->id)
                            ->update($upd);

                    //return redirect(url('send-message'))->with('status','warning');
                }
            }
            return ("Berhasil Selesai");
            // return  redirect(url('send-message'))->with('status selesai','succes');
       // }

    }
	public function getNotifSoftBand(){

        $data = TrxOutbox::where('tgl_terkirim',null)->whereIn('no_wa',['PROBLEM HANDLING'])->get();

     //   $url = env('WOOWA_URL_SEND').'send_message_group_id'; //sync
		//$url = env('WOOWA_URL_SEND').'async_send_message_group_id';  //async_send_message_group_id
		$url = env('WOOWA_URL_SEND').'send_group'; //sync
        $license = env('WOOWA_LICENSE');
        $keys = env('WOOWA_KEY');
		
       // $group_id =  'EeQPNBrxxFWCck7nPGxmkU'; //Group PROBLEM HANDLING MORA-PPV
		$group_id =  'BytwcwUWGjfBdXdypAKa0f'; //Group PROBLEM HANDLING MORA-PPV
       // $group_id =  'CL3wO83g9ZXKEhNp45V41a'; //Group Dummy

        $cek = $this->getStatusQr();
       // dd($cek,$data);
      //  if($cek->status=="phone_online"){
           // dd($data);
            foreach($data as $dt){

                $data = array(
                    "group_name" =>'PROBLEM HANDLING MORA-PPV',
                 //   "group_id"  => $group_id,
                    "key"         => $keys,
                    "message"     => $dt->isi_pesan
                );
				
				$response = Http::withHeaders([
					'Content-Type' => 'application/json',
				//	'Content-Length' => strlen($data)
				])->withOptions([
					'debug' => false,
					'connect_timeout' =>false,
					'timeout' => false,
					'verify' => false,
				])->post($url,[
					//"group_id"  => $group_id,
					"group_name" =>'PROBLEM HANDLING MORA-PPV',
                    "key"         => $keys,
                    "message"     => $dt->isi_pesan
				]);
				
               
		//		$data_string = json_encode($data);

			//	$ch = curl_init($url);
			//	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			//	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			//	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//	curl_setopt($ch, CURLOPT_VERBOSE, 0);
			//	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
			//	curl_setopt($ch, CURLOPT_TIMEOUT, 360);
			//	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			//	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			//	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			//	  'Content-Type: application/json',
			//	  'Content-Length: ' . strlen($data_string))
			//	);
				//echo $res=curl_exec($ch);
				//curl_close($ch);
			//	 $this->getNotifSoftBandFile($dt->file);
              //   dd($response->body(),$response->successful(),curl_exec($ch));
                if($response->body()=='success'){
                    $tkirim = now();
                    $upd = ([
                        'tgl_terkirim' => $tkirim,
                        'status' => 'success'
                    ]);
                    TrxOutbox::where('id',$dt->id)
                            ->update($upd);
                    $sending =([
                        'id' => $dt->id,
                        'jenis_pesan' => $dt->jenis_pesan,
                        'id_source' => $dt->id_source,
                        'tgl_kirim' => $dt->tgl_kirim,
                        'tgl_terkirim' => $tkirim,
                        'id_unit' => $dt->id_unit,
                        'no_wa' => $dt->no_wa,
                        'isi_pesan' => $dt->isi_pesan,
						'file' => $dt->file,
                        'status' => $dt->status,
                    ]);
                    TrxSending::create($sending);

                    TrxOutbox::destroy($dt->id);
                    //return redirect(url('send-message'))->with('status','success');
                }else{
                    $tkirim = now();
                    $upd = ([
                        'tgl_terkirim' => $tkirim,
                        'status' => 'failed'
                    ]);
                    $sending =([
                        'id' => $dt->id,
                        'jenis_pesan' => $dt->jenis_pesan,
                        'id_source' => $dt->id_source,
                        'tgl_kirim' => $dt->tgl_kirim,
                        'tgl_terkirim' => $tkirim,
                        'id_unit' => $dt->id_unit,
                        'no_wa' => $dt->no_wa,
                        'isi_pesan' => $dt->isi_pesan,
						'file' => $dt->file,
                        'status' => $dt->status,
                    ]);
                    TrxSending::create($sending);

                    TrxOutbox::where('id',$dt->id)
                            ->update($upd);

                    //return redirect(url('send-message'))->with('status','warning');
                }
            }
            return ("Berhasil Selesai");
            // return  redirect(url('send-message'))->with('status selesai','succes');
       // }

    }
	public function getNotifSoftBandFile($file){
      
        $url = env('WOOWA_URL_SEND').'send_file_url_group_id';
        $license = env('WOOWA_LICENSE');
        $keys = env('WOOWA_KEY');
       // $group_id =  'BytwcwUWGjfBdXdypAKa0f'; //Group PROBLEM HANDLING MORA-PPV
        $group_id =  'CL3wO83g9ZXKEhNp45V41a'; //Group Dummy
        $img_url = $file;

        $cek = $this->getStatusQr();
       // dd($cek,$data);
        if($cek=="online"){
           
              $data = array(
                    // "group_name" =>'Mentoring Decoding',
                    "group_id"  => $group_id,
                    "key"         => $keys,
                    "url"     => $img_url,
                );

                $data_string = json_encode($data);
                //dd($data_string);
                $ch = curl_init($url);
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
               // dd(curl_exec($ch));
                $res=curl_exec($ch);
                 curl_close($ch);
                // dd($res);
               //return ("Berhasil Selesai");
        }
    }
    public function cek_number($no_wa)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->withOptions([
            'debug' => false,
            'connect_timeout' =>false,
            'timeout' => false,
            'verify' => false,
        ])->post('http://116.203.191.58/api/check_number',[
            'phone_no' => $no_wa,
            'key' => $this->key,
        ]);

        // // dd($response,$msg,$request->no_wa,$response->successful(),$response->status());
        // if($response->successful()){
        //     return redirect(url('send-message'))->with('status','success');
        // }else{

        //     return redirect(url('send-message'))->with('status','warning');
        // }
        //dd($response->successful(),$response->body(),$response->ok(),$no_wa);
        return $response->successful();
    }
	
	public function getStatusQr(){

        $url = env('WOOWA_URL_SEND');
        $license = env('WOOWA_KEY');

        $data = array(
            'key' => $license,
        );

    //    $data_string = http_build_query($data);

   //     $ch = curl_init($url.'qrstatus');
    //    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  //      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  //      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  //      curl_setopt($ch, CURLOPT_VERBOSE, 0);
  //      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
  //      curl_setopt($ch, CURLOPT_TIMEOUT, 360);
   //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
   //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  //      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  //          'Authorization: Basic dXNtYW5ydWJpYW50b3JvcW9kcnFvZHJiZWV3b293YToyNjM3NmVkeXV3OWUwcmkzNDl1ZA=='
   //     ));
  //  $res = json_decode(curl_exec($ch));
	try {
		$response = Http::post($url . 'qr_status', $data);
	}catch (\Exception $e) {
                // Tangani kesalahan
                return 'Error: ' . $e->getMessage();
            }
		
    return $response->body();

    //dd($res->key,$res->message,$res->status);


    }
	
}
