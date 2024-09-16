<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Tower;
use App\Models\Lantai;
use App\Models\Pelanggan;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
       // $davaValidator()
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'no_unit' => ['required', 'string', 'min:6'],
            'is_pemilik' => ['required'],
            'no_identitas' => ['required','string', 'min:10', 'max:30'],
            'alamat' => ['required','string','min:10', 'max:255'],
            'no_telpon' => ['required','min:10','max:30'],
            'no_hp' => ['required','min:10','max:30'],

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'no_unit' => $data['no_unit'],
            'is_pemilik' => $data['is_pemilik'],
            'no_identitas' => $data['no_identitas'],
            'alamat' => $data['alamat'],
            'no_telpon' => $data['no_telpon'],
            'no_hp' => $data['no_hp'],
        ]);
    }
}
