<?php

namespace App\Http\Controllers;

use App\Models\Tower;
use App\Models\Lantai;
use App\Models\SubTower;
use App\Models\TrxBayar;
use App\Models\TrxOrder;
use App\Models\Pelanggan;
use Nette\Utils\DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePelangganRequest;
use App\Http\Requests\UpdatePelangganRequest;
use App\Models\ViewTagihanPelanggan;

class PelangganController extends Controller
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
        //
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
     * @param  \App\Http\Requests\StorePelangganRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePelangganRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function show(Pelanggan $pelanggan)
    {
      //  dd($pelanggan);
        $identitas = [
            ['id' =>'1','name' =>'KTP'],
            ['id' =>'2','name' =>'SIM'],
            ['id' =>'3','name' =>'PASPORT'],
            ];
        $status_unit = [
            ['id' =>'1','name' =>'Pemilik'],
            ['id' =>'2','name' =>'Penyewa'],
            ];
        $jkel = [
            ['id' =>'1','name' =>'Laki-Laki'],
            ['id' =>'2','name' =>'Perempuan'],
            ['id' =>'3','name' =>'Other'],
            ];
        $agama = [
            ['id' =>'1','name' =>'ISLAM'],
            ['id' =>'2','name' =>'KATHOLIK'],
            ['id' =>'3','name' =>'KRISTEN'],
            ['id' =>'4','name' =>'HINDU'],
            ['id' =>'5','name' =>'BUDHA'],
            ['id' =>'6','name' =>'OTHER'],
            ];

        $role = Auth::user()->role;
        $dtlPelanggan = Pelanggan::where('id',Auth::user()->pelanggan_id)->first();
        return view('pelanggan.show',[
            'pelanggan' => $dtlPelanggan,
            'level' => $role,
            'title' => 'Data Pelanggan',
            'identitas' => $identitas,
            'agama' => $agama,
            'jkel' => $jkel,
            'status_unit' => $status_unit,
            'tower' => Tower::all(),
            'subtower' => SubTower::all()->sortBy('name',SORT_NATURAL),
            'lantai' => Lantai::all()->sortBy('name',SORT_NATURAL),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function edit(Pelanggan $pelanggan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePelangganRequest  $request
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePelangganRequest $request, Pelanggan $pelanggan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pelanggan $pelanggan)
    {
        //
    }

    public function getLayanan(Pelanggan $pelanggan){
        //dd();
        $idPelanggan = Auth::user()->pelanggan_id;

        $dtlPelanggan = Pelanggan::where('id',$idPelanggan)->first();
        $c = DB::select('select *,Date_add(DATE_ADD(tgl_rencana_belangganan, INTERVAL 1 month),INTERVAL -1 day) TglBerakhirLayanan from trx_order where pelanggan_id = ?', [$idPelanggan]);

        foreach($c as $r){
            $dt = $r->TglBerakhirLayanan;
        }

        $role = Auth::user()->role;
        return view('pelanggan.showlayanan',[
            'pelanggan' => $dtlPelanggan,
            'level' => $role,
            'title' => 'Data Layanan Pelanggan',
            'order' => TrxOrder::where('pelanggan_id',$idPelanggan)->get(),
            'tglJt' => $dt,
        ]);
    }
    public function getInvoice(Pelanggan $pelanggan){
        //dd();
        $idPelanggan = Auth::user()->pelanggan_id;

        $dtlPelanggan = Pelanggan::where('id',$idPelanggan)->first();
        $c = DB::select('select *,Date_add(DATE_ADD(tgl_rencana_belangganan, INTERVAL 1 month),INTERVAL -1 day) TglBerakhirLayanan from trx_order where pelanggan_id = ?', [$idPelanggan]);

        foreach($c as $r){
            $dt = $r->TglBerakhirLayanan;
        }

        $role = Auth::user()->role;
      //  dd(TrxBayar::where('pelanggan_id',$idPelanggan )->get());
        return view('pelanggan.showInvoice',[
            'pelanggan' =>  $dtlPelanggan ,
            'level' => $role,
            'title' => 'Data Invoice',
            'bayar' => ViewTagihanPelanggan::where('pelanggan_id',$idPelanggan )->get(),
            'tglJt' => $dt,
        ]);
    }

public function getShowInvoice (Request $request)
    {
        $role = Auth::user()->role;
        $idPelanggan = $request->uid;
        $no_inv = $request->no_inv;
        return view('pelanggan.showDtlInvoice',[

            'level' => $role,
            'title' => 'Data Invoice',
            'bayar' => ViewTagihanPelanggan::where('pelanggan_id',$idPelanggan )
                                        ->where('no_bayar',$no_inv)->first(),

        ]);

    }
}
