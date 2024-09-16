<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenggunaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function profile($name)
    {
        $role = Auth::user()->role;
        $user = User::where('name',$name)->first();
       // dd($user);
        return view('pengguna.index',[
            'active' => 'profile',
            'level' => $role,
            'pengguna' => $user,

        ]);

    }
}
