<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxBayarDetail extends Model
{
    use HasFactory;
    protected $table = 'trx_bayar_detail';

    protected $guarded = ['id'];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
}
