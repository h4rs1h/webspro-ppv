<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function setStatusPending()
    {
        $this->attributes['payment_status'] = '1';
        self::save();
    }
    public function setStatusSuccess()
    {
        $this->attributes['payment_status'] = '2';
        self::save();
    }
    public function setStatusFailed()
    {
        $this->attributes['payment_status'] = '4';
        self::save();
    }
    public function setStatusExpired()
    {
        $this->attributes['payment_status'] = '3';
        self::save();
    }
}
