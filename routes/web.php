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
use App\Http\Controllers\TargetController;
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
    Route::resource('/target', TargetController::class);
    Route::put('/target-toggle/{id}', [TargetController::class, 'toggle'])->name('target.toggle');

    Route::prefix('master')->group(function () {
        Route::resource('/role', RoleController::class);
        Route::get('/role-list', [RoleController::class, 'list'])->name('role.list');
        Route::get('/role/hak-akses/{id}', [RoleController::class, 'indexPermission'])->name('role.permission.index');
        Route::post('/role/hak-akses', [RoleController::class, 'storePermission'])->name('role.permission.store');
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
    Route::post('/kredit/upload-bukti-pembayaran', [KreditController::class, 'uploadBuktiPembayaran'])->name('kredit.upload_bukti_pembayaran');
    Route::post('/kredit/upload-polis', [KreditController::class, 'uploadPolis'])->name('kredit.upload_polis');
    Route::post('/kredit/upload-bpkb', [KreditController::class, 'uploadBpkb'])->name('kredit.upload_bpkb');
    Route::post('/kredit/upload-stnk', [KreditController::class, 'uploadStnk'])->name('kredit.upload_stnk');
    Route::post('/kredit/upload-berkas', [KreditController::class, 'uploadBerkas'])->name('kredit.upload_berkas');
    Route::post('/kredit/confirm-berkas', [KreditController::class, 'confirmBerkas'])->name('kredit.confirm_berkas');
    Route::post('/kredit/confirm-document', [KreditController::class, 'confirmDocumentCabang'])->name('kredit.confirm_document');
    Route::post('/kredit/confirm-document-vendor', [KreditController::class, 'confirmDocumentVendor'])->name('kredit.confirm_document_vendor');

    Route::get('/log_aktivitas', [LogActivitesController::class, 'index']);
});




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
