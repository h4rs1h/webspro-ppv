<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\AdminProsesOnSistemController;
use App\Http\Controllers\PelangganViewInvoiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('/donation',[DonationController::class,'store']);
//Route::post('/midtrans/notification',[DonationController::class,'notifications']);
Route::post('/midtrans/notifications',[PelangganViewInvoiceController::class,'notifications']);
Route::get('/bos/generateInv',[AdminProsesOnSistemController::class,'getInvoicePerDay']);
Route::get('/bos/getNotif',[AdminProsesOnSistemController::class,'getNotifTagihanOutstading']);
Route::get('/bos/getNotifPutus',[AdminProsesOnSistemController::class,'getNotifTagihanOutstadingPutus']);
Route::get('/bos/getNotifDaftar',[AdminProsesOnSistemController::class,'getNotifDaftarOutstading']);
Route::get('/bos/getNotifremin',[AdminProsesOnSistemController::class,'getNotifTagihanOutstadingReminder']);
Route::get('/bos/getNotifTermin',[AdminProsesOnSistemController::class,'getKirimNotifTerminWa']);

Route::get('/bos/send-ginv-mail',[AdminProsesOnSistemController::class,'getPinvKirimEmail']); 
Route::get('/bos/send-mail',[AdminProsesOnSistemController::class,'getKirimEmail']); 
