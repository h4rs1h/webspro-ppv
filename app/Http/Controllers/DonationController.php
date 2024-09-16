<?php

namespace App\Http\Controllers;

// use App\Donation;
// use App\Models\Donation;

use Midtrans\Snap;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is3ds');
    }
    public function index()
    {
        $donations = Donation::orderBy('id','desc')->paginate(8);
        return view('donation', compact('donations'));
    }

    public function store(Request $request)
    {
        $qty = 1;

        \DB::transaction(function () use ($request) {
            $donation = Donation::create([
                'order_id' => \Str::uuid(),
                'donor_name' => $request->donor_name,
                'donor_email' => $request->donor_email,
                'donation_type' => $request->donation_type,
                'amount' => floatval($request->amount),
                'note' => $request->note
            ]);

            $param = [
                'transaction_details' => [
                    'order_id' => 'SANDBOX-'.uniqid(),
                    'gross_amount' => $donation->amount,
                ],
                'customer_details' => [
                    'first_name' => $donation->donor_name,
                    'email' => $donation->donor_email,
                ],
                'item_details' => [
                    'id' => $donation->donation_type,
                    'price' => $donation->amount,
                    'quantity' => $request->donor_qty,
                    'name' => ucwords(str_replace('_',' ', $donation->donation_type))
                ],
            ];

          //  dd($payload);
            $snapToken = Snap::getSnapToken($param);
            $donation->snap_token = $snapToken;
            $donation->save();

            $this->response['snap_token'] = $snapToken;

        });

        return response()->json($this->response);
    }
}
