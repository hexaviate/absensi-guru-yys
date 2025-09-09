<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\Jadwal;
use App\Models\Tapel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jadwal = Jadwal::all();
        return view('jadwal.main', compact('jadwal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tapel = Tapel::all();
        $user = User::all();
        $instansi = Instansi::all();
        return view('jadwal.tambah', compact('tapel', 'instansi', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
            return redirect()->route('jadwal.create')->withErrors($validate)->withInput();
        }

        //melihat apakah ada jadwal dari instansi yang sama di hari tersebut
        $adaJadwal = Jadwal::where('user_id', $request->user_id)->where('instansi_id', $request->instansi_id)->where('hari', $request->hari)->first();
        $terdaftar = User::find($request->user_id)->instansi()->where('instansi_id', $request->instansi_id)->first(); //melihat apakah user terdaftar pada instansi

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

                return redirect()->route('jadwal.index')->with('success', 'Berhasil Menginputkan Jadwal');
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
        $tapel = Tapel::all();
        $user = User::all();
        $instansi = Instansi::all();
        $jadwal = Jadwal::find($id);
        return view('jadwal.edit', compact('jadwal', 'tapel', 'user', 'instansi'));
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
            ->where('hari', 'senin')
            ->where('id', '!=', $id)
            ->first();

        if ($adaJadwal) {
            return redirect()->back()->with('error', 'User ini sudah punya jadwal di instansi ini pada hari yang sama');
        }

        $jadwal->update([
            'tapel_id' => $request->tapel_id,
            'instansi_id' => $request->instansi_id,
            'user_id' => $request->user_id,
            'hari' => $request->hari,
            'datang' => $request->datang,
            'pulang' => $request->pulang
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Berhasil mengedit Jadwal');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $target = Jadwal::find($id);
        $target->delete();
        return redirect()->route('jadwal.index');
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
