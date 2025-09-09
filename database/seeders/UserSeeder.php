<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            "name" => 'Pak Admin',
            'telp' => '081',
            'username' => 'admin1',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ]);
        $admin->assignRole('admin_yayasan');
        $admin->instansi()->attach([1, 2, 3]);


        $operator = User::create([
            "name" => 'Pak Operator',
            "telp" => '082',
            'username' => 'operator',
            'password' => '123',
            'foto_presensi' => 'operator.jpg',
            'foto' => 'operator.jpg'
        ]);
        $operator->assignRole('operator_instansi');
        $operator->instansi()->attach([1, 2, 3]);


        $pendidik = User::create([
            "name" => 'Bu Pendidik',
            "telp" => '083',
            "username" => 'pendidik',
            'password' => '123',
            'foto_presensi' => 'pendidik.jpg',
            "foto" => 'pendidik.jpg'
        ]);
        $pendidik->assignRole('tenaga_pendidik');
        $pendidik->instansi()->attach([1, 2, 3]);

        $pendidikan = User::create([
            "name" => 'Bu Pendidikan',
            'telp' => '085',
            'username' => 'pendidikan',
            'password' => '123',
            'foto_presensi' => 'foto.jpg',
            "foto" => 'pendidikan.jpg'
        ]);
        $pendidikan->assignRole('tenaga_kependidikan');
    }
}
