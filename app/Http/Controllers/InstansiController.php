<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Validator;

class InstansiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user->hasAnyPermission(['view all instansi', 'manage instansi'])) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        $instansi = Instansi::all();
        return view('instansi.main', compact('instansi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        if (!$user->can('manage instansi')) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        $user = User::role('operator_instansi')->get();
        return view('instansi.tambah', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "nama_instansi" => "required|string",
            "kepala_instansi" => "required",
            "alamat_instansi" => "required",
            "telp_instansi" => "required|numeric",
            "latitude" => "required",
            "longitude" => "required",
            // "user_id" => "exist:user,id|required",
            // "user_id" => "required|unique:user_has_instansi,user_id",
            // "user_id.*" => "exists:users,id"
        ]);

        if ($validate->fails()) {
            return redirect()->route('instansi.create')->withErrors($validate)->withInput();
        }

        $instansi = Instansi::create([
            "nama_instansi" => $request->nama_instansi,
            "kepala_instansi" => $request->kepala_instansi,
            "alamat_instansi" => $request->alamat_instansi,
            "telp_instansi" => $request->telp_instansi,
            "latitude" => $request->latitude,
            "longitude" => $request->longitude
        ]);

        // $instansi->user()->attach($request->user_id);

        return redirect()->route('instansi.index')->with('success', 'Berhaasil Mewnambah Instansi');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = auth()->user();
        if (!$user->can('manage instansi')) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        $instansi = Instansi::find($id);
        $user = User::role('operator_instansi')->get();
        return view('instansi.edit', compact('user', 'instansi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $target = Instansi::find($id);
        $validate = Validator::make($request->all(), [
            "nama_instansi" => "required|string",
            "kepala_instansi" => "required",
            "alamat_instansi" => "required",
            "telp_instansi" => "required|numeric",
            "latitude" => "required",
            "longitude" => "required",
            // "user_id" => "exist:user,id|required",
            // "user_id" => "required|unique:user_has_instansi,user_id",
            // "user_id.*" => "exists:users,id"
        ]);

        if ($validate->fails()) {
            return redirect()->route('instansi.edit', $id)->withErrors($validate)->withInput();
        }

        $target->update([
            "nama_instansi" => $request->nama_instansi,
            "kepala_instansi" => $request->kepala_instansi,
            "alamat_instansi" => $request->alamat_instansi,
            "telp_instansi" => $request->telp_instansi,
            "latitude" => $request->latitude,
            "longitude" => $request->longitude
        ]);

        $target->user()->sync($request->user_id);

        return redirect()->route('instansi.index')->with('success', 'Berhasil Edit Instansi');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = auth()->user();
        if (!$user->can('manage instansi')) {
            return redirect()->back()->with('error', 'Anda tidak mempunya permission');
        }

        $target = Instansi::find($id);
        $target->delete();
        return redirect()->route('instansi.index')->with('success', 'Berhasil Hapus Instansi');
    }
}
