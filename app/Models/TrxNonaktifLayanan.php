<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrxNonaktifLayanan extends Model
{
    use HasFactory;
    protected $table = 'trx_nonaktif_layanan';

    protected $guarded = ['id'];


    public static function nonaktif_detail()
    {
        return $this->hasMany(TrxNonaktifLayananDetail::class);
    }
    public static  function nomerRequest()
    {
        $kode = "MPJ/PPV-MORA";
        $bulanRomawi = array("","I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
        $nbulan = DB::table('trx_nonaktif_layanan')
                        ->select(DB::raw('max(id) as noakhir'))
                        ->first();

        $noUrutAkhir = $nbulan->noakhir;

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
