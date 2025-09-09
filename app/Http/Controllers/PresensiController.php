<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function Symfony\Component\Clock\now;

class PresensiController extends Controller
{
    public function viewPresensi()
    {
        // $instansi = auth()->user()->instansi()->get(['latitude', 'longitude']);
        // return view('tesPresensi.index', compact('instansi'));
        $user = User::find(3);
        $fotoPresensi = $user->foto_presensi;
        $lokasi = $user->instansi()->get(['latitude', 'longitude', 'nama_instansi', 'instansi_id']);
        return view('tesPresensi.index', compact('user', 'lokasi', 'fotoPresensi'));

    }

    public function prosesPresensi(Request $request)
    {

        $user = User::find(3)->id; //diubah ketika final, nanti diisi user id
        $now = Carbon::now();

        //* mengambil jadwal user dan guru
        $jadwal = Jadwal::where('user_id', $user)->where('instansi_id', $request->instansi_id)->first();

        if (!$jadwal) {
            return response()->json(['status' => '❌ Jadwal tidak ditemukan'], 404);
        }

        //mengambil jam datang dan pulang dari jadwal
        $jamDatang = Carbon::parse($jadwal->datang);
        $jamPulang = Carbon::parse($jadwal->pulang);


        // Cek apakah user sudah pernah presensi hari ini untuk instansi ini, dengan menggunakan created_at
        $presensiHariIni = Presensi::where('user_id', $user)
            ->where('instansi_id', $request->instansi_id)
            ->whereDate('created_at', $now->toDateString())
            ->first();

        // jika belum ada presensi
        if (!$presensiHariIni) {
            if ($now->lessThan($jamPulang) && $now->greaterThan(Carbon::createFromTime('23', '00', '00'))) {
                Presensi::create([
                    'instansi_id' => $request->instansi_id,
                    "user_id" => 3, //diubah ketika testing final, nanti diisi user id
                    "datang" => Carbon::now(),
                    "status" => 'hadir',
                    'tanggal' => Carbon::now()->toDateString(),
                    'akurasi' => $request->akurasi,
                    'userAgent' => $request->userAgent()
                ]);

                return response()->json([
                    "status" => 'anda berhasil absensi' //status diganti ke return view blade
                ]);
            } else {
                return response()->json([
                    "status" => 'absen belum dibuka'
                ]); // status diganti ke return view blade
            }
        } else if (!$presensiHariIni->pulang) {
            // dd($now);
            if ($now->greaterThan($jamPulang) && $now->lessThan(Carbon::createFromTime('18', '00', '00'))) {

                $presensiHariIni->update([
                    "pulang" => now()
                ]);
                return response()->json([
                    "status" => 'anda berhasil absensi pulang'
                ]);
            }
        } else if ($presensiHariIni) {
            if ($now->lessThan($jamPulang) && $now->greaterThan(Carbon::createFromTime('06', '00', '00'))) {
                return response()->json([
                    "status" => 'anda telah absensi datang' //status diganti ke return view blade
                ]);
            } else {
                return response()->json([
                    'status' => 'anda'
                ]);
            }
        }

        return response()->json([
            "status" => 'invalid'
        ]);


        //* kebutuhan testing saja

        //  else if ($now > $jamPulang && $now->lessThan(Carbon::createFromTime('18', '00', '00'))){

        //     }

        // Presensi::create([
        //     'instansi_id' => $request->instansi_id,
        //     "user_id" => 3,
        //     "datang" => Carbon::now(),
        //     "pulang" => Carbon::now(),
        //     "status" => 'hadir',
        //     'tanggal' => Carbon::parse('19 August 2025')->toDateString(),
        //     'akurasi' => $request->akurasi,
        //     'userAgent' => $request->userAgent()
        // ]);

        // return response()->json([
        //     "instansi" => $request->instansi_id,
        //     "akurasi" => $request->akurasi,
        //     "userAgent" => $request->userAgent
        // ]);


    }
}
