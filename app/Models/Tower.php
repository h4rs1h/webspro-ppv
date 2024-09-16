<?php

namespace App\Models;

use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tower extends Model
{
    use HasFactory;

    protected $table = 'tower';

    protected $guarded = ['id'];

    public function pelanggan ()
    {
        return $this->hasMany(Pelanggan::class);
    }
}
