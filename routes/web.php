<?php

use App\Models\Post;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;

// TEMPORARY: Route untuk testing Gate yang lebih akurat
Route::middleware(['auth'])->group(function () {
    Route::get('/test-gate', function () {
        $currentUser = Auth::user();
        
        // SKENARIO 1: Artikel milik sendiri
        $myPost = new Post();
        $myPost->author_id = $currentUser->id;
        
        // SKENARIO 2: Artikel milik user lain (ambil user pertama yang bukan dirinya)
        $otherUser = User::where('id', '!=', $currentUser->id)->first();
        $otherPost = new Post();
        $otherPost->author_id = $otherUser ? $otherUser->id : 999; // 999 = tidak ada user
        
        $canUpdateMyPost = Gate::allows('update-post', $myPost);
        $canUpdateOtherPost = Gate::allows('update-post', $otherPost);
        $canDelete = Gate::allows('delete-post', $myPost);
        
        return view('test-gate', compact(
            'canUpdateMyPost', 
            'canUpdateOtherPost', 
            'canDelete',
            'currentUser'
        ));
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
