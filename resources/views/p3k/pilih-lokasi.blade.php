<x-layouts.app :title="'Pilih Lokasi P3K'">
  <div class="max-w-7xl mx-auto px-4 py-6 space-y-6">

    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('p3k.pilih-jenis') }}"
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
            Pilih Lokasi P3K
        </h1>
        <p class="text-sm text-slate-600 mt-2">
            Jenis: <span class="font-semibold">{{ ucfirst($jenis) }} Kotak P3K</span>
        </p>
    </div>

    {{-- Grid Lokasi --}}
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            $lokasi = [
                ['name' => 'Area Limbah B3', 'icon' => '☣️', 'color' => 'red'],
                ['name' => 'Aula', 'icon' => '🏛️', 'color' => 'blue'],
                ['name' => 'Pos Satpam', 'icon' => '🛡️', 'color' => 'indigo'],
                ['name' => 'Ruangan Pengadaan & SCM', 'icon' => '📦', 'color' => 'purple'],
                ['name' => 'Ruang Perencanaan', 'icon' => '📊', 'color' => 'cyan'],
                ['name' => 'Workshop 1', 'icon' => '🔧', 'color' => 'orange'],
                ['name' => 'Workshop 2', 'icon' => '🔧', 'color' => 'orange'],
                ['name' => 'Workshop 3', 'icon' => '🔧', 'color' => 'orange'],
                ['name' => 'Workshop 4', 'icon' => '🔧', 'color' => 'orange'],
            ];
        @endphp

        @foreach($lokasi as $item)
        <a href="{{ route('p3k.kartu.create', ['jenis' => $jenis, 'lokasi' => $item['name']]) }}" 
           class="group relative overflow-hidden rounded-2xl bg-white border-2 border-slate-200 hover:border-{{ $item['color'] }}-500 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-24 h-24 bg-{{ $item['color'] }}-100 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500"></div>
            
            <div class="relative p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-{{ $item['color'] }}-500 to-{{ $item['color'] }}-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300 text-3xl">
                        {{ $item['icon'] }}
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900">
                            {{ $item['name'] }}
                        </h3>
                        <p class="text-xs text-gray-500">
                            Klik untuk isi kartu
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-2 text-{{ $item['color'] }}-600 font-semibold text-sm group-hover:gap-4 transition-all">
                    <span>Pilih</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
            </div>
        </a>
        @endforeach
    </div>

  </div>
</x-layouts.app>
