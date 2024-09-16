<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxSending extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'trx_sending';

}
