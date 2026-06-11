<?php

use Livewire\Component;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

new class extends Component {
    public $postId;
    public $title = '';
    public $content = '';
    public $status = 'draft';
    public $category_id = '';
    public $selectedTags = [];
    
    public $categories = [];
    public $allTags = [];
    
    public $successMessage = '';

    public $scheduled_at = '';  // Untuk menyimpan nilai datetime-local
    
    public function mount($post)
    {
        // Ambil data post berdasarkan ID dari route
        $this->postId = $post;
        $postData = Post::findOrFail($this->postId);
        
        // Cek otorisasi (Gate sudah didefinisikan di AppServiceProvider)
        if (Gate::denies('update-post', $postData)) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit artikel ini.');
        }
        
        // Isi properti dengan data post
        $this->title = $postData->title;
        $this->content = $postData->content;
        $this->status = $postData->status;
        $this->category_id = $postData->category_id;
        $this->selectedTags = $postData->tags->pluck('id')->toArray();
        
        // Ambil data untuk dropdown
        $this->categories = Category::all();
        $this->allTags = Tag::all();
    }
    
    public function update()
    {
        $post = Post::findOrFail($this->postId);
        
        // Cek ulang otorisasi (untuk jaga-jaga)
        if (Gate::denies('update-post', $post)) {
            abort(403);
        }
        
        // Validasi input
        $this->validate([
            'title' => 'required|min:5|max:255',
            'content' => 'required|min:10',
            'status' => 'required|in:draft,published',
            'category_id' => 'nullable|exists:categories,id',
        ]);
        
        // Update slug jika title berubah
        $slug = Str::slug($this->title);
        if ($post->title !== $this->title) {
            $count = Post::where('slug', $slug)->where('id', '!=', $this->postId)->count();
            if ($count > 0) {
                $slug = $slug . '-' . time();
            }
            $post->slug = $slug;
        }

                // Di bagian sebelum Post::create atau Post::update
        $publishedAt = null;

        if ($this->status == 'published') {
            // Jika publish sekarang
            $publishedAt = now();
        } elseif ($this->status == 'draft' && !empty($this->scheduled_at)) {
            // Jika draft tapi ada jadwal -> status tetap draft, published_at diisi jadwal
            $publishedAt = \Carbon\Carbon::parse($this->scheduled_at);
        } else {
            // Draft tanpa jadwal
            $publishedAt = null;
        }
        
        // Update post
        $post->update([
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'published_at' => $publishedAt,
            'category_id' => $this->category_id ?: null,
        ]);
        
        // Sync tags
        $post->tags()->sync($this->selectedTags);
        
        $this->successMessage = 'Artikel berhasil diupdate!';
        
        // Kirim event untuk refresh tabel
        $this->dispatch('post-updated');
    }
    
    public function dismissMessage()
    {
        $this->successMessage = '';
    }
    
    public function render()
    {
        return view('components.admin.post.⚡edit');
    }
};

?>

<div>
    <!-- Success Message -->
    @if($successMessage)
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ $successMessage }}
            <button type="button" wire:click="dismissMessage" class="float-right font-bold">×</button>
        </div>
    @endif
    
    <form wire:submit.prevent="update" class="space-y-6">
        <!-- Judul -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Artikel *</label>
            <input type="text" wire:model="title" 
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Kategori -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <select wire:model="category_id" 
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Tag -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tag</label>
            <div class="flex flex-wrap gap-2">
                @foreach($allTags as $tag)
                    <label class="inline-flex items-center">
                        <input type="checkbox" value="{{ $tag->id }}" wire:model="selectedTags" 
                               wire:key="tag-checkbox-{{ $tag->id }}"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">{{ $tag->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        
        <!-- Status -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <div class="flex gap-4">
                <label class="inline-flex items-center">
                    <input type="radio" value="draft" wire:model.live="status" 
                           class="border-gray-300 text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Draft</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" value="published" wire:model.live="status" 
                           class="border-gray-300 text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Published</span>
                </label>
            </div>
        </div>

        <!-- ✅ TAMBAHKAN: Jadwalkan Publish (hanya muncul jika status = draft) -->
        @if($status == 'draft')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jadwalkan Publish (Opsional)</label>
            <input type="datetime-local" wire:model="scheduled_at" 
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <p class="text-xs text-gray-400 mt-1">
                Kosongkan jika ingin tetap draft. Isi tanggal/waktu jika ingin publish otomatis nanti.
            </p>
        </div>
        @endif
        
        <!-- Konten -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konten Artikel *</label>
            <textarea wire:model="content" rows="10" 
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono"></textarea>
            @error('content')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Tombol -->
        <div class="flex justify-end gap-2">
            <a href="{{ route('posts.index') }}" 
               class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                Batal
            </a>
            <button type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Update Artikel
            </button>
        </div>
    </form>
</div>

@script
<script>
    // Dengarkan event dari Livewire dan reset form native browser
    $wire.on('post-updated', () => {
        // Cari form di dalam komponen ini dan reset
        const form = $wire.$el.querySelector('form');
        if (form) {
            form.reset(); // Reset native browser
        }
    });
</script>
@endscript