<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Post;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gate untuk mengedit artikel
        Gate::define('update-post', function (User $user, Post $post) {
            // Admin dan editor bisa edit semua artikel
            if ($user->isAdmin() || $user->isEditor()) {
                return true;
            }
            // Writer hanya bisa edit artikel miliknya sendiri
            return $user->id === $post->author_id;
        });

        // Gate untuk menghapus artikel
        Gate::define('delete-post', function (User $user, Post $post) {
            // Hanya admin yang bisa hapus artikel
            return $user->isAdmin();
        });

        // Gate untuk approve komentar
        Gate::define('approve-comment', function (User $user) {
            // Admin dan editor bisa approve komentar
            return $user->isAdmin() || $user->isEditor();
        });
    }
}