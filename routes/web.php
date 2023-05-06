<?php

use App\Http\Controllers\LogActivitesController;
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
    Route::get('/', function () {
        $param['title'] = 'Dashboard';
        $param['pageTitle'] = 'Dashboard SuperAdmin';
        return view('pages.home', $param);
    });
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
    Route::get('/dashboard', function () {
        $param['title'] = 'Dashboard';
        $param['pageTitle'] = 'Dashboard SuperAdmin';
        return view('pages.home', $param);
    })->name('dashboard');

    Route::prefix('master')->group(function () {
        Route::resource('/role', RoleController::class);
        Route::get('/role-list', [RoleController::class, 'list'])->name('role.list');
        Route::resource('/pengguna', PenggunaController::class);
        Route::get('/pengguna-list-cabang', [PenggunaController::class, 'listCabang'])->name('pengguna.list_cabang');
        Route::resource('/vendor', VendorController::class);        
        Route::get('/kategori_dokumen', function () {
            $param['title'] = 'Kategori Dokumen';
            $param['pageTitle'] = 'Kategori Dokumen';
            return view('pages.kategori_dokumen.index', $param);
        });
        Route::get('/template_notifikasi', function () {
            $param['title'] = 'Template Notifikasi';
            $param['pageTitle'] = 'Template Notifikasi';
            return view('pages.template_notifikasi.index', $param);
        });
    });


    Route::get('/kredit', function () {
        $param['title'] = 'Kredit';
        $param['pageTitle'] = 'Kredit';
        return view('pages.kredit.index', $param);
    });



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
