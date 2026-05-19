<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'home'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/open-box/{product}', [ProductController::class, 'open'])->name('products.open');

Route::middleware('auth')->group(function () {
    Route::get('/inventory', [ProductController::class, 'inventory'])->name('inventory');
    Route::get('/openings/{boxOpening}', [ProductController::class, 'openingResult'])->name('openings.show');
    Route::post('/openings/{boxOpening}/{action}', [ProductController::class, 'updateOpeningStatus'])
        ->whereIn('action', ['keep', 'redeem', 'sell'])
        ->name('openings.action');
});

Route::get('/test', [ProductController::class, 'test'])->name('test');
Route::post('/resultado', [ProductController::class, 'resultado'])->name('resultado');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [ProductController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/products/create', [ProductController::class, 'create'])->name('dashboard.products.create');
    Route::post('/dashboard/products', [ProductController::class, 'store'])->name('dashboard.products.store');
    Route::get('/dashboard/products/{product}/edit', [ProductController::class, 'edit'])->name('dashboard.products.edit');
    Route::put('/dashboard/products/{product}', [ProductController::class, 'update'])->name('dashboard.products.update');
    Route::delete('/dashboard/products/{product}', [ProductController::class, 'destroy'])->name('dashboard.products.destroy');
});

require __DIR__.'/auth.php';
