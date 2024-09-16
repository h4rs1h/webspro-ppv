<?php

namespace App\Models;

use App\Models\User;
use App\Models\Tower;
use App\Models\Lantai;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';

    protected $guarded = ['id'];



    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
