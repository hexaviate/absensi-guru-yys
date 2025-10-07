<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Instansi;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Yayasan (global)
        $admin = User::create([
            'name' => 'Pak Admin',
            'telp' => '081',
            'username' => 'admin1',
            'password' => Hash::make('123'),
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ]);
        $admin->assignRole('admin_yayasan');


        // Per instansi: 1 operator + 10 guru
        foreach (Instansi::all() as $instansi) {
            // operator
            $op = User::create([
                'name' => 'Operator ' . $instansi->nama_instansi,
                'telp' => '08' . rand(11111111, 99999999),
                'username' => 'op_' . strtolower(str_replace(' ', '', $instansi->nama_instansi)),
                'password' => Hash::make('123'),
                'foto_presensi' => 'operator.jpg',
                'foto' => 'operator.jpg',
            ]);
            $op->assignRole('operator_instansi');
            $op->instansi()->attach($instansi->id);


            // 10 guru/tenaga pendidik
            for ($i = 1; $i <= 10; $i++) {
                $guru = User::create([
                    'name' => 'Guru ' . $i . ' ' . $instansi->nama_instansi,
                    'telp' => '08' . rand(11111111, 99999999),
                    'username' => strtolower($instansi->id . '_guru' . $i),
                    'password' => Hash::make('123'),
                    'foto_presensi' => 'guru' . $i . '.jpg',
                    'foto' => 'guru' . $i . '.jpg',
                ]);
                $guru->assignRole('tenaga_pendidik');
                $guru->instansi()->attach($instansi->id);
            }
        }
    }
}
