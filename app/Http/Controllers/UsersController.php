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
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $instansi = $user->instansi()->first();
        if (!$user->hasAnyPermission(['view all users', 'manage users'])) {
            return redirect()->intended('dashboard');
        }
        $semuaUser = User::all();
        $semuaRole = Role::all();
        $semuaInstansi = Instansi::all();

        $user = $instansi->user;
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
        // $imageName = time() . '.' . $request->foto->extension();

        // $request->image->move(public_path('images'), $imageName);

        //img interevention
        $manager = ImageManager::withDriver(new Driver());

        //read image
        // $imageProfil = $manager->read($request->file('foto'));
        // $imageProfil->encode(new AutoEncoder(50))->save(public_path('foto/' . $imageName));

        //read image
        $imagePresensi = $manager->read($request->file('foto_presensi'));
        $imagePresensi->encode(new AutoEncoder(50))->save(public_path('foto_presensi/' . $imageNamePresensi));

        $user = User::create([
            "name" => $request->name,
            "telp" => $request->telp,
            "username" => $request->username,
            "password" => $request->password,
            "jarak_tempuh" => $request->jarak_tempuh,
            "foto_presensi" => $imageNamePresensi,
            // "foto" => $imageName,
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
    public function update(Request $request, $id)
    {
        $target = User::find($id);

        if (!$target) {
            return redirect()->route('user.index')->with('error', 'User tidak ditemukan');
        }

        // Setup validation rules
        $rules = [
            "name" => "required|min:5",
            "telp" => "required|numeric",
            "password" => "nullable|min:6",
            "foto_presensi" => "nullable|image|mimes:jpeg,png,jpg|max:2048",
            "jarak_tempuh" => "nullable|numeric|min:0",
            "role_id" => "required|min:1",
            "instansi_id" => "required|array|min:1"
        ];

        // Validasi username hanya jika berubah
        if ($request->username != $target->username) {
            $rules['username'] = "required|unique:users,username";
        } else {
            $rules['username'] = "required";
        }

        // Validate request
        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        // Prepare data untuk update
        $dataUpdate = [
            "name" => $request->name,
            "telp" => $request->telp,
            "username" => $request->username,
            "jarak_tempuh" => $request->jarak_tempuh ?? 0,
        ];

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $dataUpdate['password'] = bcrypt($request->password);
        }

        // Handle Foto Presensi
        if ($request->hasFile('foto_presensi')) {
            try {
                if ($target->foto_presensi && file_exists(public_path('foto/' . $target->foto_presensi))) {
                    unlink(public_path('foto/' . $target->foto_presensi));
                }

                $imageName = time() . '.' . $request->foto_presensi->extension();

                $manager = ImageManager::withDriver(new Driver());
                $image = $manager->read($request->file('foto_presensi'));
                $image->encode(new AutoEncoder(quality: 50))->save(public_path('foto_presensi/' . $imageName));

                $dataUpdate['foto_presensi'] = $imageName;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal mengupload foto: ' . $e->getMessage())->withInput();
            }
        }

        try {
            $target->update($dataUpdate);

            $target->roles()->sync($request->role_id);
            $target->instansi()->sync($request->instansi_id);

            return redirect()->route('user.index')->with('success', 'User berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate user: ' . $e->getMessage())->withInput();
        }
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
