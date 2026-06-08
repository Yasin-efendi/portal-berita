<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user writer (role_id = 3)
        $writer = User::where('role_id', 3)->first();
        
        // Ambil kategori
        $categories = Category::all();
        
        // Ambil tag
        $tags = Tag::all();
        
        // Data artikel contoh
        $posts = [
            [
                'title' => 'Belajar Laravel 11 untuk Pemula',
                'content' => '<p>Laravel 11 hadir dengan berbagai fitur baru yang memudahkan developer membangun aplikasi web modern. Framework ini menyediakan tools seperti Blade, Eloquent ORM, dan routing yang elegan.</p><p>Bagi pemula, mulailah dengan memahami konsep MVC (Model-View-Controller), migration, dan authentication menggunakan Breeze. Praktik langsung adalah kunci tercepat untuk menguasai Laravel.</p>',
                'category' => 'Teknologi',
            ],
            [
                'title' => 'Pertandingan Seru di Final Liga Champions',
                'content' => '<p>Pertandingan final Liga Champions musim ini menyajikan drama yang luar biasa. Gol di menit-menit akhir membuat tim tamu berhasil membawa pulang trofi.</p><p>Pertandingan ini menjadi salah satu final terbaik dalam sejarah kompetisi.</p>',
                'category' => 'Olahraga',
            ],
            [
                'title' => 'Kebijakan Ekonomi Baru Diumumkan Pemerintah',
                'content' => '<p>Pemerintah resmi mengumumkan paket kebijakan ekonomi baru yang bertujuan untuk meningkatkan daya beli masyarakat dan mendorong investasi.</p><p>Kebijakan ini mencakup insentif pajak, kemudahan perizinan, dan bantuan modal untuk UMKM.</p>',
                'category' => 'Ekonomi',
            ],
            [
                'title' => 'Rekomendasi Film Terbaru yang Wajib Ditonton',
                'content' => '<p>Akhir tahun ini akan ada beberapa film blockbuster yang siap menghibur penonton. Dari genre action, drama, hingga animasi.</p><p>Jangan lewatkan film-film yang sudah mendapat rating tinggi dari para kritikus.</p>',
                'category' => 'Hiburan',
            ],
            [
                'title' => 'Tips Menjaga Kesehatan di Musim Hujan',
                'content' => '<p>Musim hujan seringkali membawa berbagai penyakit seperti flu dan demam. Berikut tips untuk menjaga kesehatan: perbanyak minum air putih, konsumsi vitamin C, dan istirahat cukup.</p><p>Jangan lupa untuk selalu membawa payung atau jas hujan.</p>',
                'category' => 'Kesehatan',
            ],
        ];
        
        foreach ($posts as $postData) {
            $category = Category::where('name', $postData['category'])->first();
            
            $post = Post::create([
                'title' => $postData['title'],
                'slug' => Str::slug($postData['title']),
                'content' => $postData['content'],
                'status' => 'published',
                'published_at' => now(),
                'author_id' => $writer->id,
                'category_id' => $category->id,
            ]);
            
            // Assign tags random (optional)
            if ($tags->count() > 0) {
                $post->tags()->attach($tags->random(rand(1, 2))->pluck('id')->toArray());
            }
        }
        
        // Buat 1 artikel dengan status DRAFT (tidak boleh muncul di publik)
        Post::create([
            'title' => 'Artikel Draft: Masih dalam Pengembangan',
            'slug' => 'artikel-draft-masih-dalam-pengembangan',
            'content' => '<p>Artikel ini masih draft dan TIDAK boleh muncul di halaman publik.</p>',
            'status' => 'draft',
            'published_at' => null,
            'author_id' => $writer->id,
            'category_id' => $categories->first()->id,
        ]);
        
        // Buat 1 artikel dengan published_at di masa depan (belum waktunya publish)
        Post::create([
            'title' => 'Artikel Masa Depan: Belum Tayang',
            'slug' => 'artikel-masa-depan-belum-tayang',
            'content' => '<p>Artikel ini baru akan dipublish besok, jadi TIDAK boleh muncul sekarang.</p>',
            'status' => 'published',
            'published_at' => now()->addDays(1),
            'author_id' => $writer->id,
            'category_id' => $categories->first()->id,
        ]);
    }
}