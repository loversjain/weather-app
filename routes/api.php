<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController as Admin;
use App\Http\Controllers\Admin\AdminV1Controller as AdminV1;
use App\Http\Controllers\Buyer\BuyerController as Buyer;
// use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Routes that require authentication
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    //Route::get('get-weather', [EventController::class, 'getWeather']); //testing
    Route::get('home', function() {
        return Auth::user();
    });
});

// Routes that do not require authentication
Route::controller(AuthController::class)
      ->prefix('auth')
      ->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
});


Route::controller(Admin::class)
       ->prefix('admin')
       ->middleware(['auth:api', 'checkrole'])
       ->group(function () {
            Route::post('/event',  'store');
            Route::put('/event/{id}', 'update')->where('id', '[0-9]+');
            Route::delete('/event/{id}', 'destroy')->where('id', '[0-9]+');
            Route::get('/events',  'index');
});

Route::controller(AdminV1::class)
    ->prefix('admin/v1')
    ->middleware(['auth:api', 'checkrole'])
    ->group(function () {
        Route::post('/event',  'store');
        Route::put('/event/{id}', 'update')->where('id', '[0-9]+');
        Route::delete('/event/{id}', 'destroy')->where('id', '[0-9]+');
        Route::get('/events',  'index');
    });

Route::controller(Buyer::class)
       ->prefix('buyer')
       ->middleware(['auth:api', 'checkrole'])
       ->group(function () {
            Route::get('/events', 'index');
});



