<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PublicPostController extends Controller
{
    /**
     * Menampilkan daftar semua artikel yang sudah dipublish
     */
    public function index()
    {
        $posts = Post::with(['author', 'category'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(10);

        return view('public.index', compact('posts'));
    }

    /**
     * Menampilkan detail satu artikel berdasarkan slug
     */
    public function show(string $slug)
    {
        $post = Post::with(['author', 'category', 'tags', 'comments' => function($query) {
            $query->where('approved', true)
                  ->latest();
        }])->where('slug', $slug)
          ->where('status', 'published')
          ->where('published_at', '<=', now())
          ->firstOrFail();

        return view('public.show', compact('post'));
    }
}