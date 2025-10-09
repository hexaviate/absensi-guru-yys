<?php

namespace App\Http\Controllers;

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
        $totalGuruInstansi = $instansi->user->get();
        $guruBelumHadir = User::where('instansi_id', $instansi->id)->whereDoesntHave('presensi', function ($query) {
            $query->whereDate('tanggal', today()->toDateString());
        })->get();
        $guruHadirHariIni = Presensi::where('instansi_id', $instansi->id)->where('status', 'hadir')->where('tanggal', today()->toDateString())->limit('5')->latest()->get();
        // dd($guruHadirHariIni);
        $totalGuruIzin = Izin::where('instansi_id', $instansi->id)->where('status', 'diterima')->whereDate('created_at', today())->count();

        //semua dashboard ad ini
        $presensiHariIni = Presensi::where('user_id', $user->id)->where('instansi_id', $instansi->id)->whereDate('tanggal', today()->toDateString())->first();
        $jadwalHariIni = $user->jadwal()->where('hari', now()->isoFormat('dddd'))->get();

        if (!$presensiHariIni && now() > Carbon::createFromTime('06', '00', '00')) {

            return view('dashboard.operator', compact('totalGuruInstansi', 'guruHadirHariIni', 'totalGuruIzin', 'user', 'guruBelumHadir', 'jadwalHariIni'))->with('error', 'Anda belum melakukan absensi Datang');

        } elseif (!$presensiHariIni->pulang) {

            return view('dashboard.operator', compact('totalGuruInstansi', 'guruHadirHariIni', 'totalGuruIzin', 'user', 'guruBelumHadir', 'presensiHariIni', 'jadwalHariIni'))->with('error', 'Anda belum melakukan absensi Pulang');

        }

        return view('dashboard.operator', compact('totalGuruInstansi', 'guruHadirHariIni', 'totalGuruIzin', 'user', 'guruBelumHadir', 'presensiHariIni', 'jadwalHariIni'));
        // $guruBelumHadir = User::
    }

    public function adminDashboard()
    {
        $user = auth()->user();
        $instansi = $user->instansi->first();
        if (!$user) {
            return redirect()->route('login')->with('error', "anda belum login");
        }

        $presensiHariIni = Presensi::where('user_id', $user->id)->where('instansi_id', $instansi->id)->whereDate('tanggal', today()->toDateString())->get();
        $jadwalHariIni = $user->jadwal()->where('hari', now()->isoFormat('dddd'))->get();
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
