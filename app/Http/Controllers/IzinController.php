<?php

namespace App\Http\Controllers;

use App\Models\Izin;
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
            return view('izinUser', compact('izin'));
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

        $instansi = $user->instansi();
        return view('createIzin', compact('instansi'));

    }

    public function izinCreate(Request $request)
    {
        $user = auth()->user();
        if (!$user->hasAnyPermission(['view self izin', 'manage izin'])) {
            return redirect()->intended('dashboard');
        }

        $validate = Validator::make($request->all(), [
            'bukti_izin' => 'required',
            'instanzi_id' => 'required|exists:instansis,id',
            'tanggal' => 'required',
            'keterangan' => 'required',
        ]);

        if ($validate->fails()) {
            return redirect()->route('')->withErrors($validate)->withInput();
        }

        $instansiUser = $user->instansi();

        if ($request->instansi_id != $instansiUser) {
            return redirect()->back()->with('error', 'tidak  tidak terdaftar pada instansi ini');
        }

        // * Upload untuk Foto Profil
        $buktiIzin = time() . '.' . $request->bukti_izin->extension();


        //img interevention
        $manager = ImageManager::withDriver(new Driver());

        //read image
        $fotoIzin = $manager->read($request->file('foto_profil'));
        $fotoIzin->encode(new AutoEncoder(50))->save(public_path('bukti_izin/' . $buktiIzin));

        Izin::make([
            'user_id' => $user->id,
            'instansi_id' => $request->instansi_id,
            'bukti_izin' => $buktiIzin,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'anda berhasil membuat izin');
    }

    public function viewIzinEdit(string $id)
    {

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


        // * Upload untuk Foto izin
        $buktiIzin = time() . '.' . $request->bukti_izin->extension();


        //*delete file yang sudah ada sebelumnya
        $filePath = public_path('bukti_izin/' . $buktiIzin);

        if (File::exists($filePath)) {
            File::delete($filePath);
        }


        //img interevention
        $manager = ImageManager::withDriver(new Driver());

        //read image
        $fotoIzin = $manager->read($request->file('foto_profil'));
        $fotoIzin->encode(new AutoEncoder(50))->save(public_path('bukti_izin/' . $buktiIzin));

        $izin->update([
            "instansi_id" => $request->instansi_id,
            "bukti_izin" => $buktiIzin,
            'tanggal' => $request->tanggal,
            "keterangan" => $request->keterangan
        ]);

        return redirect()->back()->with('success', 'anda berhasil mengedit izin anda');


    }


    //*---------------------------------------------------------{User}-----------------------------------------------------------------------//

}
