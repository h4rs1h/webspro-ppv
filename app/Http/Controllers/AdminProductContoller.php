<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Facade\FlareClient\Http\Response;
use Illuminate\Validation\Rules\Unique;

class AdminProductContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    public function index()
    {
        $role = Auth::user()->role;
        return view('administrator.layanan.index',[
            'layanan' => Layanan::all(),//paginate(5)->withQueryString(),
            'level' => $role,
            'title' => 'Produk / Layanan',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = Auth::user()->role;
        return view('administrator.layanan.create',[
            'level' => $role,
            'title' => 'Produk / Layanan',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $validateData = $request->validate([
            'title' => 'required|max:100',
            'slug' => 'required|unique:layanan',
            'jenis_layanan' => 'required|max:50',
            'spead' => 'required|max:50',
            'benefit' => 'required|max:254',
            'harga' => 'required',
        ]);

        Layanan::create($validateData);

        return redirect('/admin/layanan')->with('success','Tambah data Layanan Berhasil');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Layanan  $layanan
     * @return \Illuminate\Http\Response
     */
    public function show(Layanan $layanan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Layanan  $layanan
     * @return \Illuminate\Http\Response
     */
    public function edit(Layanan $layanan)
    {
        //dd($layanan);
        $role = Auth::user()->role;
        return view('administrator.layanan.edit',[
            'layanan' => $layanan,
            'level' => $role,
            'title' => 'Edit Produk / Layanan',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Layanan  $layanan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Layanan $layanan)
    {
        $rules = ([
            'title' => 'required|max:100',
            'jenis_layanan' => 'required|max:50',
            'spead' => 'required|max:50',
            'benefit' => 'required|max:254',
            'harga' => 'required',
        ]);
        //dd($rules);
        if($request->slug != $layanan->slug){
            $rules['slug'] = 'required|unique:layanan';
        }
        //dd($request->validate($rules));
        // $validateData = $request->validate([
        //     'title' => 'required|max:100',
        //     'jenis_layanan' => 'required|max:50',
        //     'spead' => 'required|max:50',
        //     'benefit' => 'required|max:254',
        //     'harga' => 'required',
        // ]);
        $validateData = $request->validate($rules);
       // dd($rules,$validateData,$layanan->id);
        Layanan::where('id',$layanan->id)
                ->update($validateData);

        return redirect('/admin/layanan')->with('success','update data Layanan Berhasil');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Layanan  $layanan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Layanan $layanan)
    {
        //dd($request,$layanan);
         Layanan::destroy($layanan->id);

        return redirect('/admin/layanan')->with('success','Layanan Berhasil di hapus!');
    }

    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(Layanan::class, 'slug', $request->title);
        return response()->json(['slug' => $slug]);
    }
}
