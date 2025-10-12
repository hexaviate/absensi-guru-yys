<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\Izin;
use App\Models\Presensi;
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

        $persentaseHadir = $totalGuruInstansi > 0
            ? round(($totalGuruHadir / $totalGuruInstansi) * 100, 2)
            : 0;

        $persentaseIzin = $totalGuruInstansi > 0
            ? round(($totalGuruIzin / $totalGuruInstansi) * 100, 2)
            : 0;

        return view('dashboard.operator', compact('totalGuruInstansi', 'guruHadirHariIni', 'totalGuruIzin', 'user', 'totalGuruHadir', 'persentaseHadir', 'persentaseIzin'));
    }

    public function adminDashboard()
    {
        $user = auth()->user();
        $instansi = Instansi::all();
        if (!$user) {
            return redirect()->route('login')->with('error', "anda belum login");
        }

        // if (!$user->role == 'operator_instansi') {
        //     return redirect()->route('login')->with('error', "anda tidak punya akses");
        // }

        //Total guru setiap instansi
        $totalGuruPerInstansi = Instansi::withCount('user')->get();



        // $totalGuruInstansi = User::where('instansi_id', $instansi->id)->count();
        $totalSemuaGuru = User::with('instansi')->count();
        // dd(User::with('instansi')->count());
        $guruHadirHariIni = Presensi::where('status', 'hadir')->where('tanggal', today()->toDateString())->limit('5')->latest()->get();
        $totalGuruHadirHariIni = Presensi::where('status', 'hadir')->where('tanggal', today()->toDateString())->latest()->count();
        // dd($guruHadirHariIni);
        $totalGuruIzin = Izin::where('status', 'diterima')->whereDate('created_at', today())->count();

        return view('dashboard.yys', compact('totalGuruInstansi', 'guruHadirHariIni', 'totalGuruIzin', 'user', 'totalGuruPerInstansi', 'totalSemuaGuru'));
    }

    public function userDashboard()
    {
        $user = auth()->user();


        if (!$user) {
            return redirect()->route('login')->with('error', "anda belum login");
        }
        $presensiHariIni = Presensi::where('user_id', $user->id)->whereDate('tanggal', today()->toDateString())->get();
        $jadwalHariIni = $user->jadwal()->where('hari', now()->isoFormat('dddd'))->get();

        $izinBulanIni = $user->izin()->whereMonth('created_at', now()->month)->count();
        $presensiMingguIni = $user->presensi()->whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->get();
        $instansi = $user->instansi->get();
        return view('dashboard.user', compact('presensiHariIni', 'jadwalHariIni', 'instansi', 'izinBulanIni'));
    }
}

// ini dari dsaya
