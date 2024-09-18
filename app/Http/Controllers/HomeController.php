<?php

namespace App\Http\Controllers;

use App\Models\ViewAktivasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $role = Auth::user()->role;
        //dd($role);
        if ($role == '0') {
            //Tampilan untuk pelanggan
            return view('pelanggan.home', [
                'level' => $role,
                'active' => ''
            ]);
        }
        if ($role == '1') {
            //tampilan untuk teknisi
            return view('teknisi.home', [
                'level' => $role
            ]);
        }
        if ($role == '2') {
            //tampilan untuk Marketing
            return view('marketing.home', [
                'level' => $role
            ]);
        }
        if ($role == '3') {
            //tampilan untuk Admin/Kasir
            return view('kasir.home', [
                'level' => $role
            ]);
        }
        if ($role == '4') {
            //tampilan untuk Dashboard Administrator
            $tgl = Carbon::parse(now())->format('Y');
            //dd($tgl-1);		
            $a = DB::table('trx_order')->where('tipe_order', '1')->count();
            $b = ViewAktivasi::count();
            $e = DB::table('vtrx_aktivasi_dtl')->whereNotNull('tgl_aktivasi_free_stb')->count();
            $c = DB::table('trx_aktivasi')->where('jenis_aktivasi', '2')->count();
            $d = DB::table('trx_tagihan')->count();
            $f = DB::table('getremindertagihan')->first();


            $con = DB::table('vpelanggan')
                ->select(DB::raw('count(*) as jml,status_layanan, ket_status_layanan'))
                ->where('tipe_order', '1')
                ->groupBy('ket_status_layanan')
                ->get();
            $con2 = DB::table('vtagihanhdr')
                ->select(DB::raw('count(*) as jml,left(no_tagihan,6) as bulan'))

                //	->where('yearinv',['year(now())'])
                ->whereBetween('yearinv', [$tgl, $tgl])
                ->groupBy('bulan')
                ->get();
            //	dd($con2);
            //	$b = 0;$c = 0; $d=0;$e=0; //$f=0;
            //$con=0;$con2=0;
            return view('administrator.home', [
                'jum_daftar' => $a,
                'jum_router' => $b,
                'jum_stb_free' => $e,
                'jum_stb' => $c,
                'jum_tagihan' => $d,
                'level' => $role,
                'dtl_plg' => $con,
                'dtl_inv' => $con2,
                'dtl_rm' => $f,
                'active' => ''
            ]);
        }
    }
}
