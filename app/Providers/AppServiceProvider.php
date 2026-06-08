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
        Gate::define('update-post', function (User $user, ?Post $post = null) {
            // ADMIN: bisa edit semua artikel
            if ($user->isAdmin()) {
                return true;
            }
            
            // EDITOR: bisa edit semua artikel
            if ($user->isEditor()) {
                return true;
            }
            
            // WRITER: 
            if ($user->isWriter()) {
                // Jika tidak ada post spesifik (untuk menampilkan menu di dashboard)
                if ($post === null) {
                    return true;  // Writer boleh melihat menu "Kelola Artikel"
                }
                // Jika ada post spesifik (untuk edit artikel tertentu)
                return $user->id === $post->author_id;
            }
            
            // READER atau role lain: TIDAK BISA
            return false;
        });

        // Gate untuk menghapus artikel
        Gate::define('delete-post', function (User $user, ?Post $post = null) {
            // Hanya admin yang bisa hapus artikel
            if ($user->isAdmin()) {
                return true;
            }
            
            // Untuk keperluan menu dashboard (menampilkan menu untuk admin saja)
            if ($post === null) {
                return false;  // Non-admin tidak boleh lihat menu hapus di dashboard
            }
            
            return false;
        });

        // Gate untuk approve komentar
        Gate::define('approve-comment', function (User $user) {
            return $user->isAdmin() || $user->isEditor();
        });
    }
}