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
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TargetController;
use App\Models\Kredit;
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

Route::get('/karyawan/{nip}', function() {
    // $json = json_decode(file_get_contents('https://develop.bankumkm.id/bio_interface/api/karyawan?nip=01497'), true);
    // return $json;
    $url = 'https://develop.bankumkm.id/bio_interface/api/karyawan';
    $data = array('nip' => '01497');

    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'GET',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) { /* Handle error */ }

    return $result;
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
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notification.index');
    Route::get('/notifikasi/json', [NotificationController::class, 'listJson'])->name('notification.json');
    Route::get('/notifikasi/{id}', [NotificationController::class, 'detail'])->name('notification.detail');
    Route::resource('/target', TargetController::class);
    Route::put('/target-toggle/{id}', [TargetController::class, 'toggle'])->name('target.toggle');

    Route::prefix('master')->group(function () {
        Route::resource('/role', RoleController::class);
        Route::get('/role-search', [RoleController::class, 'search'])->name('role.search');
        Route::get('/role-list', [RoleController::class, 'list'])->name('role.list');
        Route::get('/role-list-options', [RoleController::class, 'listOptions'])->name('role.list_options');
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
    Route::post('/kredit/confirm-penyerahan-unit', [KreditController::class, 'confirmPenyerahanUnit'])->name('kredit.confirm_penyerahan_unit');
    Route::get('/kredit/{id}', [KreditController::class, 'show'])->name('kredit.show');
    Route::post('/kredit/upload-imbal-jasa', [KreditController::class, 'uploadUImbalJasa'])->name('kredit.upload_imbal_jasa');
    Route::post('/kredit/confirm-imbal-jasa', [KreditController::class, 'confirmUploadUImbalJasa'])->name('kredit.confirm-imbal-jasa');

    Route::get('/log_aktivitas', [LogActivitesController::class, 'index'])->name('log_aktivitas.index');
});




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
