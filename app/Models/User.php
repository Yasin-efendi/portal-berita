<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi dengan Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Relasi dengan Post (sebagai penulis)
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Relasi dengan Comment (sebagai penulis komentar)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Helper method untuk memeriksa apakah pengguna memiliki peran tertentu
    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    public function isEditor(): bool
    {
        return $this->role?->name === 'editor';
    }

    public function isWriter(): bool
    {
        return $this->role?->name === 'writer';
    }

    public function isReader(): bool
    {
        return $this->role?->name === 'reader';
    }

}
