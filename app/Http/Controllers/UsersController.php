<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\User;
use Carbon\Carbon;
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
            return redirect()->back()->with('error', 'anda tidak punya permission');
        }
        // $semuaUser = User::all();
        $semuaUser = User::with(['roles', 'instansi'])->get();

        $semuaRole = Role::all();
        $semuaInstansi = Instansi::all();

        $userInstansi = $instansi->user()->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin_yayasan');
        })->get(); //untuk operator

        return view('user.main', compact('userInstansi', 'instansi', 'semuaUser', 'semuaRole', 'semuaInstansi'));
    }


    //mapping



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        if (!$user->can('manage users')) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }
        //untuk admin yayasan
        $role = Role::all();
        $semuaUser = User::all();
        $instansi = Instansi::all();

        //untuk operator
        $operatorRole = Role::whereIn('name', ['tenaga_pendidik', 'tenaga_kependidikan'])->get();
        $operatorInstansi = $user->instansi()->first();

        return view('user.tambah', compact('role', 'semuaUser', 'instansi', 'operatorRole', 'operatorInstansi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //TODO: jangan lupa tambahkan pengecekan apakah user punya role "admin_yayasan"
        //*sementara untuk keperluan testing

        $user = auth()->user();

        $validate = Validator::make($request->all(), [
            "name" => "required|min:3",
            "telp" => "required|numeric",
            "username" => "required",
            "password" => "required",
            'jarak_tempuh' => 'required',
            "nomor_induk_yayasan" => "required|unique:users",
        ]);



        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        if ($request->role_id == '1' || $request->role_id == '2' && $user->hasRole('operator_instansi')) {
            return redirect()->back()->with('error', 'anda tidak punya permission');
        }

        // dd($request->instansi_id[0]);

        if ($request->instansi_id[0] != $user->instansi()->first()->id) {
            return redirect()->back()->with('error', 'anda tidak terdaftar di instansi ini');
        }

        // * Upload untuk Foto Presensi

        $imageNamePresensi = time() . '.' . $request->foto_presensi->extension();
        //img interevention
        $manager = ImageManager::withDriver(new Driver());


        //read image
        $imagePresensi = $manager->read($request->file('foto_presensi'));
        $imagePresensi->encode(new AutoEncoder(50))->save(public_path('foto_presensi/' . $imageNamePresensi));

        $userBaru = User::create([
            "nomor_induk_yayasan" => $request->nomor_induk_yayasan,
            "name" => $request->name,
            "telp" => $request->telp,
            "username" => $request->username,
            "password" => $request->password,
            "jarak_tempuh" => $request->jarak_tempuh,
            "foto_presensi" => $imageNamePresensi,
            // "foto" => $imageName,
        ]);

        $userBaru->instansi()->attach($request->instansi_id);
        $userBaru->roles()->attach($request->role_id);


        $hariJumat = Carbon::now()->startOfMonth()
            ->daysUntil(Carbon::now()->endOfMonth())
            ->filter(fn($date) => $date->isFriday())
            ->count();

        if (!$userBaru->hasRole("tenaga_pendidik")) {
            $userBaru->update([
                "wajib_hadir" => now()->daysInMonth() - $hariJumat
            ]);
        }

        return redirect()->route('user.index')->with('success', 'Berhasil Tambah User');


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
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }


        $user = User::find($id);
        $instansi = Instansi::all();
        $role = Role::all();

        $userRoles = $user->roles()->pluck('roles.id')->toArray();
        $userInstansi = $user->instansi()->pluck('instansis.id')->toArray();

        //untuk operator
        $operatorRole = Role::whereIn('name', ['tenaga_pendidik', 'tenaga_kependidikan'])->get();
        $operatorInstansi = $user->instansi()->first();

        return view('user.edit', compact('user', 'instansi', 'role', 'userRoles', 'userInstansi', 'operatorRole', 'operatorInstansi'));

        // return view('user.edit', compact('user', 'instansi', 'role'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $target = User::find($id);
        $user = auth()->user();

        if (!$target) {
            return redirect()->route('user.index')->with('error', 'User tidak ditemukan');
        }

        // Setup validation rules
        $rules = [
            "name" => "required|min:3",
            "telp" => "required|numeric",
            "password" => "nullable|min:3",
            "foto_presensi" => "nullable|image|mimes:jpeg,png,jpg|max:2048",
            "jarak_tempuh" => "nullable|numeric|min:0",
            "role_id" => "required|min:1",
            "instansi_id" => "required|array|min:1",
            "nomor_induk_yayasan" => "required|unique:users,id",

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


        if ($request->role_id == '1' || $request->role_id == '2' && $user->hasRole('operator_instansi')) {
            return redirect()->back()->with('error', 'anda tidak punya permission');
        }

        if ($user->hasRole('operator_instansi') && $request->instansi_id != $user->instansi()->first()->id) {
            return redirect()->back()->with('error', 'anda tidak terdaftar di instansi ini');
        }

        // Prepare data untuk update
        $dataUpdate = [
            "name" => $request->name,
            "telp" => $request->telp,
            "username" => $request->username,
            "jarak_tempuh" => $request->jarak_tempuh ?? 0,
            "nomor_induk_yayasan" => $request->nomor_induk_yayasan,
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
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }
        $target = User::find($id);
        $target->delete();
        return redirect()->route('user.index')->with('success', 'Berhasil Hapus User');
    }
}
