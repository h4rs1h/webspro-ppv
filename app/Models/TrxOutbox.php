<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxOutbox extends Model
{
    use HasFactory;
    protected $table = 'trx_outbox';
	protected $guarded = ['id'];
}
