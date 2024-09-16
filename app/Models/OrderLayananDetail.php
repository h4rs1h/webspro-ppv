<?php

namespace App\Models;

use App\Models\Layanan;
use App\Models\OrderLayanan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderLayananDetail extends Model
{
    use HasFactory;

    protected $table = 'order_layanan_detail';

    protected $guarded = ['id'];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderLayanan::class);
    }

    public function tambah_detail($id_order,$id_layanan,$quantity,$price){

        $subtotal = $quantity*$price;
      //  dd( $subtotal);
        OrderLayananDetail::create ([
            'order_layanan_id' => $id_order,
            'layanan_id' => $id_layanan,
             'periode_langganan' => $quantity,
             'harga_layanan' => $price,
             'subtotal' =>  $subtotal,
        ]);

    }
}
