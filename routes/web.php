<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HariLiburController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\LaporanAbsensiController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TapelController;
use App\Http\Controllers\UsersController;
use App\Models\HariLibur;
use FontLib\Table\Type\name;
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
        Route::get('presensi', 'viewPresensi')->name('presensi');
        Route::post('prosesPresensi', 'prosesPresensi')->name('prosesPresensi');
    });

    Route::get('/cekProfile', function () {
        return view('user.cekProfile');
    });

    Route::resource('user', UsersController::class);
    Route::resource('role', RoleController::class);
    Route::resource('instansi', InstansiController::class);
    Route::resource('tapel', TapelController::class);
    Route::resource('hariLibur', HariLiburController::class);
    Route::resource('jadwal', JadwalController::class);


    Route::controller(ProfileController::class)->group(function () {
        Route::get('viewEditProfile/{id}', 'viewEditProfile')->name('viewEditProfile');
        Route::get('viewProfile', 'viewProfile')->name('viewProfile');
        Route::put('editProfile/{id}', 'editProfile')->name('editProfile');
        Route::get('jadwalUser', 'viewJadwalMingguIni')->name('jadwalUser');
        Route::get('viewRiwayatAbsensi', 'viewRiwayatAbsensi');
        Route::get('viewJadwalHariIni', 'viewJadwalHariIni');
        Route::get('viewSelfProfile', 'viewSelfProfile');
    });

    Route::controller(EventController::class)->group(function () {
        Route::get('indexEventOperator', 'indexEventOperator')->name('indexEventOperator');
        Route::get('createEventOperator', 'createEventOperator')->name('createEventOperator');
        Route::post('storeEventOperator', 'storeEventOperator')->name('storeEventOperator');
        Route::get('editEventOperator/{id}', 'editEventOperator')->name('editEventOperator');
        Route::put('updateEventOperator/{id}', 'updateEventOperator')->name('updateEventOperator');
        Route::delete('deleteEventOperator/{id}', 'deleteEventOperator')->name('deleteEventOperator');

        //* bawah ini untuk user
        Route::delete('viewEventUser', 'viewEventUser')->name('viewEventUser');
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

        Route::get('dashboardOperator', [DashboardController::class, 'operatorDashboard'])->name('operatorDashboard');
        Route::get('DashboardAdmin', [DashboardController::class, 'adminDashboard'])->name('adminYysDashboard');
        Route::get('DashboardUser', [DashboardController::class, 'userDashboard'])->name('userDashboard');

        //?---------------------------------------------------------{Operator/Admin}-----------------------------------------------------------------------//

        Route::get('/izin/operator/create', [IzinController::class, 'viewIzinCreateOperator'])
            ->name('viewIzinCreateOperator');

        // Store izin yang dibuat operator
        Route::post('/izin/operator/store', [IzinController::class, 'izinCreateOperator'])
            ->name('izinCreateOperator');

        // Search users untuk dropdown (AJAX)
        Route::get('/izin/search-users', [IzinController::class, 'searchUsers'])
            ->name('izin.searchUsers');

        // kelompok jadwal import dan export exel
        Route::post('jadwal/import', [JadwalController::class, 'import'])->name('jadwal.import');
        Route::get('/jadwal/template/download', [JadwalController::class, 'downloadTemplate'])
            ->name('jadwal.template');

        // kelompok user import dan export exel
        Route::post('/users/import', [UsersController::class, 'import'])
            ->name('users.import');
        Route::get('/users/template/download', [UsersController::class, 'downloadTemplate'])
            ->name('users.template');

        // INI UNTUK OPERATOR INSTANSI

        Route::post('/users/template/download/operator', [App\Http\Controllers\UsersController::class, 'importOperator'])->name('operator.instansi.users.import');
        Route::get('/users/import/opearator', [App\Http\Controllers\UsersController::class, 'downloadTemplateOperator'])->name('operator.instansi.users.download-template');

        //untuk view nya
        Route::get('/users/import/viewOperator', [UsersController::class, 'viewImportOperator'])
            ->name('userImportViewOperator');

        Route::get('/users/import/viewAdmin', [UsersController::class, 'viewImportAdmin'])
            ->name('userImportViewAdmin');
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
