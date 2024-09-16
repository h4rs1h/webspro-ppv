<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tower;
use App\Models\Lantai;
use App\Models\SubTower;
use App\Models\Pelanggan;
use App\Models\TrxOrder;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Egulias\EmailValidator\EmailValidator;

use Symfony\Contracts\Service\Attribute\Required;
use Egulias\EmailValidator\Validation\RFCValidation;

class AdminPelangganController extends Controller
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
		$cust = DB::table('vpelanggan_layanan')->get();

        $role = Auth::user()->role;
        return view('administrator.pelanggan.index',[
            'pelanggan' => $cust, 
			//Pelanggan::all(),
            'level' => $role,
            'title' => 'Pelanggan',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $identitas = [
            ['id' =>'1','name' =>'KTP'],
            ['id' =>'2','name' =>'SIM'],
            ['id' =>'3','name' =>'PASPORT'],
            ];
        $status_unit = [
            ['id' =>'1','name' =>'Pemilik'],
            ['id' =>'2','name' =>'Penyewa'],
            ];
        $jkel = [
            ['id' =>'1','name' =>'Laki-Laki'],
            ['id' =>'2','name' =>'Perempuan'],
            ['id' =>'3','name' =>'Other'],
            ];
        $agama = [
            ['id' =>'1','name' =>'ISLAM'],
            ['id' =>'2','name' =>'KATHOLIK'],
            ['id' =>'3','name' =>'KRISTEN'],
            ['id' =>'4','name' =>'HINDU'],
            ['id' =>'5','name' =>'BUDHA'],
            ['id' =>'6','name' =>'OTHER'],
            ];

        $role = Auth::user()->role;
        return view('administrator.pelanggan.create',[
            'level' => $role,
            'title' => 'Data Pelanggan',
            'identitas' => $identitas,
            'agama' => $agama,
            'jkel' => $jkel,
            'status_unit' => $status_unit,
            'tower' => Tower::all(),
            'subtower' => SubTower::all()->sortBy('name',SORT_NATURAL),
            'lantai' => Lantai::all()->sortBy('name',SORT_NATURAL),
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
       // dd($request);
        $validateData = $request->validate([
            'nama_lengkap' => 'required|max:100',
            'email' => 'required|email:dns',
            'tempat_lahir' => 'required|max:100',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'nomer_hp' => 'required|max:20',
            'pekerjaan' => 'required|max:50',
            'alamat_identitas' => 'required|max:200',
            'identitas' => 'required',
            'nomer_identitas' => 'required|max:30',
            'tower' => 'required',
            'sub_tower' => 'required',
            'lantai' => 'required',
            'nomer_unit' => 'required|max:4',
            'status' => 'required'
        ]);
       // dd($request,$validateData,$request->file('image'));
        if($request->file('image')){
            $validateData['image'] = $request->file('image')->store('pelanggan-images');
        }
        $validateData['user_id'] = auth()->user()->id;
        Pelanggan::create($validateData);

        return redirect('/admin/pelanggan')->with('success','Berhasil menambahkan data Pelanggan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function show(Pelanggan $pelanggan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function edit(Pelanggan $pelanggan)
    {
        $role = Auth::user()->role;
        $identitas = [
            ['id' =>'1','name' =>'KTP'],
            ['id' =>'2','name' =>'SIM'],
            ['id' =>'3','name' =>'PASPORT'],
            ];
        $status_unit = [
                ['id' =>'1','name' =>'Pemilik'],
                ['id' =>'2','name' =>'Penyewa'],
                ];
        $jkel = [
            ['id' =>'1','name' =>'Laki-Laki'],
            ['id' =>'2','name' =>'Perempuan'],
            ['id' =>'3','name' =>'Other'],
            ];
        $agama = [
            ['id' =>'1','name' =>'ISLAM'],
            ['id' =>'2','name' =>'KATHOLIK'],
            ['id' =>'3','name' =>'KRISTEN'],
            ['id' =>'4','name' =>'HINDU'],
            ['id' =>'5','name' =>'BUDHA'],
            ['id' =>'6','name' =>'OTHER'],
            ];


        return view('administrator.pelanggan.edit',[
            'level' => $role,
            'title' => 'Pelanggan',
            'pelanggan' => $pelanggan,
            'identitas' => $identitas,
            'agama' => $agama,
            'jkel' => $jkel,
            'status_unit' => $status_unit,
            'tower' => Tower::all(),
            'subtower' => SubTower::all()->sortBy('name',SORT_NATURAL),
            'lantai' => Lantai::all()->sortBy('name',SORT_NATURAL),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        $rules = ([
            'nama_lengkap' => 'required|max:100',
            'email' => 'required|email:dns',
            'tempat_lahir' => 'required|max:100',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'nomer_hp' => 'required|max:20',
            'pekerjaan' => 'required|max:50',
            'alamat_identitas' => 'required|max:200',
            'identitas' => 'required',
            'nomer_identitas' => 'required|max:30',
            'tower' => 'required',
            'sub_tower' => 'required',
            'lantai' => 'required',
            'nomer_unit' => 'required|max:8',
            'status' => 'required'
        ]);
        $validateData = $request->validate($rules);
		//dd($request->file('image')->store('pelanggan-images'));
        if($request->file('image')){
            if($request->oldImage){
                Storage::delete($request->oldImage);
            }
            $validateData['image'] = $request->file('image')->store('pelanggan-images');
        }
		//dd( $validateData['image']);
        $validateData['user_id'] = auth()->user()->id;

        Pelanggan::where('id', $pelanggan->id)
            ->update($validateData);

        return redirect('/admin/pelanggan')->with('success','Berhasil Update Data Pelanggan!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pelanggan $pelanggan)
    {
		$cek = TrxOrder::where('pelanggan_id',$pelanggan->id)->count();
		if($cek>0){
			return redirect('/admin/pelanggan')->with('danger','Gagal Menghapus Data Pelanggan, Sudah ada transaksi Order Layanan');
		}else{
			if($pelanggan->image){
				Storage::delete($pelanggan->image);
			}
			Pelanggan::destroy($pelanggan->id);

			return redirect('/admin/pelanggan')->with('success','Berhasil Menghapus Data Pelanggan');
		}
        
    }

    public function aktifasiUser(Pelanggan $pelanggan){

        $length = Str::length($pelanggan->email);

        $validator = new EmailValidator();
        $hasil = $validator->isValid($pelanggan->email, new RFCValidation()); //true

       // dd($length,$hasil);
        $cek = User::where('pelanggan_id',$pelanggan->id)->count();
        if($cek==0 & $hasil==true){
            User::create([
                'pelanggan_id' => $pelanggan->id,
                'name' => $pelanggan->nama_lengkap,
                'email' => $pelanggan->email,
                'password' => bcrypt('password'),
                'role' => '0',
                'email_verified_at' => date(now()),
            ]);
            return redirect('/admin/pelanggan')->with('success','Berhasil Aktifasi User '.$pelanggan->nama_lengkap);
        }else{
            return redirect('/admin/pelanggan')->with('danger','Gagal Aktifasi User '.$pelanggan->nama_lengkap.' Cek format data isian email');
        }


    }
	public function getPelanggan (Request $request)
    {
       // dd($request);
        $role = Auth::user()->role;
        if($request->action=="upd")
            {
                $pelanggan = Pelanggan::where('id',$request->pelangganid)->first();
                return view('administrator.pelanggan.editnpwp',[
                    'level' => $role,
                    'title' => 'Update Data NPWP Pelanggan',
                    'pelanggan' => $pelanggan,
                ]);
            }
		if($request->action=="upd_cid")
            {
                $pelanggan = Pelanggan::where('id',$request->pelangganid)->first();
                return view('administrator.pelanggan.editcid',[
                    'level' => $role,
                    'title' => 'Update Data CID Pelanggan',
                    'pelanggan' => $pelanggan,
                ]);
            }
        if($request->action=="deposit"){
                $pelanggan = Pelanggan::all();

                return view('administrator.pelanggan.getDeposit',[
                    'level' => $role,
                    'title' => 'Data Deposit Pelanggan',
                    'pelanggan' => $pelanggan,
                ]);
        }
        if($request->action=="status"){
               $pelanggan = DB::table('vpelanggan')->where('status_layanan',$request->layanan)->get();
                $status = DB::table('vpelanggan')->distinct()->get(['status_layanan','ket_status_layanan']);

                return view('administrator.pelanggan.getStatus',[
                    'level' => $role,
                    'title' => 'Data Status Pelanggan',
                    'pelanggan' => $pelanggan,
					'dtstatus' => $status,
                ]);
        }

    }
    public function upNpwpPelanggan(Request $request)
    {
        
        $rules = ([
            'npwp' => 'required|min:10|max:25',
            'nama_npwp' => 'required|min:3|max:100',
            'alamat_npwp' => 'required|min:15|max:150',
        ]);
        $validateData = $request->validate($rules);

        Pelanggan::where('id', $request->pelangganid)
            ->update($validateData);

        return redirect('/admin/pelanggan')->with('success','Berhasil Update Data NPWP Pelanggan!');

    }
	public function upCIDPelanggan(Request $request)
    {

        $rules = ([
            'cid' => 'required|min:8|max:25',
        ]);
        $validateData = $request->validate($rules);

        Pelanggan::where('id', $request->pelangganid)
            ->update($validateData);

        return redirect('/admin/pelanggan')->with('success','Berhasil Update Data CID Pelanggan!');

    }
}
