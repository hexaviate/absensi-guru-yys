<?php

namespace Database\Seeders;

use App\Models\Tapel;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use App\Models\Presensi;
use App\Models\User;
use App\Models\Instansi;
use Carbon\Carbon;

class PresensiSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::role('tenaga_pendidik')->get(); // hanya guru/pendidik
        $faker = \Faker\Factory::create('id_ID');

        for ($i = 0; $i < 200; $i++) {
            $user = $users->random();
            $instansiId = $user->instansi->first()->id ?? Instansi::inRandomOrder()->first()->id;

            // random tanggal antara 1 Juli – 21 Sept 2025
            // $tanggal = Carbon::create(2025, 7, 1)->addDays(rand(0, Carbon::create(2025, 9, 21)->diffInDays(Carbon::create(2025, 7, 1))));

            $period = CarbonPeriod::create('2025-10-05', '2025-10-17')->toArray();

            // ambil index random dari array period
            $randIndex = array_rand($period);
            $tanggal = $period[$randIndex];

            echo $tanggal->toDateString();

            // jam datang & pulang
            $datang = Carbon::createFromTime(rand(6, 8), rand(0, 59));
            $pulang = (clone $datang)->addHours(rand(4, 6));
            $tapel = Tapel::where('status', 'aktif')->first();

            Presensi::create([
                'instansi_id' => $instansiId,
                'user_id' => $user->id,
                "tapel_id" => $tapel->id,
                'datang' => $datang->format('H:i:s'),
                'pulang' => $pulang->format('H:i:s'),
                'izin_id' => null,
                'tanggal' => $tanggal->toDateString(),
                'status' => rand(0, 5) === 1 ? 'izin' : 'hadir',
                'akurasi' => $faker->randomFloat(2, 70, 99) . '%',
                'keterangan' => 'hadir',
                'userAgent' => $faker->randomElement(['Android', 'iPhone', 'Windows', 'MacBook']),
            ]);
        }
    }
}
