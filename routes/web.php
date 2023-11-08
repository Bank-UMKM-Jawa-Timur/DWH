<?php

use App\Http\Controllers\Asuransi\PelaporanPelunasanController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportKKBController;
use App\Http\Controllers\KreditController;
use App\Http\Controllers\LogActivitesController;
use App\Http\Controllers\Master\DictionaryController;
use App\Http\Controllers\Master\DocumenCategoryController;
use App\Http\Controllers\Master\ImbalJasaController;
use App\Http\Controllers\Master\JenisAsuransiController;
use App\Http\Controllers\Master\NotificationTemplateController;
use App\Http\Controllers\Master\PenggunaController;
use App\Http\Controllers\Master\RoleController;
use App\Http\Controllers\Master\VendorController;
use App\Http\Controllers\Asuransi\RegistrasiController;
use App\Http\Controllers\Asuransi\PengajuanKlaimController;
use App\Http\Controllers\Asuransi\PembayaranPremiController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Master\PerusahaanAsuransiController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Report\Asuransi\PembayaranPremiController as AsuransiPembayaranPremiController;
use App\Http\Controllers\Report\Asuransi\PengajuanKlaimController as AsuransiPengajuanKlaimController;
use App\Http\Controllers\Report\Asuransi\RegistrasiController as ReportRegistrasiController;
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
// Email
Route::get('/get-import/{import_id}', [NotificationController::class, 'getDataImportById']);
Route::get('/send-email', [NotificationController::class, 'sendEmail'])->name('sendEmail');
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('first-login', [AuthenticatedSessionController::class, 'firstLogin'])
    ->name('first-login.index');
Route::post('first-login', [AuthenticatedSessionController::class, 'firstLoginStore'])
    ->name('first-login.store');

Route::middleware('auth_api')->group(function () {
    Route::get('/get-staf-by-cabang/{kode_cabang}', [Controller::class, 'getStafByCabang'])->name('get_staf_by_cabang');
    Route::get('send-notif/{action_id}/{kredit_id}', [NotificationController::class, 'send']);
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
        // Route::resource('/pengguna', PenggunaController::class);
        Route::get('/pengguna-list-cabang', [PenggunaController::class, 'listCabang'])->name('pengguna.list_cabang');
        Route::post('/pengguna/reset-password', [PenggunaController::class, 'resetPassword'])->name('pengguna.reset_password');
        Route::resource('/vendor', VendorController::class);
        Route::resource('/kategori-dokumen', DocumenCategoryController::class);
        Route::resource('/template-notifikasi', NotificationTemplateController::class);
        Route::resource('/imbal-jasa', ImbalJasaController::class);
        Route::resource('/dictionary', DictionaryController::class);
        Route::resource('/perusahaan-asuransi', PerusahaanAsuransiController::class);
        Route::resource('/jenis-asuransi', JenisAsuransiController::class);
    });

    Route::middleware('asuransi_permission')->prefix('asuransi')->name('asuransi.')->group(function() {
        Route::prefix('/registrasi')
            ->name('registrasi.')
            ->controller(RegistrasiController::class)
            ->group(function() {
                Route::get('/', 'index')->name('index');
                Route::get('/get-user/{user_id}', 'getUser')->name('get_user');
                Route::get('/create', 'create')->name('create');
                Route::get('/review', 'review')->name('review');
                Route::get('jenis-asuransi', 'getJenisAsuransi')->name('jenis_asuransi');
                Route::get('rate-premi', 'getRatePremi')->name('rate_premi');
                Route::post('/', 'store')->name('store');
                Route::post('/review', 'reviewStore')->name('review_store');
                Route::post('/send', 'send')->name('send');
                Route::post('/not-register', 'tidakRegistrasi')->name('not_register');
                Route::get('inquery', 'inquery')->name('inquery');
                Route::post('batal', 'batal')->name('batal');
                Route::post('/pelaporan-pelunasan', 'pelunasan')->name('pelunasan');
                Route::get('/check-asuransi', 'checkAsuransi')->name('check_asuransi');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/update/{id}', 'update')->name('update');
                Route::get('/detail/{id}', 'detail')->name('detail');
            });

        Route::resource('/pengajuan-klaim', PengajuanKlaimController::class);
        Route::post('/pengajuan-klaim/cek-status', [PengajuanKlaimController::class, 'cekStatus'])->name('pengajuan-klaim.cek-status');
        Route::post('/pengajuan-klaim/pembatalan-klaim', [PengajuanKlaimController::class, 'pembatalanKlaim'])->name('pengajuan-klaim.pembatalan-klaim');
        Route::get('/pengajuan-klaim/add/{id}', [PengajuanKlaimController::class, 'addPengajuan'])->name('pengajuan-klaim.add');
        Route::resource('/pembayaran-premi', PembayaranPremiController::class);
        Route::post('/pembayaran-premi/inquery', [PembayaranPremiController::class, 'storeInquery'])->name('pembayaran_premi.inquery');
        Route::get('/jenis-by-no-aplikasi', [PembayaranPremiController::class, 'getJenisByNoAplikasi'])->name('jenis_by_no_aplikasi');

        // Review klaim
        Route::get('/pengajuan-klaim/review-penyelia/{id}', [PengajuanKlaimController::class, 'reviewPenyelia'])->name('pengajuan-klaim.review-penyelia');
        Route::post('/pengajuan-klaim/approval/{id}', [PengajuanKlaimController::class, 'approval'])->name('pengajuan-klaim.approval');
        Route::post('/pengajuan-klaim/kembalikan-ke-staf/{id}', [PengajuanKlaimController::class, 'kembalikanKeStaf'])->name('pengajuan-klaim.kembalikan-ke-staf');
        Route::post('/pengajuan-klaim/hit-endpoint/{id}', [PengajuanKlaimController::class, 'hitEndpoint'])->name('pengajuan-klaim.hit-endpoint');

        // Report
        Route::prefix('/report')
            ->name('report.')
            ->group(function() {
                Route::prefix('/registrasi')
                    ->name('registrasi.')
                    ->controller(ReportRegistrasiController::class)
                    ->group(function() {
                        Route::get('/registrasi', 'registrasi')->name('registrasi');
                        Route::get('/pembatalan', 'pembatalan')->name('pembatalan');
                        // Route::get('/pembatalan-klaim', 'pembatalanKlaim')->name('pembatalan-klaim');
                        Route::get('/pelaporan-pelunasan', 'pelaporanPelunasan')->name('pelaporan-pelunasan');
                        Route::get('/log-data', 'logData')->name('log-data');
                    });
                Route::get('/pembayaran', [AsuransiPembayaranPremiController::class, 'index'])->name('pembayaran');
                Route::prefix('/pengajuan')
                ->name('pengajuan.')
                ->controller(AsuransiPengajuanKlaimController::class)
                ->group(function() {
                    Route::get('/pembatalan-klaim', 'pembatalanKlaim')->name('pembatalan-klaim');
                });
            });
    });

    Route::prefix('kredit')->name('kredit.')->group(function() {
        Route::get('', [KreditController::class, 'index'])->name('index');
        Route::post('/set-tgl-ketersediaan-unit', [KreditController::class, 'setTglKetersedianUnit'])->name('set_tgl_ketersediaan_unit');
        Route::post('/set-tgl-penyerahan-unit', [KreditController::class, 'setPenyerahanUnit'])->name('set_tgl_penyerahan_unit');
        Route::post('/upload-tagihan', [KreditController::class, 'uploadTagihan'])->name('upload_tagihan');
        Route::post('/upload-bukti-pembayaran', [KreditController::class, 'uploadBuktiPembayaran'])->name('upload_bukti_pembayaran');
        Route::post('/upload-polis', [KreditController::class, 'uploadPolis'])->name('upload_polis');
        Route::post('/upload-bpkb', [KreditController::class, 'uploadBpkb'])->name('upload_bpkb');
        Route::post('/upload-stnk', [KreditController::class, 'uploadStnk'])->name('upload_stnk');
        Route::post('/upload-berkas', [KreditController::class, 'uploadBerkas'])->name('upload_berkas');
        Route::get('/confirm-berkas', [KreditController::class, 'confirmBerkas'])->name('confirm_berkas');
        Route::post('/confirm-document-vendor', [KreditController::class, 'confirmDocumentVendor'])->name('confirm_document_vendor');
        Route::post('/confirm-penyerahan-unit', [KreditController::class, 'confirmPenyerahanUnit'])->name('confirm_penyerahan_unit');
        Route::get('/{id}', [KreditController::class, 'show'])->name('show');
        Route::post('/upload-imbal-jasa', [KreditController::class, 'uploadUImbalJasa'])->name('upload_imbal_jasa');
        Route::post('/confirm-imbal-jasa', [KreditController::class, 'confirmUploadUImbalJasa'])->name('confirm-imbal-jasa');
        Route::post('/load-json', [KreditController::class, 'loadDataJson'])->name('load_json');
    });

    Route::resource('/import-kkb', ImportKKBController::class);

    Route::get('/log_aktivitas', [LogActivitesController::class, 'index'])->name('log_aktivitas.index');

    // Collection
    Route::get('/collection', [CollectionController::class, 'index'])->name('collection.index');
    Route::post('/collection/upload', [CollectionController::class, 'upload'])->name('collection.upload');
    Route::post('/collection', [CollectionController::class, 'store'])->name('collection.store');
    Route::post('/collection/page', [CollectionController::class, 'getPage'])->name('collection.page');

    // Get Data For Charts
    Route::get('/get-data-charts', [DashboardController::class, 'getChartData'])->name('get-data-charts');
});




Route::middleware('auth_api')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
