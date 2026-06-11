<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'status' => $this->status,
            'published_at' => $this->published_at?->toISOString(),
            
            // ✅ FIX 3: Lindungi dari error jika author null
            'author' => $this->author ? [
                'id' => $this->author->id,
                'name' => $this->author->name,
            ] : null,
            
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ] : null,
            
            'tags' => $this->tags->map(fn($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ]),
            
            // ✅ FIX 1: Ambil dari hasil withCount, bukan query ulang
            // Jika comments_count tidak di-load (misal di resource lain), fallback ke 0
            'comments_count' => $this->comments_count ?? 0, 
            
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}