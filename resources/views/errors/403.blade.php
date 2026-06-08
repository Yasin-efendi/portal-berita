<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">
                    <h1 class="text-6xl font-bold text-red-600 mb-4">403</h1>
                    <h2 class="text-2xl font-semibold mb-4">Akses Ditolak</h2>
                    <p class="text-gray-600 mb-6">
                        Anda tidak memiliki izin untuk mengakses halaman ini.
                    </p>
                    <a href="{{ url('/dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>