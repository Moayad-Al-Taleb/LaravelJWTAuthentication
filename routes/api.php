<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\WorkerAuthController;
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

Route::middleware('DbBackup')->prefix('auth')->group(function () {

    Route::controller(AdminAuthController::class)->prefix('admin')->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout',  'logout');
        Route::post('/refresh', 'refresh');
        Route::get('/user-profile', 'userProfile');
    });

    Route::controller(WorkerAuthController::class)->prefix('worker')->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout',  'logout');
        Route::post('/refresh', 'refresh');
        Route::get('/user-profile', 'userProfile');
    });

    Route::controller(ClientAuthController::class)->prefix('client')->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout',  'logout');
        Route::post('/refresh', 'refresh');
        Route::get('/user-profile', 'userProfile');
    });
});


// Route::group([
//     'middleware' => ['jwt.verify'],
// ], function () {
//     Route::get('/', function () {
//     });
// });
