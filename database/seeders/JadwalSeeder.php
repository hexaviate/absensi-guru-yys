<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Jadwal::create([
        //     "tapel_id" => 1,
        //     "instansi_id" => 2,
        //     "user_id" => 3,
        //     "hari" => "17 Agustus 2025",
        //     "datang"
        // ]);

        DB::table('jadwals')->insert([
            "tapel_id" => 1,
            "instansi_id" => 1,
            "user_id" => 3,
            "hari" => Carbon::parse('19 August 2025')->toDateString(),
            "datang" => Carbon::createFromTime('11', '20', '00'),
            "pulang" => Carbon::createFromTime('11', '30', '00'),
        ]);
    }
}
