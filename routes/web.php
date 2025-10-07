<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\LaporanAbsensiController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TapelController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.main');
});

Route::resource('user', UsersController::class);
Route::resource('role', RoleController::class);
Route::resource('instansi', InstansiController::class);

Route::get('login', [AuthController::class, 'viewLogin']);
Route::post('doLogin', [AuthController::class, 'login'])->name('doLogin');
Route::resource('tapel', TapelController::class);


Route::controller(PresensiController::class)->group(function () {
    Route::get('presensi', 'viewPresensi');
    Route::post('prosesPresensi', 'prosesPresensi')->name('prosesPresensi');
});

// Pindahkan routes export SEBELUM resource route
Route::get('/rekap-absensi/export-excel', [LaporanAbsensiController::class, 'exportExcel'])->name('rekap_absensi.exportExcel');
Route::get('/rekap-absensi/export-pdf', [LaporanAbsensiController::class, 'exportPDF'])->name('rekap_absensi.exportPDF');

// Routes untuk export bulanan
Route::get('/rekap-absensi/export-excel-bulanan', [LaporanAbsensiController::class, 'exportExcelBulanan'])->name('rekap_absensi.exportExcelBulanan');
Route::get('/rekap-absensi/export-pdf-bulanan', [LaporanAbsensiController::class, 'exportPDFBulanan'])->name('rekap_absensi.exportPDFBulanan');

// Routes untuk export tahunan
Route::get('/rekap-absensi/export-excel-tahunan', [LaporanAbsensiController::class, 'exportExcelTahunan'])->name('rekap_absensi.exportExcelTahunan');
Route::get('/rekap-absensi/export-pdf-tahunan', [LaporanAbsensiController::class, 'exportPDFTahunan'])->name('rekap_absensi.exportPDFTahunan');

// Resource route di bawah
Route::resource('rekap_absensi', LaporanAbsensiController::class);
