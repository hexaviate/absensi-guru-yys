<?php

namespace App\Http\Controllers;

use App\Models\HariLibur;
use App\Models\Instansi;
use App\Models\Izin;
use App\Models\Presensi;
use App\Models\Tapel;
use App\Models\TidakHadir;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function operatorDashboard()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', "anda belum login");
        }
        $instansi = $user->instansi->first();

        // if (!$user->role == 'operator_instansi') {
        //     return redirect()->route('login')->with('error', "anda tidak punya akses");
        // }

        // $totalGuruInstansi = User::where('instansi_id', $instansi->id)->count();
        $totalGuruInstansi = $instansi->user->count();
        $guruHadirHariIni = Presensi::where('instansi_id', $instansi->id)->where('status', 'hadir')->where('tanggal', today()->toDateString())->limit('5')->latest()->get();
        $totalGuruIzin = Izin::where('instansi_id', $instansi->id)->where('status', 'diterima')->whereDate('created_at', today())->count();
        $totalGuruHadir = Presensi::where('instansi_id', $instansi->id)->where('status', 'hadir')->where('tanggal', today()->toDateString())->count();
        $totalGuruAlpha = TidakHadir::where('tanggal', now()->toDateString())->where('instansi_id', $instansi->id)->where('tapel_id', Tapel::where('status', 'aktif')->first()->id)->count();

        $persentaseHadir = $totalGuruInstansi > 0
            ? round(($totalGuruHadir / $totalGuruInstansi) * 100, 2)
            : 0;

        $persentaseIzin = $totalGuruInstansi > 0
            ? round(($totalGuruIzin / $totalGuruInstansi) * 100, 2)
            : 0;

        $persentaseAlpha = $totalGuruInstansi > 0
            ? round(($totalGuruAlpha / $totalGuruInstansi) * 100, 2)
            : 0;


        return view('dashboard.operator', compact('totalGuruInstansi', 'guruHadirHariIni', 'totalGuruIzin', 'user', 'totalGuruHadir', 'persentaseHadir', 'persentaseAlpha', 'persentaseIzin', 'totalGuruAlpha'));
    }

    public function adminDashboard()
    {
        $user = auth()->user();
        $instansi = Instansi::all();
        if (!$user) {
            return redirect()->route('login')->with('error', "anda belum login");
        }

        if (!$user->hasRole('admin_yayasan')) {
            return redirect()->route('login')->with('error', "anda tidak punya akses");
        }

        $totalGuruTidakHadirHariIni = TidakHadir::where('tanggal', now()->toDateString())->withCount('user');
        //Total guru setiap instansi
        $totalGuruPerInstansi = Instansi::withCount('user')->get();
        // total seluruh guru di yayasan
        $totalSemuaGuru = User::all()->count();
        //total guru yang hadir hari ini (dilimit 5 data)
        $guruHadirHariIni = Presensi::where('status', 'hadir')->where('tanggal', today()->toDateString())->limit('5')->latest()->get();
        //total semua guru yang hadir hari ini
        $totalGuruHadirHariIni = Presensi::where('status', 'hadir')->where('tanggal', today()->toDateString())->latest()->count();
        //total guru yang izin hari ini
        $totalGuruIzin = Izin::where('status', 'diterima')->whereDate('created_at', today())->count();

        $chartData = [];

        $statistikHariIni = [
            'hadir' => $totalGuruHadirHariIni,
            'izin' => $totalGuruIzin,
            'tidak_hadir' => $totalGuruTidakHadirHariIni,
            'total' => $totalSemuaGuru,
        ];

        for ($i = 13; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i)->toDateString();

            // Hitung guru hadir
            // MASALAH: Hanya menghitung yang status = 'hadir'
            $hadir = Presensi::whereDate('tanggal', $tanggal)
                ->where('status', 'hadir')
                ->where(function ($query) {
                    $query->whereNotNull('datang')->orWhereNotNull('pulang');
                })
                ->distinct('id')
                ->count('id');

            // Hitung guru izin
            $izin = Izin::whereDate('created_at', $tanggal)
                ->where('status', 'diterima')
                ->distinct('id')
                ->count('id');

            // Hitung guru tidak hadir
            $tidakHadir = TidakHadir::whereDate('tanggal', $tanggal)
                ->distinct('id')
                ->count('id');


            $chartData[] = [
                'tanggal' => $tanggal,
                'hadir' => $hadir,
                'izin' => $izin,
                'tidak_hadir' => $tidakHadir,
            ];
        }

        return view('dashboard.yys', compact('user', 'statistikHariIni', 'totalGuruHadirHariIni', 'guruHadirHariIni', 'totalGuruPerInstansi', 'totalSemuaGuru', 'instansi', 'chartData'));
    }

    public function userDashboard()
    {
        $user = auth()->user();


        if (!$user) {
            return redirect()->route('login')->with('error', "anda belum login");
        }
        $presensiHariIni = Presensi::where('user_id', $user->id)->whereDate('tanggal', today()->toDateString())->get();
        $jadwalHariIni = $user->jadwal()->where('hari', now()->isoFormat('dddd'))->where('tapel_id', Tapel::where('status', 'aktif')->first()->id)->get();

        $izinBulanIni = $user->izin()->whereMonth('created_at', now()->month)->count();
        $presensiMingguIni = $user->presensi()->whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->get();
        $presensiBulanIni = $user->presensi()->whereMonth('created_at', now()->month)->count();
        $tidakHadirBulanIni = $user->tidak_hadir()->whereMonth('created_at', now()->month)->count();
        $instansi = $user->instansi()->get();
        $hariIniHariLibur = HariLibur::where('tanggal', now()->toDateString())->get();

        // unutkprogress bar

        $totalHari = 30; // contoh: jumlah hari dalam sebulan

        $persentaseTidakHadir = ($tidakHadirBulanIni / $totalHari) * 100;
        $persentaseHadir = ($presensiBulanIni / $totalHari) * 100;
        $persentaseIzin = ($izinBulanIni / $totalHari) * 100;

        return view('dashboard.user', compact('persentaseIzin','persentaseHadir','persentaseTidakHadir','presensiHariIni', 'jadwalHariIni', 'instansi', 'izinBulanIni', 'presensiMingguIni', 'presensiBulanIni', 'tidakHadirBulanIni', 'hariIniHariLibur'));
    }
}
