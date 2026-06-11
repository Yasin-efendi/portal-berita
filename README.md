# 📰 Portal Berita — Laravel 13

Aplikasi portal berita/blog CMS dengan sistem multi-author, role-based access control, dan admin dashboard interaktif. Dibangun untuk pembelajaran Laravel dengan pendekatan **learning by doing**.

![Laravel](https://img.shields.io/badge/Laravel-13-red?style=flat&logo=laravel)
![Livewire](https://img.shields.io/badge/Livewire-4-4B32C3?style=flat&logo=livewire)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-38BDF8?style=flat&logo=tailwindcss)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql)
![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=flat&logo=php)
![License](https://img.shields.io/badge/License-MIT-green?style=flat)

---

## ✨ Fitur Utama

### 📝 Untuk Penulis & Editor
- ✅ Dashboard admin dengan Livewire 4 (interaktif tanpa reload)
- ✅ CRUD artikel: buat, edit, hapus dengan form yang rapi
- ✅ Kategori & Tag untuk mengelompokkan artikel
- ✅ Status artikel: Draft / Published + **scheduling publish otomatis**
- ✅ Upload & pilih kategori/tag saat membuat artikel

### 🛡️ Multi-Author dengan Role Based Access
- ✅ **Admin** — akses penuh ke semua fitur (CRUD artikel, kategori, tag, user)
- ✅ **Editor** — bisa edit semua artikel, approve komentar (coming soon)
- ✅ **Writer** — hanya bisa edit artikel milik sendiri
- ✅ **Reader** — hanya baca (tidak punya akses dashboard)

### 🌐 Halaman Publik
- ✅ Daftar artikel dengan pagination
- ✅ Halaman detail artikel dengan konten HTML yang aman
- ✅ Menampilkan author, kategori, tag, dan timestamp
- ✅ SEO-friendly URLs (slug)
- ✅ Responsive design dengan Tailwind CSS

### 📡 API Sederhana (Mobile Ready)
- ✅ Endpoint GET `/api/posts` — list artikel dengan pagination
- ✅ Endpoint GET `/api/posts/{slug}` — detail artikel
- ✅ Response JSON dengan Laravel API Resources
- ✅ Rate limiting & pagination terbatas (max 100 per page)

### ⚙️ Teknis
- ✅ Laravel 13.x dengan Breeze (Blade + Tailwind)
- ✅ Livewire 4 (Single-File Components)
- ✅ MySQL dengan migration & seeder
- ✅ Role Middleware & Gate untuk otorisasi
- ✅ Task scheduling untuk publish artikel otomatis
- ✅ Eager Loading untuk optimasi query

---

## 🚀 Tech Stack

| Kategori | Teknologi |
|---|---|
| **Framework** | Laravel 13.x |
| **Frontend** | Blade + Tailwind CSS + Livewire 4 |
| **Database** | MySQL 8.0 |
| **Authentication** | Laravel Breeze |
| **Authorization** | Custom Middleware + Gate |
| **API** | Laravel API Resources + Sanctum |
| **Scheduling** | Laravel Task Scheduler + Cron |

---

## 📦 Instalasi & Setup

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js 18+ & NPM

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/Yasin-efendi/portal-berita.git
cd portal-berita

# 2. Install dependencies PHP
composer install

# 3. Install dependencies JavaScript
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Konfigurasi database di .env
# DB_DATABASE=portal_berita
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Jalankan migration & seeder
php artisan migrate --seed

# 8. Compile assets (development)
npm run dev

# 9. Jalankan server
php artisan serve
```

Buka [http://localhost:8000](http://localhost:8000)

---

## 🔐 Akun Default

Setelah menjalankan `php artisan migrate --seed`, akun berikut tersedia:

| Role | Email | Password |
|---|---|---|
| **Admin** | admin@portal.com | password |
| **Editor** | editor@portal.com | password |
| **Writer** | writer@portal.com | password |
| **Reader** | reader@portal.com | password |

> ⚠️ Ganti kredensial ini sebelum production.

**Akses Dashboard Admin:** [http://localhost:8000/dashboard](http://localhost:8000/dashboard) (hanya untuk role: admin, editor, writer)

---

## 🗺️ Struktur Halaman

### Halaman Publik (Tanpa Login)

| Halaman | URL | Keterangan |
|---|---|---|
| Beranda | `/` | Daftar artikel terbaru |
| Detail Artikel | `/post/{slug}` | Isi artikel lengkap |

### Halaman Admin (Login Required)

| Halaman | URL | Keterangan |
|---|---|---|
| Dashboard | `/dashboard` | Menu navigasi admin |
| Buat Artikel | `/admin/posts/create` | Form artikel baru |
| Daftar Artikel | `/admin/posts` | Tabel dengan search, filter, pagination |
| Edit Artikel | `/admin/posts/{id}/edit` | Edit artikel yang sudah ada |
| Kelola Kategori | `/admin/categories` | CRUD kategori |
| Kelola Tag | `/admin/tags` | CRUD tag |

---

## 🔌 API Reference

Semua endpoint API menggunakan prefix `/api` dan **tidak memerlukan autentikasi** (public).

| Method | Endpoint | Deskripsi | Response |
|---|---|---|---|
| `GET` | `/api/posts` | List artikel (published) dengan pagination | `{ data: [], links: {}, meta: {} }` |
| `GET` | `/api/posts/{slug}` | Detail artikel berdasarkan slug | `{ data: { ... } }` |

### Parameter Query untuk `/api/posts`

| Parameter | Type | Default | Maksimal | Deskripsi |
|---|---|---|---|---|
| `per_page` | integer | 15 | 100 | Jumlah item per halaman |

### Contoh Response

```json
{
  "data": [
    {
      "id": 1,
      "title": "Belajar Laravel untuk Pemula",
      "slug": "belajar-laravel-untuk-pemula",
      "content": "<p>Isi artikel...</p>",
      "status": "published",
      "published_at": "2026-06-11T10:00:00.000000Z",
      "author": {
        "id": 3,
        "name": "Penulis Kreatif"
      },
      "category": {
        "id": 1,
        "name": "Teknologi",
        "slug": "teknologi"
      },
      "tags": [
        { "id": 1, "name": "laravel", "slug": "laravel" }
      ],
      "comments_count": 0,
      "created_at": "2026-06-10T08:00:00.000000Z",
      "updated_at": "2026-06-11T09:00:00.000000Z"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/posts?page=1",
    "last": "http://localhost:8000/api/posts?page=3",
    "prev": null,
    "next": "http://localhost:8000/api/posts?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 3,
    "per_page": 15,
    "to": 15,
    "total": 45
  }
}
```

---

## 📁 Struktur Proyek (Livewire 4 - SFC)

```
portal-berita/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Web/
│   │   │   │   └── PublicPostController.php   # Halaman publik
│   │   │   └── Api/
│   │   │       └── PostApiController.php      # API endpoint
│   │   └── Middleware/
│   │       └── RoleMiddleware.php             # Role-based access
│   ├── Models/
│   │   ├── User.php
│   │   ├── Role.php
│   │   ├── Post.php
│   │   ├── Category.php
│   │   ├── Tag.php
│   │   └── Comment.php
│   └── Providers/
│       └── AppServiceProvider.php             # Gate definitions
├── resources/
│   └── views/
│       ├── components/
│       │   └── admin/                         # Livewire 4 SFC Components
│       │       ├── post/
│       │       │   ├── ⚡create.blade.php
│       │       │   ├── ⚡edit.blade.php
│       │       │   └── ⚡table.blade.php
│       │       ├── category/
│       │       │   └── ⚡manager.blade.php
│       │       └── tag/
│       │           └── ⚡manager.blade.php
│       ├── public/
│       │   ├── index.blade.php               # Daftar artikel
│       │   └── show.blade.php                # Detail artikel
│       ├── layouts/
│       │   └── app.blade.php
│       └── dashboard.blade.php
├── database/
│   ├── migrations/                           # 10+ migration files
│   └── seeders/
│       ├── RoleSeeder.php
│       ├── UserSeeder.php
│       ├── CategorySeeder.php
│       └── PostSeeder.php
├── routes/
│   ├── web.php                               # Web routes + Livewire
│   └── api.php                               # API routes
├── public/
│   └── build/                                # Compiled assets
├── .env
└── artisan
```

---

## 🧠 Konsep Penting yang Dipelajari

### 1. Middleware vs Gate
| **Middleware** | **Gate** |
|---|---|
| Melindungi **halaman/route** | Melindungi **tindakan spesifik** |
| Contoh: `role:admin` | Contoh: `update-post` |
| Dijalankan sebelum controller | Dijalankan di dalam controller/blade |

### 2. Eager Loading
```php
// ❌ N+1 Query (lambat)
$posts = Post::all();

// ✅ Eager Loading (cepat)
$posts = Post::with(['author', 'category'])->get();
```

### 3. Scheduling Publish Otomatis
```bash
# Command artisan untuk publish artikel terjadwal
php artisan posts:publish-scheduled

# Cron job di server (setiap menit)
* * * * * cd /path-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## ⚠️ Catatan Penting

### Livewire 4 — Single-File Component (SFC)

Livewire 4 menggunakan pendekatan **satu file**:
- File disimpan di `resources/views/components/admin/⚡nama-component.blade.php`
- Class PHP ditulis di dalam file Blade (bukan terpisah di `app/Livewire/`)
- Routing menggunakan `Route::livewire('/path', 'admin.nama.component')`

```blade
{{-- ⚡nama-component.blade.php --}}
<?php

use Livewire\Component;

new class extends Component {
    public $message = "Hello World!";
    
    public function doSomething()
    {
        // ...
    }
};

?>

<div>
    <!-- Blade template di sini -->
</div>
```

### Laravel 13 — Perubahan Penting

- Middleware didaftarkan di `bootstrap/app.php`, bukan `Kernel.php`
- Tidak ada `Kernel.php` di Laravel 13

### Schedule di Laravel 13

Scheduling didefinisikan di `routes/console.php`:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('posts:publish-scheduled')->everyMinute();
```

---

## 🛠️ Troubleshooting

| Masalah | Solusi |
|---|---|
| `Class 'App\Models\User' not found` | Jalankan `composer dump-autoload` |
| `SQLSTATE[HY000] [1045] Access denied` | Cek kredensial database di `.env` |
| Livewire component tidak ditemukan | Pastikan file di `components/admin/` dan pakai prefix ⚡ |
| `Parameter $slug has no type information` | Tambahkan type hinting: `public function show(string $slug)` |
| Dashboard error 403 setelah login | Cek role user di database (`role_id` harus 1,2,3 untuk akses) |

### Clear Cache

```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 🔮 Rencana Pengembangan ke Depan

- [ ] Fitur komentar dengan approval admin (Fase 7)
- [ ] Upload gambar artikel
- [ ] Autentikasi API dengan Sanctum (untuk mobile app)
- [ ] Redis cache untuk homepage
- [ ] Queue untuk kirim email notifikasi

---

## 🤝 Kontribusi

Proyek ini dibuat untuk pembelajaran pribadi. Saran dan masukan sangat diterima.

1. Fork repository
2. Buat branch fitur: `git checkout -b fitur/baru`
3. Commit perubahan: `git commit -m 'feat: deskripsi fitur'`
4. Push: `git push origin fitur/baru`
5. Buat Pull Request

---

## 📄 License

Dibagikan di bawah lisensi [MIT](LICENSE). Bebas digunakan, dimodifikasi, dan didistribusikan untuk tujuan pembelajaran.

---

> 🎯 **Dibuat dengan ❤️ untuk belajar Laravel secara praktik langsung (learning by doing)**  
> *Laravel 13 • Livewire 4 • Tailwind CSS • MySQL • API Ready*
```