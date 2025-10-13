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
        $role = $user->roles()->get();
        // $ = $user->instansi()->get();


        return view('profile.index', compact('user', 'instansiName', 'role'));
    }

    public function viewEditProfile(string $id)
    {
        $user = auth()->user();

        if ($id != $user->id) {
            return redirect()->intended('login');
        }

        return view('profile.editProfile', compact('user'));
    }


    public function editProfile(Request $request, string $id)
    {
        $user = auth()->user();

        $validate = Validator::make($request->all(), [
            'telp' => "required|numeric|sometimes",
            'username' => "required|sometimes",
            "foto_profil" => "required|sometimes"
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
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

        return redirect()->route('viewProfile');
    }

    public function viewJadwalMingguIni(Request $request)
    {
        $user = auth()->user();
        if (!$user->can('view self jadwal')) {
            return redirect()->back();
        }
        $instansiList = $user->instansi()->get();

        $tapelAktif = Tapel::where('status', 'aktif')->first();
        $jadwal = Jadwal::where('user_id', $user->id)->where('tapel_id', $tapelAktif->id)->get();

        // get filter inputs (nullable)
        $filterHari = $request->input('filter_hari');           // e.g. 'senin', 'selasa', etc.
        $filterInstansi = $request->input('filter_instansi'); // e.g. 3

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
        $jadwal = $query->with('instansi')->get();

        return view('jadwalUser.index', compact('jadwal', 'instansiList')); //view nanti diganti
    }

    public function viewRiwayatAbsensi()
    {
        $user = auth()->user();
        if (!$user->can('view self riwayat absen')) {
            return redirect()->back();

        }

        $presensi = Presensi::where('user_id', $user->id)->get();

        return view('riwayatAbsen', compact('presensi'));// view nanti diganti
    }

    public function viewJadwalHariIni()
    {
        $user = auth()->user();
        if (!$user->can('view self jadwal')) {
            return redirect()->back();

        }

        $tapelAktif = Tapel::where('status', "aktif")->first();
        $hariIni = Carbon::now()->isoFormat('dddd');


        $jadwal = Jadwal::where('user_id', $user->id)->where('hari', $hariIni)->where('tapel_id', $tapelAktif->id)->first();

        return view('jadwalHariIni', compact('jadwal'));// view nanti diganti

    }
}
