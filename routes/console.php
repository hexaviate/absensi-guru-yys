<?php

use App\Models\Instansi;
use App\Models\Presensi;
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
    $today = now()->toDateString();

    $users = User::with('instansi')->get();
    foreach ($users as $user) {
        foreach ($user->instansi as $instansi) {
            $presensi = Presensi::where('user_id', $user->id)->where('instansi_id', $instansi->id)->whereDate('tanggal', $today)->exists();

            if (!$presensi) {

                $tidakHadir = TidakHadir::where('user_id', $user->id)->where('instansi_id', $instansi->id)->whereDate('tanggal', $today)->exists();
                if (!$tidakHadir) {
                    TidakHadir::create([
                        "user_id" => $user->id,
                        "instansi_id" => $instansi->id,
                        "tanggal" => now()->toDateString()
                    ]);

                }
            }
        }

    }
})->dailyAt('14:00'); //! waktu nanti disesuaikan

//!perlu disusaikan cron nya jika sudah di server
//*jika running lokal maka pakai "php artisan schedule:work"


// $instansi = Instansi::all();

// foreach ($instansi as $item) {
//     $user = User::all();

//     foreach ($user as $key) {
//         $presensi =
//     }
// }
// foreach ($user as $key) {
//     $presensi = $key->presensi()->tanggal = Carbon::now()->toDateString();

//     if (!$presensi) {
//         TidakHadir::create([
//             ""
//         ]);
//     }
// }

