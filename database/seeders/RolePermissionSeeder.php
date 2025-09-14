<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //crud
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage instansi']);
        Permission::create(['name' => 'manage presensi']);
        Permission::create(['name' => 'manage kaldik']);
        Permission::create(['name' => 'manage tapel']);
        Permission::create(['name' => 'manage hari_libur']);
        Permission::create(['name' => 'manage jadwal']);

        //hanya view saja. apabila sudah mempunyai permission manage maka tidak usah diberi permission view
        Permission::create(['name' => 'view all users']);
        Permission::create(['name' => 'view all jadwal']);
        Permission::create(['name' => 'view all presensi']);
        Permission::create(['name' => 'view all instansi']);
        Permission::create(['name' => 'view all kaldik']);
        Permission::create(['name' => 'view all tapel']);
        Permission::create(['name' => 'view all hari_libur']);

        //permission view tapi hanya yang dimiliki oleh user. contoh: user 1 hanya bisa melihat jadwalnya dia sendiri dan tidak bisa melihat jadwal user lain
        Permission::create(['name' => 'view self profile']);
        Permission::create(['name' => 'view self riwayat absen']);
        Permission::create(['name' => 'view self jadwal']);
        Permission::create(['name' => 'presensi']);


        //lain lain
        Permission::create(['name' => 'rekap presensi']);


        $permission = Permission::all();

        $admin = Role::create(['name' => 'admin_yayasan']);
        $operator = Role::create(['name' => 'operator_instansi']);
        $pendidik = Role::create(['name' => 'tenaga_pendidik']);
        $kependidikan = Role::create(['name' => 'tenaga_kependidikan']);

        $admin->syncPermissions($permission);
        $operator->givePermissionTo(['rekap presensi', 'manage presensi', 'manage jadwal', 'manage hari_libur', 'view all users']);
        $pendidik->givePermissionTo(['view self jadwal', 'view self riwayat absen']);
        $kependidikan->givePermissionTo(['view self riwayat absen']);


        // $userAdmin = User::factory()->make([
        //     "name" => 'Pak Admin',
        //     'telp' => '081',
        //     'username' => 'admin1',
        //     'password' => '123',
        //     'uid_rfid' => '1234',
        //     'foto' => 'admin.jpg',
        // ]);
        // $userAdmin->assignRole('admin_yayasan');

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
