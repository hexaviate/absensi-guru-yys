<?php

namespace App\Http\Controllers;

use App\Http\Resources\JadwalResource;
use App\Models\Jadwal;
use App\Models\Presensi;
use App\Models\Tapel;
use App\Models\User;
use Carbon\Carbon;
use Http;
use Illuminate\Http\Request;
use function Symfony\Component\Clock\now;

class PresensiController extends Controller
{
    public function viewPresensi()
    {
        // dd(Carbon::now()->isoFormat('dddd'));
        // $instansi = auth()->user()->instansi()->get(['latitude', 'longitude']);
        // return view('tesPresensi.index', compact('instansi'));
        $user = auth()->user();
        $fotoPresensi = $user->foto_presensi;
        $lokasi = $user->instansi()->get(['latitude', 'longitude', 'nama_instansi', 'instansi_id']);
        // $hariIni = Carbon::now()->isoFormat('dddd');
        // dd($hariIni);
        return view('tesPresensi.index', compact('user', 'lokasi', 'fotoPresensi'));

    }

    public function prosesPresensi(Request $request)
    {

        $user = auth()->user(); //diubah ketika final, nanti diisi user id
        $now = Carbon::now();

        //*Mengambil Hari Saat ini
        $hariIni = Carbon::now()->isoFormat('dddd');

        $tapelAktif = Tapel::where('status', "aktif")->first();

        //* mengambil jadwal user dan guru
        $jadwal = Jadwal::where('user_id', $user->id)->where('instansi_id', $request->instansi_id)->where('hari', $hariIni)->where('tapel_id', $tapelAktif->id)->first();

        // Cek apakah user sudah pernah presensi hari ini untuk instansi ini, dengan menggunakan created_at
        $presensiHariIni = Presensi::where('user_id', $user->id)
            ->where('instansi_id', $request->instansi_id)
            ->whereDate('created_at', $now->toDateString())
            ->first();

        $token = '74SPnec8JM2KKXmKDNSz';


        //? tidak ada jadwal
        if (!$jadwal) {
            if (!$presensiHariIni) {
                if ($now->greaterThan(Carbon::createFromTime('06', '00', '00')) && $now->lessThan(Carbon::createFromTime('12', '00', '00'))) {
                    Presensi::create([
                        'instansi_id' => $request->instansi_id,
                        "user_id" => $user->id, //diubah ketika testing final, nanti diisi user id
                        "datang" => Carbon::now(),
                        "status" => 'hadir',
                        'tanggal' => Carbon::now()->toDateString(),
                        'akurasi' => $request->akurasi,
                        'userAgent' => $request->userAgent()
                    ]);

                    // //*Send Message
                    // Http::withOptions(['verify' => false]) // << DISABLE SSL VERIFY
                    //     ->withHeaders(['Authorization' => $token])
                    //     ->asForm()->post('https://api.fonnte.com/send', [
                    //             'target' => '083186180137',
                    //             'message' => "anda telah absen pada $now dan anda tidak punya jadwal hari ini",
                    //         ]);

                    return response()->json([
                        "status" => 'anda berhasil absensi tidak ada jadwal hari ini' //status diganti ke return view blade
                    ]);
                } elseif ($now->greaterThan(Carbon::createFromTime('12', '00', '00')) && $now->lessThan(Carbon::createFromTime('18', '00', '00'))) {
                    Presensi::create([
                        'instansi_id' => $request->instansi_id,
                        "user_id" => $user->id, //diubah ketika testing final, nanti diisi user id
                        "pulang" => Carbon::now(),
                        "status" => 'hadir',
                        'tanggal' => Carbon::now()->toDateString(),
                        'akurasi' => $request->akurasi,
                        'userAgent' => $request->userAgent()
                    ]);



                    return response()->json([
                        "status" => 'anda berhasil absensi pulang dan tidak ada jadwal hari ni' //status diganti ke return view blade
                    ]);
                } else {
                    return response()->json([
                        "status" => 'absen belum dibuka'
                    ]); // status diganti ke return view blade
                }
            } else if (!$presensiHariIni->pulang) {
                // dd($now);
                if ($now->greaterThan(Carbon::createFromTime('12', '00', '00')) && $now->lessThan(Carbon::createFromTime('18', '00', '00'))) {

                    $presensiHariIni->update([
                        "pulang" => now()
                    ]);
                    return response()->json([
                        "status" => 'anda berhasil absensi pulang'
                    ]);
                }
            } else if ($presensiHariIni) {
                if ($now->greaterThan(Carbon::createFromTime('06', '00', '00')) && $now->lessThan(Carbon::createFromTime('12', '00', '00'))) {
                    return response()->json([
                        "status" => 'anda telah absensi datang' //status diganti ke return view blade
                    ]);
                } else {
                    return response()->json([
                        'status' => 'anda tidak ada absen dan diluar jadwal pulang ataupun datamg'
                    ]);
                }
            }
        }

        //mengambil jam datang dan pulang dari jadwal
        $jamDatang = Carbon::parse($jadwal->datang);
        $jamPulang = Carbon::parse($jadwal->pulang);


        // jika belum ada presensi
        if (!$presensiHariIni) {
            if ($now->lessThan($jamPulang) && $now->greaterThan(Carbon::createFromTime('6', '00', '00'))) {
                Presensi::create([
                    'instansi_id' => $request->instansi_id,
                    "user_id" => $user->id, //diubah ketika testing final, nanti diisi user id
                    "datang" => Carbon::now(),
                    "status" => 'hadir',
                    'tanggal' => Carbon::now()->toDateString(),
                    'akurasi' => $request->akurasi,
                    'userAgent' => $request->userAgent()
                ]);

                // //*Send Message
                // $token = '74SPnec8JM2KKXmKDNSz';
                // Http::withOptions(['verify' => false]) // << DISABLE SSL VERIFY
                //     ->withHeaders(['Authorization' => $token])
                //     ->asForm()->post('https://api.fonnte.com/send', [
                //             'target' => '083186180137',
                //             'message' => "Anda berhasil Absen pada hari ini",
                //         ]);

                return response()->json([
                    "status" => 'anda berhasil absensi' //status diganti ke return view blade
                ]);
            } elseif ($now->greaterThan($jamPulang) && $now->lessThan(Carbon::createFromTime('18', '00', '00'))) {
                Presensi::create([
                    'instansi_id' => $request->instansi_id,
                    "user_id" => $user->id, //diubah ketika testing final, nanti diisi user id
                    "pulang" => Carbon::now(),
                    "status" => 'hadir',
                    'tanggal' => Carbon::now()->toDateString(),
                    'akurasi' => $request->akurasi,
                    'userAgent' => $request->userAgent()
                ]);

                return response()->json([
                    "status" => 'anda berhasil absensi pulang' //status diganti ke return view blade
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
