<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use App\Models\Izin;

class IzinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('izins')->insert([
            "user_id" => 3,
            "instansi_id" => 1,
            "tapel_id" => 1,
            "bukti_izin" => "operator.pdf",
            "status" => "belum_diverifikasi",
            "tanggal" => Carbon::now(),
            "keterangan" => "Tes Izin",
        ]);
    }
}
