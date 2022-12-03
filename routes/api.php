<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;

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


    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });

    Route::get('/barang', [BarangController::class, 'getAll']);
    Route::post('/barang', [BarangController::class, 'pushBarang']);
    Route::put('/barang/{id}', [BarangController::class, 'updateBarang']);
    Route::get('/barang/{id}', [BarangController::class, 'getById']);
    Route::delete('/barang/{id}', [BarangController::class, 'destroy']);

    Route::post('/customer', [CustomerController::class, 'pushCustomer']);
    Route::get('/customer', [CustomerController::class, 'getAll']);
    Route::get('/customer/{id}', [CustomerController::class, 'getById']);
    Route::put('/customer/{id}', [CustomerController::class, 'updateCustomer']);
    Route::delete('/customer/{id}', [CustomerController::class, 'destroy']);

    Route::post('/logout', [AuthController::class, 'logout']);
});