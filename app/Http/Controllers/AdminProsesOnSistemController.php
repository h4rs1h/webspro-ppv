<?php

namespace App\Http\Controllers;

use App\Models\TrxTagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\TrxOutbox;
use Illuminate\Support\Facades\Crypt;

class AdminProsesOnSistemController extends Controller
{
    public function getInvoicePerDay(){
        DB::select('call GenerateInvoicePerDay()');

        $jm = TrxTagihan::whereDate('created_at',Carbon::today()->toDateString())->count();
        if($jm){
            DB::table('Trx_logProses')->insert([
                'tgl_proses' => now(),
                'proses' => 'Generate Invoice Harian',
                'keterangan' => 'Proses Berhasil membuat Invoice sebanyak '.$jm,
            ]);
        }

        return ("Berhasil");
    }
	
	public function getNotifDaftarOutstading(Request $request){

        DB::select('call GetNotifPendaftaranWA('.$request->jumlah.')');

        $jm = TrxOutbox::whereDate('created_at',Carbon::today()->toDateString())
                        ->where('isi_pesan','LIKE','%https://mediaprimajaringan.com/viewinvoice?no_order%')->count();
        if($jm){
            DB::table('Trx_logProses')->insert([
                'tgl_proses' => now(),
                'proses' => 'Generate Notifikasi Tagihan Pendaftaran',
                'keterangan' => 'Proses Berhasil membuat Notifikasi Tagihan Pendaftaran sebanyak '.$jm,
            ]);
        }

        return ("Berhasil");
    }
	public function getNotifTagihanOutstading(Request $request){

        DB::select('call GetNotifWA_Warning('.$request->jumlah.')');

        $jm = TrxOutbox::whereDate('created_at',Carbon::today()->toDateString())
                        ->where('isi_pesan','LIKE','%Expired Notifikasi%')->count();
        if($jm){
            DB::table('Trx_logProses')->insert([
                'tgl_proses' => now(),
                'proses' => 'Generate Notifikasi Tagihan',
                'keterangan' => 'Proses Berhasil membuat Notifikasi sebanyak '.$jm,
            ]);
        }

        return ("Berhasil");
    }
	public function getNotifTagihanOutstadingPutus(Request $request){

        DB::select('call GetNotifWA_WarningPutus_18('.$request->jumlah.')');

        $jm = TrxOutbox::whereDate('created_at',Carbon::today()->toDateString())
                        ->where('isi_pesan','LIKE','%Expired Notifikasi%')->count();
        if($jm){
            DB::table('Trx_logProses')->insert([
                'tgl_proses' => now(),
                'proses' => 'Generate Notifikasi Tagihan',
                'keterangan' => 'Proses Berhasil membuat Notifikasi sebanyak '.$jm,
            ]);
        }

        return ("Berhasil");
    }
	public function getNotifTagihanOutstadingReminder(Request $request){

        DB::select('call GetNotifWA_Reminder('.$request->jumlah.')');

        $jm = TrxOutbox::whereDate('created_at',Carbon::today()->toDateString())
                        ->where('isi_pesan','LIKE','%*H -%')->count();
        if($jm){
            DB::table('Trx_logProses')->insert([
                'tgl_proses' => now(),
                'proses' => 'Generate Notifikasi Tagihan Reminder',
                'keterangan' => 'Proses Berhasil membuat Notifikasi Reminder sebanyak '.$jm,
            ]);
        }

        return ("Berhasil");
    }
	public function getKirimEmail(Request $request){



        $details = [
            'title' => 'Tagihan Layanan Bulan Juni - MPJ',
            'tgl' => Carbon::today()->format('d-m-Y'),

        ];

        \Mail::to('hrsanto@gmail.com')->send(new \App\Mail\MyTestMail($details));

        return "berhasil";
    }
	public function getPinvKirimEmail(Request $request){
		// dd("a");
        $data1 = DB::select("call GetUnitReadytoInv();");
        $data2 = DB::select("call GetChackUnitInv();");
		$data3 = DB::select("call GetNotifTerminPelanggan();");
		
		DB::table('trx_outbox')->where('status', 'failed')->delete();
        $details = [
            'title' => 'Proses Generate Proforma Inv - '.Carbon::today()->format('d-m-Y'),
            'tgl' => Carbon::today()->format('d-m-Y'),
            'data1' => $data1,
            'data2' => $data2,
			 'data3' => $data3,
        ];
		//dd($details);
        \Mail::to('hrsanto@gmail.com')->send(new \App\Mail\MyTestMail($details));

        return "berhasil";
    }
	public function getKirimNotifTerminWa(){

        $dataTermin = DB::select('call GetNotifTerminPelanggan()');
        foreach($dataTermin as $dt){
            if($dt->jt_exp>-1){
                $hdr="*H ".$dt->jt_exp." Expired Notifikasi*
                ============================
                Jakarta,";
                $data = DB::select('call GetKirimNotifTermin('.$dt->no_order.','.$dt->tipe_order.')');
                foreach($data as $d){
                    $pesan = str_replace('Jakarta,',' '.$hdr,$d->pesan_); ;
                    $id = $d->id_;
                    $no_wa = $d->no_wa_;
                    $unitid = $d->unitid_;
                    $no_formulir = $d->no_formulir_;
                }
				
				$link1='viewtagihantermin&no_order='.$dt->no_order.'&tipe_order='.$dt->tipe_order;
            	$link2 = Crypt::encrypt($link1);
            	$link=url('/data/'.$link2);
            	$psn = str_replace('[link]',' '.$link,$pesan);

				$pesan_outbox =([
					'jenis_pesan' => 'trx_termin_tagihan',
					'id_source' => $id,
					'tgl_kirim' => now(),
					'no_wa' => $no_wa,
					'id_unit' => $unitid ,
					'isi_pesan' => $psn,
					'status' => 'proses'
				]);

            	TrxOutbox::create($pesan_outbox);
            }else{
                $data = DB::select('call GetKirimNotifTermin('.$dt->no_order.','.$dt->tipe_order.')');
                foreach($data as $d){
                    $pesan = $d->pesan_;
                    $id = $d->id_;
                    $no_wa = $d->no_wa_;
                    $unitid = $d->unitid_;
                    $no_formulir = $d->no_formulir_;
                }
				$link1='viewtagihantermin&no_order='.$dt->no_order.'&tipe_order='.$dt->tipe_order;
            	$link2 = Crypt::encrypt($link1);
            	$link=url('/data/'.$link2);
            	$psn = str_replace('[link]',' '.$link,$pesan);

				$pesan_outbox =([
					'jenis_pesan' => 'trx_termin_tagihan',
					'id_source' => $id,
					'tgl_kirim' => now(),
					'no_wa' => $no_wa,
					'id_unit' => $unitid ,
					'isi_pesan' => $psn,
					'status' => 'proses'
				]);

            	TrxOutbox::create($pesan_outbox);
            }            
        }
        return "berhasil";
    }
}
