<?php

namespace App\Imports;

use App\Models\Jadwal;
use App\Models\Tapel;
use App\Models\Instansi;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;

class JadwalImport implements ToModel,WithValidation,SkipsOnFailure
{
    use SkipsFailures;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $tapelAktif = Tapel::where('status', 'aktif')->first();
        $instansi = Instansi::where('nama_instansi',$row[1])->first();
        $user = User::where('kode',$row[2])->first();

        if([!$user,$instansi,$tapelAktif]){
            return null;
        }

        return new Jadwal([
            'tapel_id' => $tapelAktif->id,
            'instansi_id' => $instansi->id[1],
            'user_id' => $user->id[2],
            'hari' => $row[3],
            'datang' => $row[4],
            'pulang' => $row[5]
        ]);
    }

    public function rules():array
    {
        return[
            '0' => 'required',
            '1' => 'required',
            '2' => 'required',
            '3' => 'required',
            '4' => 'required|date_format',
            '5' => 'required',
        ];
    }
}
