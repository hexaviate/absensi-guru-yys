<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory(2)->create();

        $this->call([
            RolePermissionSeeder::class,
            InstansiSeeder::class,
            UserSeeder::class,
            TapelSeeder::class,
            JadwalSeeder::class
        ]);

        // $admin = User::factory()->make([
        //     "name" => 'Pak Admin',
        //     'telp' => '081',
        //     'username' => 'admin1',
        //     'password' => '123',
        //     'uid_rfid' => '1234',
        //     'foto' => 'admin.jpg',
        // ]);
        // $admin->assignRole('admin_yayasan');

        // $operator = User::factory()->make([
        //     "name" => 'Pak Operator',
        //     "telp" => '082',
        //     'username' => 'operator',
        //     'password' => '123',
        //     'uid_rfid' => '12345',
        //     'foto' => 'operator.jpg'
        // ]);
        // $operator->assignRole('operator_instansi');

        // $pendidik = User::factory()->make([
        //     "name" => 'Bu Pendidik',
        //     "telp" => '083',
        //     "username" => 'pendidik',
        //     'password' => '123',
        //     'uid_rfid' => '132',
        //     "foto" => 'pendidik.jpg'
        // ]);
        // $pendidik->assignRole('tenaga_pendidik');

        // $pendidikan = User::factory()->make([
        //     "name" => 'Bu Pendidikan',
        //     'telp' => '085',
        //     'username' => 'pendidikan',
        //     'password' => '123',
        //     "uid_rfid" => '145',
        //     "foto" => 'pendidikan.jpg'
        // ]);
        // $pendidikan->assignRole('tenaga_kependidikan');
    }
}
