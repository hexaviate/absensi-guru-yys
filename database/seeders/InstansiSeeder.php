<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instansi;

class InstansiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['PAUD Terpadu Salafiyah', 'Bu Rahma', 'Jl. PAUD Salafiyah No.1'],
            ['MI Salafiyah', 'Pak Ahmad', 'Jl. MI Salafiyah No.2'],
            ['MTs Salafiyah', 'Bu Nur', 'Jl. MTs Salafiyah No.3'],
            ['MA Salafiyah', 'Pak Yusuf', 'Jl. MA Salafiyah No.4'],
            ['SMK Salafiyah', 'Bu Lina', 'Jl. SMK Salafiyah No.5'],
            ['PATTA', 'Pak Hadi', 'Jl. PATTA Salafiyah No.6'],
        ];

        foreach ($data as $d) {
            Instansi::create([
                'nama_instansi'   => $d[0],
                'kepala_instansi' => $d[1],
                'alamat_instansi' => $d[2],
                'telp_instansi'   => '08' . rand(111111111, 999999999),
                'latitude'        => fake()->latitude(-6.62, -6.59),
                'longitude'       => fake()->longitude(111.05, 111.08),
            ]);
        }
    }
}
