<?php

use Livewire\Component;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

new class extends Component {
    public $tags;
    public $name = '';
    public $editingId = null;
    public $editName = '';
    public $successMessage = '';
    public $errorMessage = '';
    
    public function mount()
    {
        $this->loadTags();
    }
    
    public function loadTags()
    {
        $this->tags = Tag::orderBy('name')->get();
    }
    
    public function save()
    {
        if (!Gate::allows('delete-post', new \App\Models\Post())) {
            $this->errorMessage = 'Hanya admin yang bisa mengelola tag.';
            return;
        }
        
        $this->validate([
            'name' => 'required|min:2|max:50|unique:tags,name',
        ]);
        
        Tag::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
        ]);
        
        $this->name = '';
        $this->successMessage = 'Tag berhasil ditambahkan!';
        $this->loadTags();
        $this->dispatch('hide-message');
    }
    
    public function startEdit($id)
    {
        $tag = Tag::findOrFail($id);
        $this->editingId = $id;
        $this->editName = $tag->name;
    }
    
    public function cancelEdit()
    {
        $this->editingId = null;
        $this->editName = '';
    }
    
    public function update()
    {
        if (!Gate::allows('delete-post', new \App\Models\Post())) {
            $this->errorMessage = 'Hanya admin yang bisa mengelola tag.';
            return;
        }
        
        $tag = Tag::findOrFail($this->editingId);
        
        $this->validate([
            'editName' => 'required|min:2|max:50|unique:tags,name,' . $tag->id,
        ]);
        
        $tag->update([
            'name' => $this->editName,
            'slug' => Str::slug($this->editName),
        ]);
        
        $this->editingId = null;
        $this->editName = '';
        $this->successMessage = 'Tag berhasil diupdate!';
        $this->loadTags();
        $this->dispatch('hide-message');
    }
    
    public function delete($id)
    {
        if (!Gate::allows('delete-post', new \App\Models\Post())) {
            $this->errorMessage = 'Hanya admin yang bisa menghapus tag.';
            return;
        }
        
        $tag = Tag::findOrFail($id);
        
        // Cek apakah tag memiliki artikel (melalui relasi many-to-many)
        if ($tag->posts()->count() > 0) {
            $this->errorMessage = 'Tag ini digunakan di artikel. Hapus tag dari artikel terlebih dahulu.';
            return;
        }
        
        $tag->delete();
        $this->successMessage = 'Tag berhasil dihapus!';
        $this->loadTags();
        $this->dispatch('hide-message');
    }
    
    public function dismissMessage()
    {
        $this->successMessage = '';
        $this->errorMessage = '';
    }
    
    public function render()
    {
        return view('components.admin.tag.⚡manager');
    }
};

?>

<div>
    <!-- Flash Messages -->
    @if($successMessage)
        <div wire:poll.3s="dismissMessage" class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ $successMessage }}
            <button type="button" wire:click="dismissMessage" class="float-right font-bold">×</button>
        </div>
    @endif
    
    @if($errorMessage)
        <div wire:poll.5s="dismissMessage" class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            {{ $errorMessage }}
            <button type="button" wire:click="dismissMessage" class="float-right font-bold">×</button>
        </div>
    @endif
    
    <!-- Form Tambah Tag -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-bold mb-4">Tambah Tag Baru</h3>
        <form wire:submit.prevent="save" class="flex gap-4">
            <input type="text" wire:model="name" 
                   placeholder="Nama tag (contoh: laravel, php, tutorial)"
                   class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <button type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Simpan
            </button>
        </form>
        @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Daftar Tag -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-bold">Daftar Tag</h3>
            <p class="text-sm text-gray-500 mt-1">Tag dapat dipilih saat membuat artikel.</p>
        </div>
        
        <div class="p-4">
            @forelse($tags as $tag)
                <div wire:key="tag-{{ $tag->id }}" class="flex justify-between items-center py-2 border-b last:border-0">
                    @if($editingId == $tag->id)
                        <div class="flex-1 flex gap-2">
                            <input type="text" wire:model="editName" 
                                   class="flex-1 border-gray-300 rounded-md shadow-sm">
                            <button wire:click="update" class="bg-green-600 text-white px-3 py-1 rounded">Simpan</button>
                            <button wire:click="cancelEdit" class="bg-gray-400 text-white px-3 py-1 rounded">Batal</button>
                        </div>
                        @error('editName')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    @else
                        <div class="flex items-center gap-2">
                            <span class="text-gray-700">{{ $tag->name }}</span>
                            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full">
                                {{ $tag->posts()->count() }} artikel
                            </span>
                        </div>
                        <div class="space-x-2">
                            <button wire:click="startEdit({{ $tag->id }})" 
                                    class="text-blue-600 hover:text-blue-900 text-sm">Edit</button>
                            <button wire:click="delete({{ $tag->id }})" 
                                    wire:confirm="Yakin hapus tag '{{ $tag->name }}'?"
                                    class="text-red-600 hover:text-red-900 text-sm">Hapus</button>
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-center text-gray-500 py-8">Belum ada tag. Buat tag pertama!</p>
            @endforelse
        </div>
    </div>
</div>