<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxOrderDetail extends Model
{
    use HasFactory;

    protected $table = 'trx_order_detail';

    protected $guarded = ['id'];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    // public function orderDetail()
    // {
    //     return $this->hasMany(TrxOrder::class);
    // }
}
