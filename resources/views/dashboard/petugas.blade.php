<x-layouts.app :title="'Dashboard Petugas'">
  {{-- Unit Info Banner --}}
  @if(auth()->user()->unit)
    <div class="mb-4 sm:mb-6 p-4 sm:p-6 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl shadow-lg">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
          <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
              d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
              clip-rule="evenodd" />
          </svg>
        </div>
        <div class="flex-1">
          <p class="text-white/80 text-xs sm:text-sm font-medium">Unit Anda</p>
          <h3 class="text-white text-lg sm:text-2xl font-bold">{{ auth()->user()->unit->code }}</h3>
          <p class="text-white/90 text-xs sm:text-sm mt-0.5">{{ auth()->user()->unit->name }}</p>
          @if(auth()->user()->position)
            <span
              class="inline-block mt-2 px-2.5 py-1 bg-white/20 backdrop-blur rounded-lg text-white text-xs font-semibold">
              {{ ucfirst(auth()->user()->position) }}
            </span>
          @endif
        </div>
      </div>
    </div>
  @endif

  {{-- Header Section --}}
  <section class="mb-4 sm:mb-8 p-4 sm:p-8 shadow-lg rounded-lg bg-white">
    <div class="mb-4 sm:mb-6">
      <h2 class="text-xl sm:text-2xl font-bold">Dashboard Petugas</h2>
      <p class="text-xs sm:text-sm text-gray-600 mt-1">Akses cepat untuk input data dan lihat informasi</p>
    </div>

    {{-- Quick Actions Section --}}
    <section class="mb-6 sm:mb-8">
      <h2 class="text-base sm:text-lg font-bold mb-3 sm:mb-4 flex items-center gap-2">
        <span class="w-1.5 h-5 sm:h-6 bg-gradient-to-b from-blue-500 to-blue-400 rounded-full"></span>
        Quick Actions
      </h2>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-5">
        <a href="{{ route('quick.scan') }}"
          class="group relative rounded-lg bg-white p-3 sm:p-4 shadow-sm ring-1 ring-slate-200 hover:shadow-lg transition-all duration-300 overflow-hidden">
          <div class="relative z-10">
            <div class="flex items-start justify-between mb-2 sm:mb-3">
              <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center bg-blue-100">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
              </div>
              <span
                class="text-gray-400 group-hover:text-gray-600 group-hover:translate-x-1 transition-all text-lg sm:text-xl">→</span>
            </div>
            <h3 class="font-bold text-sm sm:text-md mb-1 sm:mb-2">Scan QR</h3>
            <p class="text-xs sm:text-sm text-gray-600 mb-2 hidden sm:block">Scan untuk input data</p>
            <div
              class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-blue-500 text-white group-hover:bg-blue-600 transition-colors">
              Scan
            </div>
          </div>
        </a>

        <a href="{{ route('floor-plan.index') }}"
          class="group relative rounded-lg bg-white p-3 sm:p-4 shadow-sm ring-1 ring-slate-200 hover:shadow-lg transition-all duration-300 overflow-hidden">
          <div class="relative z-10">
            <div class="flex items-start justify-between mb-2 sm:mb-3">
              <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center bg-indigo-100">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                </svg>
              </div>
              <span
                class="text-gray-400 group-hover:text-gray-600 group-hover:translate-x-1 transition-all text-lg sm:text-xl">→</span>
            </div>
            <h3 class="font-bold text-sm sm:text-md mb-1 sm:mb-2">Denah Lokasi</h3>
            <p class="text-xs sm:text-sm text-gray-600 mb-2 hidden sm:block">Lihat lokasi peralatan</p>
            <div
              class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-indigo-500 text-white group-hover:bg-indigo-600 transition-colors">
              Lihat
            </div>
          </div>
        </a>

        <a href="{{ route('quick.rekap') }}"
          class="group relative rounded-lg bg-white p-3 sm:p-4 shadow-sm ring-1 ring-slate-200 hover:shadow-lg transition-all duration-300 overflow-hidden">
          <div class="relative z-10">
            <div class="flex items-start justify-between mb-2 sm:mb-3">
              <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center bg-cyan-100">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <span
                class="text-gray-400 group-hover:text-gray-600 group-hover:translate-x-1 transition-all text-lg sm:text-xl">→</span>
            </div>
            <h3 class="font-bold text-sm sm:text-md mb-1 sm:mb-2">Laporan</h3>
            <p class="text-xs sm:text-sm text-gray-600 mb-2 hidden sm:block">Lihat laporan data</p>
            <div
              class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-cyan-500 text-white group-hover:bg-cyan-600 transition-colors">
              Lihat
            </div>
          </div>
        </a>

        <a href="{{ route('quick.inspeksi') }}"
          class="group relative rounded-lg bg-white p-3 sm:p-4 shadow-sm ring-1 ring-slate-200 hover:shadow-lg transition-all duration-300 overflow-hidden">
          <div class="relative z-10">
            <div class="flex items-start justify-between mb-2 sm:mb-3">
              <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center bg-sky-100">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
              </div>
              <span
                class="text-gray-400 group-hover:text-gray-600 group-hover:translate-x-1 transition-all text-lg sm:text-xl">→</span>
            </div>
            <h3 class="font-bold text-sm sm:text-md mb-1 sm:mb-2">Input Data</h3>
            <p class="text-xs sm:text-sm text-gray-600 mb-2 hidden sm:block">Tambah data peralatan</p>
            <div
              class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-sky-500 text-white group-hover:bg-sky-600 transition-colors">
              Input
            </div>
          </div>
        </a>
      </div>
    </section>

    {{-- Modules with grid layout --}}
    @php
      $modules = [
        ['APAR', 'Alat Pemadam Api Ringan', 'images/apar.png', true, 'apar.index', 'from-blue-500 to-teal-500', 'from-blue-50 to-teal-50'],
        ['APAT', 'Alat Pemadam Api Tradisional', 'images/apat.png', true, 'apat.index', 'from-cyan-500 to-sky-500', 'from-cyan-50 to-sky-50'],
        ['APAB', 'Alat Pemadam Api Berat', 'images/apab.png', true, 'apab.index', 'from-red-500 to-orange-500', 'from-red-50 to-orange-50'],
        ['Fire Alarm', 'Panel & titik alarm', 'images/fire-alarm.png', true, 'fire-alarm.index', 'from-red-500 to-pink-500', 'from-red-50 to-pink-50'],
        ['Box Hydrant', 'Box, hose, nozzle', 'images/box-hydrant.png', true, 'box-hydrant.index', 'from-blue-700 to-cyan-500', 'from-blue-50 to-cyan-50'],
        ['Rumah Pompa', 'Hydrant Rumah Pompa', 'images/box-hydrant.png', true, 'rumah-pompa.index', 'from-purple-600 to-indigo-600', 'from-purple-50 to-indigo-50'],
        ['P3K', 'Kotak & isi P3K', 'images/p3k.png', true, 'p3k.pilih-jenis', 'from-emerald-500 to-teal-500', 'from-emerald-50 to-teal-50'],
        ['Referensi', 'Kategori/Lokasi/Petugas', 'images/referensi.png', false, 'referensi.index', 'from-purple-500 to-indigo-500', 'from-purple-50 to-indigo-50'],
      ];
    @endphp

    <section id="modules">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-xl sm:text-2xl font-bold">Modul Sistem</h2>
          <p class="text-sm text-gray-600">Pilih modul untuk input data</p>
        </div>
      </div>

      {{-- Grid: Modules --}}
      <div class="grid lg:grid-cols-12 gap-3 sm:gap-5">
        @foreach ($modules as $idx => [$name, $desc, $img, $unlocked, $routeName, $gradient, $bgGradient])
          @php
            $href = $unlocked && $routeName ? route($routeName) : '#';
            $spanClass = $idx < 2 ? 'lg:col-span-6' : 'lg:col-span-3';
            $isLarge = $idx < 2;
          @endphp

          <a href="{{ $href }}" class="group relative {{ $spanClass }} col-span-12 sm:col-span-6 rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 
                 {{ $isLarge ? 'min-h-[320px]' : 'min-h-[280px]' }}
                 @if($unlocked) hover:scale-[1.02] @else opacity-80 @endif">

            <div class="absolute inset-0 transition-all duration-500"></div>

            <div class="absolute inset-0 flex items-center justify-center z-10 {{ $isLarge ? 'p-12' : 'p-8' }}">
              <div class="relative w-full h-full flex items-center justify-center">
                <img src="{{ asset($img) }}" alt="{{ $name }}" class="relative z-10 {{ $isLarge ? 'max-h-48' : 'max-h-32' }} w-auto object-contain 
                    @if($unlocked)
                      group-hover:scale-110 group-hover:rotate-3
                    @else
                      grayscale opacity-40
                    @endif
                    transition-all duration-700 drop-shadow-2xl">
              </div>
            </div>

            <div class="absolute bottom-0 left-0 right-0 z-20 p-5 {{ $isLarge ? 'pb-6' : 'pb-5' }}">
              <div class="relative backdrop-blur-xl bg-white/95 rounded-2xl p-5 shadow-xl ring-1 ring-black/5
                  @if($unlocked) group-hover:bg-white @else bg-white/70 @endif
                  transition-all duration-300">

                <div class="flex items-start justify-between gap-3">
                  <div class="flex-1 min-w-0">
                    <h3 class="font-bold {{ $isLarge ? 'text-2xl mb-2' : 'text-lg mb-1.5' }} truncate">
                      {{ $name }}
                    </h3>
                    <p class="text-sm text-gray-600 {{ $isLarge ? 'line-clamp-2' : 'line-clamp-1' }}">
                      {{ $desc }}
                    </p>
                  </div>

                  @if($unlocked)
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br {{ $gradient }} 
                          flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                      </svg>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </a>
        @endforeach
      </div>
    </section>
  </section>
</x-layouts.app>