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
                    
                    <div class="mb-4 p-3 bg-blue-50 rounded">
                        <p class="font-semibold">Login sebagai: <span class="text-blue-700">{{ $currentUser->name }}</span> ({{ $currentUser->role?->name }})</p>
                    </div>
                    
                    <h3 class="text-lg font-bold mb-4">Hasil Cek Otorisasi:</h3>
                    
                    <div class="space-y-4">
                        <!-- Update Artikel Milik Sendiri -->
                        <div class="border-b pb-3">
                            <p>
                                <span class="font-semibold">Update artikel MILIK SENDIRI:</span>
                                @if($canUpdateMyPost)
                                    <span class="text-green-600">✅ YA</span>
                                @else
                                    <span class="text-red-600">❌ TIDAK</span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-500">Artikel dengan author_id = {{ $currentUser->id }} (sama dengan user login)</p>
                        </div>
                        
                        <!-- Update Artikel Milik Orang Lain -->
                        <div class="border-b pb-3">
                            <p>
                                <span class="font-semibold">Update artikel MILIK ORANG LAIN:</span>
                                @if($canUpdateOtherPost)
                                    <span class="text-green-600">✅ YA</span>
                                @else
                                    <span class="text-red-600">❌ TIDAK</span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-500">Artikel dengan author_id = user lain (bukan milik sendiri)</p>
                        </div>
                        
                        <!-- Hapus Artikel -->
                        <div>
                            <p>
                                <span class="font-semibold">Hapus artikel (sembarang):</span>
                                @if($canDelete)
                                    <span class="text-green-600">✅ YA</span>
                                @else
                                    <span class="text-red-600">❌ TIDAK</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 bg-gray-100 rounded">
                        <p class="text-sm text-gray-600">
                            💡 <strong>Aturan Gate:</strong>
                        </p>
                        <ul class="text-sm text-gray-600 list-disc list-inside mt-2">
                            <li><strong>Admin</strong> → bisa update & hapus semua artikel</li>
                            <li><strong>Editor</strong> → bisa update semua artikel (tidak bisa hapus)</li>
                            <li><strong>Writer</strong> → bisa update artikel milik sendiri saja (tidak bisa hapus)</li>
                            <li><strong>Reader</strong> → tidak bisa update atau hapus</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>