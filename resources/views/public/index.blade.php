<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Portal Berita') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($posts->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        <p class="text-gray-500">Belum ada artikel. Silakan cek lagi nanti.</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                            <div class="p-6">
                                <!-- Kategori -->
                                @if($post->category)
                                    <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                                
                                <!-- Judul -->
                                <h3 class="text-xl font-bold mt-2 mb-2">
                                    <a href="{{ route('post.show', $post->slug) }}" class="hover:text-blue-600">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                
                                <!-- Informasi author & tanggal -->
                                <div class="text-sm text-gray-500 mb-3">
                                    Oleh {{ $post->author->name }} • 
                                    {{ $post->published_at->format('d M Y') }}
                                </div>
                                
                                <!-- Cuplikan konten -->
                                <p class="text-gray-700 line-clamp-3">
                                    {{ Str::limit(strip_tags($post->content), 150) }}
                                </p>
                                
                                <!-- Link baca selengkapnya -->
                                <div class="mt-4">
                                    <a href="{{ route('post.show', $post->slug) }}" class="text-blue-600 hover:underline text-sm">
                                        Baca selengkapnya →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @endif
            
        </div>
    </div>
</x-app-layout>