<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AwbController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;

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

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth'])->group(function () {

    Route::get('/', [DashboardController::class, 'index']);
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::get('/getAll', [UserController::class, 'getAll'])->name('user.getAll');
    });

    Route::prefix('customer')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customer.index');
        Route::get('/getAll', [CustomerController::class, 'getAll'])->name('customer.getAll');
        Route::get('/{id}', [CustomerController::class, 'show']);
    });

    Route::prefix('outlet')->group(function () {
        Route::get('/', [OutletController::class, 'index'])->name('outlet.index');
        Route::get('/getAll', [OutletController::class, 'getAll'])->name('outlet.getAll');
        Route::get('/create', [OutletController::class, 'create']);
        Route::post('/', [OutletController::class, 'store']);
        Route::get('/{id}/edit', [OutletController::class, 'edit']);
        Route::patch('/{id}', [OutletController::class, 'update']);
        Route::delete('/{id}', [OutletController::class, 'destroy']);
    });

    Route::prefix('order')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('order.index');
        Route::get('/getAll', [OrderController::class, 'getAll'])->name('order.getAll');
        Route::get('/create', [OrderController::class, 'create']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{id}/edit', [OrderController::class, 'edit']);
        Route::patch('/{id}', [OrderController::class, 'update']);
        Route::delete('/{id}', [OrderController::class, 'destroy']);
    });

    Route::get('/logout', [AuthController::class, 'logout']);

    Route::prefix('cek-resi')->group(function () {
        Route::get('/', [AwbController::class, 'index'])->name('cek-resi.index');
        Route::get('/{awb}', [AwbController::class, 'getResi'])->name('cek-resi.find');
    });
});
