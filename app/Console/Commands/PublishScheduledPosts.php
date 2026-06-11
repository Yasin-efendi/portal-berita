<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Carbon\Carbon;

class PublishScheduledPosts extends Command
{
    protected $signature = 'posts:publish-scheduled';
    protected $description = 'Mengubah status artikel dari draft ke published sesuai jadwal';

    public function handle()
    {
        // Cari artikel draft yang sudah waktunya publish
        $posts = Post::where('status', 'draft')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', Carbon::now())
            ->get();

        $count = 0;
        foreach ($posts as $post) {
            $post->update([
                'status' => 'published',
            ]);
            $count++;
            $this->info("Artikel '{$post->title}' telah dipublish.");
        }

        $this->info("Selesai. $count artikel dipublish.");
    }
}