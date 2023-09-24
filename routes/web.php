<?php

use Illuminate\Support\Facades\Route;
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

Route::get('/', [OrderController::class, 'index'])->name('orders.index');

Route::prefix('orders')->group(function () {
    Route::get('/search', [OrderController::class, 'search'])->name('orders.search');
     Route::prefix('{order}')->group(function () {
        Route::get('/details', [OrderController::class, 'show'])->name('orders.show');
        Route::get('/edit', [OrderController::class, 'edit'])->name('orders.edit');
        Route::put('/', [OrderController::class, 'update'])->name('orders.update');
    });
});
