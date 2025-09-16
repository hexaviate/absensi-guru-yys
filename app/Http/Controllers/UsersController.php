<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Validator;
use Spatie\Permission\Models\Role;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AutoEncoder;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user->hasAnyPermission(['view all users', 'manage users'])) {
            return redirect()->intended('dashboard');
        }
        $user = User::all();
        $role = Role::all();
        $instansi = Instansi::all();
        return view('user.main', compact('user', 'role', 'instansi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        if (!$user->can('manage users')) {
            return redirect()->intended('dashboard');
        }
        $role = Role::all();
        $user = User::all();
        $instansi = Instansi::all();
        return view('user.tambah', compact('role', 'user', 'instansi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //TODO: jangan lupa tambahkan pengecekan apakah user punya role "admin_yayasan"
        //*sementara untuk keperluan testing

        // $validate = Validator::make($request->all(), [
        //     "name" => "required|min:5",
        //     "telp" => "required|numeric",
        //     "username" => "required",
        //     "password" => "required",
        //     "uid_rfid" => "required"
        // ]);

        // if ($validate->fails()) {
        //     return redirect()->route('users.create')->withErrors($validate)->withInput();
        // }

        // * Upload untuk Foto Presensi

        $imageNamePresensi = time() . '.' . $request->foto_presensi->extension();

        // $request->foto_presensi->move(public_path('foto_presensi/'), $imagePresensi);

        // * Upload untuk Foto Profil
        $imageName = time() . '.' . $request->foto_profil->extension();

        // $request->image->move(public_path('images'), $imageName);

        //img interevention
        $manager = ImageManager::withDriver(new Driver());

        //read image
        $imageProfil = $manager->read($request->file('foto_profil'));
        $imageProfil->encode(new AutoEncoder(50))->save(public_path('foto/' . $imageName));

        //read image
        $imagePresensi = $manager->read($request->file('foto_presensi'));
        $imagePresensi->encode(new AutoEncoder(50))->save(public_path('foto_presensi/' . $imageNamePresensi));

        $user = User::create([
            "name" => $request->name,
            "telp" => $request->telp,
            "username" => $request->username,
            "password" => $request->password,
            "foto_presensi" => $imageNamePresensi,
            "foto" => $imageName,
        ]);

        $user->instansi()->attach($request->instansi_id);
        $user->roles()->attach($request->role_id);

        return redirect()->route('user.index');


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = auth()->user();
        if (!$user->can('manage users')) {
            return redirect()->intended('dashboard');
        }
        $user = User::find($id);
        $instansi = Instansi::all();
        $role = Role::all();

        $userRoles = $user->roles()->pluck('roles.id')->toArray();
        $userInstansi = $user->instansi()->pluck('instansis.id')->toArray();

        return view('user.edit', compact('user', 'instansi', 'role', 'userRoles', 'userInstansi'));

        // return view('user.edit', compact('user', 'instansi', 'role'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $target = User::find($id);
        $validate = Validator::make($request->all(), [
            "name" => "required|min:5",
            "telp" => "required|numeric",
            "username" => "required",
            "password" => "required",
            "uid_rfid" => "required"
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }


        $imageName = time() . '.' . $request->foto->extension();

        // $request->image->move(public_path('images'), $imageName);

        //img intervention
        $manager = ImageManager::withDriver(new Driver());

        //read image
        $image = $manager->read($request->file('foto'));
        $image->encode(new AutoEncoder(quality: 50))->save(public_path('foto/' . $imageName));

        $target->update([
            "name" => $request->name,
            "telp" => $request->telp,
            "username" => $request->username,
            "password" => $request->password,
            "uid_rfid" => $request->uid_rfid,
            "foto" => $imageName
        ]);

        $target->roles()->sync($request->role_id);
        $target->roles()->sync($request->instansi_id);

        return redirect()->route('user.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = auth()->user();
        if (!$user->can('manage users')) {
            return redirect()->intended('dashboard');
        }
        $target = User::find($id);
        $target->delete();
        return redirect()->route('user.index');
    }
}
