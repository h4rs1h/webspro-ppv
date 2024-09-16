<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;

class TrxOrder extends Model
{
    use HasFactory;
    protected $table = 'trx_order';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function order_detail()
    {
        return $this->hasMany(TrxOrderDetail::class);
    }
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
    public static function nomerformulir()
    {
        $kode = "MPJ/PPV";
        $bulanRomawi = array("","I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
        $nbulan = DB::table('trx_order')
                        ->select(DB::raw('max(no_order) as noakhir'))
                        ->whereMonth('tgl_order',now())
                        ->first();
       // dd($nbulan,Carbon::now());
        $noUrutAkhir = $nbulan->noakhir;
        //$noUrutAkhir = TrxOrder::max('no_order');
       // dd($noUrutAkhir);
        $no = 1;
        if($noUrutAkhir){
            $hasil = sprintf("%04s",abs($noUrutAkhir+1)).'/'.$kode.'/'.$bulanRomawi[date('n')].'/'.date('Y');

        }
        else{
            $hasil = sprintf("%04s",abs($no)).'/'.$kode.'/'.$bulanRomawi[date('n')].'/'.date('Y');
        }

        return $hasil;
    }
	public static function nomerUpgrade()
    {
        $kode = "UPG/MPJ/PPV";
        $bulanRomawi = array("","I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
        $nbulan = DB::table('trx_order')
                        ->select(DB::raw('max(no_order) as noakhir'))
                        //->whereMonth('tgl_order',now())
                        ->where('tipe_order','2')
                        ->first();
       // dd($nbulan,Carbon::now());
        $noUrutAkhir = $nbulan->noakhir;
        //$noUrutAkhir = TrxOrder::max('no_order');
       // dd($noUrutAkhir);
        $no = 1;
        if($noUrutAkhir){
            $hasil = sprintf("%04s",abs($noUrutAkhir+1)).'/'.$kode.'/'.$bulanRomawi[date('n')].'/'.date('Y');

        }
        else{
            $hasil = sprintf("%04s",abs($no)).'/'.$kode.'/'.$bulanRomawi[date('n')].'/'.date('Y');
        }

        return $hasil;
    }
	public static function getNomer($tipe_order)
    {
        //$kode = "DNG/MPJ/PPV";
        $bulanRomawi = array("","I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
        $nbulan = DB::table('trx_order')
                        ->select(DB::raw('max(no_order) as noakhir'))
                        ->where('tipe_order',$tipe_order)
                        ->first();

        $noUrutAkhir = $nbulan->noakhir;
        if($tipe_order=='1'){
            $kode = "MPJ/PPV";
        }
        if($tipe_order=='2'){
            $kode = "UPG/MPJ/PPV";
        }
        if($tipe_order=='3'){
            $kode = "DNG/MPJ/PPV";
        }
        if($tipe_order=='4'){
            $kode = "CT/MPJ/PPV";
        }
        if($tipe_order=='5'){
            $kode = "STP/MPJ/PPV";
        }
		if($tipe_order=='6'){
            $kode = "STP-LGN/MPJ/PPV";
        }

        $no = 1;
        if($noUrutAkhir){
            $hasil = sprintf("%04s",abs($noUrutAkhir+1)).'/'.$kode.'/'.$bulanRomawi[date('n')].'/'.date('Y');

        }
        else{
            $hasil = sprintf("%04s",abs($no)).'/'.$kode.'/'.$bulanRomawi[date('n')].'/'.date('Y');
        }

        return $hasil;
    }
}
