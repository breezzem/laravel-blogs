<?php

use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'block.blocked.user'])->group(function () {
    Route::post('blogs', [BlogController::class, 'store'])->name('blogs.store');
});

Route::middleware('auth')->group(function () {
    Route::get('blogs/{blog}', [BlogController::class, 'show'])->name('blogs.show');
    Route::put('blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');
    Route::delete('blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');
});
