<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;

Route::get('products', [ProductController::class, 'index']);
Route::get('products/categories', [ProductController::class, 'categories']);
Route::get('products/{id}', [ProductController::class, 'show']);

Route::get('cart', [CartController::class, 'index']);
Route::post('cart/items', [CartController::class, 'store']);
Route::put('cart/items/{product}', [CartController::class, 'update'])->whereNumber('product');
Route::delete('cart/items/{product}', [CartController::class, 'destroy'])->whereNumber('product');

Route::post('checkout', [CheckoutController::class, 'store']);

Route::get('setup-production', function (\Illuminate\Http\Request $request) {
    if ($request->query('key') !== 'mysecretkey') return response()->json(['error' => 'Unauthorized'], 401);
    
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    
    return [
        'status' => 'success',
        'message' => 'Cache cleared and migrations ran successfully.',
        'output' => \Illuminate\Support\Facades\Artisan::output()
    ];
});

