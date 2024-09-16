<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrxBayar extends Model
{
    use HasFactory;
    protected $table = 'trx_bayar';

    protected $guarded = ['id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function bayar_detail()
    {
        return $this->hasMany(TrxBayarDetail::class);
    }
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
    public function nomerformulir()
    {
        $kode = "MPJ/PPV";
        $bulanRomawi = array("","I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
        $nbulan = DB::table('trx_bayar')
                        ->select(DB::raw('max(no_bayar) as noakhir'))
                        ->whereMonth('tgl_bayar',now())
                        ->first();
       // dd($nbulan,Carbon::now());
        $noUrutAkhir = $nbulan->noakhir;
        //$noUrutAkhir = TrxOrder::max('no_order');
       // dd($noUrutAkhir);

       // $noUrutAkhir = TrxBayar::max('no_order')->where(month(tgl_bayar),month(now()));
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
