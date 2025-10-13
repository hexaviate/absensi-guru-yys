<?php

namespace App\Http\Controllers;

use App\Models\HariLibur;
use App\Models\Instansi;
use App\Models\Tapel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HariLiburController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasAnyPermission(['view all hari_libur', 'manage hari_libur'])) {
            $tapel = Tapel::all();
            $semuaHariLibur = HariLibur::all();

            $instansi = $user->instansi()->first(); //nanti diubah agar instansi yang muncul sesuai dengan instansi nya operator
            $operatorHariLibur = HariLibur::where('instansi_id', $instansi->id)->get();
            return view('hariLibur.main', compact('user', 'tapel', 'instansi', 'semuaHariLibur', 'operatorHariLibur'));
        } else {
            return redirect()->route('login')->with('error', 'Anda tidak punya Permission');
        }

        // $hariLibur = HariLibur::where('instansi_id', $user->id); //nanti diubah agar instansi disamakan dengan instansi nya operator
        // return view('hariLibur.index', compact('hariLibur', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        if (!$user->can('manage hari_libur')) {
            return redirect()->intended('dashboard');
        }

        $instansiId = $user->instansi()->first()->id;
        $tapel = Tapel::where('status', "aktif")->first();
        // $userList = User::where('instansi_id', $instansiId)->get();
        // $instansi = Instansi::all();
        $instansi = $user->instansi()->get();
        return view('hariLibur.tambah', compact('tapel', 'instansi', 'instansiId'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();


        $validate = Validator::make($request->all(), [
            'tapel_id' => 'required|exists:tapels,id',
            'instansi_id' => 'required|exists:instansis,id',
            'keterangan' => 'required|string',
            'tanggal' => 'required',
        ]);

        if ($validate->fails()) {
            return redirect()->route('hariLibur.create')->withErrors($validate)->withInput();
        }

        $instansiOperator = $user->instansi()->where('instansi_id', $request->instansi_id)->first();

        if (!$instansiOperator) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di instansi ini');
        } else {
            HariLibur::create([
                'tapel_id' => $request->tapel_id,
                'instansi_id' => $request->instansi_id,
                'keterangan' => $request->keterangan,
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
            ]);

            return redirect()->route('hariLibur.index')->with('success', 'Berhasil menambah Hari Libur');

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
        if (!$user->can('manage hari_libur')) {
            return redirect()->intended('dashboard');
        }

        $hariLibur = HariLibur::findOrFail($id);
        $tapel = Tapel::all();
        $instansi = Instansi::where('id', $user->instansi()->first()->id)->get(); //nanti diubah agar instansi yang muncul sesuai dengan instansi nya operator
        $instansiId = $user->instansi()->first()->id;

        return view('hariLibur.edit', compact('tapel', 'instansi', 'hariLibur', 'instansiId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        $hariLibur = HariLibur::findOrFail($id);

        $validate = Validator::make($request->all(), [
            'tapel_id' => 'required|exists:tapels,id',
            'instansi_id' => 'required|exists:instansis,id',
            'keterangan' => 'required|string',
            'tanggal' => 'required',
            'waktu' => 'required|sometimes',
        ]);

        if ($validate->fails()) {
            return redirect()->route('hariLibur.create')->withErrors($validate)->withInput();
        }

        $instansiOperator = $user->instansi()->where('instansi_id', $request->instansi_id)->first();

        if (!$instansiOperator) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di instansi ini');
        } else {
            $hariLibur->update([
                'tapel_id' => $request->tapel_id,
                'instansi_id' => $request->instansi_id,
                'keterangan' => $request->keterangan,
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
            ]);

            return redirect()->route('hariLibur.index')->with('success', 'Berhasil menambah Hari Libur');

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = auth()->user();
        if (!$user->can('manage hari_libur')) {
            return redirect()->intended('dashboard');
        }

        $target = HariLibur::find($id);
        $target->delete();
        return redirect()->route('hariLibur.index');
    }
}
