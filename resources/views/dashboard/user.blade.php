<x-layouts.app :title="'Dashboard — User'">
  {{-- Unit Info Banner --}}
  @if(auth()->user()->unit)
    <div class="mb-4 sm:mb-6 p-4 sm:p-6 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl shadow-lg">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
          <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
          </svg>
        </div>
        <div class="flex-1">
          <p class="text-white/80 text-xs sm:text-sm font-medium">Unit Anda</p>
          <h3 class="text-white text-lg sm:text-2xl font-bold">{{ auth()->user()->unit->code }}</h3>
          <p class="text-white/90 text-xs sm:text-sm mt-0.5">{{ auth()->user()->unit->name }}</p>
          @if(auth()->user()->position)
            <span class="inline-block mt-2 px-2.5 py-1 bg-white/20 backdrop-blur rounded-lg text-white text-xs font-semibold">
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
      <h2 class="text-xl sm:text-2xl font-bold">Modul Sistem</h2>
      <p class="text-xs sm:text-sm text-gray-600 mt-1">Pilih modul yang ingin Anda akses</p>
    </div>

    {{-- Chart Section with Dropdown --}}
    <section class="mb-6 sm:mb-8">
      {{-- Module Selector --}}
      <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-1">Laporan Peralatan</h3>
          <p class="text-xs sm:text-sm text-slate-600">Pilih modul untuk melihat detail statistik</p>
        </div>
        <div class="relative w-full sm:w-auto">
          <select id="moduleSelector" onchange="switchModule(this.value)" 
                  class="w-full sm:w-auto appearance-none bg-white border-2 border-slate-200 rounded-xl px-4 py-2.5 pr-10 text-xs sm:text-sm font-semibold text-slate-700 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all cursor-pointer shadow-sm">
            <option value="apar">APAR - Alat Pemadam Api Ringan</option>
            <option value="apat">APAT - Alat Pemadam Api Tradisional</option>
            <option value="apab">APAB - Alat Pemadam Api Berat</option>
            <option value="fire-alarm">Fire Alarm - Panel & Titik Alarm</option>
            <option value="box-hydrant">Box Hydrant - Box, Hose, Nozzle</option>
            <option value="rumah-pompa">Rumah Pompa - Hydrant Rumah Pompa</option>
          </select>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </div>
        </div>
      </div>

      {{-- Charts Grid --}}
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        {{-- Status Peralatan Chart --}}
        <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg ring-1 ring-slate-200 hover:shadow-xl transition-shadow">
          <div class="flex items-center justify-between mb-4 sm:mb-5">
            <div>
              <h3 class="text-sm sm:text-base font-bold text-slate-900">Status Peralatan</h3>
              <p class="text-xs text-slate-600 mt-0.5" id="statusChartSubtitle">Kondisi semua modul</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
            </div>
          </div>
          <div class="relative h-48 sm:h-56 md:h-64">
            <canvas id="statusChart"></canvas>
          </div>
        </div>

        {{-- Tren Inspeksi Chart --}}
        <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg ring-1 ring-slate-200 hover:shadow-xl transition-shadow">
          <div class="flex items-center justify-between mb-4 sm:mb-5">
            <div>
              <h3 class="text-sm sm:text-base font-bold text-slate-900">Tren Inspeksi</h3>
              <p class="text-xs text-slate-600 mt-0.5">6 bulan terakhir</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-lg">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
              </svg>
            </div>
          </div>
          <div class="relative h-48 sm:h-56 md:h-64">
            <canvas id="trendChart"></canvas>
          </div>
        </div>
      </div>

      {{-- Stats Summary Cards --}}
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mt-4 sm:mt-6" id="moduleStats">
        {{-- Stats will be updated by JavaScript --}}
      </div>
    </section>

    {{-- KPI Cards --}}
    @php
      $totalBaik = ($aparData['baik'] ?? 0) + ($apatData['baik'] ?? 0) + ($apabData['baik'] ?? 0) + 
                   ($fireAlarmData['baik'] ?? 0) + ($boxHydrantData['baik'] ?? 0) + ($rumahPompaData['baik'] ?? 0);
      $totalRusak = ($aparData['rusak'] ?? 0) + ($apatData['rusak'] ?? 0) + ($apabData['tidak_baik'] ?? 0) + 
                    ($fireAlarmData['rusak'] ?? 0) + ($boxHydrantData['rusak'] ?? 0) + ($rumahPompaData['rusak'] ?? 0);
    @endphp
    <section class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-8">
      @foreach ([
        ['Total Item', $totalItems ?? 0, 'Semua modul', 'blue', 'M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z'],
        ['Kondisi Baik', $totalBaik, 'Siap digunakan', 'cyan', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['Perlu Perbaikan', $totalRusak, 'Segera perbaiki', 'sky', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
        ['Modul Aktif', '6', 'Sistem berjalan', 'blue', 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z'],
      ] as [$label, $val, $sub, $tone, $icon])
        <div class="group rounded-lg bg-white p-3 sm:p-6 shadow-md ring-1 ring-slate-200 hover:shadow-xl transition-transform duration-300">
          <div class="flex items-start justify-between mb-2 sm:mb-4">
            <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center 
              @if($tone==='blue') bg-blue-100 
              @elseif($tone==='cyan') bg-cyan-100 
              @else bg-sky-100 @endif">
              <svg class="w-6 h-6 
                @if($tone==='blue') text-blue-600 
                @elseif($tone==='cyan') text-cyan-600 
                @else text-sky-600 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
              </svg>
            </div>
          </div>
          <p class="text-gray-600 text-xs sm:text-sm font-medium">{{ $label }}</p>
          <p class="text-xl sm:text-3xl font-semibold mt-1 sm:mt-2 mb-2 sm:mb-3 
            @if($tone==='blue') text-blue-600 
            @elseif($tone==='cyan') text-cyan-600 
            @else text-sky-800 @endif">
            {{ $val }}
          </p>
          <div class="flex items-center gap-1.5 text-xs sm:text-sm 
            @if($tone==='blue') text-blue-700 
            @elseif($tone==='cyan') text-cyan-700 
            @else text-sky-600 @endif">
            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
            {{ $sub }}
          </div>
        </div>
      @endforeach
    </section>

    {{-- Quick Actions Section --}}
    <section class="mb-6 sm:mb-8">
      <h2 class="text-base sm:text-lg font-bold mb-3 sm:mb-4 flex items-center gap-2">
        <span class="w-1.5 h-5 sm:h-6 bg-gradient-to-b from-blue-500 to-blue-400 rounded-full"></span>
        Quick Actions
      </h2>
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-5">
        <a href="{{ route('quick.scan') }}" class="group relative rounded-lg bg-white p-3 sm:p-4 shadow-sm ring-1 ring-slate-200 hover:shadow-lg transition-all duration-300 overflow-hidden">
          <div class="relative z-10">
            <div class="flex items-start justify-between mb-2 sm:mb-3">
              <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center bg-blue-100">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
              </div>
              <span class="text-gray-400 group-hover:text-gray-600 group-hover:translate-x-1 transition-all text-lg sm:text-xl">→</span>
            </div>
            <h3 class="font-bold text-sm sm:text-md mb-1 sm:mb-2">Scan / Input QR</h3>
            <p class="text-xs sm:text-sm text-gray-600 mb-2">Gunakan scanner untuk tambah item.</p>
            <div class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-blue-500 text-white group-hover:bg-blue-600 transition-colors">
              Scan
            </div>
          </div>
        </a>

        <a href="{{ route('quick.inspeksi') }}" class="group relative rounded-lg bg-white p-3 sm:p-4 shadow-sm ring-1 ring-slate-200 hover:shadow-lg transition-all duration-300 overflow-hidden">
          <div class="relative z-10">
            <div class="flex items-start justify-between mb-2 sm:mb-3">
              <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center bg-sky-100">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
              <span class="text-gray-400 group-hover:text-gray-600 group-hover:translate-x-1 transition-all text-lg sm:text-xl">→</span>
            </div>
            <h3 class="font-bold text-sm sm:text-md mb-1 sm:mb-2">Buat Inspeksi</h3>
            <p class="text-xs sm:text-sm text-gray-600 mb-2">Catat status baik/rusak/perbaikan.</p>
            <div class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-sky-500 text-white group-hover:bg-sky-600 transition-colors">
              Form
            </div>
          </div>
        </a>

        <a href="{{ route('quick.rekap') }}" class="group relative rounded-lg bg-white p-3 sm:p-4 shadow-sm ring-1 ring-slate-200 hover:shadow-lg transition-all duration-300 overflow-hidden">
          <div class="relative z-10">
            <div class="flex items-start justify-between mb-2 sm:mb-3">
              <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center bg-cyan-100">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
              <span class="text-gray-400 group-hover:text-gray-600 group-hover:translate-x-1 transition-all text-lg sm:text-xl">→</span>
            </div>
            <h3 class="font-bold text-sm sm:text-md mb-1 sm:mb-2">Rekap & Export</h3>
            <p class="text-xs sm:text-sm text-gray-600 mb-2">Unduh laporan periodik.</p>
            <div class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold bg-cyan-500 text-white group-hover:bg-cyan-600 transition-colors">
              Export
            </div>
          </div>
        </a>
      </div>
    </section>

    {{-- Modules with grid layout --}}
    @php
      $modules = [
        ['APAR',       'Alat Pemadam Api Ringan',      'images/apar.png',        true,  'apar.index',     'from-blue-500 to-teal-500',    'from-blue-50 to-teal-50'],
        ['APAT',       'Alat Pemadam Api Tradisional', 'images/apat.png',        true,  'apat.index',     'from-cyan-500 to-sky-500',   'from-cyan-50 to-sky-50'],
        ['APAB',       'Alat Pemadam Api Berat',       'images/apab.png',        true,  'apab.index',     'from-red-500 to-orange-500',   'from-red-50 to-orange-50'],
        ['Fire Alarm', 'Panel & titik alarm',          'images/fire-alarm.png',  true,  'fire-alarm.index', 'from-red-500 to-pink-500',       'from-red-50 to-pink-50'],
        ['Box Hydrant','Box, hose, nozzle',            'images/box-hydrant.png', true,  'box-hydrant.index', 'from-blue-700 to-cyan-500',      'from-blue-50 to-cyan-50'],
        ['Rumah Pompa','Hydrant Rumah Pompa',          'images/box-hydrant.png', true,  'rumah-pompa.index', 'from-purple-600 to-indigo-600',  'from-purple-50 to-indigo-50'],
        ['P3K',        'Kotak & isi P3K',              'images/p3k.png',         true, 'p3k.pilih-jenis',       'from-emerald-500 to-teal-500',   'from-emerald-50 to-teal-50'],
        ['Referensi',  'Kategori/Lokasi/Petugas',      'images/referensi.png',   false,  'referensi.index', 'from-purple-500 to-indigo-500',  'from-purple-50 to-indigo-50'],
      ];
    @endphp

    <section id="modules">
      <div class="flex items-center justify-between mb-6">
        <div>
          <p class="text-sm text-gray-600">Pilih modul yang ingin Anda akses</p>
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

          <a href="{{ $href }}"
             class="group relative {{ $spanClass }} col-span-12 sm:col-span-6 rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
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

  {{-- Chart.js Script --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script>
    // Data dari Backend (Real-time)
    const moduleData = {
      'apar': {
        name: 'APAR',
        fullName: 'Alat Pemadam Api Ringan',
        baik: {{ $aparData['baik'] ?? 0 }},
        isi_ulang: {{ $aparData['isi_ulang'] ?? 0 }},
        rusak: {{ $aparData['rusak'] ?? 0 }},
        total: {{ $aparData['total'] ?? 0 }},
        color: 'rgb(59, 130, 246)',
        trendData: {!! json_encode($trendData['datasets']['APAR'] ?? [0,0,0,0,0,0]) !!}
      },
      'apat': {
        name: 'APAT',
        fullName: 'Alat Pemadam Api Tradisional',
        baik: {{ $apatData['baik'] ?? 0 }},
        isi_ulang: 0,
        rusak: {{ $apatData['rusak'] ?? 0 }},
        total: {{ $apatData['total'] ?? 0 }},
        color: 'rgb(6, 182, 212)',
        trendData: {!! json_encode($trendData['datasets']['APAT'] ?? [0,0,0,0,0,0]) !!}
      },
      'apab': {
        name: 'APAB',
        fullName: 'Alat Pemadam Api Berat',
        baik: {{ $apabData['baik'] ?? 0 }},
        isi_ulang: 0,
        rusak: {{ $apabData['tidak_baik'] ?? 0 }},
        total: {{ $apabData['total'] ?? 0 }},
        color: 'rgb(239, 68, 68)',
        trendData: {!! json_encode($trendData['datasets']['APAB'] ?? [0,0,0,0,0,0]) !!}
      },
      'fire-alarm': {
        name: 'Fire Alarm',
        fullName: 'Panel & Titik Alarm',
        baik: {{ $fireAlarmData['baik'] ?? 0 }},
        isi_ulang: 0,
        rusak: {{ $fireAlarmData['rusak'] ?? 0 }},
        total: {{ $fireAlarmData['total'] ?? 0 }},
        color: 'rgb(236, 72, 153)',
        trendData: {!! json_encode($trendData['datasets']['Fire Alarm'] ?? [0,0,0,0,0,0]) !!}
      },
      'box-hydrant': {
        name: 'Box Hydrant',
        fullName: 'Box, Hose, Nozzle',
        baik: {{ $boxHydrantData['baik'] ?? 0 }},
        isi_ulang: 0,
        rusak: {{ $boxHydrantData['rusak'] ?? 0 }},
        total: {{ $boxHydrantData['total'] ?? 0 }},
        color: 'rgb(14, 165, 233)',
        trendData: {!! json_encode($trendData['datasets']['Box Hydrant'] ?? [0,0,0,0,0,0]) !!}
      },
      'rumah-pompa': {
        name: 'Rumah Pompa',
        fullName: 'Hydrant Rumah Pompa',
        baik: {{ $rumahPompaData['baik'] ?? 0 }},
        isi_ulang: 0,
        rusak: {{ $rumahPompaData['rusak'] ?? 0 }},
        total: {{ $rumahPompaData['total'] ?? 0 }},
        color: 'rgb(168, 85, 247)',
        trendData: {!! json_encode($trendData['datasets']['Rumah Pompa'] ?? [0,0,0,0,0,0]) !!}
      }
    };

    let currentModule = 'apar';
    let statusChart = null;
    let trendChart = null;

    // Initialize Status Chart (Doughnut)
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
      statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
          labels: ['Baik', 'Rusak', 'Isi Ulang'],
          datasets: [{
            data: [0, 0, 0],
            backgroundColor: [
              'rgba(52, 211, 153, 0.85)',
              'rgba(248, 113, 113, 0.85)',
              'rgba(251, 191, 36, 0.85)'
            ],
            borderColor: [
              'rgb(16, 185, 129)',
              'rgb(239, 68, 68)',
              'rgb(245, 158, 11)'
            ],
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                padding: 12,
                font: {
                  size: 11,
                  family: "'Inter', sans-serif"
                },
                usePointStyle: true,
                pointStyle: 'circle'
              }
            },
            tooltip: {
              backgroundColor: 'rgba(0, 0, 0, 0.85)',
              padding: 12,
              titleFont: {
                size: 13,
                weight: 'bold'
              },
              bodyFont: {
                size: 12
              },
              callbacks: {
                label: function(context) {
                  return context.label + ': ' + context.parsed + ' unit';
                }
              }
            }
          },
          cutout: '70%'
        }
      });
    }

    // Initialize Trend Chart (Line)
    const trendCtx = document.getElementById('trendChart');
    if (trendCtx) {
      trendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
          labels: {!! json_encode($trendData['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) !!},
          datasets: [{
            label: 'Inspeksi',
            data: moduleData['apar'].trendData,
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: 'rgb(59, 130, 246)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointHoverBackgroundColor: 'rgb(59, 130, 246)',
            pointHoverBorderColor: '#fff',
            pointHoverBorderWidth: 3
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              backgroundColor: 'rgba(0, 0, 0, 0.85)',
              padding: 12,
              titleFont: {
                size: 13,
                weight: 'bold'
              },
              bodyFont: {
                size: 12
              },
              callbacks: {
                label: function(context) {
                  return 'Inspeksi: ' + context.parsed.y + ' unit';
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                font: {
                  size: 10
                },
                color: '#64748b',
                stepSize: 5
              },
              grid: {
                color: 'rgba(148, 163, 184, 0.1)',
                drawBorder: false
              }
            },
            x: {
              ticks: {
                font: {
                  size: 10
                },
                color: '#64748b'
              },
              grid: {
                display: false,
                drawBorder: false
              }
            }
          }
        }
      });
    }

    // Switch Module Function
    function switchModule(module) {
      currentModule = module;
      const data = moduleData[module];
      const isiUlang = data.isi_ulang || 0;
      
      // Update Status Chart
      if (statusChart) {
        statusChart.data.datasets[0].data = [data.baik, data.rusak, isiUlang];
        statusChart.update('active');
      }

      // Update Trend Chart
      if (trendChart) {
        trendChart.data.datasets[0].data = data.trendData;
        trendChart.data.datasets[0].borderColor = data.color;
        trendChart.data.datasets[0].backgroundColor = data.color.replace('rgb', 'rgba').replace(')', ', 0.1)');
        trendChart.data.datasets[0].pointBackgroundColor = data.color;
        trendChart.data.datasets[0].pointHoverBackgroundColor = data.color;
        trendChart.update('active');
      }

      // Update Chart Subtitle
      document.getElementById('statusChartSubtitle').textContent = data.fullName;

      // Update Stats Cards
      const statsHtml = `
        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-3 sm:p-5 shadow-sm ring-1 ring-blue-100">
          <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-2">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-lg sm:rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-md">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
              </svg>
            </div>
            <div>
              <p class="text-xs text-blue-700 font-medium">Total Unit</p>
              <p class="text-xl sm:text-2xl font-bold text-blue-900">${data.total}</p>
            </div>
          </div>
          <p class="text-xs text-blue-600 mt-1 sm:mt-2">${data.name}</p>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl p-3 sm:p-5 shadow-sm ring-1 ring-emerald-100">
          <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-2">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-lg sm:rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-md">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-xs text-emerald-700 font-medium">Kondisi Baik</p>
              <p class="text-xl sm:text-2xl font-bold text-emerald-900">${data.baik}</p>
            </div>
          </div>
          <div class="flex items-center justify-between mt-1 sm:mt-2">
            <div class="flex-1 bg-emerald-200 rounded-full h-1.5 mr-2">
              <div class="bg-emerald-500 h-1.5 rounded-full transition-all duration-500" style="width: ${data.total > 0 ? (data.baik / data.total) * 100 : 0}%"></div>
            </div>
            <span class="text-xs font-bold text-emerald-700">${data.total > 0 ? Math.round((data.baik / data.total) * 100) : 0}%</span>
          </div>
        </div>

        <div class="bg-gradient-to-br from-amber-50 to-yellow-50 rounded-xl p-3 sm:p-5 shadow-sm ring-1 ring-amber-100">
          <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-2">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-lg sm:rounded-xl bg-gradient-to-br from-amber-500 to-yellow-500 flex items-center justify-center shadow-md">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-xs text-amber-700 font-medium">Perlu Isi Ulang</p>
              <p class="text-xl sm:text-2xl font-bold text-amber-900">${isiUlang}</p>
            </div>
          </div>
          <div class="flex items-center justify-between mt-1 sm:mt-2">
            <div class="flex-1 bg-amber-200 rounded-full h-1.5 mr-2">
              <div class="bg-amber-500 h-1.5 rounded-full transition-all duration-500" style="width: ${data.total > 0 ? (isiUlang / data.total) * 100 : 0}%"></div>
            </div>
            <span class="text-xs font-bold text-amber-700">${data.total > 0 ? Math.round((isiUlang / data.total) * 100) : 0}%</span>
          </div>
        </div>

        <div class="bg-gradient-to-br from-rose-50 to-red-50 rounded-xl p-5 shadow-sm ring-1 ring-rose-100">
          <div class="flex items-center gap-3 mb-2">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-rose-500 to-red-500 flex items-center justify-center shadow-md">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
              </svg>
            </div>
            <div>
              <p class="text-xs text-rose-700 font-medium">Rusak / Tidak Baik</p>
              <p class="text-2xl font-bold text-rose-900">${data.rusak}</p>
            </div>
          </div>
          <div class="flex items-center justify-between mt-2">
            <div class="flex-1 bg-rose-200 rounded-full h-1.5 mr-2">
              <div class="bg-rose-500 h-1.5 rounded-full transition-all duration-500" style="width: ${data.total > 0 ? (data.rusak / data.total) * 100 : 0}%"></div>
            </div>
            <span class="text-xs font-bold text-rose-700">${data.total > 0 ? Math.round((data.rusak / data.total) * 100) : 0}%</span>
          </div>
        </div>
      `;
      document.getElementById('moduleStats').innerHTML = statsHtml;
    }

    // Initialize with APAR data
    switchModule('apar');
  </script>
</x-layouts.app>
