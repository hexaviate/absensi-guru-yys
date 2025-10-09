<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use App\Models\Presensi;
use App\Models\User;
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
        $instansi = $user->instansi->first();
        if (!$user) {
            return redirect()->route('login')->with('error', "anda belum login");
        }

        // if (!$user->role == 'operator_instansi') {
        //     return redirect()->route('login')->with('error', "anda tidak punya akses");
        // }

        // $totalGuruInstansi = User::where('instansi_id', $instansi->id)->count();
        $totalGuruInstansi = $instansi->user->count();
        $guruHadirHariIni = Presensi::where('instansi_id', $instansi->id)->where('status', 'hadir')->where('tanggal', today()->toDateString())->limit('5')->latest()->get();
        // dd($guruHadirHariIni);
        $totalGuruIzin = Izin::where('instansi_id', $instansi->id)->where('status', 'diterima')->whereDate('created_at', today())->count();

        return view('dashboard.yys', compact('totalGuruInstansi', 'guruHadirHariIni', 'totalGuruIzin', 'user'));
    }

    public function userDashboard()
    {
        $user = auth()->user();
        $instansi = $user->instansi->first();
        if (!$user) {
            return redirect()->route('login')->with('error', "anda belum login");
        }
        return view('dashboard.user');
    }
}

// ini dari saya
