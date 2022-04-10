<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;
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

Route::prefix('user')->group(function () {
    Route::match(['get', 'post'], '/login', [UserController::class, 'login'])
        ->name('login')
        ->middleware('guest');
    Route::post('/logout', [UserController::class, 'logout'])
        ->name('logout')
        ->middleware('auth');
});

Route::prefix('admin')->group(function () {
    Route::match(['get', 'post'], '/{section?}/{model?}/{action?}/{id?}', [AdminController::class, 'page'])
        ->name('admin')
        ->middleware('admin');
});

Route::fallback(SiteController::class);
