<?php

namespace Database\Seeders;

use App\Models\Tapel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tapel::create([
            "kode" => "2025/2026"
        ]);
    }
}
