<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HariLiburController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TapelController;
use App\Http\Controllers\UsersController;
use App\Models\HariLibur;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.main');
});

Route::resource('user', UsersController::class);
Route::resource('role', RoleController::class);
Route::resource('instansi', InstansiController::class);
Route::resource('tapel', TapelController::class);
Route::resource('hariLibur', HariLiburController::class);
Route::resource('jadwal', JadwalController::class);


Route::view('loginjal', 'auth.login');
Route::view('jadwal', 'jadwal.main');

Route::get('login', [AuthController::class, 'viewLogin']);
Route::post('doLogin', [AuthController::class, 'login'])->name('doLogin');

Route::resource('tapel', TapelController::class);
Route::resource('jadwal', JadwalController::class);

Route::get('/dashboard', function () {
    return view('dashboard.main');
})->name('dashboard');

Route::controller(PresensiController::class)->group(function () {
    Route::get('presensi', 'viewPresensi');
    Route::post('prosesPresensi', 'prosesPresensi')->name('prosesPresensi');
});

// untuk pencarian user di jadwal
// Tambahkan route ini di routes/web.php
Route::get('/search-users', [JadwalController::class, 'searchUsers'])->name('search.users');

// Atau kalau mau lebih spesifik dengan middleware
// Route::get('/search-users', [JadwalController::class, 'searchUsers'])
//      ->name('search.users')
//      ->middleware('auth'); // sesuaikan middleware yang dipakai
// routes/web.php (temporary untuk debug)
Route::get('/check-db', [JadwalController::class, 'checkDatabaseStructure']);
