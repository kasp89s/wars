<?php

use App\Http\Controllers\ApiController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/api')->group(function () {
    Route::post('/auth-by-code', [ApiController::class, 'authByCode'])->name('api.auth-by-code');
    Route::post('/auth-by-login', [ApiController::class, 'authByLogin'])->name('api.auth-by-login');
    Route::post('/use-time', [ApiController::class, 'useTime'])->name('api.use-time');
    Route::post('/register', [ApiController::class, 'register'])->name('api.register');
    Route::post('/recovery-password', [ApiController::class, 'recoveryPassword'])->name('api.recovery-password');
});
