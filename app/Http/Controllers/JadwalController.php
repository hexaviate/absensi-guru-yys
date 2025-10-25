<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Tapel;
use App\Models\User;
use App\Imports\JadwalImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;
use Maatwebsite\Excel\Facades\Excel;
use ValueError;
use function Livewire\of;
use function PHPUnit\Framework\returnArgument;

class JadwalController extends Controller
{

    public function import(Request $request)
    {
        $import = new JadwalImport();

        DB::beginTransaction();

        try {
            Excel::import($import, $request->file('file'));

            if ($import->failures()->isNotEmpty()) {
                $failures = $import->failures();

                $userErrors = $failures
                    ->filter(fn($f) => str_contains(implode(', ', $f->errors()), 'User dengan kode'))
                    ->map(fn($f) => $f->values()['kode'] ?? '')
                    ->filter()
                    ->implode(', ');


                $otherErrors = $failures
                    ->reject(fn($f) => str_contains(implode(', ', $f->errors()), 'User dengan kode'))
                    ->map(fn($f) => "Baris {$f->row()}: " . implode(', ', $f->errors()))
                    ->implode('. ');

                $message = $userErrors
                    ? "User dengan kode $userErrors tidak ditemukan di database. " . $otherErrors
                    : $otherErrors;

                // rollback transaksi
                DB::rollBack();

                return back()->with('error', $message);
            }


            DB::commit();
            return back()->with('success', 'Semua Data Jadwal Berhasil Di Import');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user->hasAnyPermission(['view all instansi', 'manage jadwal'])) {
            return redirect()->back()->with('error', 'Anda tidak punya permission');
        }



        $tapelAktif = Tapel::where('status', 'aktif')->first();
        $jadwal = Jadwal::where('instansi_id', $user->instansi()->first()->id)->where('tapel_id', $tapelAktif->id);

        // get filter inputs (nullable)
        $filterHari = $request->input('filter_hari');           // e.g. 'senin', 'selasa', etc.
        $filterInstansi = $request->input('instansi_id'); // e.g. 3

        // Base query: jadwal for this user + active tapel
        $query = Jadwal::where('user_id', $user->id)
            ->where('tapel_id', $tapelAktif->id);

        // Apply day filter if provided
        $query->when($filterHari, function ($q, $hari) {
            return $q->where('hari', $hari);
        });

        // Apply instansi filter if provided
        $query->when($filterInstansi, function ($q, $instansiId) {
            return $q->where('instansi_id', $instansiId);
        });

        // Optionally eager load instansi relationship for display
        // $jadwal = $query->with('instansi')->get();
        $semuaJadwal = Jadwal::all();

        //untuk operator
        $jadwal = Jadwal::where('instansi_id', $user->instansi()->first()->id)->where('tapel_id', Tapel::where('status', 'aktif')->first()->id)->get();
        // dd($jadwal);

        return view('jadwal.main', compact('jadwal', 'semuaJadwal', 'filterHari', 'filterInstansi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $instansiId = $user->instansi()->first()->id;
        if (!$user->can('manage jadwal')) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        $tapel = Tapel::where('status', "aktif")->first();
        $userList = $user->instansi()->get(); //! masih prlu tindak lanjut
        $instansi = $user->instansi()->get();
        return view('jadwal.tambah', compact('tapel', 'instansi', 'userList', 'instansiId'))->with('success', 'Berhasil Menambah Jadwal');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $user = auth()->user();

        $validate = Validator::make($request->all(), [
            'tapel_id' => 'required|exists:tapels,id',
            'instansi_id' => 'required|exists:instansis,id',
            'user_id' => 'required|exists:users,id',
            'hari' => 'required',
            'datang' => 'required',
            'pulang' => 'required'
        ]);

        if ($validate->fails()) {
            return redirect()->route('jadwal.create')->withErrors($validate)->withInput();
        }

        $tapelAktif = Tapel::where('status', "aktif")->first();

        //melihat apakah ada jadwal dari instansi yang sama di hari tersebut
        $adaJadwal = Jadwal::where('user_id', $request->user_id)->where('instansi_id', $request->instansi_id)->where('hari', $request->hari)->where('tapel_id', $tapelAktif->id)->first();
        $terdaftar = User::find($request->user_id)->instansi()->where('instansi_id', $request->instansi_id)->first(); //melihat apakah user terdaftar pada instansi

        //*Penghitungan Wajib Hadir
        // Get number of Fridays in current month
        // if ($request->hari == "Senin") {
        //     $wajibHadir = Carbon::now()->startOfMonth()
        //         ->daysUntil(Carbon::now()->endOfMonth())
        //         ->filter(fn($date) => $date->isMonday())
        //         ->count();
        // } elseif ($request->hari == "Selasa") {
        //     $wajibHadir = Carbon::now()->startOfMonth()
        //         ->daysUntil(Carbon::now()->endOfMonth())
        //         ->filter(fn($date) => $date->isTuesday())
        //         ->count();
        // }

        $dayMap = [
            'Senin' => 'isMonday',
            'Selasa' => 'isTuesday',
            'Rabu' => 'isWednesday',
            'Kamis' => 'isThursday',
            'Jumat' => 'isFriday',
            'Sabtu' => 'isSaturday',
            'Minggu' => 'isSunday',
        ];

        $carbonMethod = $dayMap[$request->hari] ?? null;

        if (!$carbonMethod) {
            return back()->withErrors(['hari' => 'Hari tidak valid']);
        }

        $wajibHadir = Carbon::now()->startOfMonth()
            ->daysUntil(Carbon::now()->endOfMonth())
            ->filter(fn($date) => $date->$carbonMethod())
            ->count();



        // dd($carbonMethod);




        if ($terdaftar) {
            if (!$adaJadwal) {
                Jadwal::create([
                    'tapel_id' => $request->tapel_id,
                    'instansi_id' => $request->instansi_id,
                    'user_id' => $request->user_id,
                    'hari' => $request->hari,
                    'datang' => $request->datang,
                    'pulang' => $request->pulang
                ]);

                $guru = User::find($request->user_id);

                if ($guru->hasRole('tenaga_pendidik')) {
                    $guru->update([
                        'wajib_hadir' => $guru->wajib_hadir + $wajibHadir
                    ]);

                    return redirect()->route('jadwal.index')->with('success', 'Berhasil Menginputkan Jadwal');
                }

                // return redirect()->route('jadwal.index')->with('success', 'Berhasil Menginputkan Jadwal');
            } else {
                return redirect()->back()->with('error', 'User ini telah terjadwal di instansi ini pada hari ini');
            }
        } else {
            return redirect()->back()->with('error', 'User ini tidak terdaftar pada instansi ini');
        }



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
        if (!$user->can('manage jadwal')) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        $instansiId = $user->instansi()->first()->id;
        $tapel = Tapel::where('status', "aktif")->first();
        // $userList = User::where('instansi_id', $instansiId)->get();
        // $instansi = Instansi::all();
        $instansi = $user->instansi()->get();
        $jadwal = Jadwal::find($id);
        return view('jadwal.edit', compact('jadwal', 'tapel', 'instansi', 'instansiId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            'tapel_id' => 'required|exists:tapels,id',
            'instansi_id' => 'required|exists:instansis,id',
            'user_id' => 'required|exists:users,id',
            'hari' => 'required',
            'datang' => 'required',
            'pulang' => 'required'
        ]);

        if ($validate->fails()) {
            return redirect()->route('jadwal.create', $id)->withErrors($validate)->withInput();
        }

        $jadwal = Jadwal::findOrFail($id);
        $terdaftar = User::find($request->user_id)->instansi()->where('instansi_id', $request->instansi_id)->first(); //melihat apakah user terdaftar pada instansi


        if (!$terdaftar) {
            return redirect()->back()->with('error', 'User ini tidak terdaftar pada instansi ini');
        }

        $adaJadwal = Jadwal::where('user_id', $request->user_id)->where('instansi_id', $request->instansi_id)
            ->where('hari', $request->hari)
            ->where('id', '!=', $id)
            ->first();

        if ($adaJadwal) {
            return redirect()->back()->with('error', 'User ini sudah punya jadwal di instansi ini pada hari yang sama');
        }

        $guru = User::find($request->user_id);

        $dayMap = [
            'Senin' => 'isMonday',
            'Selasa' => 'isTuesday',
            'Rabu' => 'isWednesday',
            'Kamis' => 'isThursday',
            'Jumat' => 'isFriday',
            'Sabtu' => 'isSaturday',
            'Minggu' => 'isSunday',
        ];

        $jadwalCarbonMethod = $dayMap[$jadwal->hari] ?? null;

        if (!$jadwalCarbonMethod) {
            return back()->withErrors(['hari' => 'Hari tidak valid']);
        }

        $wajibHadirSebelumnya = Carbon::now()->startOfMonth()
            ->daysUntil(Carbon::now()->endOfMonth())
            ->filter(fn($date) => $date->$jadwalCarbonMethod())
            ->count();

        if ($guru->hasRole('tenaga_pendidik')) {
            $guru->update([
                'wajib_hadir' => $guru->wajib_hadir - $wajibHadirSebelumnya
            ]);
        }

        $jadwal->update([
            'tapel_id' => $request->tapel_id,
            'instansi_id' => $request->instansi_id,
            'user_id' => $request->user_id,
            'hari' => $request->hari,
            'datang' => $request->datang,
            'pulang' => $request->pulang
        ]);

        $carbonMethod = $dayMap[$request->hari] ?? null;
        if (!$carbonMethod) {
            return back()->withErrors(['hari' => 'Hari tidak valid']);
        }
        $wajibHadir = Carbon::now()->startOfMonth()
            ->daysUntil(Carbon::now()->endOfMonth())
            ->filter(fn($date) => $date->$carbonMethod())
            ->count();


        if ($guru->hasRole('tenaga_pendidik')) {
            $guru->update([
                'wajib_hadir' => $guru->wajib_hadir + $wajibHadir
            ]);
        }

        return redirect()->route('jadwal.index')->with('success', 'Berhasil mengedit Jadwal');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = auth()->user();
        if (!$user->can('manage jadwal')) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        $target = Jadwal::find($id);
        $userJadwal = User::find($target->user_id);

        $dayMap = [
            'Senin' => 'isMonday',
            'Selasa' => 'isTuesday',
            'Rabu' => 'isWednesday',
            'Kamis' => 'isThursday',
            'Jumat' => 'isFriday',
            'Sabtu' => 'isSaturday',
            'Minggu' => 'isSunday',
        ];

        $carbonMethod = $dayMap[$target->hari] ?? null;

        if (!$carbonMethod) {
            return back()->withErrors(['hari' => 'Hari tidak valid']);
        }

        $wajibHadir = Carbon::now()->startOfMonth()
            ->daysUntil(Carbon::now()->endOfMonth())
            ->filter(fn($date) => $date->$carbonMethod())
            ->count();

        if ($userJadwal->hasRole('tenaga_pendidik')) {
            $userJadwal->update([
                'wajib_hadir' => $userJadwal->wajib_hadir - $wajibHadir
            ]);
        }

        $target->delete();
        return redirect()->route('jadwal.index')->with('success', 'Berhasil Hapus Jadwal');
    }

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

            // Cek parameter manual
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
                'users' => $users->toArray(),
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
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
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error database: ' . (config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan query'),
                'debug' => config('app.debug') ? [
                    'sql' => $e->getSql(),
                    'error' => $e->getMessage()
                ] : null
            ], 500);
        } catch (\Exception $e) {
            \Log::error('General Error in searchUsers:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . (config('app.debug') ? $e->getMessage() : 'Internal server error'),
                'debug' => config('app.debug') ? [
                    'error' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ] : null
            ], 500);
        }
    }
}
