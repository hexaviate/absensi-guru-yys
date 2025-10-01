<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Presensi;
use App\Models\Tapel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\ImageManager;
use Validator;

class ProfileController extends Controller
{

    public function viewProfile()
    {
        $user = auth()->user();
        $instansiName = $user->instansi()->get();

        return view('instansiIndex');
    }

    public function viewEditProfile(string $id)
    {
        $user = auth()->user();

        if ($id != $user->id) {
            return redirect()->intended('login');
        }

        return view('editProfile', compact('user')); //view nanti diganti
    }


    public function editProfile(Request $request, string $id)
    {
        $user = auth()->user();

        $validate = Validator::make($request->all(), [
            'telp' => "required|numeric|sometimes",
            'username' => "required|min:5|sometimes",
            "foto_profil" => "required|sometimes"
        ]);

        if ($validate->fails()) {
            return redirect()->route('hariLibur.create')->withErrors($validate)->withInput();
        }

        $imageName = time() . '.' . $request->foto_profil->extension();

        //img interevention
        $manager = ImageManager::withDriver(new Driver());

        //read image
        $image = $manager->read($request->file('foto_profil'));
        $image->encode(new AutoEncoder(quality: 50))->save(public_path('foto/' . $imageName));

        $user->update([
            "telp" => $request->telp,
            "username" => $request->username,
            "foto" => $imageName
        ]);
    }

    public function viewJadwalMingguIni()
    {
        $user = auth()->user();
        if (!$user->can('view self jadwal')) {
            return redirect()->intended('dashboard');
        }

        $tapelAktif = Tapel::where('status', "aktif")->first();

        $jadwal = Jadwal::where('user_id', $user->id)->where('tapel_id', $tapelAktif->id)->get();

        return view('jadwalSaya', compact('jadwal')); //view nanti diganti
    }

    public function viewRiwayatAbsensi()
    {
        $user = auth()->user();
        if (!$user->can('view self riwayat absen')) {
            return redirect()->intended('dashboard');
        }

        $presensi = Presensi::where('user_id', $user->id)->get();

        return view('riwayatAbsen', compact('presensi'));// view nanti diganti
    }

    public function viewJadwalHariIni()
    {
        $user = auth()->user();
        if (!$user->can('view self jadwal')) {
            return redirect()->intended('dashboard');
        }

        $tapelAktif = Tapel::where('status', "aktif")->first();
        $hariIni = Carbon::now()->isoFormat('dddd');


        $jadwal = Jadwal::where('user_id', $user->id)->where('hari', $hariIni)->where('tapel_id', $tapelAktif->id)->first();

        return view('jadwalHariIni', compact('jadwal'));// view nanti diganti

    }
}
