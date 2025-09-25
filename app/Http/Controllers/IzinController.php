<?php

namespace App\Http\Controllers;

use App\Models\Izin;
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

        if ($user->hasAnyPermission(['view self izin', 'manage izin'])) {
            // $instansi = $user->instansi; //nanti diubah agar instansi yang muncul sesuai dengan instansi nya operator
            $izin = Izin::where('user_id', $user->id)->get();
            return view('izin.users.index', compact('izin'));
        } else {
            return redirect()->route('login')->with('error', 'Anda tidak punya Permission');
        }
    }

    public function viewIzinCreate()
    {
        $user = auth()->user();
        if (!$user->hasAnyPermission(['view self izin', 'manage izin'])) {
            return redirect()->intended('dashboard');
        }

        $instansi = $user->instansi;
        return view('izin.users.tambah', compact('instansi'));

    }

    public function izinCreate(Request $request)
    {

        $user = auth()->user();
        if (!$user->hasAnyPermission(['view self izin', 'manage izin'])) {
            return redirect()->intended('dashboard');
        }

        $validate = Validator::make($request->all(), [
            'bukti_izin' => 'required',
            'instansi_id' => 'required|exists:instansis,id',
            'keterangan' => 'required',
        ]);

        if ($validate->fails()) {
            return redirect()->route('viewIzinCreate')->withErrors($validate)->withInput();
        }

        foreach ($request->instansi_id as $instansi) {


            $instansiUser = $user->instansi();

            // if ($request->instansi_id != $instansiUser->id) {
            //     // return redirect()->back()->with('error', 'tidak  tidak terdaftar pada instansi ini');
            // }

            // * Upload untuk Foto Profil
            $buktiIzin = time() . $instansi . '.' . $request->bukti_izin->extension();


            //img interevention
            $manager = ImageManager::withDriver(new Driver());

            //read image
            $fotoIzin = $manager->read($request->file('bukti_izin'));
            $fotoIzin->encode(new AutoEncoder(50))->save(public_path('bukti_izin/' . $buktiIzin));

            Izin::create([
                'user_id' => $user->id,
                'instansi_id' => $instansi,
                'bukti_izin' => $buktiIzin,
                'tanggal' => Carbon::now()->toDateString(),
                'keterangan' => $request->keterangan,
            ]);
        }



        // dd($created);

        return redirect()->route("izinIndexUser")->with('success', value: 'anda berhasil membuat izin');
    }

    public function viewIzinEdit(string $id)
    {
        $user = auth()->user();
        if (!$user->hasAnyPermission(['view self izin', 'manage izin'])) {
            return redirect()->intended('dashboard');
        }

        $izin = Izin::find($id);
        $instansi = $user->instansi;

        if ($izin->status != 'belum_diverifikasi') {
            return redirect()->intended('dashboard')->with('error', 'izin ini telah diverifikasi');
        }

        if ($izin->user_id != $user->id) {
            return redirect()->intended('dashboard')->with('error', 'anda tidak punya permission');
        }

        return view('izin.users.edit', compact('izin', 'instansi'));

    }

    public function izinEdit(Request $request, string $id)
    {
        $user = auth()->user();
        $izin = Izin::find($id);

        if (!$user->hasAnyPermission(['view self izin', 'manage izin'])) {
            return redirect()->intended('dashboard')->with('error', 'anda tidak punya permission');
        }

        if ($izin->user_id != $user->id) {
            return redirect()->intended('dashboard')->with('error', 'anda tidak punya permission');
        }

        if ($izin->status != 'belum_diverifikasi') {
            return redirect()->intended('dashboard')->with('error', 'izin ini telah diverifikasi');
        }

        $validate = Validator::make($request->all(), [
            'instansi_id' => 'required|sometimes',
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

            //img interevention
            $manager = ImageManager::withDriver(new Driver());

            //read image
            $fotoIzin = $manager->read($request->file('bukti_izin'));
            $fotoIzin->encode(new AutoEncoder(50))->save(public_path('bukti_izin/' . $buktiIzin));

            $izin->update([
                "bukti_izin" => $buktiIzin,
            ]);
        }



        $izin->update([
            "instansi_id" => $request->instansi_id,
            'tanggal' => $request->tanggal,
            "keterangan" => $request->keterangan
        ]);

        return redirect()->back()->with('success', 'anda berhasil mengedit izin anda');
    }


    //*---------------------------------------------------------{User}-----------------------------------------------------------------------//

    //?---------------------------------------------------------{Operator/Admin}-----------------------------------------------------------------------//


    public function izinIndexOperator()
    {
        $user = auth()->user();
        if (!$user->hasAnyPermission(['manage izin'])) {
            return redirect()->intended('dashboard');
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
            return redirect()->intended('dashboard');
        }

        $izin = Izin::find($id);
        if ($izin->user_id == $user->id) {
            return redirect()->intended('dashboard')->with('error', 'anda tidak punya permission');
        }

        return view('izinVerification', compact('izin'));
    }

    public function izinVerify(Request $request, string $id)
    {
        $user = auth()->user();
        if (!$user->hasAnyPermission(['manage izin'])) {
            return redirect()->intended('dashboard');
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

        if ($izin->status == 'diterima') {
            Presensi::create([
                "instansi_id" => $izin->instansi_id,
                "user_id" => $izin->user_id,
                "izin_id" => $izin->id,
                "status" => 'izin',
                "tanggal" => Carbon::now()->toDateString(),
            ]);
        }

        return redirect()->route('izinIndexOperator')->with('success', 'anda berhasil memverifikasi izin ini');

    }

    //?---------------------------------------------------------{Operator/Admin}-----------------------------------------------------------------------//


}
