<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// TAMBAHKAN RUTE INI UNTUK TESTING MIDDLEWARE
Route::get('/admin-only', function () {
    return 'Halaman ini hanya bisa diakses oleh admin!';
})->middleware(['auth', 'role:admin']);

Route::get('/writer-only', function () {
    return 'Halaman ini hanya bisa diakses oleh writer!';
})->middleware(['auth', 'role:writer']);

Route::get('/editor-writer', function () {
    return 'Halaman ini bisa diakses oleh editor dan writer!';
})->middleware(['auth', 'role:editor,writer']);

require __DIR__.'/auth.php';
