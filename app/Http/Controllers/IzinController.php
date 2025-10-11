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
        $instansiId = $user->instansi()->first()->id;

        $instansi = $user->instansi;
        return view('izin.users.tambah', compact('instansi', 'instansiId'));

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
                    'user_id' => $user->id,
                    'instansi_id' => $instansi,
                    'bukti_izin' => $buktiIzin,
                    'tanggal' => Carbon::now()->toDateString(),
                    'keterangan' => $request->keterangan,
                ]);

            }


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
        $instansiId = $user->instansi()->first()->id;


        if ($izin->status != 'belum_diverifikasi') {
            return redirect()->intended('dashboard')->with('error', 'izin ini telah diverifikasi');
        }

        if ($izin->user_id != $user->id) {
            return redirect()->intended('dashboard')->with('error', 'anda tidak punya permission');
        }

        return view('izin.users.edit', compact('izin', 'instansi', 'instansiId'));

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
            return redirect()->intended('dashboard')->with('error', 'anda tidak punya permission');
        }

        if ($izin->user_id != $user->id) {
            return redirect()->intended('dashboard')->with('error', 'anda tidak punya permission');
        }

        if ($izin->status != 'belum_diverifikasi') {
            return redirect()->intended('dashboard')->with('error', 'izin ini telah diverifikasi');
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

        if ($izin->status == 'tidak_diterima') {
            $izin->update(attributes: [
                "keterangan_ditolak" => $request->keterangan_ditolak
            ]);
        }


        if ($izin->status == 'diterima') {

            if (Presensi::where('instansi_id', $izin->instansi_id)->where('user_id', $izin->user_id)->where('tanggal', $izin->tanggal)->exists()) {
                $presensi = Presensi::where('instansi_id', $izin->instansi_id)->where('user_id', $izin->user_id)->where('tanggal', $izin->tanggal)->first();
                $presensi->update([
                    "izin_id" => $izin->id,
                    "pulang" => "-"
                ]);
            }



            Presensi::create([
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


}
