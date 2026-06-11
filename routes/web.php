<?php

use App\Models\Post;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;

use App\Http\Controllers\Web\PublicPostController;
// use App\Livewire\Admin\TestDashboard;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Halaman publik (letakkan di atas route auth)
Route::get('/', [PublicPostController::class, 'index'])->name('home');
Route::get('/post/{slug}', [PublicPostController::class, 'show'])->name('post.show');

// Route::middleware(['auth', 'role:admin,editor,writer'])->group(function () {
//     Route::get('/test-livewire', function () {
//         // Arahkan ke path components, dan jangan lupa awalan ⚡
//         return view('components.admin.⚡test-dashboard'); 
//     });
// });

Route::middleware(['auth', 'role:admin,editor,writer'])->group(function () {
    // Gunakan Route::livewire() dan namespace admin::
    Route::livewire('/test-livewire', 'admin.test-dashboard');

    // Route untuk create post
    Route::livewire('admin/posts/create', 'admin.post.create')->name('posts.create');

    // Sementara untuk edit (redirect ke create dulu, nanti akan diperbaiki)
    Route::livewire('/admin/posts/{post}/edit', 'admin.post.edit')->name('posts.edit');

    Route::livewire('/admin/posts', 'admin.post.table')->name('posts.index');

    Route::livewire('/admin/categories', 'admin.category.manager')->name('categories.index');

    Route::livewire('/admin/tags', 'admin.tag.manager')->name('tags.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:admin,editor,writer'])->name('dashboard');

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
