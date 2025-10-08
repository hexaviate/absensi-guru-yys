<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instansi;

class InstansiSeeder extends Seeder
{
    public function run(): void
    {

        // 1
        Instansi::create([
            "nama_instansi" => "MI",
            "kepala_instansi" => "Pak Ini",
            "alamat_instansi" => "kajen",
            "telp_instansi" => "081",
            "latitude" => "-6.6116567",
            "longitude" => "111.0661932"
        ]);

        // 2
        Instansi::create([
            "nama_instansi" => "MTS",
            "kepala_instansi" => "Bu Ini",
            "alamat_instansi" => "kajen",
            "telp_instansi" => "081",
            "latitude" => "-6.60795",
            "longitude" => "111.059405"
        ]);

        // 3
        Instansi::create([
            "nama_instansi" => "SMK",
            "kepala_instansi" => "Pak Hamdan",
            "alamat_instansi" => "Tunjungrejo",
            "telp_instansi" => "081",
            "latitude" => "-6.608060",
            "longitude" => "111.059537"
        ]);

        // 4
        Instansi::create([
            "nama_instansi" => "MA",
            "kepala_instansi" => "Pak MA",
            "alamat_instansi" => "Tunjungrejo",
            "telp_instansi" => "081",
            "latitude" => "-6.59299635311",
            "longitude" => "111.0674858040"
        ]);

        // 5
        Instansi::create([
            "nama_instansi" => "TK",
            "kepala_instansi" => "Bu TK",
            "alamat_instansi" => "Tunjungrejo",
            "telp_instansi" => "081",
            "latitude" => "-6.5920099635311",
            "longitude" => "111.067485098040"
        ]);

        //6
        Instansi::create([
            "nama_instansi" => "PATTA",
            "kepala_instansi" => "Pak Patta",
            "alamat_instansi" => "Tunjungrejo",
            "telp_instansi" => "081",
            "latitude" => "-6.59200996353d11",
            "longitude" => "111.06748509804d0"
        ]);

        //!
        //MI

        // foreach ($data as $d) {
        //     Instansi::create([
        //         'nama_instansi' => $d[0],
        //         'kepala_instansi' => $d[1],
        //         'alamat_instansi' => $d[2],
        //         'telp_instansi' => '08' . rand(111111111, 999999999),
        //         'latitude' => fake()->latitude(-6.62, -6.59),
        //         'longitude' => fake()->longitude(111.05, 111.08),
        //     ]);
        // }
    }
}
