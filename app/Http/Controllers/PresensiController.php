<?php

namespace App\Http\Controllers;

use App\Http\Resources\JadwalResource;
use App\Models\Jadwal;
use App\Models\Presensi;
use App\Models\Tapel;
use App\Models\TidakHadir;
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
        $user = auth()->user();
        $now = Carbon::now();
        $hariIni = Carbon::now()->isoFormat('dddd');
        $tapelAktif = Tapel::where('status', "aktif")->first();

        $jadwal = Jadwal::where('user_id', $user->id)
            ->where('instansi_id', $request->instansi_id)
            ->where('hari', $hariIni)
            ->where('tapel_id', $tapelAktif->id)
            ->first();

        $presensiHariIni = Presensi::where('user_id', $user->id)
            ->where('instansi_id', $request->instansi_id)
            ->whereDate('created_at', $now->toDateString())
            ->first();

        $tidakHadir = TidakHadir::where('tapel_id', $tapelAktif->id)->where('user_id', $user->id)->where('instansi_id', $request->instansi_id)->first();
        if ($tidakHadir) {
            return redirect()->back()->with('error', 'Anda tidak hadir hari ini, anda tidak bisa absen');
        }

        // Tidak ada jadwal
        if (!$jadwal) {
            if (!$presensiHariIni) {
                if ($now->greaterThan(Carbon::createFromTime('06', '00', '00')) && $now->lessThan(Carbon::createFromTime('12', '00', '00'))) {
                    Presensi::create([
                        'instansi_id' => $request->instansi_id,
                        "user_id" => $user->id,
                        "tapel_id" => $tapelAktif->id,
                        "datang" => Carbon::now(),
                        "status" => 'hadir',
                        'tanggal' => Carbon::now()->toDateString(),
                        "keterangan" => "Presensi belum lengkap, belum presensi pulang",
                        'akurasi' => $request->akurasi,
                        'userAgent' => $request->userAgent()
                    ]);

                    $presensiHariIni = Presensi::where('user_id', $user->id)
                        ->where('instansi_id', $request->instansi_id)
                        ->whereDate('created_at', $now->toDateString())
                        ->first();

                    return response()->json([
                        "status" => 'anda berhasil absensi tidak ada jadwal hari ini',
                        "datang" => $presensiHariIni->datang ?? null,
                        "pulang" => $presensiHariIni->pulang ?? null
                    ]);
                } elseif ($now->greaterThan(Carbon::createFromTime('12', '00', '00')) && $now->lessThan(Carbon::createFromTime('18', '00', '00'))) {
                    Presensi::create([
                        'instansi_id' => $request->instansi_id,
                        "user_id" => $user->id,
                        "tapel_id" => $tapelAktif->id,
                        "pulang" => Carbon::now(),
                        "status" => 'hadir',
                        'tanggal' => Carbon::now()->toDateString(),
                        "keterangan" => "Absensi tidak lengkap, tidak mempunyai absensi datang",
                        'akurasi' => $request->akurasi,
                        'userAgent' => $request->userAgent()
                    ]);

                    $presensiHariIni = Presensi::where('user_id', $user->id)
                        ->where('instansi_id', $request->instansi_id)
                        ->whereDate('created_at', $now->toDateString())
                        ->first();

                    return response()->json([
                        "status" => 'anda berhasil absensi pulang dan tidak ada jadwal hari ni',
                        "datang" => $presensiHariIni->datang ?? null,
                        "pulang" => $presensiHariIni->pulang ?? null
                    ]);
                } else {
                    return response()->json([
                        "status" => 'absen belum dibuka',
                        "datang" => null,
                        "pulang" => null
                    ]);
                }
            } else if (!$presensiHariIni->pulang) {
                if ($now->greaterThan(Carbon::createFromTime('12', '00', '00')) && $now->lessThan(Carbon::createFromTime('18', '00', '00'))) {
                    $presensiHariIni->update([
                        "pulang" => now(),
                        "keterangan" => "Presensi lengkap",
                    ]);

                    $presensiHariIni = Presensi::where('user_id', $user->id)
                        ->where('instansi_id', $request->instansi_id)
                        ->whereDate('created_at', $now->toDateString())
                        ->first();

                    return response()->json([
                        "status" => 'anda berhasil absensi pulang',
                        "datang" => $presensiHariIni->datang ?? null,
                        "pulang" => $presensiHariIni->pulang ?? null,
                    ]);
                }
            } else if ($presensiHariIni) {
                if ($now->greaterThan(Carbon::createFromTime('06', '00', '00')) && $now->lessThan(Carbon::createFromTime('12', '00', '00'))) {
                    return response()->json([
                        "status" => 'anda telah absensi datang',
                        "datang" => $presensiHariIni->datang ?? null,
                        "pulang" => $presensiHariIni->pulang ?? null
                    ]);
                } else {
                    return response()->json([
                        'status' => 'anda tidak ada absen dan diluar jadwal pulang ataupun datang',
                        "datang" => $presensiHariIni->datang ?? null,
                        "pulang" => $presensiHariIni->pulang ?? null
                    ]);
                }
            }
        }

        $jamDatang = Carbon::parse($jadwal->datang);
        $jamPulang = Carbon::parse($jadwal->pulang);

        // Jika belum ada presensi
        if (!$presensiHariIni) {
            if ($now->lessThan($jamPulang) && $now->greaterThan(Carbon::createFromTime('6', '00', '00'))) {
                Presensi::create([
                    'instansi_id' => $request->instansi_id,
                    "user_id" => $user->id,
                    "tapel_id" => $tapelAktif->id,
                    "datang" => Carbon::now(),
                    "status" => 'hadir',
                    "keterangan" => "Presensi belum lengkap, belum presensi pulang",
                    'tanggal' => Carbon::now()->toDateString(),
                    'akurasi' => $request->akurasi,
                    'userAgent' => $request->userAgent()
                ]);

                $presensiHariIni = Presensi::where('user_id', $user->id)
                    ->where('instansi_id', $request->instansi_id)
                    ->whereDate('created_at', $now->toDateString())
                    ->first();

                return response()->json([
                    "status" => 'anda berhasil absensi',
                    "datang" => $presensiHariIni->datang ?? null,
                    "pulang" => $presensiHariIni->pulang ?? null
                ]);
            } elseif ($now->greaterThan($jamPulang) && $now->lessThan(Carbon::createFromTime('18', '00', '00'))) {
                Presensi::create([
                    'instansi_id' => $request->instansi_id,
                    "user_id" => $user->id,
                    "tapel_id" => $tapelAktif->id,
                    "pulang" => Carbon::now(),
                    "status" => 'hadir',
                    "keterangan" => "Absensi tidak lengkap, tidak mempunyai absensi datang",
                    'tanggal' => Carbon::now()->toDateString(),
                    'akurasi' => $request->akurasi,
                    'userAgent' => $request->userAgent()
                ]);

                $presensiHariIni = Presensi::where('user_id', $user->id)
                    ->where('instansi_id', $request->instansi_id)
                    ->whereDate('created_at', $now->toDateString())
                    ->first();

                return response()->json([
                    "status" => 'anda berhasil absensi pulang',
                    "datang" => $presensiHariIni->datang ?? null,
                    "pulang" => $presensiHariIni->pulang ?? null
                ]);
            } else {
                return response()->json([
                    "status" => 'absen belum dibuka',
                    "datang" => null,
                    "pulang" => null
                ]);
            }
        } else if (!$presensiHariIni->pulang) {
            if ($now->greaterThan($jamPulang) && $now->lessThan(Carbon::createFromTime('18', '00', '00'))) {
                $presensiHariIni->update([
                    "pulang" => now(),
                    "keterangan" => "Presensi lengkap",
                ]);

                $presensiHariIni = Presensi::where('user_id', $user->id)
                    ->where('instansi_id', $request->instansi_id)
                    ->whereDate('created_at', $now->toDateString())
                    ->first();

                return response()->json([
                    "status" => 'anda berhasil absensi pulang',
                    "datang" => $presensiHariIni->datang ?? null,
                    "pulang" => $presensiHariIni->pulang ?? null
                ]);
            }
        } else if ($presensiHariIni) {
            if ($now->lessThan($jamPulang) && $now->greaterThan(Carbon::createFromTime('06', '00', '00'))) {
                return response()->json([
                    "status" => 'anda telah absensi datang',
                    "datang" => $presensiHariIni->datang ?? null,
                    "pulang" => $presensiHariIni->pulang ?? null
                ]);
            } else {
                return response()->json([
                    'status' => 'anda telah absen hari ini',
                    "datang" => $presensiHariIni->datang ?? null,
                    "pulang" => $presensiHariIni->pulang ?? null
                ]);
            }
        } else if ($now->greaterThan(Carbon::createFromTime('14', '00', '00')) && !$presensiHariIni) {
            return back()->with('error', 'Anda tidak bisa melakukan presensi');
        }

        return response()->json([
            "status" => 'invalid',
            "datang" => null,
            "pulang" => null
        ]);
    }
}
