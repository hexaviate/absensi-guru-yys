<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\User;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $tapelId = 1; // 2025/2026
        $instansiId = 5; // SMK Salafiyah
        $hariList = ['Sabtu', 'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis'];

        // Ambil semua user SMK Salafiyah kecuali user id=1 (Pak Admin)
        $users = User::whereHas('instansi', function ($q) use ($instansiId) {
            $q->where('instansi_id', $instansiId);
        })->where('id', '!=', 1)->get();

        foreach ($users as $user) {
            foreach ($hariList as $hari) {
                Jadwal::create([
                    'tapel_id' => $tapelId,
                    'instansi_id' => $instansiId,
                    'user_id' => $user->id,
                    'hari' => $hari,
                    'datang' => '07:30:00',
                    'pulang' => '14:00:00',
                ]);
            }
        }
    }
}
