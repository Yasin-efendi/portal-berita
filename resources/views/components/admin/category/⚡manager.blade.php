<?php

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

new class extends Component {
    public $categories;
    public $name = '';
    public $editingId = null;
    public $editName = '';
    public $successMessage = '';
    public $errorMessage = '';
    
    public function mount()
    {
        $this->loadCategories();
    }
    
    public function loadCategories()
    {
        $this->categories = Category::orderBy('name')->get();
    }
    
    public function save()
    {
        // Cek otorisasi (hanya admin)
        if (!Gate::allows('delete-post', new \App\Models\Post())) {
            $this->errorMessage = 'Hanya admin yang bisa mengelola kategori.';
            return;
        }
        
        $this->validate([
            'name' => 'required|min:2|max:100|unique:categories,name',
        ]);
        
        Category::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => null,
        ]);
        
        $this->name = '';
        $this->successMessage = 'Kategori berhasil ditambahkan!';
        $this->loadCategories();
        
        // Auto-hide success message
        $this->dispatch('hide-message');
    }
    
    public function startEdit($id)
    {
        $category = Category::findOrFail($id);
        $this->editingId = $id;
        $this->editName = $category->name;
    }
    
    public function cancelEdit()
    {
        $this->editingId = null;
        $this->editName = '';
    }
    
    public function update()
    {
        // Cek otorisasi (hanya admin)
        if (!Gate::allows('delete-post', new \App\Models\Post())) {
            $this->errorMessage = 'Hanya admin yang bisa mengelola kategori.';
            return;
        }
        
        $category = Category::findOrFail($this->editingId);
        
        $this->validate([
            'editName' => 'required|min:2|max:100|unique:categories,name,' . $category->id,
        ]);
        
        $category->update([
            'name' => $this->editName,
            'slug' => Str::slug($this->editName),
        ]);
        
        $this->editingId = null;
        $this->editName = '';
        $this->successMessage = 'Kategori berhasil diupdate!';
        $this->loadCategories();
        
        $this->dispatch('hide-message');
    }
    
    public function delete($id)
    {
        // Cek otorisasi (hanya admin)
        if (!Gate::allows('delete-post', new \App\Models\Post())) {
            $this->errorMessage = 'Hanya admin yang bisa menghapus kategori.';
            return;
        }
        
        $category = Category::findOrFail($id);
        
        // Cek apakah kategori memiliki artikel
        if ($category->posts()->count() > 0) {
            $this->errorMessage = 'Kategori ini memiliki artikel. Hapus artikel terlebih dahulu.';
            return;
        }
        
        $category->delete();
        $this->successMessage = 'Kategori berhasil dihapus!';
        $this->loadCategories();
        
        $this->dispatch('hide-message');
    }
    
    public function dismissMessage()
    {
        $this->successMessage = '';
        $this->errorMessage = '';
    }
    
    public function render()
    {
        return view('components.admin.category.⚡manager');
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
    
    <!-- Form Tambah Kategori -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-bold mb-4">Tambah Kategori Baru</h3>
        <form wire:submit.prevent="save" class="flex gap-4">
            <input type="text" wire:model="name" 
                   placeholder="Nama kategori (contoh: Teknologi)"
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
    
    <!-- Daftar Kategori -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-bold">Daftar Kategori</h3>
        </div>
        
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Artikel</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($categories as $category)
                    <tr wire:key="category-{{ $category->id }}">
                        @if($editingId == $category->id)
                            <!-- Mode Edit -->
                            <td class="px-6 py-4" colspan="4">
                                <form wire:submit.prevent="update" class="flex gap-2">
                                    <input type="text" wire:model="editName" 
                                           class="flex-1 border-gray-300 rounded-md shadow-sm">
                                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded">Simpan</button>
                                    <button type="button" wire:click="cancelEdit" class="bg-gray-400 text-white px-3 py-1 rounded">Batal</button>
                                </form>
                                @error('editName')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </td>
                        @else
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $category->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $category->slug }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $category->posts()->count() }}</td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <button wire:click="startEdit({{ $category->id }})" 
                                        class="text-blue-600 hover:text-blue-900">Edit</button>
                                <button wire:click="delete({{ $category->id }})" 
                                        wire:confirm="Yakin hapus kategori '{{ $category->name }}'?"
                                        class="text-red-600 hover:text-red-900">Hapus</button>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            Belum ada kategori. Buat kategori pertama!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>