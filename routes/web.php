<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TransactionsController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    Route::get('/product', [ProductsController::class, 'index'])
        ->name('product.index');
    Route::get('/product/create', [ProductsController::class, 'create'])
        ->name('product.create');
    Route::post('/product/store', [ProductsController::class, 'store'])
        ->name('product.store');
    Route::get('/product/{id}/edit', [ProductsController::class, 'edit'])
        ->name('product.edit');
    Route::put('/product/{id}', [ProductsController::class, 'update'])
        ->name('product.update');
    Route::delete('/product/{id}', [ProductsController::class, 'destroy'])
        ->name('product.destroy');


    Route::get('/transaction', [TransactionsController::class, 'index'])
        ->name('transaction.index');
    Route::get('/transaction/create', [TransactionsController::class, 'create'])
        ->name('transaction.create');
    Route::post('/transaction/store', [TransactionsController::class, 'store'])
        ->name('transaction.store');
    Route::get('/transaction/{transaction}/edit', [TransactionsController::class, 'edit'])
        ->name('transaction.edit');
    Route::put('/transaction/{transaction}', [TransactionsController::class, 'update'])
        ->name('transaction.update');
    Route::delete('/transaction/{transaction}', [TransactionsController::class, 'destroy'])
        ->name('transaction.destroy');

});

require __DIR__.'/auth.php';