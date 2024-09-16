<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use app\Mail\SendMail;

class KirimEmailController extends Controller
{
    public function kirim()
    {
        $email = 'hrsanto@gmail.com';
        $data = [
            'title' => 'selamat datang',
            'url' => 'https://harsihrianto.net'
        ];

        Mail::to($email)->send(new SendMail($data));
        return 'Berhasil kirim email';

    }
}
