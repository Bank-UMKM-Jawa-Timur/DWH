<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', function () {
    return view('auth.login');
});
Route::get('/role', function () {
    $param['title'] = 'Role/Peran';
    $param['pageTitle'] = 'Role/Peran';
    return view('pages.role.index',$param);
});

Route::get('/pengguna', function () {
    $param['title'] = 'Pengguna';
    $param['pageTitle'] = 'Pengguna';
    return view('pages.pengguna.index',$param);
});

Route::get('/kredit', function () {
    $param['title'] = 'Kredit';
    $param['pageTitle'] = 'Kredit';
    return view('pages.kredit.index',$param);
});

Route::get('/vendor', function () {
    $param['title'] = 'Vendor';
    $param['pageTitle'] = 'Vendor';
    return view('pages.vendor.index',$param);
});

Route::get('/hak_akses/1', function () {
    $param['title'] = 'Hak Akses';
    $param['pageTitle'] = 'Hak Akses';
    $param['role'] = 'Cabang';
    return view('pages.hak_akses.index',$param);
});
Route::get('/template_notifikasi', function () {
    $param['title'] = 'Template Notifikasi';
    $param['pageTitle'] = 'Template Notifikasi';
    return view('pages.template_notifikasi.index',$param);
});

Route::get('/log_aktivitas', function () {
    $param['title'] = 'Log Aktivitas';
    $param['pageTitle'] = 'Log Aktivitas';
    return view('pages.log_aktivitas.index',$param);
});


Route::get('/', function () {
    $param['title'] = 'Dashboard';
    $param['pageTitle'] = 'Dashboard SuperAdmin';
    return view('pages.home',$param);
});
