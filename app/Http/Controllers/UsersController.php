<?php

namespace App\Http\Controllers;

use App\Exports\TemplateAdminExport;
use App\Exports\TemplateOperatorInstansiExport;
use App\Imports\UsersImport;
use App\Imports\UsersImportOperator;
use App\Models\Instansi;
use App\Models\Tapel;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;
use Spatie\Permission\Models\Role;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AutoEncoder;
// use Vtiful\Kernel\Excel;
use Maatwebsite\Excel\Facades\Excel;
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
        // TODO: jangan lupa tambahkan pengecekan apakah user punya role "admin_yayasan"
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



        // dd($validate);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        if ($request->role_id == '1' || $request->role_id == '2' && $user->hasRole('operator_instansi')) {
            return redirect()->back()->with('error', 'anda tidak punya permission');
        }

        // dd($request->instansi_id[0]);

        // if ($request->instansi_id[0] != $user->instansi()->first()->id) {
        //     return redirect()->back()->with('error', 'anda tidak terdaftar di instansi ini');
        // }
        // if ($user->hasRole('operator_instansi') && $request->instansi_id != $user->instansi()->first()->id) {
        //     return redirect()->back()->with('error', 'anda tidak terdaftar di instansi ini');
        // }

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
        $userLogin = auth()->user();
        if (!$userLogin->can('manage users')) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        $user = User::find($id);
        $tapelAktif = Tapel::where('status', 'aktif')->first();
        $wajibHadir = $user->wajib_hadir;
        $hadir = $user->presensi()->where('tapel_id', $tapelAktif->id)->where('status', 'hadir')->count();
        $izin = $user->presensi()->where('tapel_id', $tapelAktif->id)->where('status', 'izin')->count();
        $tidakHadir = $user->tidak_hadir()->where('tapel_id', $tapelAktif->id)->count();

        return view('user.cekProfile', compact('user', 'wajibHadir', 'hadir', 'izin', 'tidakHadir'));
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

        // if ($user->hasRole('operator_instansi') && $request->instansi_id != $user->instansi()->first()->id) {
        //     return redirect()->back()->with('error', 'anda tidak terdaftar di instansi ini');
        // }

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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        DB::beginTransaction();
        $import = new UsersImport();

        try {
            Excel::import($import, $request->file('file'));

            // Cek apakah ada error
            if ($import->getFailureCount() > 0) {
                DB::rollBack();

                return redirect()->back()->with([
                    'error' => 'Import gagal! Terdapat ' . $import->getFailureCount() . ' baris yang error.',
                    'errors' => $import->getErrors()
                ]);
            }

            // Jika tidak ada error, commit transaction
            DB::commit();

            return redirect()->back()->with([
                'success' => 'Import berhasil! ' . $import->getSuccessCount() . ' user berhasil diimport.'
            ]);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();

            $failures = $e->failures();
            $errors = [];

            foreach ($failures as $failure) {
                $errors[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return redirect()->back()->with([
                'error' => 'Import gagal! Terdapat error pada file Excel.',
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with([
                'error' => 'Import gagal! ' . $e->getMessage()
            ]);
        }
    }

    public function downloadTemplate()
    {
        // ini saya isi nanti ketika sudah ada ini untuk admin yayasan
        return Excel::download(new TemplateAdminExport(), 'TemplateUserExport.xlsx');
    }


    // IMORT UNTUK OPERATORR YA INII

    public function importOperator(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ], [
            'file.required' => 'File wajib diunggah.',
            'file.mimes' => 'File harus berformat Excel atau CSV.',
            'file.max' => 'Ukuran file maksimal 2MB.'
        ]);

        DB::beginTransaction();

        try {
            $import = new UsersImportOperator();
            Excel::import($import, $request->file('file'));

            // Cek apakah ada error
            if ($import->getFailureCount() > 0) {
                DB::rollBack();
                return redirect()->back()->with([
                    'error' => 'Import gagal! Terdapat ' . $import->getFailureCount() . ' baris yang error.',
                    'errors' => $import->getErrors()
                ]);
            }

            // Jika tidak ada error, commit transaction
            DB::commit();
            return redirect()->back()->with([
                'success' => 'Import berhasil! ' . $import->getSuccessCount() . ' user berhasil diimport ke instansi Anda.'
            ]);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $errors = [];

            foreach ($failures as $failure) {
                $errors[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return redirect()->back()->with([
                'error' => 'Import gagal! Terdapat error pada file Excel.',
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with([
                'error' => 'Import gagal! ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Download template Excel untuk import
     * Template hanya berisi kolom: name, telp, username, password, jarak_tempuh, nomor_induk_yayasan, peran
     */
    public function downloadTemplateOperator()
    {
        return Excel::download(new TemplateOperatorInstansiExport(), 'Template_Import_Operator_Instansi.xlsx');
    }

    public function viewImportOperator()
    {
        return view('user.importOperator');
    }
    public function viewImportAdmin()
    {
        return view('user.importAdmin');
    }
}
