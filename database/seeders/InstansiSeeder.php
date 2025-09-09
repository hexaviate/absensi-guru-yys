<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instansi;

class InstansiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Instansi::create([
            "nama_instansi" => "rumah 1",
            "kepala_instansi" => "Pak Ini",
            "alamat_instansi" => "kajen",
            "telp_instansi" => "081",
            "latitude" => "-6.6116567",
            "longitude" => "111.0661932"
        ]);

        Instansi::create([
            "nama_instansi" => "SMK 1",
            "kepala_instansi" => "Bu Ini",
            "alamat_instansi" => "kajen",
            "telp_instansi" => "081",
            "latitude" => "-6.60795",
            "longitude" => "111.059405"
        ]);

        Instansi::create([
            "nama_instansi" => "Rumah Pak Hamdan",
            "kepala_instansi" => "Pak Hamdan",
            "alamat_instansi" => "Tunjungrejo",
            "telp_instansi" => "081",
            "latitude" => "-6.592996353118405",
            "longitude" => "111.06748580403307"
        ]);
    }
}
