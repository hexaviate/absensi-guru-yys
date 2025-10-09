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
        $admin = User::create([
            "name" => 'Pak Admin',
            'telp' => '081',
            'username' => 'admin1',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ]);
        $admin->assignRole('admin_yayasan');
        $admin->instansi()->attach([1, 2, 3, 4, 5, 6, 7]);


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

        //?Admin Yayasan
        User::create([
            "name" => 'nirmala dewi',
            'telp' => '081',
            'username' => 'nirmala',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('admin_yayasan')->instansi()->attach([1, 2, 3, 4, 5, 6]);

        User::create([
            "name" => 'joko wiratama',
            'telp' => '081',
            'username' => 'joko',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('admin_yayasan')->instansi()->attach([1, 2, 3, 4, 5, 6]);

        User::create([
            "name" => 'citra handayani',
            'telp' => '081',
            'username' => 'citra',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('admin_yayasan')->instansi()->attach([1, 2, 3, 4, 5, 6]);

        //!*tenaga pendidik
        //MI
        User::create([
            "name" => 'Anindya Putri',
            'telp' => '081',
            'username' => 'anindya',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('tenaga_pendidik')->instansi()->attach([1]);

        User::create([
            "name" => 'yudha pratama',
            'telp' => '081',
            'username' => 'yudha',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('tenaga_pendidik')->instansi()->attach([1]);

        //TK
        User::create([
            "name" => 'kartika sari',
            'telp' => '081',
            'username' => 'kartika',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('tenaga_pendidik')->instansi()->attach([5]);

        User::create([
            "name" => 'rangga kusuma',
            'telp' => '081',
            'username' => 'rangga',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('tenaga_pendidik')->instansi()->attach([5]);

        //MTS
        User::create([
            "name" => 'wulan puspita',
            'telp' => '081',
            'username' => 'wulan',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('tenaga_pendidik')->instansi()->attach([2]);

        User::create([
            "name" => 'satria nugroho',
            'telp' => '081',
            'username' => 'satria',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('tenaga_pendidik')->instansi()->attach([2]);

        //MA
        User::create([
            "name" => 'intan maharani',
            'telp' => '081',
            'username' => 'intan',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('tenaga_pendidik')->instansi()->attach([4]);

        User::create([
            "name" => 'arya wicaksana',
            'telp' => '081',
            'username' => 'arya',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('tenaga_pendidik')->instansi()->attach([4]);

        //SMK
        User::create([
            "name" => 'cempaka ayuningtyas',
            'telp' => '081',
            'username' => 'cempaka',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('tenaga_pendidik')->instansi()->attach([3]);

        User::create([
            "name" => 'rama aditya',
            'telp' => '081',
            'username' => 'rama',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('tenaga_pendidik')->instansi()->attach([3]);

        //Patta
        User::create([
            "name" => 'ratih anggraini',
            'telp' => '081',
            'username' => 'ratih',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('tenaga_pendidik')->instansi()->attach([3]);

        User::create([
            "name" => 'galih prasetyo',
            'telp' => '081',
            'username' => 'galih',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('tenaga_pendidik')->instansi()->attach([3]);

        //?OPERATOR

        User::create([
            "name" => 'dwi handoko',
            'telp' => '081',
            'username' => 'dwi',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('operator_instansi')->instansi()->attach([1]);

        User::create([
            "name" => 'siti rahmawati',
            'telp' => '081',
            'username' => 'siti',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('operator_instansi')->instansi()->attach([1]);

        //TK
        User::create([
            "name" => 'maya lestari',
            'telp' => '081',
            'username' => 'maya',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('operator_instansi')->instansi()->attach([5]);

        User::create([
            "name" => 'bima pradana',
            'telp' => '081',
            'username' => 'bima',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('operator_instansi')->instansi()->attach([5]);

        //MTS
        User::create([
            "name" => 'surya kurniawan',
            'telp' => '081',
            'username' => 'surya',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('operator_instansi')->instansi()->attach([2]);

        User::create([
            "name" => 'lia anggun',
            'telp' => '081',
            'username' => 'lia',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('operator_   nsi')->instansi()->attach([2]);

        //MA
        User::create([
            "name" => 'dini rahmawati',
            'telp' => '081',
            'username' => 'dini',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('operator_instansi')->instansi()->attach([4]);

        User::create([
            "name" => 'farhan nugraha',
            'telp' => '081',
            'username' => 'farhan',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('operator_instansi')->instansi()->attach([4]);

        //SMK
        User::create([
            "name" => 'agus setiawan',
            'telp' => '081',
            'username' => 'agus',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('operator_instansi')->instansi()->attach([3]);

        User::create([
            "name" => 'melati paramita',
            'telp' => '081',
            'username' => 'melati',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('operator_instansi')->instansi()->attach([3]);

        //Patta
        User::create([
            "name" => 'laras permata',
            'telp' => '081',
            'username' => 'laras',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('operator_instansi')->instansi()->attach([3]);

        User::create([
            "name" => 'rizky firmansyah',
            'telp' => '081',
            'username' => 'rizky',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ])->assignRole('operator_instansi')->instansi()->attach([3]);
    }
}
