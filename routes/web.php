<?php

use App\Models\Post;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// TEMPORARY: Route untuk testing Gate (akan dihapus nanti)
Route::middleware(['auth'])->group(function () {
    Route::get('/test-gate', function () {
        // Buat post dummy untuk testing (tidak akan disimpan ke database)
        $dummyPost = new Post();
        $dummyPost->author_id = Auth::id(); // Set author_id ke user yang sedang login
        
        $canUpdate = Gate::allows('update-post', $dummyPost);
        $canDelete = Gate::allows('delete-post', $dummyPost);
        
        return view('test-gate', compact('canUpdate', 'canDelete'));
    });
});

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
