<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ViewOutstdPendaftaran;

class AdminLaporanSalesAdmin extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $a = DB::table('trx_order')->where('tipe_order','1')->count();
        $b = DB::table('trx_order')->whereDate('tgl_order','=',Carbon::yesterday()->toDateString())->count();
      //  $c = ViewOutstdPendaftaran::where('outstanding','<>',0)->count();
		$c = DB::table('vGetOuststandingDaftar')->where('tipe_order','1')->whereNotIn('statuslayanan',['4','5'])->count();
        $d = ViewOutstdPendaftaran::where('tgl_order',Carbon::today()->toDateString())->count();
        $role = Auth::user()->role;
        return view('administrator.laporan.sales_admin.index',[
            'jum_daftar' => $a,
            'jum_hari_ini' => $b,
            'jum_out_daftar' => $c,
            'jum_bayar_daftar' => $d,
            'level' => $role,
            'title' => 'Laporan Sales Admin',
        ]);
    }
	public function getPelanggan(){
        $a = DB::table('vpelanggan')->get();
        //dd($a);
        $role = Auth::user()->role;
        return view('administrator.laporan.sales_admin.lap_pelanggan',[
            'pelanggan' => $a,

            'level' => $role,
            'title' => 'Laporan Sales Admin',
        ]);
    }

}
