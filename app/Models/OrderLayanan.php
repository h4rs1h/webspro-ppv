<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderLayanan extends Model
{
    use HasFactory;

    protected $table = 'order_layanan';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order_detail()
    {
        return $this->hasMany(OrderLayananDetail::class);
    }

    public function nomerpesan()
    {
        $kode = "MPJ/PPV";
        $bulanRomawi = array("","I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
        $noUrutAkhir = OrderLayanan::max('no_order');
        $no = 1;
        if($noUrutAkhir){
            $hasil = sprintf("%04s",abs($noUrutAkhir+1)).'/'.$kode.'/'.$bulanRomawi[date('n')].'/'.date('Y');

        }
        else{
            $hasil = sprintf("%04s",abs($no)).'/'.$kode.'/'.$bulanRomawi[date('n')].'/'.date('Y');
        }

        return $hasil;
    }
    public function getNomerPesan($idPesan)
    {
        $kode = "MPJ/PPV";
        $bulanRomawi = array("","I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");

        $dataOrder = OrderLayanan::where('id',$idPesan)->first();

        $noUrutAkhir = $idPesan;
        $tgl= $dataOrder->tgl_order;

        $bulan = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$tgl)->format('n');
        $tahun = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$tgl)->format('Y');

        $hasil = sprintf("%04s",abs($noUrutAkhir)).'/'.$kode.'/'.$bulanRomawi[$bulan].'/'.$tahun;
       // dd($tgl,$bulan,$tahun,$hasil);

        OrderLayanan::where('id',$idPesan)
            ->update(['nomor_order' => $hasil]);
        return $hasil;

    }
    public function tambah_header($no_order,$deposit,$biaya_pasang,$ppn,$gdTotal,$kodeunik,$jenis_pembayaran,$totalTransfer)
    {
        $id = OrderLayanan::create([
            'no_order' => $no_order,

            'tgl_order' => date('Y-m-d H:i:s'),
            'user_id' => Auth::user()->id,
            'deposit' => $deposit,
            'biaya_pemasangan' => $biaya_pasang,
            'ppn' => $ppn,
            'total_tagihan' => $gdTotal,
            'status_bayar' => 'unpaid',
            'jenis_pembayaran' => $jenis_pembayaran,
            'kodeunik' => $kodeunik,
            'total_tagihan_transfer' => $totalTransfer,
        ]);

        return $id->id;
    }


    public function setStatusPending()
    {
        $this->attributes['payment_status'] = '1';
        $this->attributes['status_bayar'] = 'Pending';
        self::save();
    }
    public function setStatusSuccess()
    {
        $this->attributes['payment_status'] = '2';
        $this->attributes['status_bayar'] = 'Success';
        self::save();
    }
    public function setStatusFailed()
    {
        $this->attributes['payment_status'] = '4';
        $this->attributes['status_bayar'] = 'Failed';
        self::save();
    }
    public function setStatusExpired()
    {
        $this->attributes['payment_status'] = '3';
        $this->attributes['status_bayar'] = 'Expired';
        self::save();
    }
}
