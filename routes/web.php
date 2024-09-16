<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\AdminBayarController;
use App\Http\Controllers\AdminLaporanSalesAdmin;
use App\Http\Controllers\AdminProductContoller;
use App\Http\Controllers\OrderLayananController;
use App\Http\Controllers\AdminTrxOrderController;
use App\Http\Controllers\AdminPelangganController;
use App\Http\Controllers\AdminTrxAktivasiController;
use App\Http\Controllers\AdminTrxUpLayananController;
use App\Http\Controllers\AdminTrxDwnLayananController;
use App\Http\Controllers\AdminTrxCutiLayananController;
use App\Http\Controllers\AdminTrxStopLayananController;
use App\Http\Controllers\AdminTrxBerhentiLanggananController;
use App\Http\Controllers\AdminLaporanAkuntingController;
use App\Http\Controllers\PelangganViewInvoiceController;
use App\Http\Controllers\AdminProsesRequestMoraController;
use App\Http\Controllers\AdminTrxTagihanInvoiceController;
use App\Http\Controllers\AdminBlastWaController;
use App\Http\Controllers\AdminMonitoringController;
// use App\Http\Controllers\KirimEmailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('beranda.home');
});

// Route::resource('/layanan',[LayananController::class]);

Route::get('/layanan',[ProdukController::class,'layanan']);
Route::get('/layanan/{produk}',[ProdukController::class,'layanan']);

// Route::post('/layanan/{layanan}/{produk}',[ProdukController::class,'tambah_order']);

// rout cart order sementara

Route::get('cart', [CartController::class, 'cartList'])->name('cart.list');
Route::post('cart', [CartController::class, 'addToCart'])->name('cart.store');
Route::post('update-cart', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('remove', [CartController::class, 'removeCart'])->name('cart.remove');
Route::post('clear', [CartController::class, 'clearAllCart'])->name('cart.clear');
Route::post('confirm', [CartController::class, 'pesanLayanan'])->name('cart.confirm');
Route::post('confirm-bayar', [CartController::class, 'confirm_bayar'])->name('cart.confirm-bayar');
Route::post('prosesinvpdf', [CartController::class, 'prosesPdf'])->name('cart.prosesPdf');
Route::get('orders', [CartController::class, 'getListorder'])->name('orders.list');

Route::get('/kirim_email',[MailController::class,'index']);
Route::get('/send-email-pdf', [PDFController::class, 'index']);


Route::get('/send-message',[MessageController::class,'index']);
Route::get('/send-outbox',[MessageController::class,'getOutbox']);
Route::get('/send-inv',[MessageController::class,'getNotifInv']);
Route::get('/send-group',[MessageController::class,'getNotifSoftBand']);
Route::get('/send-group-billing',[MessageController::class,'getNotifgroupBilling']);

Route::get('/send-kirimpromo',[MessageController::class,'getSendImg']);
Route::post('/send-message',[MessageController::class,'sendMessage']);
// Route::resource('order/{kodeuser}', OrderLayananController::class)->only(['index', 'show']);
// Route::get('order/{kodeuser}', [OrderLayananController::class,'getIndex']);
Route::get('/admin/wa/setting',[AdminBlastWaController::class,'getSetting']);
Route::get('/admin/wa/sending',[AdminBlastWaController::class,'getSending']);
Route::get('/admin/wa/outbox',[AdminBlastWaController::class,'getOutbox']);
Route::get('/admin/wa/blast',[AdminBlastWaController::class,'getblast']);
Route::post('/admin/wa/kirim_blast',[AdminBlastWaController::class,'actKirimBlast']);
Route::get('/admin/wa/resend',[AdminBlastWaController::class,'getResend']);

Route::get('/userprofile/{name}',[PenggunaController::class,'profile']);//->name('pengguna.profile');

Auth::routes();

//verifikasi email
Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/admin', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Adminstrator
Route::get('/admin/produks/checkSlug',[AdminProductContoller::class,'checkSlug']);

Route::resource('/admin/layanan', AdminProductContoller::class)->except('show');

Route::get('/admin/pelanggan/aktifasi/{pelanggan}', [AdminPelangganController::class,'aktifasiUser']);
Route::get('/admin/pelanggan/get', [AdminPelangganController::class,'getPelanggan']);
Route::post('/admin/pelanggan/upnpwp', [AdminPelangganController::class,'upNpwpPelanggan']);
Route::post('/admin/pelanggan/upcid', [AdminPelangganController::class,'upCIDPelanggan']);
Route::resource('/admin/pelanggan', AdminPelangganController::class)->except('show');

Route::get('/admin/users/change-password', [AdminUserController::class, 'changePassword'])->name('changePassword');
Route::post('/admin/users/change-password', [AdminUserController::class, 'changePasswordSave'])->name('postChangePassword');

Route::resource('/admin/users', AdminUserController::class)->except('show');

Route::get('/admin/trx_order/check_nopelanggan',[AdminTrxOrderController::class,'check_nopelanggan']);
Route::get('/admin/trx_order/layanan',[AdminTrxOrderController::class,'getLayanan']);

Route::get('/admin/trx_order/download/{trx_order}', [AdminTrxOrderController::class,'download']);
Route::get('/admin/trx_order/do_non_ppn/{trx_order}', [AdminTrxOrderController::class,'download_nppn']);
Route::get('/admin/trx_order/inv_tagihan_daftar/{trx_order}', [AdminTrxOrderController::class,'getTagihanDaftar']);
Route::get('/admin/trx_order/inv_full_tagihan_daftar/{trx_order}', [AdminTrxOrderController::class,'getTagihanDaftarfull']);
Route::get('/admin/trx_order/get', [AdminTrxOrderController::class,'getFormLayanan']);
Route::get('/admin/trx_order/termin', [AdminTrxOrderController::class,'getTagihanTermin']);
Route::get('/admin/trx_order/kirim_wa/', [AdminTrxOrderController::class,'getKirimWaTagihanTermin']);
Route::Post('/admin/trx_order/getdata', [AdminTrxOrderController::class,'getDataOrder']);

//upgrade Layanan
Route::get('/admin/trx_order/upgrade', [AdminTrxUpLayananController::class,'index']);
Route::get('/admin/trx_order/upgrade/create', [AdminTrxUpLayananController::class,'create']);
Route::Post('/admin/trx_order/upgrade/getTotal', [AdminTrxUpLayananController::class,'getTotal']);
Route::Post('/admin/trx_order/upgrade/Simpan', [AdminTrxUpLayananController::class,'getSimpan']);
Route::get('/admin/trx_order/upgrade/getAksi', [AdminTrxUpLayananController::class,'getAksi']);

//Downgrade Layanan
Route::get('/admin/trx_order/downgrade', [AdminTrxDwnLayananController::class,'index']);
Route::get('/admin/trx_order/downgrade/create', [AdminTrxDwnLayananController::class,'create']);
Route::Post('/admin/trx_order/downgrade/getTotal', [AdminTrxDwnLayananController::class,'getTotal']);
Route::Post('/admin/trx_order/downgrade/Simpan', [AdminTrxDwnLayananController::class,'getSimpan']);
Route::get('/admin/trx_order/downgrade/getAksi', [AdminTrxDwnLayananController::class,'getAksi']);

//Cuti Layanan
Route::get('/admin/trx_order/cuti', [AdminTrxCutiLayananController::class,'index']);
Route::get('/admin/trx_order/cuti/create', [AdminTrxCutiLayananController::class,'create']);
Route::Post('/admin/trx_order/cuti/getTotal', [AdminTrxCutiLayananController::class,'getTotal']);
Route::Post('/admin/trx_order/cuti/Simpan', [AdminTrxCutiLayananController::class,'getSimpan']);
Route::get('/admin/trx_order/cuti/getAksi', [AdminTrxCutiLayananController::class,'getAksi']);

//Stop Layanan
Route::get('/admin/trx_order/stop', [AdminTrxStopLayananController::class,'index']);
Route::get('/admin/trx_order/stop/check_nopelanggan',[AdminTrxStopLayananController::class,'check_nopelanggan']);
Route::get('/admin/trx_order/stop/create', [AdminTrxStopLayananController::class,'create']);
Route::Post('/admin/trx_order/stop/getTotal', [AdminTrxStopLayananController::class,'getTotal']);
Route::Post('/admin/trx_order/stop/Simpan', [AdminTrxStopLayananController::class,'getSimpan']);
Route::get('/admin/trx_order/stop/getAksi', [AdminTrxStopLayananController::class,'getAksi']);

//Berhenti Langganan
Route::get('/admin/trx_order/stop_lgn', [AdminTrxBerhentiLanggananController::class,'index']);
Route::get('/admin/trx_order/stop_lgn/create', [AdminTrxBerhentiLanggananController::class,'create']);
Route::Post('/admin/trx_order/stop_lgn/getTotal', [AdminTrxBerhentiLanggananController::class,'getTotal']);
Route::Post('/admin/trx_order/stop_lgn/Simpan', [AdminTrxBerhentiLanggananController::class,'getSimpan']);
Route::get('/admin/trx_order/stop_lgn/getAksi', [AdminTrxBerhentiLanggananController::class,'getAksi']);

Route::POST('/admin/trx_order/simpan/', [AdminTrxOrderController::class,'store']);
Route::resource('/admin/trx_order', AdminTrxOrderController::class);//->except('edit','update','destroy');

Route::get('/admin/trx_bayar/kwitansi/{trx_bayar}', [AdminBayarController::class,'getKwitansi']);
Route::get('/admin/trx_bayar/ttbayar/{trx_bayar}', [AdminBayarController::class,'getTandaTerimaBayar']);
Route::get('/admin/trx_bayar/bayar', [AdminBayarController::class,'getBayar']);
Route::get('/admin/trx_bayar/checkno_bayar', [AdminBayarController::class,'getcheckno_bayar']);
Route::get('/admin/trx_bayar/getoutdaftar', [AdminBayarController::class,'getoutdaftar']);
Route::get('/admin/trx_bayar/getouttagihan', [AdminBayarController::class,'getouttagihan']);
Route::get('/admin/trx_bayar/getout', [AdminBayarController::class,'getOut']);
Route::POST('/admin/trx_bayar/simpan/', [AdminBayarController::class,'store']);
Route::POST('/admin/trx_bayar/bayar_tagihan', [AdminBayarController::class,'bayar_tagihan']);
Route::POST('/admin/trx_bayar/edit_bayar_tagihan', [AdminBayarController::class,'update_bayar_tagihan']);
Route::get('/admin/trx_bayar/approved', [AdminBayarController::class,'getApproved']);
Route::get('/admin/trx_bayar/notif', [AdminBayarController::class,'getKirimWAKwitansi']);
Route::POST('/admin/trx_bayar/getdata', [AdminBayarController::class,'getDataPembayaran']);
Route::get('/admin/trx_bayar/hapus_detail', [AdminBayarController::class,'getHapusDetail']);
Route::resource('/admin/trx_bayar', AdminBayarController::class)->except('edit','update');

Route::get('/admin/trx_aktivasi/nonaktivasi', [AdminTrxAktivasiController::class,'getorder']);
Route::get('/admin/trx_aktivasi/tambah', [AdminTrxAktivasiController::class,'show']);
Route::POST('/admin/trx_aktivasi/simpan', [AdminTrxAktivasiController::class,'store']);
Route::get('/admin/trx_aktivasi/edit', [AdminTrxAktivasiController::class,'edit']);
Route::get('/admin/trx_aktivasi/delete', [AdminTrxAktivasiController::class,'destroy']);
// Route::get('/admin/trx_aktivasi/tambah/{trx_aktivasi}/telepon', [AdminTrxAktivasiController::class,'show']);
Route::resource('/admin/trx_aktivasi', AdminTrxAktivasiController::class);//->except('edit','update','destroy');

Route::get('/admin/trx_nonaktif', [AdminProsesRequestMoraController::class,'index']);
Route::get('/admin/trx_nonaktif/getdata', [AdminProsesRequestMoraController::class,'showdata']);
Route::get('/viewnonaktif', [AdminProsesRequestMoraController::class,'shownonaktif']);
Route::POST('/admin/trx_nonaktif/getdatanonaktif', [AdminProsesRequestMoraController::class,'GetRequestMora']);
Route::POST('/admin/trx_nonaktif/getsimpanupt', [AdminProsesRequestMoraController::class,'simpanUpt']);
Route::POST('/admin/trx_nonaktif/getsimpanrequest', [AdminProsesRequestMoraController::class,'simpanRequest']);
Route::resource('/admin/trx_nonaktif', AdminProsesRequestMoraController::class);//->except('edit','update','destroy');

Route::get('/admin/trx_invoice/get', [AdminTrxTagihanInvoiceController::class,'getTagihan']);
Route::POST('/admin/trx_invoice/getdata', [AdminTrxTagihanInvoiceController::class,'getDataTagihan']);
Route::POST('/admin/trx_invoice/cek_expdate', [AdminTrxTagihanInvoiceController::class,'getexpdate']);
Route::POST('/admin/trx_invoice/upd_expdate', [AdminTrxTagihanInvoiceController::class,'getupdexpdate']);
Route::POST('/admin/trx_invoice/upd_invbatal', [AdminTrxTagihanInvoiceController::class,'getupdInvBatal']);
Route::POST('/admin/trx_invoice/upd_expdateinv', [AdminTrxTagihanInvoiceController::class,'getupdExpDateInv']);
Route::get('/admin/trx_invoice/inv', [AdminTrxTagihanInvoiceController::class,'getInvoicePdf']);
Route::get('/admin/trx_invoice/kirim_wa/', [AdminTrxTagihanInvoiceController::class,'getKirimWa']);
Route::resource('/admin/trx_invoice', AdminTrxTagihanInvoiceController::class);//->except('edit','update','destroy');

// Pelanggan
// Route::resource('/pelanggan/{pelanggan}', PelangganController::class)->except('index','create','store','edit','update','destroy');
Route::get('/pelanggan/myprofile/{pelanggan}', [PelangganController::class,'show']);
Route::get('/pelanggan/layanan/{pelanggan}', [PelangganController::class,'getLayanan']);
Route::get('/pelanggan/billing/{pelanggan}', [PelangganController::class,'getInvoice']);


Route::get('/viewinvoice', [PelangganViewInvoiceController::class,'index']);
Route::get('/data/{data_kwitansi}', [PelangganViewInvoiceController::class,'getViewKwitansi']);
Route::get('/viewinvoice/download', [PelangganViewInvoiceController::class,'getInvoicePdf']);
Route::post('/viewinvoice/tambah', [PelangganViewInvoiceController::class,'store_bukti_bayar']);
Route::post('/viewinvoice/tambah-daftar', [PelangganViewInvoiceController::class,'store_bukti_bayar_daftar']);
Route::get('/viewinv', [PelangganViewInvoiceController::class,'inv']);
Route::get('/viewinvoice/midtrans', [PelangganViewInvoiceController::class,'confim_bayar']);
Route::post('/viewinvoice/bayar-midtrans', [PelangganViewInvoiceController::class,'store_midtras_bayar']);

//Laporan
Route::get('/admin/sales-admin', [AdminLaporanSalesAdmin::class,'index']);
Route::get('/admin/sales-admin/pelanggan', [AdminLaporanSalesAdmin::class,'getPelanggan']);

//Laporan Akunting
Route::get('/admin/akunting', [AdminLaporanAkuntingController::class,'index']);
Route::get('/admin/akunting/rpt', [AdminLaporanAkuntingController::class,'getPelanggan']);
Route::post('/admin/akunting/pelanggan/getdata', [AdminLaporanAkuntingController::class,'getPelangganData']);

//Laporan monitoring
Route::get('/admin/monitoring', [AdminMonitoringController::class,'index']);
Route::get('/admin/monitoring/getfilter', [AdminMonitoringController::class,'getformfilter']);
Route::post('/admin/monitoring/getdata', [AdminMonitoringController::class,'getData']);

