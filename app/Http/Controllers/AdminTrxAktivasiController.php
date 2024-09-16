<?php

namespace App\Http\Controllers;

use App\Models\TrxAktivasi;
use App\Models\ViewAktivasi;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Models\ViewListAktivasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminTrxAktivasiController extends Controller
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
        //  dd(ViewAktivasi::all());
        $role = Auth::user()->role;
        return view('administrator.aktivasi.index',[
            'order' => ViewAktivasi::all(),
            'level' => $role,
            'title' => 'Data Aktivasi Pelanggan',
        ]);
    }

    public function getorder()
    {
        $role = Auth::user()->role;
        return view('administrator.aktivasi.listaktivasi',[
            'order' => ViewListAktivasi::all(),
            'level' => $role,
            'title' => 'Data Pelanggan Siap Aktivasi',
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
        return view('administrator.aktivasi.create',[
            'order' => ViewAktivasi::all(),
            'level' => $role,
            'title' => 'Tambah Data Aktivasi',
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

      //  dd($request,$request->jenis_aktivasi);
        if($request->jenis_aktivasi=='1'){

            $validateData = $request->validate([
                'nama_penerima' => 'required|max:100',
                'nama_teknisi' => 'required|max:100',
                'tanggal_aktivasi_int' => 'required|date',
                'nomor_router' => 'required|max:100',
                'jml_router' => 'required',
                'denda_router' => 'required',
            ]);

            $dataSimpan = ([
                'trx_order_id' => $request->trx_order_id,
                'jenis_aktivasi' => $request->jenis_aktivasi,
                'tgl_aktivasi' => $request->tanggal_aktivasi_int,
                'jml_perangkat' => $request->jml_router,
                'no_perangkat' => $request->nomor_router,
                'denda_rusak_hilang' => $request->denda_router,
                'nama_penerima' => $request->nama_penerima,
                'nama_teknisi' => $request->nama_teknisi,
                'layanan_id' => $request->layanan_id,
               // 'images_tta' => $request->file('image')->store('aktivasi-router'),
                'user_id' => auth()->user()->id,
            ]);

            if($request->file('image')){
                $dataSimpan['images_tta'] = $request->file('image')->store('aktivasi-router');
            }

            $dataSimpanFreeStb = ([
                'trx_order_id' => $request->trx_order_id,
                'jenis_aktivasi' => $request->jenis_aktivasi_free_stb,
                'tgl_aktivasi' => $request->tanggal_aktivasi_free_stb,
                'jml_perangkat' => $request->jml_free_stb,
                'no_perangkat' => $request->nomor_free_stb,
                'denda_rusak_hilang' => $request->denda_free_stb,
                'nama_penerima' => $request->nama_penerima_free_stb,
                'nama_teknisi' => $request->nama_teknisi_free_stb,
                'layanan_id' => $request->layanan_id,
               // 'images_tta' => $request->file('image')->store('aktivasi-router'),
                'user_id' => auth()->user()->id,
            ]);
            if($request->file('image_free_stb')){
                $dataSimpanFreeStb['images_tta'] = $request->file('image_free_stb')->store('aktivasi-router');
            }
            if($request->tanggal_aktivasi_free_stb!=null){
                TrxAktivasi::create($dataSimpanFreeStb);
				$upPelanggan =([
                    'tgl_aktivasi_stb_free' => $request->tanggal_aktivasi_free_stb,

                ]);
                Pelanggan::where('id',$request->pelanggan_id)->update($upPelanggan);
            }

			$upPelanggan =([
                'tgl_aktivasi' => $request->tanggal_aktivasi_int,
                'tgl_tagihan_router' => $request->tanggal_aktivasi_int,
            ]);
            Pelanggan::where('id',$request->pelanggan_id)->update($upPelanggan);
            $msg = 'Internet';
        }
        elseif($request->jenis_aktivasi=='2'){
            $validateData = $request->validate([
                'nama_penerima' => 'required|max:100',
                'nama_teknisi' => 'required|max:100',
                'tanggal_aktivasi_tv' => 'required|date',
                'nomor_stb' => 'required|max:100',
                'jml_stb' => 'required',
                'denda_stb' => 'required',
            ]);

            if($request->file('image_stb')){
                $validateData['image'] = $request->file('image_stb')->store('aktivasi-stb');
            }

            $dataSimpan = ([
                'trx_order_id' => $request->trx_order_id,
                'jenis_aktivasi' => $request->jenis_aktivasi,
                'tgl_aktivasi' => $request->tanggal_aktivasi_tv,
                'jml_perangkat' => $request->jml_stb,
                'no_perangkat' => $request->nomor_stb,
                'denda_rusak_hilang' => $request->denda_stb,
                'nama_penerima' => $request->nama_penerima,
                'nama_teknisi' => $request->nama_teknisi,
                'layanan_id' => $request->layanan_id,
               
                'user_id' => auth()->user()->id,
            ]);
			if($request->file('image_stb')){
                $validateData['images_tta'] = $request->file('image_stb')->store('aktivasi-stb');
            }
			 $upPelanggan =([
                'tgl_aktivasi_stb_berbayar' => $request->tanggal_aktivasi_tv,

            ]);
            Pelanggan::where('id',$request->pelanggan_id)->update($upPelanggan);
            $msg = 'TV';
		//	dd($dataSimpan);
        }
        else{
            $validateData = $request->validate([
                'nama_penerima' => 'required|max:100',
                'nama_teknisi' => 'required|max:100',
                'tanggal_aktivasi_telepon' => 'required|date',
            ]);
            $dataSimpan = ([
                'trx_order_id' => $request->trx_order_id,
                'jenis_aktivasi' => $request->jenis_aktivasi,
                'tgl_aktivasi' => $request->tanggal_aktivasi_telepon,
                'jml_perangkat' => null,
                'no_perangkat' => null,
                'denda_rusak_hilang' => null,
                'nama_penerima' => $request->nama_penerima,
                'nama_teknisi' => $request->nama_teknisi,
                'layanan_id' => $request->layanan_id,
                'images_tta' => null,
                'user_id' => auth()->user()->id,
            ]);
			 $upPelanggan =([
                'tgl_aktivasi_telepony' => $request->tanggal_aktivasi_telepon,

            ]);
            Pelanggan::where('id',$request->pelanggan_id)->update($upPelanggan);
            $msg = 'Telepon';
        }

       // dd($dataSimpan);



       TrxAktivasi::create($dataSimpan);

        return redirect('/admin/trx_aktivasi')->with('success','Berhasil melakukan aktivasi layanan '.$msg);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrxAktivasi  $trxAktivasi
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
      //  dd( $request);
        $order = $request->id;
        $role = Auth::user()->role;
       // dd(ViewListAktivasi::where('id',$order)->first());
        return view('administrator.aktivasi.create',[
            'order' => ViewListAktivasi::where('id',$order)->first(),
            'jns_aktivasi' => $request->lyn,
           // 'order' => ViewAktivasi::all(),
            'level' => $role,
            'title' => 'Tambah Data Aktivasi',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrxAktivasi  $trxAktivasi
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
       // dd($request);
        $order = $request->id;
        if($request->lyn=='int'){
            $dtldata =ViewAktivasi::where('id_aktivasi_router',$order)->first();
            $m = 'Internet';
        }
        elseif($request->lyn=='tv'){
            $dtldata =ViewAktivasi::where('id_aktivasi_stb',$order)->first();
            $m = 'TV';
        }else{
            $dtldata =ViewAktivasi::where('id_aktivasi_telp',$order)->first();
            $m = 'Telepon';
        }
      //  dd($dtldata);
        $role = Auth::user()->role;
        return view('administrator.aktivasi.edit',[
            'id_aktivasi' => $order,
            'order' => $dtldata,
            'jns_aktivasi' => $request->lyn,
            'level' => $role,
            'title' => 'Edit Data Aktivasi '.$m,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrxAktivasi  $trxAktivasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TrxAktivasi $trxAktivasi)
    {
        //

      //  dd($request->file('image')->store('aktivasi-router'));
       // dd($request);
        if($request->jenis_aktivasi=='1'){

            //$validateData =
            $request->validate([
                'nama_penerima' => 'required|max:100',
                'nama_teknisi' => 'required|max:100',
                'nama_penerima_free_stb' => 'required|max:100',
                'nama_teknisi_free_stb' => 'required|max:100',
                'tanggal_aktivasi_int' => 'required|date',
                'nomor_router' => 'required|max:100',
                'jml_router' => 'required',
                'denda_router' => 'required',
            ]);

            $dataSimpan = ([
                'trx_order_id' => $request->trx_order_id,
                'jenis_aktivasi' => $request->jenis_aktivasi,
                'tgl_aktivasi' => $request->tanggal_aktivasi_int,
                'jml_perangkat' => $request->jml_router,
                'no_perangkat' => $request->nomor_router,
                'denda_rusak_hilang' => $request->denda_router,
                'nama_penerima' => $request->nama_penerima,
                'nama_teknisi' => $request->nama_teknisi,
              //  'images_tta' => $request->file('image')->store('aktivasi-router'),
                'user_id' => auth()->user()->id,
            ]);
			 
            $dataSimpanFreeStb = ([
                'trx_order_id' => $request->trx_order_id,
                'jenis_aktivasi' => $request->jenis_aktivasi_free_stb,
                'tgl_aktivasi' => $request->tanggal_aktivasi_free_stb,
                'jml_perangkat' => $request->jml_free_stb,
                'no_perangkat' => $request->nomor_free_stb,
                'denda_rusak_hilang' => $request->denda_free_stb,
                'nama_penerima' => $request->nama_penerima_free_stb,
                'nama_teknisi' => $request->nama_teknisi_free_stb,
				 'layanan_id' => '1',
               // 'images_tta' => $request->file('image_free_stb')->store('aktivasi-free-stb'),
                'user_id' => auth()->user()->id,
            ]);
            if($request->file('image')){
                if($request->oldImage){
                    Storage::delete($request->oldImage);
                }
                $dataSimpan['images_tta'] = $request->file('image')->store('aktivasi-router');
            }
            if($request->file('image_free_stb')){
                if($request->oldImage_free_stb){
                    Storage::delete($request->oldImage_free_stb);
                }
                $dataSimpanFreeStb['images_tta'] = $request->file('image_free_stb')->store('aktivasi-free-stb');
            }

            $msg = 'Internet';
           // dd($dataSimpanFreeStb,$request->id_aktivasi_free_stb);
			if($request->id_aktivasi_free_stb){
				TrxAktivasi::where('id', $request->id_aktivasi_free_stb)
                        ->update($dataSimpanFreeStb);

			}
            if($request->tanggal_aktivasi_free_stb!=null){
                TrxAktivasi::create($dataSimpanFreeStb);
            }
        }
        elseif($request->jenis_aktivasi=='2'){

            $validateData = $request->validate([
                'nama_penerima' => 'required|max:100',
                 'nama_teknisi' => 'required|max:100',
                 'tanggal_aktivasi_tv' => 'required|date',
                 'nomor_stb' => 'required|max:100',
                 'jml_stb' => 'required',
                 'denda_stb' => 'required',
            ]);
           // dd($request,$validateData);
            $dataSimpan = ([
                'trx_order_id' => $request->trx_order_id,
                'jenis_aktivasi' => $request->jenis_aktivasi,
                'tgl_aktivasi' => $request->tanggal_aktivasi_tv,
                'jml_perangkat' => $request->jml_stb,
                'no_perangkat' => $request->nomor_stb,
                'denda_rusak_hilang' => $request->denda_stb,
                'nama_penerima' => $request->nama_penerima,
                'nama_teknisi' => $request->nama_teknisi,
              //  'images_tta' => $request->file('image_stb')->store('aktivasi-stb'),
                'user_id' => auth()->user()->id,
            ]);

            if($request->file('image_stb')){
                if($request->oldImage_stb){
                    Storage::delete($request->oldImage_stb);
                }
                $dataSimpan['images_tta'] = $request->file('image_stb')->store('aktivasi-stb');
            }
            $msg = 'TV';
           // dd($dataSimpan);
        }
        else{
            $validateData = $request->validate([
                'nama_penerima' => 'required|max:100',
                'nama_teknisi' => 'required|max:100',
                'tanggal_aktivasi_telepon' => 'required|date',
            ]);
            $dataSimpan = ([
                'trx_order_id' => $request->trx_order_id,
                'jenis_aktivasi' => $request->jenis_aktivasi,
                'tgl_aktivasi' => $request->tanggal_aktivasi_telepon,
                'jml_perangkat' => null,
                'no_perangkat' => null,
                'denda_rusak_hilang' => null,
                'nama_penerima' => $request->nama_penerima,
                'nama_teknisi' => $request->nama_teknisi,
                'images_tta' => null,
                'user_id' => auth()->user()->id,
            ]);
            $msg = 'Telepon';
        }

        //dd($dataSimpan);

       TrxAktivasi::where('id', $trxAktivasi->id)
                    ->update($dataSimpan);


        // return redirect('/admin/pelanggan')->with('success','Berhasil Update Data Aktivasi '.$msg);
        return redirect('/admin/trx_aktivasi')->with('success','Berhasil Update Data Aktivasi layanan '.$msg);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrxAktivasi  $trxAktivasi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $trxAktivasi = TrxAktivasi::where('id',$request->id)->first();
       // dd($trxAktivasi,$request, $trxAktivasi->no_perangkat);
        $no_bayar = $trxAktivasi->no_perangkat;
        if($trxAktivasi->images_tta){
            Storage::delete($trxAktivasi->images_tta);
        }

         TrxAktivasi::destroy($trxAktivasi->id);
        // TrxOrderDetail::destroy($trxOrder->no_order);
         return redirect('/admin/trx_aktivasi')->with('success','Berhasil Menghapus Data Aktivasi no '.$no_bayar.' Pelanggan');
    }
}
