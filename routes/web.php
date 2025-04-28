<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/produkt/{product:slug}', [ProductController::class, 'index'])->name('product.index');
Route::post('/orders', [OrderController::class, 'store'])->name('order.store');