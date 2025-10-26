<?php

use App\Models\HariLibur;
use App\Models\Instansi;
use App\Models\Jadwal;
use App\Models\Presensi;
use App\Models\Tapel;
use App\Models\TidakHadir;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//KURANG WOII KURANG JADWAL :((((((()))))))
Schedule::call(function () {

    if (now()->isoFormat('dddd') == "Jumat") {
        return;
    }

    $today = now()->toDateString();
    $tapelAktif = Tapel::where('status', "aktif")->first();
    $users = User::with('instansi')->get();
    foreach ($users as $user) {
        foreach ($user->instansi as $instansi) {
            $presensi = Presensi::where('user_id', $user->id)->where('instansi_id', $instansi->id)->whereDate('tanggal', $today)->exists();
            if (!$presensi) {

                if ($user->hasRole('admin_yayasan')) {
                }

                $hariLibur = HariLibur::where('instansi_id', $instansi->id)->where('tanggal', now()->toDateString())->first();

                if ($user->hasRole('tenaga_pendidik')) {
                    $jadwalHariIni = $user->jadwal()->where('hari', now()->isoFormat('dddd'))->where('instansi_id', $instansi->id)->where('tapel_id', $tapelAktif->id)->first();
                    if (!$jadwalHariIni) {
                        continue;
                    }

                    if ($hariLibur) {
                        if ($hariLibur->waktu) {

                            if ($hariLibur->waktu <= $jadwalHariIni->datang) {
                                continue;
                            }

                        } else {
                            continue;
                        }
                    }

                } else {
                    if ($hariLibur) {
                        if (!$hariLibur->waktu) {
                            continue;
                        }
                    }
                }

                $tidakHadir = TidakHadir::where('user_id', $user->id)->where('instansi_id', $instansi->id)->whereDate('tanggal', $today)->exists();
                if (!$tidakHadir) {
                    TidakHadir::create([
                        "user_id" => $user->id,
                        "tapel_id" => $tapelAktif->id,
                        "instansi_id" => $instansi->id,
                        "tanggal" => now()->toDateString()
                    ]);

                }
            }
        }

    }
})->dailyAt('09:01'); //! waktu nanti disesuaikan

//!perlu disusaikan cron nya jika sudah di server
//*jika running lokal maka pakai "php artisan schedule:work"

//Reset Wajib Hadir setiap awal bulan
Schedule::call(function () {
    $users = User::with('instansi', 'jadwal')->get();

    foreach ($users as $user) {
        $user->update([
            "wajib_hadir" => 0
        ]);

        if (!$user->hasRole('tenaga_pendidik')) {
            $hariJumat = Carbon::now()->startOfMonth()
                ->daysUntil(Carbon::now()->endOfMonth())
                ->filter(fn($date) => $date->isFriday())
                ->count();

            $user->update([
                "wajib_hadir" => now()->daysInMonth() - $hariJumat
            ]);
        } else {
            foreach ($user->jadwal as $jadwal) {
                $dayMap = [
                    'Senin' => 'isMonday',
                    'Selasa' => 'isTuesday',
                    'Rabu' => 'isWednesday',
                    'Kamis' => 'isThursday',
                    'Jumat' => 'isFriday',
                    'Sabtu' => 'isSaturday',
                    'Minggu' => 'isSunday',
                ];

                $carbonMethod = $dayMap[$jadwal->hari] ?? null;

                if (!$carbonMethod) {
                    return back()->withErrors(['hari' => 'Hari tidak valid']);
                }

                $wajibHadir = Carbon::now()->startOfMonth()
                    ->daysUntil(Carbon::now()->endOfMonth())
                    ->filter(fn($date) => $date->$carbonMethod())
                    ->count();

                $user->update([
                    "wajib_hadir" => $user->wajib_hadir + $wajibHadir
                ]);
            }
        }
    }
})->dailyAt('15:23');
//diganti menjadi setiap awal bulan
