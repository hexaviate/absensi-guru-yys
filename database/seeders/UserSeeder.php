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
        // $faker = Faker

        $admin = User::create([
            "nomor_induk_yayasan" => "0982",
            "name" => 'Pak Admin',
            'telp' => '081',
            'username' => 'admin1',
            'password' => '123',
            'foto_presensi' => 'pegawai.jpg',
            'foto' => 'pegawai.jpg',
        ]);
        $admin->assignRole('admin_yayasan');
        $admin->instansi()->attach([1, 2, 3, 4, 5, 6, 7]);


        $users = [
            ['Erni Sofa Nugraha, S.Pd.', 'tenaga_pendidik'],
            ['H. Ubaidillah Wahab, SH.,M.Si', 'tenaga_pendidik'],
            ['Dra. Hj. Umi Atiyah', 'tenaga_pendidik'],
            ['Yeni Dewi Sulihtiyaningrum, S.Pd.', 'tenaga_pendidik'],
            ['Endang Sulastri, S.Sos.', 'tenaga_pendidik'],
            ['Ah. Dainuri, S.Pd.I', 'tenaga_pendidik'],
            ['Maulidah Rohmah, S.Pd.', 'tenaga_pendidik'],
            ['Sri Wahyuni, SE.', 'tenaga_pendidik'],
            ['Atik Nur Fatkiyah, S.Pd.', 'tenaga_pendidik'],
            ['Mochammad Chamdan Yuwafi, S.ST.', 'tenaga_pendidik'],
            ['Moh Humam Wafi, S.Kom.', 'tenaga_pendidik'],
            ['Muhammad Athoillah, SE., M.Pd.', 'tenaga_pendidik'],
            ['Dra. Siti Rusiana', 'tenaga_pendidik'],
            ['Bintari Kustianingrum, A.Md.', 'tenaga_pendidik'],
            ['Khoridah Hanim, S.Pd.', 'tenaga_pendidik'],
            ['Faroh Luluatul Afidah, S.Pd.', 'tenaga_pendidik'],
            ['Vitna Puji Lestari, S.Tr.Bns.', 'tenaga_pendidik'],
            ['Ir. Farid Helmi', 'tenaga_pendidik'],
            ['Mohamad Aris Fuad, S.Kom., M.Pd.', 'tenaga_pendidik'],
            ['Eko Ardhiyanto, A.Md.', 'tenaga_pendidik'],
            ['Irham Abdul Jalil, S.Kom.', 'tenaga_pendidik'],
            ['Laili Mushoffa, S.Pd.', 'tenaga_pendidik'],
            ['Agus Badruzzaman', 'tenaga_pendidik'],
            ['Ahsin, A.Md.', 'tenaga_pendidik'],
            ['Ana Suryani Tohir, S.Pd., M.Pd.', 'tenaga_pendidik'],
            ['Angga Widhi Pratama, S.Pd.', 'tenaga_pendidik'],
            ['Cipto Arief Leksono Aji, S.Pd.', 'tenaga_pendidik'],
            ['Drs. H. Abdul Kafi, M.Si.', 'tenaga_pendidik'],
            ['Irna Baroroh, S.Pd.I.', 'tenaga_pendidik'],
            ['Khida Efti Nely Ifada, S.Pd.', 'tenaga_pendidik'],
            ['Mukhammad Jaza\'us Salam, S.Pd.I.', 'tenaga_pendidik'],
            ['Mohammad Misbachul Huda, S.Or.', 'tenaga_pendidik'],
            ['Sri Wahyuni, S.Pd.', 'tenaga_pendidik'],
            ['Udkhiana, S.Pd.', 'tenaga_pendidik'],
            ['Hilma Huril Aini, S.Pd.', 'tenaga_pendidik'],
            ['Eni Mufarichah, S.Pd.', 'tenaga_kependidikan'],
            ['Aziz Muslim, S.Pd.I', 'tenaga_pendidik'],
            ['Muhammad Fahmi ‘Ainunnajib', 'operator_instansi'],
            ['Ahmad Hanif Dzikron', 'tenaga_kependidikan'],
            ['Nabila Putri Agustin, S.Pd.', 'tenaga_kependidikan'],
            ['Ahmad Saiful', 'tenaga_kependidikan'],
            ['Faridatun Nisa\'', 'tenaga_kependidikan'],
        ];

        foreach ($users as $index => [$name, $role]) {
            $user = User::create([
                'nomor_induk_yayasan' => fake()->unique()->randomNumber(5, true),
                'name' => $name,
                'telp' => '08' . rand(100000000, 999999999),
                'username' => strtolower(str_replace(['.', ',', ' ', '\'', '’'], '', explode(' ', $name)[0])) . rand(10, 99),
                'password' => Hash::make('123456'),
                'foto_presensi' => 'pegawai.jpg',
                'jarak_tempuh' => rand(1, 15),
            ]);

            $user->assignRole($role);
            $user->instansi()->attach([5]); // ID 5 = SMK Salafiyah
        }
    }
}
