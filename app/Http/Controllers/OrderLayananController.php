<?php

namespace App\Http\Controllers;

use App\Models\OrderLayanan;
use App\Http\Requests\StoreOrderLayananRequest;
use App\Http\Requests\UpdateOrderLayananRequest;
use App\Models\OrderLayananDetail;
use Illuminate\Support\Facades\Auth;

class OrderLayananController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = Auth::user()->role;

        $head_order_layanan = OrderLayanan::where('user_id',Auth::user()->id)->get();
        $body_order = OrderLayananDetail::where('layanan_id',$head_order_layanan->id)->get();

        return view('OrderLayanan.index',[
            'active' => 'order',
            'level' => $role,
            'head_order' => $head_order_layanan,
            'body_order' =>  $body_order,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrderLayananRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderLayananRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrderLayanan  $orderLayanan
     * @return \Illuminate\Http\Response
     */
    public function show(OrderLayanan $orderLayanan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrderLayanan  $orderLayanan
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderLayanan $orderLayanan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderLayananRequest  $request
     * @param  \App\Models\OrderLayanan  $orderLayanan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderLayananRequest $request, OrderLayanan $orderLayanan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrderLayanan  $orderLayanan
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderLayanan $orderLayanan)
    {
        //
    }
}
