<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\TrxOutbox;
use App\Models\TrxBayar;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ViewGetInvTagihan;
use App\Models\TrxNonaktifLayanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Crypt;
use App\Models\TrxNonaktifLayananDetail;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;

use function PHPUnit\Framework\isNull;

class AdminProsesRequestMoraController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {

        $data = TrxNonaktifLayanan::all();
        //dd($bayar);
        $role = Auth::user()->role;
        $id_user = Auth::user()->id;
        return view('administrator.nonaktif_layanan.index',[
            'data' => $data,
            'level' => $role,
            'id_user' =>$id_user,
            'title' => 'Data Request Non Aktif Pelanggan',
        ]);
    }
    public function showdata(Request $request)
    {

     //   dd($request);
        $datahdr = TrxNonaktifLayanan::where('id',$request->id)->first();


        // dd($data);
        $role = Auth::user()->role;
        $id_user = Auth::user()->id;
        if($request->show=="list")
        {
            $data = TrxNonaktifLayananDetail::join('pelanggan','pelanggan.id','=','trx_nonaktif_layanan_detail.pelanggan_id')
                                            ->where('trx_nonaktif_layanan_detail.id',$request->id)
                                            ->get(['trx_nonaktif_layanan_detail.*','pelanggan.nama_lengkap']);
            return view('administrator.nonaktif_layanan.show',[
                'hdrdata' => $datahdr,
                'data' => $data,
                'level' => $role,
                'id_user' =>$id_user,
                'title' => 'Data Request Non Aktif Pelanggan',
            ]);
        }
        if($request->show=="upd"){
            $data = TrxNonaktifLayananDetail::join('pelanggan','pelanggan.id','=','trx_nonaktif_layanan_detail.pelanggan_id')
                                            ->where('trx_nonaktif_layanan_detail.id',$request->id)
                                            ->where('trx_nonaktif_layanan_detail.tipe_trx',$request->tipe_trx)
                                            ->where('trx_nonaktif_layanan_detail.id_trx',$request->id_trx)
                                            ->first(['trx_nonaktif_layanan_detail.*','pelanggan.nama_lengkap']);
            //dd($data);
            return view('administrator.nonaktif_layanan.showform',[
                'hdrdata' => $datahdr,
                'data' => $data,
                'level' => $role,
                'id_user' =>$id_user,
                'title' => 'Form Update Data Pelanggan ',
            ]);
        }
        if($request->sendgroup=='list')
        {
            $link1='viewnonaktif&id='.$request->id;
            $link2 = Crypt::encrypt($link1);
            $link=url('/data/'.$link2);
            //$link=url('/viewnonaktif&id='.$request->id);
            $datahdr = TrxNonaktifLayanan::where('id',$request->id)->first();
			$data = TrxNonaktifLayananDetail::join('pelanggan', 'pelanggan.id', '=', 'trx_nonaktif_layanan_detail.pelanggan_id')
                ->where('trx_nonaktif_layanan_detail.id', $request->id)
                ->get(['pelanggan.nama_lengkap', 'pelanggan.sub_tower', 'pelanggan.lantai', 'pelanggan.nomer_unit', 'pelanggan.cid']);
            $isi = '';
            $a = 1;
            foreach ($data as $dt) {
                $isi = $isi . "\n" . $a . ") " . $dt->cid . " " . $dt->nama_lengkap . " " . $dt->sub_tower . "/" . $dt->lantai . "/" . $dt->nomer_unit ;
                $a++;
            }
			
            $psn = DB::table('set_tmp_pesan')->where('kode_pesan','PS011')->first('pesan')->pesan;
            $psn = str_replace('[link]',$link . "\n " . $isi,$psn);
			// $psn = str_replace('[link]','' . "\n " . $isi,$psn);
            $pesan_outbox =([
                'jenis_pesan' => 'notif_suspen',
                'id_source' => $request->id,
                'tgl_kirim' => now(),
                'no_wa' => 'Billing MPJ',
                'id_unit' => 'all',
                'isi_pesan' => $psn,
                'status' => 'proses'
            ]);

            TrxOutbox::create($pesan_outbox);
            if(isNull($datahdr->kirim_notif)){
                $d =$datahdr->kirim_notif;
            }else{
                $d=0;
            }

            $updNotif = ([
                'kirim_notif' => $d=$d+1,
                'tgl_kirim_notif' => now(),
            ]);
            TrxNonaktifLayanan::where('id',$request->id)->update($updNotif);

            return redirect('/admin/trx_nonaktif')->with('success','Berhasil mengirim notifikasi '.$datahdr->no_nonaktif_layanan.' ke Group Whatsapp Billing MPJ');
        }
        if($request->sendgroup=='req_on')
        {
            $data = TrxNonaktifLayananDetail::join('pelanggan','pelanggan.id','=','trx_nonaktif_layanan_detail.pelanggan_id')
                                            ->where('trx_nonaktif_layanan_detail.id',$request->id)
                                            ->where('trx_nonaktif_layanan_detail.tipe_trx',$request->tipe_trx)
                                            ->where('trx_nonaktif_layanan_detail.id_trx',$request->id_trx)
                                            ->first(['trx_nonaktif_layanan_detail.*','pelanggan.nama_lengkap','pelanggan.sub_tower','pelanggan.lantai','pelanggan.nomer_unit','pelanggan.cid']);
			$cekBayar = TrxBayar::where('no_tagihan',$request->id_trx)->first();
			// dd($cekBayar);
			if($cekBayar){
            $psn = DB::table('set_tmp_pesan')->where('kode_pesan','PS013')->first('pesan')->pesan;
            $psn = str_replace('[cid]',$data->cid,$psn);
            $psn = str_replace('[nama_lengkap]',$data->nama_lengkap,$psn);
            $psn = str_replace('[unitid]',$data->sub_tower."/".$data->lantai."/".$data->nomer_unit,$psn);
            $pesan_outbox =([
                'jenis_pesan' => 'notif pengaktifan',
                'id_source' => $request->id_trx,
                'tgl_kirim' => now(),
                'no_wa' => 'Billing MPJ',
                'id_unit' => $data->sub_tower."/".$data->lantai."/".$data->nomer_unit,
                'isi_pesan' => $psn,
                'status' => 'proses'
            ]);

            TrxOutbox::create($pesan_outbox);
            if(isNull($data->kirim_notif)){
                $d =$data->kirim_notif;
            }else{
                $d=0;
            }

            $updNotif = ([
                'kirim_notif' => $d=$d+1,
                'tgl_kirim_notif' => now(),
                'tgl_req_on' => now(),
            ]);
            TrxNonaktifLayananDetail::where('id',$request->id)
                                    ->where('tipe_trx',$request->tipe_trx)
                                    ->where('id_trx',$request->id_trx)
                                    ->update($updNotif);

            return redirect('/admin/trx_nonaktif/getdata?show=list&id='.$request->id)->with('success','Berhasil mengirim notifikasi pengaktifan kembali invoice '.$data->nomer_trx.', atas nama '.$data->nama_lengkap.' ke Group Whatsapp Billing MPJ');
				}else{

                return redirect('/admin/trx_nonaktif/getdata?show=list&id=' . $request->id)->with('danger', 'Gagal mengirim notifikasi pengaktifan kembali invoice ' . $data->nomer_trx . ', atas nama ' . $data->nama_lengkap . ' ke Group Whatsapp Billing MPJ. Karena transaksi pembayaran belum dilakukan. Input Transaksi pembayaran terlebih dahulu');
            }
        }
		if($request->show=="rekap"){
            $data = DB::table('vGetRekapNonAktif')->get();
            return view('administrator.nonaktif_layanan.showRekap',[
                'data' => $data,
                'level' => $role,
                'id_user' =>$id_user,
                'title' => 'Monitoring Data Pelanggan Non Aktif',
            ]);
        }
        if($request->show=="dtlPelanggan"){
            $data = DB::table('vGetDtlNonAktif')->get();
            return view('administrator.nonaktif_layanan.showdtlPelanggan',[
                'data' => $data,
                'level' => $role,
                'id_user' =>$id_user,
                'title' => 'Monitoring Data Pelanggan Non Aktif',
            ]);
        }
    }
    public function shownonaktif(Request $request)
    {
        $role=0;

        return view('beranda.pageListNonAktif',[

            'level' => $role,
            'active' => ''
        ]);
        // dd($request->all(),Auth::user());
    }
    public function simpanRequest(Request $request){

        $no_nonaktif_layanan = $request->nomer;
        $tgl = Carbon::parse($request->tgl)->format('Y-m-d H:i');
        $jml = count($request->cek);
        $hdrnonaktif =([
            'no_nonaktif_layanan' => $no_nonaktif_layanan,
            'tgl' => $tgl,
            'jml' => $jml,
        ]);

        $idhdr = TrxNonaktifLayanan::create($hdrnonaktif)->id;
      //  dd($idhdr);
        $cek = $request->cek;
        for($i=0;$i<$jml;$i++){
            $data = ViewGetInvTagihan::where('outstanding','>',0)->where('no_invoice',$cek[$i])->first();
            $dtlNonaktif =([
                'id' => $idhdr,
                'tipe_trx' => '2',
                'id_trx' => $data->id_tagihan,
                'nomer_trx' => $data->no_invoice,
                'pelanggan_id' => $data->pelanggan_id,
                'jml_hari_telat' => $data->exp,
                'tgl_jatuh_tempo' => $data->tgl_jatuh_tempo,
                'periode_pemakaian' => $data->periode_pemakaian,
                'gtot_tagihan' => $data->outstanding,
                'tgl_req_off' => $tgl
            ]);
            // dd($dtlNonaktif);
            $valid = TrxNonaktifLayananDetail::where('id_trx',$data->id_tagihan)->where('nomer_trx',$data->no_invoice)->count();
            if($valid<1){
                TrxNonaktifLayananDetail::create($dtlNonaktif);
                $up = ([
                    'status_layanan' => '2'
                ]);
                Pelanggan::where('id',$data->pelanggan_id)
                ->update($up);

            }
        }
        // dd($request->all(),$jml);
        return redirect('/admin/trx_nonaktif')->with('success','Tambah data Non Aktif Berhasil');

    }
    public function simpanUpt(Request $request)
    {

        $id = $request->id;
        $tipe_trx = $request->tipe_trx;
        $id_trx = $request->id_trx;
        $tgl_req_on = null;
        $tgl_act_off = null;
        $tgl_act_on = null;

        $dtl = TrxNonaktifLayananDetail::where('id',$id)
                                        ->where('tipe_trx',$tipe_trx)
                                        ->where('id_trx',$id_trx)
                                        ->first();
        if(isset($request->tgl_act_off) and isset($request->tgl_act_off_time)){
            $tgl_act_off = Carbon::parse($request->tgl_act_off)->format('Y-m-d').' '.$request->tgl_act_off_time;
        }
        if(isset($request->tgl_reg_on) and isset($request->tgl_reg_on_time)){
            $tgl_req_on = Carbon::parse($request->tgl_reg_on)->format('Y-m-d').' '.$request->tgl_reg_on_time;
        }
        if(isset($request->tgl_act_on) and isset($request->tgl_act_on_time)){
            $tgl_act_on = Carbon::parse($request->tgl_act_on)->format('Y-m-d').' '.$request->tgl_act_on_time;
            $up = ([
                'status_layanan' => '1'
            ]);
            Pelanggan::where('id',$dtl->pelanggan_id)
                        ->update($up);
        }
       // $dt = Carbon::parse($request->tgl_act_off)->format('Y-m-d');

       // dd($request->all(),$tgl_req_on, $tgl_act_off,$tgl_act_on,$dt);
        $upddata = ([
            'tgl_req_on' => $tgl_req_on,
            'tgl_act_off' =>$tgl_act_off,
            'tgl_act_on' => $tgl_act_on,
        ]);

        TrxNonaktifLayananDetail::where('id',$id)
                                ->where('tipe_trx',$tipe_trx)
                                ->where('id_trx',$id_trx)
                                ->update($upddata);
        $nama = Pelanggan::where('id',$dtl->pelanggan_id)->first('nama_lengkap')->nama_lengkap;
        return redirect('/admin/trx_nonaktif/getdata?show=list&id='.$id)->with('success','Update data '.$nama.', Berhasil');
        // dd($request->all(),$tgl_req_on, $tgl_act_off,$tgl_act_on);
    }
    public function create()
    {
        $role = Auth::user()->role;
        $id_user = Auth::user()->id;
        return view('administrator.nonaktif_layanan.create',[
            'nomerRequest' => TrxNonaktifLayanan::nomerRequest(),
            'level' => $role,
            'id_user' =>$id_user,
            'title' => 'Form Request Non Aktif Pelanggan',
        ]);
    }
    public function GetRequestMora(Request $request)
    {
        $awal = $request->awal;
        $akhir = $request->akhir;

        $dt = TrxNonaktifLayananDetail::select('id_trx')->get();
        foreach($dt as $dtl){
            $datax[] = $dtl->id_trx;
        }

        $data = ViewGetInvTagihan::where('outstanding','>',0)
								->wherebetween('exp',[$awal,$akhir])
                                ->whereNotIn('id_tagihan',$datax)
								->whereNull('status_bayar')
								/*->whereIn('StatusPembayaran',["'Lunas'"]) */
                                ->get();
      //  dd($data);
        $role = Auth::user()->role;
        $id_user = Auth::user()->id;
        return view('administrator.nonaktif_layanan.showproses',[
            'nomerRequest' => TrxNonaktifLayanan::nomerRequest(),
            'data' => $data,
            'level' => $role,
            'id_user' =>$id_user,
            'title' => 'Data Invoice Pelanggan Telat Bayar ',
        ]);
    }
}
