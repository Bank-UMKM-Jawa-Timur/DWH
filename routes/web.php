<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KreditController;
use App\Http\Controllers\LogActivitesController;
use App\Http\Controllers\Master\DocumenCategoryController;
use App\Http\Controllers\Master\ImbalJasaController;
use App\Http\Controllers\Master\NotificationTemplateController;
use App\Http\Controllers\Master\PenggunaController;
use App\Http\Controllers\Master\RoleController;
use App\Http\Controllers\Master\VendorController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change_password');
    Route::post('/change-password', [ProfileController::class, 'updatePassword'])->name('update_password');

    Route::get('/reset_password', function () {
        $param['title'] = 'Reset Password';
        $param['pageTitle'] = 'Reset Password';
        return view('pages.reset_password.index', $param);
    });
    Route::get('/notifikasi', function () {
        $param['title'] = 'Notifikasi';
        $param['pageTitle'] = 'Notifikasi';
        return view('pages.notifikasi.index', $param);
    });
    Route::get('/target', function () {
        $param['title'] = 'Target Cabang';
        $param['pageTitle'] = 'Target';
        return view('pages.target.index', $param);
    });

    Route::prefix('master')->group(function () {
        Route::resource('/role', RoleController::class);
        Route::get('/role-list', [RoleController::class, 'list'])->name('role.list');
        Route::resource('/pengguna', PenggunaController::class);
        Route::get('/pengguna-list-cabang', [PenggunaController::class, 'listCabang'])->name('pengguna.list_cabang');
        Route::post('/pengguna/reset-password', [PenggunaController::class, 'resetPassword'])->name('pengguna.reset_password');
        Route::resource('/vendor', VendorController::class);
        Route::resource('/kategori-dokumen', DocumenCategoryController::class);
        Route::resource('/template-notifikasi', NotificationTemplateController::class);
        Route::resource('/imbal-jasa', ImbalJasaController::class);
    });


    Route::get('/kredit', [KreditController::class, 'index'])->name('kredit.index');
    Route::post('/kredit/set-tgl-ketersediaan-unit', [KreditController::class, 'setTglKetersedianUnit'])->name('kredit.set_tgl_ketersediaan_unit');
    Route::post('/kredit/set-tgl-penyerahan-unit', [KreditController::class, 'setPenyerahanUnit'])->name('kredit.set_tgl_penyerahan_unit');
    Route::post('/kredit/upload-police', [KreditController::class, 'uploadPolice'])->name('kredit.upload_police');
    Route::post('/kredit/upload-bpkb', [KreditController::class, 'uploadBpkb'])->name('kredit.upload_bpkb');
    Route::post('/kredit/upload-stnk', [KreditController::class, 'uploadStnk'])->name('kredit.upload_stnk');
    Route::post('/kredit/confirm-document', [KreditController::class, 'confirmDocument'])->name('kredit.confirm_document');



    Route::get('/hak_akses/1', function () {
        $param['title'] = 'Hak Akses';
        $param['pageTitle'] = 'Hak Akses';
        $param['role'] = 'Cabang';
        return view('pages.hak_akses.index', $param);
    });

    Route::get('/log_aktivitas', [LogActivitesController::class, 'index']);
});




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
