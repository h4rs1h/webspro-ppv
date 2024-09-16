<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxTagihan extends Model
{
    use HasFactory;
    protected $table = 'trx_tagihan';

    protected $guarded = ['id'];
	
	public function setStatusPending()
    {

        $this->attributes['metode_pembayaran'] = '4';
        $this->attributes['status_tagihan'] = '1';
        $this->attributes['status_bayar_midtrans'] = 'Pending';
        self::save();
    }
    public function setStatusSuccess()
    {
        $this->attributes['metode_pembayaran'] = '4';
        $this->attributes['status_tagihan'] = '2';
        $this->attributes['status_bayar_midtrans'] = 'Success';
		
        self::save();
    }
    public function setStatusFailed()
    {
        $this->attributes['metode_pembayaran'] = '4';
        $this->attributes['status_tagihan'] = '5';
        $this->attributes['status_bayar_midtrans'] = 'Failed';
        self::save();
    }
    public function setStatusExpired()
    {
        $this->attributes['metode_pembayaran'] = '4';
        $this->attributes['status_tagihan'] = '3';
        $this->attributes['status_bayar_midtrans'] = 'Expired';
        self::save();
    }
}
