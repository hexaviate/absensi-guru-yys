<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Instansi;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private $successCount = 0;
    private $failureCount = 0;
    private $errors = [];
    private $currentRow = 1; // Header = baris 1

    public function model(array $row)
    {
        $this->currentRow++;

        // Validasi data
        $this->validateRow($row);

        // Cek duplikasi username
        if (User::where('username', $row['username'])->exists()) {
            $errorMsg = "Username '{$row['username']}' sudah digunakan.";
            $this->recordError($this->currentRow, $errorMsg);
            $this->failureCount++;
            throw new \Exception($errorMsg);
        }

        // Buat user baru
        $user = User::create([
            'nomor_induk_yayasan' => $row['nomor_induk_yayasan'],
            'name' => $row['name'],
            'telp' => $row['telp'],
            'username' => $row['username'],
            'password' => Hash::make($row['password']),
            'jarak_tempuh' => $row['jarak_tempuh'],
            'foto_presensi' => ''
        ]);

        // Assign role dari Spatie
        $role = Role::where('name', $row['peran'])->first();
        if (!$role) {
            $errorMsg = "Peran '{$row['peran']}' tidak ditemukan di database.";
            $this->recordError($this->currentRow, $errorMsg);
            $this->failureCount++;
            throw new \Exception($errorMsg);
        }
        $user->assignRole($role->name);

        // Proses instansi (bisa multiple dengan pemisah koma)
        $namaInstansiArray = explode(',', $row['nama_instansi']);
        $instansiIds = [];

        foreach ($namaInstansiArray as $namaInstansi) {
            // Trim whitespace
            $namaInstansi = trim($namaInstansi);

            $instansi = Instansi::where('nama_instansi', $namaInstansi)->first();
            if (!$instansi) {
                $errorMsg = "Nama instansi '{$namaInstansi}' tidak ditemukan di database.";
                $this->recordError($this->currentRow, $errorMsg);
                $this->failureCount++;
                throw new \Exception($errorMsg);
            }

            $instansiIds[] = $instansi->id;
        }

        // Hubungkan user dengan instansi (asumsi ada relasi many-to-many)
        $user->instansi()->sync($instansiIds);

        $this->successCount++;
        return $user;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|min:3',
            'telp' => 'required|numeric',
            'username' => 'required',
            'password' => 'required',
            'jarak_tempuh' => 'required',
            'nomor_induk_yayasan' => 'required',
            'nama_instansi' => 'required',
            'peran' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama minimal 3 karakter.',
            'telp.required' => 'Nomor telepon wajib diisi.',
            'telp.numeric' => 'Nomor telepon harus berupa angka.',
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'jarak_tempuh.required' => 'Jarak tempuh wajib diisi.',
            'nomor_induk_yayasan.required' => 'Nomor induk yayasan wajib diisi.',
            'nama_instansi.required' => 'Nama instansi wajib diisi.',
            'peran.required' => 'Peran wajib diisi.',
        ];
    }

    private function validateRow(array $row)
    {
        $validator = \Validator::make($row, $this->rules(), $this->customValidationMessages());

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(' | ', $errors);
            $this->recordError($this->currentRow, $errorMessage);
            $this->failureCount++;
            throw new \Exception($errorMessage);
        }
    }

    private function recordError($rowNumber, $message)
    {
        $this->errors[] = "Baris {$rowNumber}: {$message}";
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getFailureCount()
    {
        return $this->failureCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
