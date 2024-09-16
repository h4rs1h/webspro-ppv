<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminMonitoringController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    public function index(){

        $role = Auth::user()->role;
        $id_user = Auth::user()->id;

        $list = [
            ['id' =>'0','link' =>'/admin/monitoring/getfilter?id=no_inv','title' => 'Generate Nomer Invoice '],
            ['id' =>'1','link' =>'/admin/monitoring/getfilter?id=amt_inv','title' => 'Report Monitoring Invoice '],
            ['id' =>'2','link' =>'/admin/monitoring/getfilter?id=aging_inv','title' => 'Report Aging Invoice '],
        ];

        $list2 = [
            ['id' =>'0','link' =>'/admin/monitoring/getfilter?id=cek_unit','title' => 'Ready Generate Invoice '],
            ['id' =>'1','link' =>'/admin/monitoring/getfilter?id=cek_unit_sukses','title' => 'Chack Unit Generate Invoice '],
        ];

        return view('administrator.laporan.monitoring.index',[

            'level' => $role,
            'title' => 'Monitoring Proses Sistem',
            'data' => $list,
            'id_user' => $id_user,
            'data2' => $list2,
        ]);
    }
    public function getformfilter(Request $request){
        $id=$request->id;
        $role = Auth::user()->role;
        $hari=31;
        $year=2025;
        //  dd($hari,$year);
  $statusAging = [
            ['id' =>'0','name' =>'All'],
            ['id' =>'1','name' =>'Aging'],
            ];
        if($id=='no_inv'){
            $title = 'Filter Form Monitoring Nomer Invoice';
        }elseif($id=='amt_inv'){
            $title = 'Filter Form Monitoring Amount Invoice';
        }elseif($id=='aging_inv'){
            $title = 'Filter Form Report Aging Invoice';
        }elseif($id=='cek_unit'){
            $title = 'Filter Ready Generate Invoice ';
        }elseif($id=='cek_unit_sukses'){
            $title = 'Filter Chack Unit Generate Invoice';
        }
        return view('administrator.laporan.monitoring.filterform',[
            'level' => $role,
            'rpt' => $id,
            'title' => $title,
            'opt_hari' =>  $hari,
            'opt_tahun' => $year,
			'opt_aging' => $statusAging,
        ]);
    }

    public function getData(Request $request){
        $role = Auth::user()->role;
		if($request->rpt=='aging_inv'){
            $hdrTrxBayar = $request->validate([
                'tgl_aging' => 'required|date',
                'opt_aging' => 'required',
            ]);
            $id = $request->rpt;
            $tgl = $request->tgl_aging;
            $aging = $request->opt_aging;
        }else{
        $hdrTrxBayar = $request->validate([
            'awal_hari' => 'required|numeric|lte:akhir_hari',
            'akhir_hari' => 'required|numeric|gte:awal_hari',
            'tahun' => 'required',
        ]);
        $id = $request->rpt;
        $awal = $request->awal_hari;
        $akhir = $request->akhir_hari;
        $tahun = $request->tahun;
		}
		
        if($id=='no_inv'){
            $data = DB::select("call getRptGenInvoice2(".$awal.",".$akhir.",".$tahun.");");
            $b = 'lap_monitoring_no_inv';
            $title = 'Monitoring Generate Invoice ';
        }elseif($id=='amt_inv'){
            $data = DB::select("call getRptGenInvoice4(".$awal.",".$akhir.",".$tahun.");");
            $b = 'lap_monitoring_amt_inv';
            $title = 'Monitoring Amount Invoice ';
        }elseif($id=='cek_unit'){
            $data = DB::select("call GetUnitReadytoInv();");
            $b = 'lap_ready_to_inv';
            $title = 'Monitoring Ready Generate Invoice';
        }elseif($id=='cek_unit_sukses'){
            $data = DB::select("call GetChackUnitInv();");
            $b = 'lap_chack_generate_inv';
            $title = 'Monitoring Chack Unit Generate Invoice ';
        }elseif($id=='aging_inv'){
            $data = DB::select("call getRptGenAging('".$tgl."','".$aging."');");
            $b = 'lap_monitoring_aging_inv';
            $title = 'Report Aging Invoice ';
        }
            // dd($data);

        if($id=='aging_inv'){
            return view('administrator.laporan.monitoring.'.$b,[
                'level' => $role,
                'title' => $title, // 'Laporan Tagihan Pendaftaran Pelanggan',
                'sub_title' => 'Tanggal: '.$tgl,
                'pelanggan' => $data,
            ]);
        }else{
            return view('administrator.laporan.monitoring.'.$b,[
                'level' => $role,
                'title' => $title, // 'Laporan Tagihan Pendaftaran Pelanggan',
                'sub_title' => 'Dari Tanggal: '.$awal.' S/d Tanggal: '.$akhir.' Tahun: '.$tahun,
                'pelanggan' => $data,
            ]);

        }
    }
}
