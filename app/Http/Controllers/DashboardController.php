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

        if (!$user->role == 'operator_instansi') {
            return redirect()->route('login')->with('error', "anda tidak punya akses");
        }

        $totalGuruInstansi = User::where('instansi_id', $user->instansi_id)->count();
        $guruHadirHariIni = Presensi::where('instansi_id', $user->instansi_id)->whereDate('created_at', today())->latest()->get();
        $totalGuruIzin = Izin::where('instansi_id', $user->instansi_id)->where('status', 'diterima')->whereDate('created_at', today())->count();

        return view('dashboard.main', compact('totalGuruInstansi', 'guruHadirHariIni', 'totalGuruIzin', 'user'));
        // $guruBelumHadir = User::
    }
}
