<x-layouts.app :title="'Pilih Jenis Kartu P3K'">
  <div class="max-w-7xl mx-auto px-4 py-6 space-y-6">

    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('p3k.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border-2 border-slate-200 text-slate-700 text-sm font-medium hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    {{-- Header --}}
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold tracking-tight bg-gradient-to-r from-emerald-600 to-green-600 bg-clip-text text-transparent">
            Pilih Jenis Kartu P3K
        </h1>
        <p class="text-sm text-slate-600 mt-2">
            Pilih jenis kartu yang ingin Anda isi
        </p>
    </div>

    {{-- Pilihan Jenis Kartu --}}
    <div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto">
        {{-- Pemeriksaan Kotak P3K --}}
        <a href="{{ route('p3k.pilih-lokasi', ['jenis' => 'pemeriksaan']) }}" 
           class="group relative overflow-hidden rounded-2xl bg-white border-2 border-slate-200 hover:border-emerald-500 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-100 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
            
            <div class="relative p-6">
                <div class="flex justify-center mb-4">
                    <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                </div>
                
                <h3 class="text-xl font-bold text-gray-900 text-center mb-2">
                    Pemeriksaan Kotak P3K
                </h3>
                <p class="text-xs text-gray-600 text-center mb-4">
                    Periksa kelengkapan dan kondisi isi kotak P3K
                </p>
                
                <div class="flex items-center justify-center gap-2 text-emerald-600 font-semibold text-sm group-hover:gap-4 transition-all">
                    <span>Pilih</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </div>
        </a>

        {{-- Pemakaian Kotak P3K --}}
        <a href="{{ route('p3k.pilih-lokasi', ['jenis' => 'pemakaian']) }}" 
           class="group relative overflow-hidden rounded-2xl bg-white border-2 border-slate-200 hover:border-blue-500 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
            
            <div class="relative p-6">
                <div class="flex justify-center mb-4">
                    <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                
                <h3 class="text-xl font-bold text-gray-900 text-center mb-2">
                    Pemakaian Kotak P3K
                </h3>
                <p class="text-xs text-gray-600 text-center mb-4">
                    Catat penggunaan obat dan alat dari kotak P3K
                </p>
                
                <div class="flex items-center justify-center gap-2 text-blue-600 font-semibold text-sm group-hover:gap-4 transition-all">
                    <span>Pilih</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </div>
        </a>

        {{-- Stock P3K --}}
        <a href="{{ route('p3k.pilih-lokasi', ['jenis' => 'stock']) }}" 
           class="group relative overflow-hidden rounded-2xl bg-white border-2 border-slate-200 hover:border-purple-500 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-32 h-32 bg-purple-100 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
            
            <div class="relative p-6">
                <div class="flex justify-center mb-4">
                    <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                </div>
                
                <h3 class="text-xl font-bold text-gray-900 text-center mb-2">
                    Stock P3K
                </h3>
                <p class="text-xs text-gray-600 text-center mb-4">
                    Kartu kendali stock obat dan alat P3K
                </p>
                
                <div class="flex items-center justify-center gap-2 text-purple-600 font-semibold text-sm group-hover:gap-4 transition-all">
                    <span>Pilih</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </div>
        </a>
    </div>

    {{-- Info Box --}}
    <div class="max-w-6xl mx-auto mt-8">
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex gap-4">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-2">Perbedaan Jenis Kartu:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li><strong>Pemeriksaan:</strong> Untuk mengecek kelengkapan dan kondisi isi kotak P3K secara berkala</li>
                        <li><strong>Pemakaian:</strong> Untuk mencatat setiap kali ada penggunaan obat atau alat dari kotak P3K</li>
                        <li><strong>Stock:</strong> Kartu kendali untuk memantau kondisi stock obat dan alat P3K (seperti APAR)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

  </div>
</x-layouts.app>
