<?php

use Livewire\Component;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $title = '';
    public $content = '';
    public $status = 'draft';
    public $category_id = '';
    public $selectedTags = [];
    
    public $categories = [];
    public $allTags = [];
    
    public $successMessage = '';
    
    public function mount()
    {
        // Ambil data untuk dropdown
        $this->categories = Category::all();
        $this->allTags = Tag::all();
    }
    
    public function save()
    {
        // Validasi input
        $this->validate([
            'title' => 'required|min:5|max:255',
            'content' => 'required|min:10',
            'status' => 'required|in:draft,published',
            'category_id' => 'nullable|exists:categories,id',
        ]);
        
        // Buat slug dari title
        $slug = Str::slug($this->title);
        
        // Cek slug unik
        $count = Post::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . time();
        }
        
        // Simpan artikel
        $post = Post::create([
            'title' => $this->title,
            'slug' => $slug,
            'content' => $this->content,
            'status' => $this->status,
            'published_at' => $this->status == 'published' ? now() : null,
            'author_id' => Auth::id(),
            'category_id' => $this->category_id ?: null,
        ]);
        
        // Attach tags
        if (!empty($this->selectedTags)) {
            $post->tags()->attach($this->selectedTags);
        }
        
        // ✅ PERBAIKAN 1: Reset SEMUA properti form (termasuk status!)
        $this->reset(['title', 'content', 'status', 'category_id', 'selectedTags']);
        // ✅ PERBAIKAN 2: Bersihkan error validasi yang mungkin masih tampil
        $this->resetValidation();

        $this->successMessage = 'Artikel berhasil dibuat!';
        
        // Kirim event ke komponen lain (untuk refresh tabel)
        $this->dispatch('post-created');
    }
    
    // Method untuk reset success message setelah 3 detik
    public function dismissMessage()
    {
        $this->successMessage = '';
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
    
    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Judul -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Artikel *</label>
            <input type="text" wire:model="title" 
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Masukkan judul artikel">
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
                        {{-- ✅ PERBAIKAN 3: Tambahkan wire:key yang unik --}}
                        <input 
                            type="checkbox" 
                            value="{{ $tag->id }}" 
                            wire:model="selectedTags" 
                            wire:key="tag-checkbox-{{ $tag->id }}"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        >
                        <span class="ml-2 text-sm text-gray-700">{{ $tag->name }}</span>
                    </label>
                @endforeach
            </div>
            @if(count($allTags) == 0)
                <p class="text-gray-400 text-sm">Belum ada tag. <a href="#" class="text-blue-600">Buat tag dulu</a></p>
            @endif
        </div>
        
        <!-- Status -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <div class="flex gap-4">
                <label for="status-draft" class="inline-flex items-center cursor-pointer">
                    <input 
                        type="radio" 
                        id="status-draft" 
                        name="status" 
                        value="draft" 
                        wire:model.live="status" 
                        wire:key="radio-draft"
                        class="w-4 h-4 border-gray-300 text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-1 checked:bg-blue-600"
                    >
                    <span class="ml-2 text-sm text-gray-700">Draft</span>
                </label>
                <label for="status-published" class="inline-flex items-center">
                    <input 
                        type="radio" 
                        id="status-published" 
                        name="status" 
                        value="published" 
                        wire:model.live="status" 
                        wire:key="radio-published"
                        class="w-4 h-4 border-gray-300 text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-1 checked:bg-blue-600"
                    >
                    <span class="ml-2 text-sm text-gray-700">Published (Langsung tayang)</span>
                </label>
            </div>
            @error('status')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Konten (Textarea) -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konten Artikel *</label>
            <textarea wire:model="content" rows="10" 
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 font-mono"
                      placeholder="Tulis isi artikel di sini..."></textarea>
            @error('content')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Tombol Submit -->
        <div class="flex justify-end">
            <button type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Simpan Artikel
            </button>
        </div>
    </form>
</div>

@script
<script>
    // Dengarkan event dari Livewire dan reset form native browser
    $wire.on('post-created', () => {
        // Cari form di dalam komponen ini dan reset
        const form = $wire.$el.querySelector('form');
        if (form) {
            form.reset(); // Reset native browser
        }
    });
</script>
@endscript