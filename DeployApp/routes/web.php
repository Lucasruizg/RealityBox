<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'home'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/open-box/{product}', [ProductController::class, 'open'])->name('products.open');

Route::get('/test', [ProductController::class, 'test'])->name('test');
Route::post('/resultado', [ProductController::class, 'resultado'])->name('resultado');
