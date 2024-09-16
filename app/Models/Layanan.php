<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Layanan extends Model
{
    use HasFactory;
    use Sluggable;

    protected $table = 'layanan';

    //protected $with =['orderdetail'];

    protected $guarded = ['id'];

    public function orderdetail()
    {
        return $this->belongsTo(OrderLayananDetail::class,'layanan_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
