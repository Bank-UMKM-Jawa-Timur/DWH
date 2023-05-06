<?php

use App\Http\Controllers\Master\PenggunaController;
use App\Http\Controllers\Master\RoleController;
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
    Route::get('/dashboard', function () {
        $param['title'] = 'Dashboard';
        $param['pageTitle'] = 'Dashboard SuperAdmin';
        return view('pages.home', $param);
    })->name('dashboard');

    Route::prefix('master')->group(function () {
        Route::resource('/role', RoleController::class);
        Route::get('/role-list', [RoleController::class, 'list'])->name('role.list');
        Route::resource('/pengguna', PenggunaController::class);
        Route::get('/vendor', function () {
            $param['title'] = 'Vendor';
            $param['pageTitle'] = 'Vendor';
            return view('pages.vendor.index', $param);
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

    Route::get('/log_aktivitas', function () {
        $param['title'] = 'Log Aktivitas';
        $param['pageTitle'] = 'Log Aktivitas';
        return view('pages.log_aktivitas.index', $param);
    });
});




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
