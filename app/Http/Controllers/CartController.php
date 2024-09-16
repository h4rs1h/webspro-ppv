<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use App\Models\Layanan;
use Darryldecode\Cart\Cart;
use App\Models\OrderLayanan;
use Illuminate\Http\Request;
use App\Models\OrderLayananDetail;
use Illuminate\Support\Facades\Auth;
use PDF;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is3ds');
    }
    public function cartList()
    {
        $role = Auth::user()->role;

        $cartItems = \Cart::getContent();
        // dd($cartItems);

        $jum=0;
        foreach ($cartItems as $item){
            if ($item->attributes->layanan==='Internet'){
                $jum = $jum+1;
            }
        }
        if($jum>1){
            session()->flash('danger', 'Cek kembali layanan internet yang anda pilih');
        }
        // dd($jum);

        return view('produk.cart',[
            'active' => 'layanan',
            'level' => $role,
            'cartItems' => \Cart::getContent(),
            'isEmpty' => \Cart::isEmpty(),
        ]);
    }

    public function addToCart(Request $request)
    {
        //  dd($request);
        \Cart::add([
            'id' => $request->id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'attributes' => array(
                'layanan' => $request->layanan,
            )
        ]);
        session()->flash('success', 'Product is Added to Cart Successfully !');

        return redirect()->route('cart.list');
    }

    public function updateCart(Request $request)
    {
        \Cart::update(
            $request->id,
            [
                'quantity' => [
                    'relative' => false,
                    'value' => $request->quantity
                ],
            ]
        );

        session()->flash('success', 'Item Cart is Updated Successfully !');

        return redirect()->route('cart.list');
    }

    public function removeCart(Request $request)
    {
        \Cart::remove($request->id);
        session()->flash('success', 'Item Cart Remove Successfully !');

        return redirect()->route('cart.list');
    }

    public function clearAllCart()
    {
        \Cart::clear();

        session()->flash('success', 'All Item Cart Clear Successfully !');

        return redirect()->route('cart.list');
    }
    public function pesanLayanan()
    {
        $role = Auth::user()->role;
        // tentukan nilai deposit
        $deposit = 0;
        $cartItems = \Cart::getContent();

        foreach ($cartItems as $item){
            if ($item->attributes->layanan==='Internet'){
                $deposit = $item->price;
            }
        }
        //tentukan ppn dan subtotal
        $subtotal = \Cart::getTotal();
        $ppn = $subtotal * 11/100;

        // tentukan biaya pemasangan
        $biaya = Layanan::where('id','10')->first();

        $biaya_pasang = $biaya->harga;
        $gdTotal = $subtotal+$ppn+$biaya_pasang+$deposit;
       // dd($subtotal,$ppn,$deposit,$biaya_pasang,$gdTotal);
        //Nomer pesanan
        $nomer = OrderLayanan::nomerpesan();
        $kodeunik = mt_rand(0,999);
        $btransfer = 6000;
        $cekPesan = OrderLayanan::Where('user_id',Auth::user()->id)->count();
        $cekPesan2 = OrderLayanan::Where('user_id',Auth::user()->id)
                                ->where('payment_status','=','4')
                                ->count();

        //dd($cekPesan);
        if($cekPesan=='0'){
            return view('produk.pesanLayanan',[
                'active' => 'layanan',
                'level' => $role,
                'cartItems' => \Cart::getContent(),
                'subtotal' => $subtotal,
                'ppn' => $ppn,
                'deposit' => $deposit,
                'gdTotal' => $gdTotal,
                'noPesan' => $nomer,
                'biayapasang' => $biaya_pasang,
                'kodeunik' => $kodeunik,
                'biayatransfer' => $btransfer,
                'ttlTransfer' => $gdTotal+$btransfer,
                'ttlmantransfer' => $gdTotal+$kodeunik,
            ]);

        }else if($cekPesan2=='1')
        {
            return view('produk.pesanLayanan',[
                'active' => 'layanan',
                'level' => $role,
                'cartItems' => \Cart::getContent(),
                'subtotal' => $subtotal,
                'ppn' => $ppn,
                'deposit' => $deposit,
                'gdTotal' => $gdTotal,
                'noPesan' => $nomer,
                'biayapasang' => $biaya_pasang,
                'kodeunik' => $kodeunik,
                'biayatransfer' => $btransfer,
                'ttlTransfer' => $gdTotal+$btransfer,
                'ttlmantransfer' => $gdTotal+$kodeunik,
            ]);
        }else
        {
            \Cart::clear();

            session()->flash('success', 'Pesanan Anda masih dalam proses Aktifasi !');

            return redirect()->route('orders.list');

        }
    }
    public function confirm_bayar(Request $request)
    {
        $role = Auth::user()->role;

         // tentukan nilai deposit
        $deposit = 0;
        $cartItems = \Cart::getContent();

        foreach ($cartItems as $item){
            if ($item->attributes->layanan==='Internet'){
                $deposit = $item->price;
            }
        }
         //tentukan ppn dan subtotal
        $subtotal = \Cart::getTotal();
         $ppn = $subtotal * 11/100;

         // tentukan biaya pemasangan
        $biaya = Layanan::where('id','10')->first();

        $biaya_pasang = $biaya->harga;
        $gdTotal = $subtotal+$ppn+$biaya_pasang+$deposit;

         //Nomer pesanan
        $nomer = $request->nomerpesan;
        $kodeunik = $request->kodeunik;
        $jenis_pembayaran = $request->jenis_pembayaran;
        $totalTransfer = $request->total_tagihan_transfer;
        // Simpan di OrderLayanan
       // dd($subtotal,$ppn,$deposit,$biaya_pasang,$gdTotal,$nomer,$kodeunik,$jenis_pembayaran,$totalTransfer);

        $cek = \Cart::isEmpty();

        if(!$cek) {
            $id_order = OrderLayanan::tambah_header($nomer,$deposit,$biaya_pasang,$ppn,$gdTotal,$kodeunik,$jenis_pembayaran,$totalTransfer);

            foreach ($cartItems as $item){
               // dd($id_order,$item->id,$item->quantity,$item->price);
                OrderLayananDetail::tambah_detail($id_order,$item->id,$item->quantity,$item->price);
            }

        }

        \Cart::clear();

        //tampilkan halaman bayar dan kirim notifikasi email dan pdf
        $nomerfromulir = OrderLayanan::getNomerPesan($nomer);
        $head_order_layanan = OrderLayanan::where('no_order',$nomer)->first();
        $detail_order_layanan = OrderLayananDetail::where('order_layanan_id',$head_order_layanan->id)->get();
        $sub = OrderLayananDetail::where('order_layanan_id',$head_order_layanan->id)->sum('subtotal');

        $btransfer = 6000;
        // midtrans
        $snapToken = $head_order_layanan->snap_token;
        if (empty($snapToken)) {
            // Jika snap token masih NULL, buat token snap dan simpan ke database

            foreach ($detail_order_layanan as $item){
               $res[] = [
                    'id' => $item->layanan_id,
                    'price' => $item->subtotal,
                    'quantity' => 1,
                    'name' => $item->layanan->title,
               ];
            }
            $res[] = [
                'id' => 10,
                'price' => $head_order_layanan->deposit+$head_order_layanan->biaya_pemasangan+$head_order_layanan->ppn+$btransfer,
                'quantity' => 1,
                'name' => 'Biaya Pemasangan, Deposit, PPn, Biaya Transfer',
            ];
           // dd($res);
            $params = [
                'transaction_details' => [
                    'order_id' => $head_order_layanan->nomor_order,
                    'gross_amount' => $head_order_layanan->total_tagihan_transfer,
                ],
                'item_details' => $res,
                'customer_details' => [
                    'first_name' => $head_order_layanan->user->name,
                    'email' => $head_order_layanan->user->email ,
                    'phone' => $head_order_layanan->user->no_hp,
                ]
            ];
            //dd($params);
            $snapToken = Snap::getSnapToken($params);

            // $midtrans = new CreateSnapTokenService($order);
            // $snapToken = $midtrans->getSnapToken();

            $head_order_layanan->snap_token = $snapToken;
            $head_order_layanan->save();
        }
        // Kirim Email;

        return view('produk.bayarLayanan',[
            'active' => 'layanan',
            'level' => $role,
            'head_order' => $head_order_layanan,
            'detail_order' => $detail_order_layanan,
            'nomer_formulir' =>$nomerfromulir,
            'subtotal' => $sub,
            'ppn' => $ppn,
            'deposit' => $deposit,
            'gdTotal' => $gdTotal,
            'noPesan' => $nomer,
            'biayapasang' => $biaya_pasang,
            'kodeunik' => $kodeunik,
            'biayatransfer' => $btransfer,
            'ttlTransfer' => $gdTotal+$kodeunik+$btransfer,
            'ttlmantransfer' => $gdTotal+$kodeunik,
            'snapToken' => $snapToken
        ]);

    }

    public function prosesPdf(Request $request)
    {

            //Nomer pesanan
            $nomer_order = $request->nomerpesan;
            $head_order = OrderLayanan::where('nomor_order',$nomer_order)->first();
            $body_order = OrderLayananDetail::where('order_layanan_id',$head_order->id)->get();

           // dd($head_order);
            // $data[] = [
            //     'head_order' => $head_order,
            //     'body_order' => $body_order,];
            $pdf = PDF::loadView('produk.pdfpesanan',[
                'head_order' => $head_order,
                'body_order' => $body_order]);
           return  $pdf->download('Pesanan.pdf','landscape');
    }

    public function getListorder ()
    {
        $id =  Auth::user()->id;

        $role = Auth::user()->role;

        $head_order_layanan = OrderLayanan::where('user_id',$id)->first();

        $body_order = OrderLayananDetail::where('layanan_id',$head_order_layanan->id)->get();

        // dd($id,$role,$head_order_layanan,$body_order );

        return view('OrderLayanan.index',[
            'active' => 'order',
            'level' => $role,
            'head_order' => $head_order_layanan,
            'body_order' =>  $body_order,
            'biayatransfer' => 6000
        ]);

    }
}
