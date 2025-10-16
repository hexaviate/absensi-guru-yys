<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\Izin;
use App\Models\Tapel;
use App\Models\User;
use App\Models\Presensi;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\ImageManager;
use Validator;

class IzinController extends Controller
{
    //*---------------------------------------------------------{User}-----------------------------------------------------------------------//
    public function izinIndexUser()
    {
        $user = auth()->user();
        // dd();

        if ($user->hasAnyPermission(['view self izin', 'manage izin'])) {
            $tapelAktif = Tapel::where('status', 'aktif')->first();

            // $instansi = $user->instansi; //nanti diubah agar instansi yang muncul sesuai dengan instansi nya operator
            $izin = Izin::where('user_id', $user->id)->where('tapel_id', $tapelAktif->id)->get();
            return view('izin.users.index', compact('izin'));
        } else {
            return redirect()->route('login')->with('error', 'Anda tidak punya Permission');
        }
    }

    public function viewIzinCreate()
    {
        $user = auth()->user();

        if ($user->instansi()->where('nama_Instansi', 'SMK Salafiyah') && $user->instansi()->count() == 1) {
            return redirect()->back()->with('error', 'Anda tidak bisa Izin di instansi ini');
        }

        if (!$user->hasAnyPermission(['view self izin', 'manage izin'])) {
            return redirect()->back();
        }

        $instansiId = $user->instansi()->first()->id;

        $instansi = $user->instansi()->get();
        return view('izin.users.tambah', compact('instansi', 'instansiId'));

    }

    public function izinCreate(Request $request)
    {

        $user = auth()->user();
        $tapelAktif = Tapel::where('status', 'aktif')->first();
        if (!$user->hasAnyPermission(['view self izin', 'manage izin'])) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }


        $validate = Validator::make($request->all(), [
            // 'tapel_id' => 'required',
            'bukti_izin' => 'required',
            'instansi_id' => 'required|exists:instansis,id',
            'keterangan' => 'required',
        ]);

        if ($validate->fails()) {
            return redirect()->route('viewIzinCreate')->withErrors($validate)->withInput();
        }


        foreach ($request->instansi_id as $instansi) {

            if ($instansi == Instansi::where('nama_instansi', 'SMK Salafiyah')->first()->id) {
                return redirect()->back()->with('error', 'Anda tidak bisa izin di instansi ini');
            }

            if ($user->izin()->where('tanggal', today()->toDateString())->where('instansi_id', $instansi)->exists()) {
                return redirect()->back()->with('error', 'anda telah melakukan izin hari ini di instansi' . $instansi);
            }

            $instansiUser = $user->instansi();

            // if ($request->instansi_id != $instansiUser->id) {
            //     // return redirect()->back()->with('error', 'tidak  tidak terdaftar pada instansi ini');
            // }

            if ($request->bukti_izin->extension() == "pdf") {

                $file = time() . '.' . $request->bukti_izin->extension();
                $request->bukti_izin->move(public_path('bukti_izin/'), $file);


                Izin::create([
                    'tapel_id' => $tapelAktif->id,
                    'user_id' => $user->id,
                    'instansi_id' => $instansi,
                    'bukti_izin' => $file,
                    'tanggal' => Carbon::now()->toDateString(),
                    'keterangan' => $request->keterangan,
                ]);

            } else {

                // * Upload untuk Foto Profil
                $buktiIzin = time() . $instansi . '.' . $request->bukti_izin->extension();


                //img interevention
                $manager = ImageManager::withDriver(new Driver());

                //read image
                $fotoIzin = $manager->read($request->file('bukti_izin'));
                $fotoIzin->encode(new AutoEncoder(50))->save(public_path('bukti_izin/' . $buktiIzin));

                Izin::create([
                    'tapel_id' => $tapelAktif->id,
                    'user_id' => $user->id,
                    'instansi_id' => $instansi,
                    'bukti_izin' => $buktiIzin,
                    'tanggal' => Carbon::now()->toDateString(),
                    'keterangan' => $request->keterangan,
                ]);

            }

        }
        return redirect()->route("izinIndexUser")->with('success', value: 'anda berhasil membuat izin');
    }

    public function viewIzinEdit(string $id)
    {
        $user = auth()->user();
        if (!$user->hasAnyPermission(['view self izin', 'manage izin'])) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        $izin = Izin::find($id);
        $instansi = $user->instansi()->get();
        $instansiId = $user->instansi()->first()->id;


        if ($izin->status != 'belum_diverifikasi') {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        if ($izin->user_id != $user->id) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        return view('izin.users.edit', compact('izin', 'instansi', 'instansiId'));

    }

    public function izinEdit(Request $request, string $id)
    {
        $user = auth()->user();
        $izin = Izin::find($id);

        if (!$user->hasAnyPermission(['view self izin', 'manage izin'])) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        if ($izin->user_id != $user->id) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        if ($izin->status != 'belum_diverifikasi') {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        $validate = Validator::make($request->all(), [
            'instansi_id' => 'required|sometimes',
            'tapel_id' => 'required',
            'bukti_izin' => 'required|sometimes',
            'tanggal' => 'required|sometimes',
            'keterangan' => 'required|sometimes'
        ]);

        if ($validate->fails()) {
            return redirect()->route('', $id)->withErrors($validate)->withInput();
        }

        if ($request->bukti_izin) {
            // * Upload untuk Foto izin
            $buktiIzin = time() . '.' . $request->file('bukti_izin')->extension();
            //*delete file yang sudah ada sebelumnya
            $filePath = public_path('bukti_izin/' . $buktiIzin);

            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            if ($request->bukti_izin->extension() == "pdf") {
                $file = time() . '.' . $request->bukti_izin->extension();
                $request->bukti_izin->move(public_path('bukti_izin/'), $file);

                $izin->update([
                    "bukti_izin" => $file,
                ]);
            } else {
                //img interevention
                $manager = ImageManager::withDriver(new Driver());
                //read image
                $fotoIzin = $manager->read($request->file('bukti_izin'));
                $fotoIzin->encode(new AutoEncoder(50))->save(public_path('bukti_izin/' . $buktiIzin));

                $izin->update([
                    "bukti_izin" => $buktiIzin,
                ]);
            }
        }



        $izin->update([
            "instansi_id" => $request->instansi_id,
            'tanggal' => $request->tanggal,
            "keterangan" => $request->keterangan
        ]);

        return redirect()->back()->with('success', 'anda berhasil mengedit izin anda');
    }

    public function izinDelete(string $id)
    {
        $user = auth()->user();
        $izin = Izin::find($id);

        if (!$user->hasAnyPermission(['view self izin', 'manage izin'])) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        if ($izin->user_id != $user->id) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        if ($izin->status != 'belum_diverifikasi') {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        $izin->delete();
        return redirect()->back()->with('success', 'Anda berhasil menghapus izin ini');
    }


    //*---------------------------------------------------------{User}-----------------------------------------------------------------------//

    //?---------------------------------------------------------{Operator/Admin}-----------------------------------------------------------------------//


    public function izinIndexOperator()
    {
        $user = auth()->user();

        if (!$user->hasAnyPermission(['manage izin'])) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        // $instansi_id = $user->instansi()->id;
        // $izin = Izin::where('instansi_id', $instansi_id)->first();

        $instansi_ids = $user->instansi->pluck('id');

        // ambil semua izin untuk semua instansi user
        $izin = Izin::whereIn('instansi_id', $instansi_ids)->get();
        return view('izin.admin.index', compact('izin'));
    }

    public function viewIzinVerify(string $id)
    {
        $user = auth()->user();

        if (!$user->hasAnyPermission(['manage izin'])) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        $izin = Izin::find($id);
        if ($izin->user_id == $user->id) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        return view('izinVerification', compact('izin'));
    }

    public function izinVerify(Request $request, string $id)
    {
        $user = auth()->user();
        $tapelAktif = Tapel::where('status', 'aktif')->first();

        if (!$user->hasAnyPermission(['manage izin'])) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        $izin = Izin::find($id);
        if ($izin->user_id == $user->id) {
            return redirect()->intended('izinIndexOperator')->with('error', 'anda tidak punya permission');
        }

        $validate = Validator::make($request->all(), [
            "status" => "required"
        ]);

        if ($validate->fails()) {
            return redirect()->route('izinIndexOperator', $id)->withErrors($validate)->withInput();
        }

        $izin->update([
            'status' => $request->status
        ]);

        if ($izin->status == 'tidak_diterima') {
            $izin->update(attributes: [
                "keterangan_ditolak" => $request->keterangan_ditolak
            ]);
        }


        if ($izin->status == 'diterima') {

            if (Presensi::where('instansi_id', $izin->instansi_id)->where('user_id', $izin->user_id)->where('tanggal', $izin->tanggal)->exists()) {

                $presensi = Presensi::where('instansi_id', $izin->instansi_id)->where('user_id', $izin->user_id)->where('tanggal', $izin->tanggal)->first();

                if ($presensi->pulang) {
                    return redirect()->back()->with('error', 'User ini telah emiliki data presensi');
                }

                $presensi->update([
                    "izin_id" => $izin->id,
                    "pulang" => ""
                ]);
            }



            Presensi::create([
                'tapel_id' => $tapelAktif->id,
                "instansi_id" => $izin->instansi_id,
                "user_id" => $izin->user_id,
                "izin_id" => $izin->id,
                "status" => 'izin',
                "tanggal" => $izin->tanggal,
            ]);
        }

        return redirect()->route('izinIndexOperator')->with('success', 'anda berhasil memverifikasi izin ini');

    }

    //?---------------------------------------------------------{Operator/Admin}-----------------------------------------------------------------------//

    // Tambahkan method-method ini ke IzinController yang sudah ada

    /**
     * Show form untuk operator membuat izin untuk user
     */
    public function viewIzinCreateOperator()
    {
        $user = auth()->user();
        $tapelAktif = Tapel::where('status', 'aktif')->first();

        if (!$user->hasAnyPermission(['manage izin'])) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        // Ambil instansi pertama dari operator (sesuaikan jika operator bisa punya multiple instansi)
        $instansi = $user->instansi()->first();

        if (!$instansi) {
            return redirect()->route('izinIndexOperator')
                ->with('error', 'Anda tidak memiliki akses ke instansi manapun');
        }

        return view('izin.admin.tambah', compact('instansi'));
    }

    /**
     * Store izin yang dibuat oleh operator untuk user
     */
    public function izinCreateOperator(Request $request)
    {
        $user = auth()->user();
        $tapelAktif = Tapel::where('status', 'aktif')->first();

        if (!$user->hasAnyPermission(['manage izin'])) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        // Validasi input
        $validate = Validator::make($request->all(), [
            'instansi_id' => 'required|exists:instansis,id',
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:500',
            'bukti_izin' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
        ], [
            'instansi_id.required' => 'Instansi harus dipilih',
            'instansi_id.exists' => 'Instansi tidak valid',
            'user_id.required' => 'User harus dipilih',
            'user_id.exists' => 'User tidak valid',
            'tanggal.required' => 'Tanggal harus diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'keterangan.required' => 'Keterangan harus diisi',
            'keterangan.max' => 'Keterangan maksimal 500 karakter',
            'bukti_izin.file' => 'Bukti izin harus berupa file',
            'bukti_izin.mimes' => 'Bukti izin harus berformat: jpeg, jpg, png, atau pdf',
            'bukti_izin.max' => 'Ukuran bukti izin maksimal 2MB',
        ]);

        if ($validate->fails()) {
            return redirect()->route('viewIzinCreateOperator')->withErrors($validate)->withInput();
        }

        try {
            // Cek apakah operator punya akses ke instansi ini
            $operatorHasInstansi = $user->instansi()->where('instansi_id', $request->instansi_id)->exists();

            if (!$operatorHasInstansi) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Anda tidak memiliki akses ke instansi ini');
            }

            // Cek apakah user terdaftar di instansi yang dipilih
            $userInInstansi = User::find($request->user_id)
                ->instansi()
                ->where('instansi_id', $request->instansi_id)
                ->exists();

            if (!$userInInstansi) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'User tidak terdaftar di instansi yang dipilih');
            }

            // Cek apakah user sudah punya izin di tanggal yang sama untuk instansi ini
            $existingIzin = Izin::where('user_id', $request->user_id)
                ->where('instansi_id', $request->instansi_id)
                ->where('tanggal', $request->tanggal)
                ->exists();

            if ($existingIzin) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'User sudah memiliki izin pada tanggal yang sama di instansi ini');
            }

            // Handle upload bukti izin (opsional)
            $buktiIzinPath = null;

            if ($request->hasFile('bukti_izin')) {
                $file = $request->file('bukti_izin');

                if ($file->extension() == "pdf") {
                    // Upload PDF langsung
                    $fileName = time() . '_' . $request->instansi_id . '.' . $file->extension();
                    $file->move(public_path('bukti_izin/'), $fileName);
                    $buktiIzinPath = $fileName;

                } else {
                    // Upload dan compress gambar menggunakan Intervention Image
                    $fileName = time() . '_' . $request->instansi_id . '.' . $file->extension();

                    $manager = ImageManager::withDriver(new Driver());
                    $image = $manager->read($file);
                    $image->encode(new AutoEncoder(50))->save(public_path('bukti_izin/' . $fileName));

                    $buktiIzinPath = $fileName;
                }
            }

            // Simpan data izin dengan status diterima (karena dibuat oleh operator)
            $izin = Izin::create([
                'tapel_id' => $tapelAktif->id,
                'user_id' => $request->user_id,
                'instansi_id' => $request->instansi_id,
                'bukti_izin' => $buktiIzinPath,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'status' => 'diterima', // Auto approve karena dibuat operator
            ]);

            // Buat presensi izin otomatis
            // Presensi::create([
            //     'tapel_id' => $tapelAktif->id,
            //     'instansi_id' => $request->instansi_id,
            //     'user_id' => $request->user_id,
            //     'izin_id' => $izin->id,
            //     'status' => 'izin',
            //     'tanggal' => $request->tanggal,
            // ]);

            if ($izin->status == 'diterima') {

                if (Presensi::where('instansi_id', $izin->instansi_id)->where('user_id', $izin->user_id)->where('tanggal', $izin->tanggal)->exists()) {

                    $presensi = Presensi::where('instansi_id', $izin->instansi_id)->where('user_id', $izin->user_id)->where('tanggal', $izin->tanggal)->first();

                    if ($presensi->pulang) {
                        return redirect()->back()->with('error', 'User ini telah emiliki data presensi');
                    }

                    $presensi->update([
                        "izin_id" => $izin->id,
                        "pulang" => ""
                    ]);
                }



                Presensi::create([
                    'tapel_id' => $tapelAktif->id,
                    "instansi_id" => $izin->instansi_id,
                    "user_id" => $izin->user_id,
                    "izin_id" => $izin->id,
                    "status" => 'izin',
                    "tanggal" => $izin->tanggal,
                ]);
            }

            return redirect()->route('izinIndexOperator')
                ->with('success', 'Data izin berhasil ditambahkan');

        } catch (\Exception $e) {
            \Log::error('Error saat menyimpan izin operator:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Search users berdasarkan instansi (untuk AJAX dropdown)
     */
    public function searchUsers(Request $request)
    {
        try {
            // Log untuk debugging
            \Log::info('Search Users Request:', [
                'instansi' => $request->get('instansi'),
                'search' => $request->get('search'),
                'all_params' => $request->all()
            ]);

            // Ambil parameter
            $instansiId = $request->get('instansi');
            $searchTerm = $request->get('search');

            // Validasi parameter
            if (empty($instansiId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter instansi tidak ditemukan'
                ], 400);
            }

            if (empty($searchTerm) || strlen($searchTerm) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kata kunci pencarian minimal 2 karakter'
                ], 400);
            }

            // Query dasar dengan join ke tabel user_has_instansi
            $query = User::select('users.id', 'users.name', 'users.username', 'users.telp')
                ->join('user_has_instansi', 'users.id', '=', 'user_has_instansi.user_id')
                ->where('user_has_instansi.instansi_id', $instansiId);

            // Filter berdasarkan nama, username, atau telp
            $query->where(function ($q) use ($searchTerm) {
                $q->where('users.name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('users.username', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('users.telp', 'LIKE', '%' . $searchTerm . '%');
            });

            // Batasi hasil dan urutkan
            $users = $query->limit(15)
                ->orderBy('users.name', 'asc')
                ->get();

            // Log hasil query
            \Log::info('Search Users Result:', [
                'count' => $users->count(),
                'users' => $users->toArray()
            ]);

            // Format hasil untuk dropdown
            $formattedUsers = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'telp' => $user->telp,
                    'display' => $user->name . ' (' . $user->username . ')'
                ];
            });

            return response()->json([
                'success' => true,
                'users' => $formattedUsers
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            // Error database spesifik
            \Log::error('Database Error in searchUsers:', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql() ?? 'N/A',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error database: ' . (config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan query')
            ], 500);

        } catch (\Exception $e) {
            \Log::error('General Error in searchUsers:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . (config('app.debug') ? $e->getMessage() : 'Internal server error')
            ], 500);
        }
    }

}
