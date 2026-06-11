<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostApiController extends Controller
{
    // GET /api/posts
    public function index(Request $request)
    {
        // ✅ FIX 2: Batasi per_page maksimal 100 untuk mencegah server crash
        $perPage = min($request->input('per_page', 15), 100);

        $posts = Post::with(['author', 'category', 'tags'])
            // ✅ FIX 1: Gunakan withCount untuk menghitung komentar tanpa N+1 query
            ->withCount(['comments' => fn($query) => $query->where('approved', true)])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate($perPage);

        return PostResource::collection($posts);
    }

    // GET /api/posts/{slug}
    public function show(string $slug)
    {
        $post = Post::with(['author', 'category', 'tags', 'comments' => function($query) {
                $query->where('approved', true)->latest();
            }])
            // ✅ FIX 1: Tambahkan withCount juga di show jika resource membutuhkannya
            ->withCount(['comments' => fn($query) => $query->where('approved', true)])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        return new PostResource($post);
    }
}