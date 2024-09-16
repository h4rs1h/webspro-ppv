<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
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
        return view('administrator.user.index',[
            'user' => User::all(),
            'level' => $role,
            'title' => 'Data User',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $level = [
            ['id' =>'0','name' =>'Pelanggan'],
            ['id' =>'1','name' =>'Teknisi'],
            ['id' =>'2','name' =>'Marketing'],
            ['id' =>'3','name' =>'Admin / Kasir'],
            ['id' =>'4','name' =>'Administrator'],
            ];

        $role = Auth::user()->role;
      //  dd($role);
        return view('administrator.user.create',[
            //'user' => User::all(),
            'level' => $role,
            'title' => 'Tambah User',
            'grlevel' => $level,
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
       // dd(date('Y-m-d H:i:s'));

        $validateData = $request->validate(
        [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required'],
        ]);

        $validateData['password'] = Hash::make($request->password);
        if($request->role!='0'){
            $validateData['email_verified_at'] = date('Y-m-d H:i:s');

        }

        User::create($validateData);

        return redirect('/admin/users')->with('success','Berhasil menambah data User');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $level = [
            ['id' =>'0','name' =>'Pelanggan'],
            ['id' =>'1','name' =>'Teknisi'],
            ['id' =>'2','name' =>'Marketing'],
            ['id' =>'3','name' =>'Admin / Kasir'],
            ['id' =>'4','name' =>'Administrator'],
            ];

        $role = Auth::user()->role;
        return view('administrator.user.edit',[
            'level' => $role,
            'title' => 'Data User',
            'grlevel' => $level,
            'dtUser' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
       // dd($request->email,$user->email);
        $rules = ([
                'name' => ['required', 'string', 'max:255'],
                'role' => ['required'],
            ]);

        if($request->email != $user->email){
                $rules['email'] = 'required|email|max:255|unique:users';
        }

        $validateData = $request->validate($rules);

            User::where('id', $user->id)
            ->update($validateData);

        return redirect('/admin/users')->with('success','Berhasil Update Data User!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        User::destroy($user->id);

        return redirect('/admin/users')->with('success','Berhasil Menghapus Data User');
    }
	public function changePassword(Request $request)
    {

        // dd($request->userid);

        $level = [
            ['id' =>'0','name' =>'Pelanggan'],
            ['id' =>'1','name' =>'Teknisi'],
            ['id' =>'2','name' =>'Marketing'],
            ['id' =>'3','name' =>'Admin / Kasir'],
            ['id' =>'4','name' =>'Administrator'],
            ];

        $role = Auth::user()->role;
        return view('administrator.user.change-password',[
            'level' => $role,
            'title' => 'Change Password User',
            'grlevel' => $level,
            'dtUser' => User::where('id', $request->userid)->first(),
        ]);

    }
    public function changePasswordSave(Request $request)
    {
// dd($request);
        $this->validate($request, [
            // 'current_password' => 'required|string',
            'new_password' => 'required|confirmed|min:8|string'
        ]);
 		

 // The passwords matches
        // if (!Hash::check($request->get('current_password'), $auth->password))
        // {
        //     return back()->with('error', "Current Password is Invalid");
        // }

// Current password and new password same
        // if (strcmp($request->get('current_password'), $request->new_password) == 0)
        // {
        //     return redirect()->back()->with("error", "New Password cannot be same as your current password.");
        // }

        $user =  User::find($request->id);
        $user->password =  Hash::make($request->new_password);

        $user->save();

        return redirect('/admin/users')->with('success','Password Changed Successfully');
    }
}
