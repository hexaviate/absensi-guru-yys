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
        $hariLibur = HariLibur::where('instansi_id', 3); //nanti diubah agar instansi disamakan dengan instansi nya operator
        return view('instansi.main', compact('hariLibur'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tapel = Tapel::all();
        $instansi = Instansi::where('id', 3); //nanti diubah agar instansi yang muncul sesuai dengan instansi nya operator
        return view('', compact('tapel', 'instansi'));

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
                'tanggal' => $request->tanggal
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
        $tapel = Tapel::all();
        $instansi = Instansi::where('id', 3); //nanti diubah agar instansi yang muncul sesuai dengan instansi nya operator
        return view('', compact('tapel', 'instansi'));
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
                'tanggal' => $request->tanggal
            ]);

            return redirect()->route('hariLibur.index')->with('success', 'Berhasil menambah Hari Libur');

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $target = HariLibur::find($id);
        $target->delete();
        return redirect()->route('hariLibur.index');
    }
}
