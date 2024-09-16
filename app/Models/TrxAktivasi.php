<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxAktivasi extends Model
{
    use HasFactory;

    protected $table = 'trx_aktivasi';
    protected $guarded = ['id'];
}
