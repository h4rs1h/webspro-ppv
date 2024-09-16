<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTower extends Model
{
    use HasFactory;
    protected $table = 'subtower';

    protected $guarded = ['id'];
}
