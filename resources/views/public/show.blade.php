<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $post->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Artikel Utama -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <!-- Kategori & Tag -->
                    <div class="mb-4">
                        @if($post->category)
                            <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">
                                {{ $post->category->name }}
                            </span>
                        @endif
                        
                        @foreach($post->tags as $tag)
                            <span class="text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded-full ml-1">
                                #{{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                    
                    <!-- Judul -->
                    <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>
                    
                    <!-- Informasi Author & Tanggal -->
                    <div class="flex items-center justify-between border-b pb-4 mb-6">
                        <div class="text-sm text-gray-600">
                            <span class="font-semibold">Penulis:</span> {{ $post->author->name }}
                        </div>
                        <div class="text-sm text-gray-500">
                            Dipublish: {{ $post->published_at->format('d F Y, H:i') }}
                        </div>
                    </div>
                    
                    <!-- Konten Artikel -->
                    <div class="prose max-w-none">
                        {!! $post->content !!}
                    </div>
                    
                </div>
            </div>
            
            <!-- Bagian Komentar (sementara masih placeholder) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">💬 Komentar ({{ $post->comments->count() }})</h3>
                    
                    @if($post->comments->isEmpty())
                        <p class="text-gray-500">Belum ada komentar. Jadilah yang pertama!</p>
                    @else
                        <div class="space-y-4">
                            @foreach($post->comments as $comment)
                                <div class="border-b pb-3">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-semibold">{{ $comment->user->name }}</span>
                                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-700">{{ $comment->body }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <!-- Form komentar (sementara masih disabled, akan di Fase 7) -->
                    <div class="mt-6 p-4 bg-gray-50 rounded">
                        <p class="text-sm text-gray-500">
                            🔒 Fitur komentar akan segera hadir. (Fase 7)
                        </p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>