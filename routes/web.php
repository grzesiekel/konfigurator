<?php

use App\Http\Controllers\BaselinkerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('order/{order}/print', [OrderController::class, 'print'])->name('admin.order.print');
});

Route::get('/produkt/{product:slug}', [ProductController::class, 'index'])->name('product.index');
Route::post('/orders', [OrderController::class, 'store'])->name('order.store');
Route::get('/podsumowanie/{order:number}', [OrderController::class, 'show'])->name('order.show');

Route::get('/orders/print/{statusId}', [BaselinkerController::class, 'getOrdersByStatus'])
    ->name('orders.print');

    //273997