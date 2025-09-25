<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HariLiburController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TapelController;
use App\Http\Controllers\UsersController;
use App\Models\HariLibur;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('login');
// });


// Route::view('loginjal', 'auth.login');
// Route::view('jadwal', 'jadwal.main');

Route::get('/', [AuthController::class, 'viewLogin'])->name('login');
Route::post('doLogin', [AuthController::class, 'login'])->name('doLogin');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
// Route::resource('jadwal', JadwalController::class);
// Route::get('jadwal', [JadwalController::class, 'store']);
;



Route::middleware(['auth'])->group(function () {
    Route::controller(PresensiController::class)->group(function () {
        Route::get('presensi', 'viewPresensi');
        Route::post('prosesPresensi', 'prosesPresensi')->name('prosesPresensi');
    });

    Route::get('/dashboard', function () {
        return view('dashboard.main');
    })->name('dashboard');

    Route::resource('user', UsersController::class);
    Route::resource('role', RoleController::class);
    Route::resource('instansi', InstansiController::class);
    Route::resource('tapel', TapelController::class);
    Route::resource('hariLibur', HariLiburController::class);
    Route::resource('jadwal', JadwalController::class);


    Route::controller(ProfileController::class)->group(function () {
        Route::get('viewEditProfile', 'viewEditProfile');
        Route::post('editProfile', 'editProfile');
        Route::get('viewJadwalMingguIni', 'viewJadwalMingguIni');
        Route::get('viewRiwayatAbsensi', 'viewRiwayatAbsensi');
        Route::get('viewJadwalHariIni', 'viewJadwalHariIni');
    });

    Route::controller(IzinController::class)->group(function () {
        //*---------------------------------------------------------{User}-----------------------------------------------------------------------//

        Route::get('izinIndexUser', 'izinIndexUser')->name("izinIndexUser");
        Route::get('viewIzinCreate', 'viewIzinCreate')->name("viewIzinCreate");
        Route::post('izinCreate', 'izinCreate')->name("izinCreate");
        Route::get('viewIzinEdit/{id}', 'viewIzinEdit')->name("viewIzinEdit");
        Route::put('izinEdit/{id}', 'izinEdit')->name("izinEdit");
        //*---------------------------------------------------------{User}-----------------------------------------------------------------------//
        //?---------------------------------------------------------{Operator/Admin}-----------------------------------------------------------------------//

        Route::get('izinIndexOperator', 'izinIndexOperator')->name("izinIndexOperator");
        Route::get('viewIzinVerify', 'viewIzinVerify')->name("viewIzinVerify");
        Route::put('izinVerify/{id}', 'izinVerify')->name("izinVerify");



        //?---------------------------------------------------------{Operator/Admin}-----------------------------------------------------------------------//


    });
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
