<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Info User -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-2">Selamat datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600">
                        Anda login sebagai: 
                        <span class="font-semibold text-blue-600">{{ Auth::user()->role?->display_name }}</span>
                    </p>
                </div>
            </div>
            
            <!-- Menu Berdasarkan Role -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Menu: Kelola Artikel (untuk admin, editor, writer) -->
                @can('update-post', null)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold mb-2">📝 Kelola Artikel</h3>
                            <p class="text-gray-600 text-sm mb-4">Buat, edit, dan hapus artikel.</p>
                            <a href="#" class="text-blue-600 hover:underline">Buat Artikel Baru →</a>
                            <br>
                            <a href="#" class="text-blue-600 hover:underline">Daftar Semua Artikel →</a>
                        </div>
                    </div>
                @endcan
                
                <!-- Menu: Kelola Komentar (untuk admin dan editor) -->
                @can('approve-comment')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold mb-2">💬 Kelola Komentar</h3>
                            <p class="text-gray-600 text-sm mb-4">Approve dan hapus komentar.</p>
                            <a href="#" class="text-blue-600 hover:underline">Komentar Menunggu Approval →</a>
                        </div>
                    </div>
                @endcan
                
                <!-- Menu: Kelola Kategori (hanya admin) -->
                @can('delete-post', null)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold mb-2">🏷️ Kelola Kategori & Tag</h3>
                            <p class="text-gray-600 text-sm mb-4">Tambah, edit, hapus kategori dan tag.</p>
                            <a href="#" class="text-blue-600 hover:underline">Kelola Kategori →</a>
                            <br>
                            <a href="#" class="text-blue-600 hover:underline">Kelola Tag →</a>
                        </div>
                    </div>
                @endcan
                
                <!-- Menu: Kelola User (hanya admin) -->
                @can('delete-post', null)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold mb-2">👥 Kelola User</h3>
                            <p class="text-gray-600 text-sm mb-4">Tambah, edit, hapus user dan role.</p>
                            <a href="#" class="text-blue-600 hover:underline">Daftar User →</a>
                        </div>
                    </div>
                @endcan
                
            </div>
            
            <!-- Pesan untuk role yang tidak memiliki menu -->
            @cannot('update-post', null)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-6">
                    <p class="text-yellow-700">
                        🔔 Anda tidak memiliki akses untuk mengelola konten. Silakan hubungi admin jika perlu bantuan.
                    </p>
                </div>
            @endcannot
            
        </div>
    </div>
</x-app-layout>