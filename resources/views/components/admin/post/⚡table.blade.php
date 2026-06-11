<?php

use Livewire\Component;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate; // ✅ Perbaikan di sini (menambah Gate untuk otorisasi)

new class extends Component {
    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;
    
    // Listen untuk event dari komponen create (refresh setelah create)
    protected $listeners = ['post-created' => '$refresh'];
    
    public function delete($postId)
    {
        $post = Post::findOrFail($postId);
        
        // Cek otorisasi (Gate sudah didefinisikan di AppServiceProvider)
        if (Gate::denies('delete-post', $post)) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus artikel ini.');
            return;
        }
        
        $post->delete();
        session()->flash('success', 'Artikel berhasil dihapus!');
    }
    
    public function getPostsProperty()
    {
        $query = Post::with(['author', 'category']);
        
        // Filter berdasarkan role (writer hanya lihat artikel sendiri)
        if (Auth::user()->isWriter()) {
            $query->where('author_id', Auth::id());
        }
        
        // Search berdasarkan title
        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }
        
        // Filter status
        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }
        
        return $query->latest()->paginate($this->perPage);
    }
    
    public function render()
    {
        return view('components.admin.post.⚡table', [
            'posts' => $this->posts, // ✅ Perbaikan di sini (hapus kata Property)
        ]);
    }
};

?>

<div>
    <!-- Flash Message -->
    @if(session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif
    
    <!-- Filter & Search Bar -->
    <div class="flex flex-wrap gap-4 mb-6 justify-between items-center">
        <div class="flex gap-2">
            <input type="text" wire:model.live.debounce.300ms="search" 
                   placeholder="Cari judul artikel..." 
                   class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 w-64">
            
            <select wire:model.live="statusFilter" 
                    class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
            
            <select wire:model.live="perPage" 
                    class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="10">10 per halaman</option>
                <option value="25">25 per halaman</option>
                <option value="50">50 per halaman</option>
            </select>
        </div>
        
        <!-- Tombol Create (akan terlihat jika punya akses update) -->
        @can('update-post', null)
            <a href="{{ route('posts.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                + Buat Artikel Baru
            </a>
        @endcan
    </div>
    
    <!-- Tabel Artikel -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penulis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($posts as $post)
                    <tr wire:key="post-{{ $post->id }}">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $post->title }}</div>
                            <div class="text-xs text-gray-500">{{ Str::limit(strip_tags($post->content), 50) }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $post->author->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $post->category?->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($post->status == 'published')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Published</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $post->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm font-medium space-x-2">
                            @can('update-post', $post)
                                <a href="{{ route('posts.edit', $post->id) }}" 
                                   class="text-blue-600 hover:text-blue-900">Edit</a>
                            @endcan
                            
                            @can('delete-post', $post)
                                <button wire:click="delete({{ $post->id }})" 
                                        wire:confirm="Apakah Anda yakin ingin menghapus artikel '{{ $post->title }}'?"
                                        class="text-red-600 hover:text-red-900">
                                    Hapus
                                </button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            Belum ada artikel. 
                            <a href="{{ route('posts.create') }}" class="text-blue-600 hover:underline">Buat artikel pertama</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>