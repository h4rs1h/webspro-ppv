<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    public function layanan($data)
    {
        $role = Auth::user()->role;

        return view('produk.index',[
            'heading' => $data,
            'active' => 'layanan',
            'level' => $role,
            'produk' => Layanan::where('jenis_layanan',$data)->get(),
        ]);
    }
}
