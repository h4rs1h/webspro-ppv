<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use app\Mail\MyTestMail;

class MailController extends Controller
{
    public function index()
    {
        $details = [
            'title' => 'Mail From mediaprimajaringan.com',
            'body' => 'ini adalah testing email dari laravel'
        ];

        \Mail::to('hrsanto@gmail.com')->send(new \App\Mail\MyTestMail($details));

        dd("email sudah terkirim");
    }
}
