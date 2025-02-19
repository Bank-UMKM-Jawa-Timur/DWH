<?php

use App\Http\Controllers\API\v1\DictionaryController;
use App\Http\Controllers\API\v1\KreditController;
use App\Http\Middleware\CheckLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::middleware([CheckLogin::class])->group(function () {
        Route::post('store-kredit', [KreditController::class, 'store']);
        Route::get('dictionary', [DictionaryController::class, 'index']);
    });
});
