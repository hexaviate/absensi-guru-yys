<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instansi;

class InstansiSeeder extends Seeder
{
    public function run(): void
    {
        Instansi::insert([
            [
                'nama_instansi' => 'PAUD Terpadu Salafiyah',
                'kepala_instansi' => 'Ummu Hani, S.Pd.AUD',
                'alamat_instansi' => 'Jl. KH. Sahal Mahfudh No.1, Kajen, Margoyoso, Pati',
                'telp_instansi' => '081234567890',
                'latitude' => -6.6080306,
                'longitude' => 111.060302,
            ],
            [
                'nama_instansi' => 'MI Salafiyah',
                'kepala_instansi' => 'Hj. Nur Aini, S.Pd.I',
                'alamat_instansi' => 'Jl. KH. Sahal Mahfudh No.2, Kajen, Margoyoso, Pati',
                'telp_instansi' => '081234567891',
                'latitude' => -6.6094923,
                'longitude' => 111.0594997,
            ],
            [
                'nama_instansi' => 'MTs Salafiyah',
                'kepala_instansi' => 'H. M. Ali Mukti, S.Pd.I',
                'alamat_instansi' => 'Jl. KH. Sahal Mahfudh No.3, Kajen, Margoyoso, Pati',
                'telp_instansi' => '081234567892',
                'latitude' => -6.6088885,
                'longitude' => 111.059638,
            ],
            [
                'nama_instansi' => 'MA Salafiyah',
                'kepala_instansi' => 'H. Ahmad Basir, M.Pd.I',
                'alamat_instansi' => 'Jl. KH. Sahal Mahfudh No.4, Kajen, Margoyoso, Pati',
                'telp_instansi' => '081234567893',
                'latitude' => -6.6088684,
                'longitude' => 111.058323,
            ],
            [
                'nama_instansi' => 'SMK Salafiyah',
                'kepala_instansi' => 'Erni Sofa Nugraha, S.Pd.',
                'alamat_instansi' => 'Jl. KH. Sahal Mahfudh No.5, Kajen, Margoyoso, Pati',
                'telp_instansi' => '081234567894',
                'latitude' => -6.608540,
                'longitude' => 111.059240,
            ],
            [
                'nama_instansi' => 'PATTA',
                'kepala_instansi' => 'Drs. Syaifuddin Zuhri',
                'alamat_instansi' => 'Jl. KH. Sahal Mahfudh No.6, Kajen, Margoyoso, Pati',
                'telp_instansi' => '081234567895',
                'latitude' => -6.608700,
                'longitude' => 111.059110,
            ],
            [
                'nama_instansi' => 'PUSPELA',
                'kepala_instansi' => 'Hj. Siti Maryam, S.Pd.I',
                'alamat_instansi' => 'Jl. KH. Sahal Mahfudh No.7, Kajen, Margoyoso, Pati',
                'telp_instansi' => '081234567896',
                'latitude' => -6.6084289,
                'longitude' => 111.0576724,
            ],
        ]);
    }
}
