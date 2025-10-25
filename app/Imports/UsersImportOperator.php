<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class UsersImportOperator implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private $successCount = 0;
    private $failureCount = 0;
    private $errors = [];
    private $currentRow = 1;
    private $instansiId;
    private $usedInstansiIds = []; // Tracking instansi yang sudah digunakan

    public function __construct()
    {
        // Ambil ID instansi dari user yang sedang login
        $user = Auth::user();
        $this->instansiId = $user->instansi()->first()->id ?? null;

        if (!$this->instansiId) {
            throw new \Exception('User tidak memiliki instansi yang terhubung.');
        }
    }

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

        // Validasi peran hanya untuk tenaga_pendidik dan tenaga_kependidikan
        $allowedRoles = ['tenaga_pendidik', 'tenaga_kependidikan'];
        if (!in_array($row['peran'], $allowedRoles)) {
            $errorMsg = "Peran '{$row['peran']}' tidak diizinkan. Hanya 'tenaga_pendidik' dan 'tenaga_kependidikan' yang dapat ditambahkan.";
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
            'jarak_tempuh' => $row['jarak_tempuh'] ?? 0,
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

        // Hubungkan user dengan instansi dari auth user (tidak boleh lebih dari 1 instansi)
        $user->instansi()->sync([$this->instansiId]);
        $this->usedInstansiIds[] = $this->instansiId;

        $this->successCount++;
        return $user;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|min:3',
            'telp' => 'required|numeric',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'jarak_tempuh' => 'nullable|numeric',
            'nomor_induk_yayasan' => 'required',
            'peran' => 'required|in:tenaga_pendidik,tenaga_kependidikan',
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
            'username.unique' => 'Username sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'nomor_induk_yayasan.required' => 'Nomor induk yayasan wajib diisi.',
            'peran.required' => 'Peran wajib diisi.',
            'peran.in' => 'Peran hanya boleh "tenaga_pendidik" atau "tenaga_kependidikan".',
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
