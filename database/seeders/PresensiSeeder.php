<?php

namespace Database\Seeders;

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

        for ($i = 0; $i < 44; $i++) {
            $user = $users->random();
            $instansiId = $user->instansi->first()->id ?? Instansi::inRandomOrder()->first()->id;

            // random tanggal antara 1 Juli – 21 Sept 2025
            $tanggal = Carbon::create(2025, 7, 1)->addDays(rand(0, Carbon::create(2025, 9, 21)->diffInDays(Carbon::create(2025, 7, 1))));

            // jam datang & pulang
            $datang = Carbon::createFromTime(rand(6, 8), rand(0, 59));
            $pulang = (clone $datang)->addHours(rand(4, 6));

            Presensi::create([
                'instansi_id' => $instansiId,
                'user_id'     => $user->id,
                'datang'      => $datang->format('H:i:s'),
                'pulang'      => $pulang->format('H:i:s'),
                'bukti_izin'  => rand(0, 5) === 1 ? 'izin.jpg' : null,
                'tanggal'     => $tanggal->toDateString(),
                'status'      => rand(0, 5) === 1 ? 'izin' : 'hadir',
                'akurasi'     => $faker->randomFloat(2, 70, 99) . '%',
                'userAgent'   => $faker->randomElement(['Android', 'iPhone', 'Windows', 'MacBook']),
            ]);
        }
    }
}
