<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Post;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Gate untuk mengedit artikel
        Gate::define('update-post', function (User $user, Post $post) {
            // ADMIN: bisa edit semua artikel
            if ($user->isAdmin()) {
                return true;
            }
            
            // EDITOR: bisa edit semua artikel
            if ($user->isEditor()) {
                return true;
            }
            
            // WRITER: hanya bisa edit artikel miliknya sendiri
            if ($user->isWriter() && $user->id === $post->author_id) {
                return true;
            }
            
            // READER atau role lain: TIDAK BISA
            return false;
        });

        // Gate untuk menghapus artikel
        Gate::define('delete-post', function (User $user, Post $post) {
            // Hanya admin yang bisa hapus artikel
            if ($user->isAdmin()) {
                return true;
            }
            
            return false;
        });

        // Gate untuk approve komentar
        Gate::define('approve-comment', function (User $user) {
            return $user->isAdmin() || $user->isEditor();
        });
    }
}