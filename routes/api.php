<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show'])->whereNumber('product');

Route::get('cart', [CartController::class, 'index']);
Route::post('cart/items', [CartController::class, 'store']);
Route::put('cart/items/{product}', [CartController::class, 'update'])->whereNumber('product');
Route::delete('cart/items/{product}', [CartController::class, 'destroy'])->whereNumber('product');

Route::post('checkout', [CheckoutController::class, 'store']);

