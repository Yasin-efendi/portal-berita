<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Testing Gate Otorisasi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Hasil Cek Otorisasi:</h3>
                    
                    <div class="space-y-2">
                        <p>
                            <span class="font-semibold">Bisa update artikel:</span>
                            @if($canUpdate)
                                <span class="text-green-600">✅ YA</span>
                            @else
                                <span class="text-red-600">❌ TIDAK</span>
                            @endif
                        </p>
                        
                        <p>
                            <span class="font-semibold">Bisa hapus artikel:</span>
                            @if($canDelete)
                                <span class="text-green-600">✅ YA</span>
                            @else
                                <span class="text-red-600">❌ TIDAK</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="mt-6 p-4 bg-gray-100 rounded">
                        <p class="text-sm text-gray-600">
                            💡 <strong>Informasi:</strong> Gate ini nantinya digunakan di controller dan blade.
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            Contoh penggunaan: 
                            <code class="bg-gray-200 px-1 rounded">@@can('update-post', $post)</code> di blade,
                            atau <code class="bg-gray-200 px-1 rounded">$this->authorize('update-post', $post)</code> di controller.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>