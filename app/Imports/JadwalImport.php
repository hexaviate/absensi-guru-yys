<?php

namespace App\Imports;

use App\Models\Jadwal;
use App\Models\Tapel;
use App\Models\Instansi;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;

class JadwalImport implements ToModel, WithValidation, SkipsOnFailure, WithHeadingRow
{
    use SkipsFailures;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function rules(): array
    {
        return [
            '*.nama_instansi' => 'required',
            '*.kode' => 'required',
            '*.hari' => 'required',
            '*.datang' => 'required',
            '*.pulang' => 'required'
        ];
    }

    private function excelTimeToString($excelTime)
    {
        // Excel simpan waktu sebagai decimal (0.291667 = 07:00)
        if (is_numeric($excelTime)) {
            $seconds = $excelTime * 86400; // Convert ke detik
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            return sprintf('%02d:%02d', $hours, $minutes);
        }

        // Kalau sudah string format H:i, langsung return
        return $excelTime;
    }

    public function model(array $row)
    {
        $tapelAktif = Tapel::where('status', 'aktif')->first();

        // Cari instansi berdasarkan nama
        $instansi = Instansi::where('nama_instansi', 'like', "%{$row['nama_instansi']}%")->first();
        $user = User::where('nomor_induk_yayasan', strval($row['kode']))->first();

        // dd([
        //     'row' => $row,
        //     'tapel' => $tapelAktif,
        //     'instansi' => $instansi,
        //     'user' => $user
        // ]);



        // kalau instansi / user nggak ditemukan, skip aja biar gak crash
        if (!$instansi) {
            $this->failures[] = new Failure(
                $this->getRowNumber(),
                'nama_instansi',
                ['Instansi tidak ditemukan di database'],
                $row
            );
            return null;
        }

        if (!$user) {
            $this->failures[] = new Failure(
                $this->getRowNumber(),
                'kode',
                ["User dengan kode {$row['kode']} tidak ditemukan di database"],
                $row
            );
            return null;
        }

        return new Jadwal([
            'tapel_id' => $tapelAktif->id,
            'instansi_id' => $instansi->id,
            'user_id' => $user->id,
            'hari' => $row['hari'],
            'datang' => $this->excelTimeToString($row['datang']),
            'pulang' => $this->excelTimeToString($row['pulang'])
        ]);
    }

    private $currentRow = 1;
    public function onRow($row)
    {
        $this->currentRow++;
    }
    private function getRowNumber()
    {
        return $this->currentRow;
    }
}
