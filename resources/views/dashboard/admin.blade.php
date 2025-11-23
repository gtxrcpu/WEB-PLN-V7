<x-layouts.app :title="'Dashboard — Admin'">
  {{-- Unit Viewing Info Banner (jika admin sedang viewing unit tertentu) --}}
  @if(session('viewing_unit_id'))
    @php $viewingUnit = \App\Models\Unit::find(session('viewing_unit_id')); @endphp
    @if($viewingUnit)
      <div class="mb-4 sm:mb-6 p-4 sm:p-6 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl shadow-lg">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
              <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-white/80 text-xs sm:text-sm font-medium">Sedang Melihat Data Unit</p>
              <h3 class="text-white text-lg sm:text-2xl font-bold">{{ $viewingUnit->code }}</h3>
              <p class="text-white/90 text-xs sm:text-sm mt-0.5">{{ $viewingUnit->name }}</p>
            </div>
          </div>
          <form method="POST" action="{{ route('unit.clear') }}">
            @csrf
            <button type="submit" class="px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur text-white rounded-lg text-sm font-semibold transition-colors">
              Lihat Semua Unit
            </button>
          </form>
        </div>
      </div>
    @endif
  @endif

  {{-- Header Section --}}
  <section class="mb-4 sm:mb-8 p-4 sm:p-8 shadow-lg rounded-lg bg-white">
    <div class="mb-4 sm:mb-6">
      <h2 class="text-xl sm:text-2xl font-bold">Admin Dashboard</h2>
      <p class="text-xs sm:text-sm text-gray-600 mt-1">Kelola sistem dan monitor semua modul</p>
    </div>

    {{-- Chart Section with Module Selector --}}
    <section class="mb-6 sm:mb-8">
      {{-- Module Selector --}}
      <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
          <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-1">Laporan Peralatan</h3>
          <p class="text-xs sm:text-sm text-slate-600">Pilih modul untuk melihat detail statistik</p>
        </div>
        <div class="relative w-full sm:w-auto">
          <select id="moduleSelector" onchange="switchModule(this.value)" 
                  class="w-full sm:w-auto appearance-none bg-white border-2 border-slate-200 rounded-xl px-4 py-2.5 pr-10 text-xs sm:text-sm font-semibold text-slate-700 hover:border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all cursor-pointer shadow-sm">
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

      {{-- Charts Grid - Row 1 --}}
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
        {{-- Status Peralatan Chart --}}
        <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg ring-1 ring-slate-200 hover:shadow-xl transition-shadow">
          <div class="flex items-center justify-between mb-4 sm:mb-5">
            <div>
              <h3 class="text-sm sm:text-base font-bold text-slate-900">Status Peralatan</h3>
              <p class="text-xs text-slate-600 mt-0.5" id="statusChartSubtitle">Kondisi semua modul</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center shadow-lg">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
            </div>
          </div>
          <div class="relative h-48 sm:h-56 md:h-64">
            <canvas id="statusChart"></canvas>
          </div>
        </div>

        {{-- User Statistics Chart --}}
        <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg ring-1 ring-slate-200 hover:shadow-xl transition-shadow">
          <div class="flex items-center justify-between mb-4 sm:mb-5">
            <div>
              <h3 class="text-sm sm:text-base font-bold text-slate-900">User Statistics</h3>
              <p class="text-xs text-slate-600 mt-0.5">Breakdown pengguna sistem</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
              </svg>
            </div>
          </div>
          <div class="relative h-48 sm:h-56 md:h-64">
            <canvas id="userChart"></canvas>
          </div>
        </div>
      </div>

      {{-- Charts Grid - Row 2 --}}
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
        {{-- Perbandingan Semua Modul (Bar Chart) --}}
        <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg ring-1 ring-slate-200 hover:shadow-xl transition-shadow">
          <div class="flex items-center justify-between mb-4 sm:mb-5">
            <div>
              <h3 class="text-sm sm:text-base font-bold text-slate-900">Perbandingan Modul</h3>
              <p class="text-xs text-slate-600 mt-0.5">Total equipment per modul</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center shadow-lg">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
              </svg>
            </div>
          </div>
          <div class="relative h-48 sm:h-56 md:h-64">
            <canvas id="comparisonChart"></canvas>
          </div>
        </div>

        {{-- Distribusi Equipment (Pie Chart) --}}
        <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg ring-1 ring-slate-200 hover:shadow-xl transition-shadow">
          <div class="flex items-center justify-between mb-4 sm:mb-5">
            <div>
              <h3 class="text-sm sm:text-base font-bold text-slate-900">Distribusi Equipment</h3>
              <p class="text-xs text-slate-600 mt-0.5">Proporsi per modul</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center shadow-lg">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
              </svg>
            </div>
          </div>
          <div class="relative h-48 sm:h-56 md:h-64">
            <canvas id="distributionChart"></canvas>
          </div>
        </div>
      </div>

      {{-- Charts Grid - Row 3 (Full Width) --}}
      <div class="grid grid-cols-1 gap-4 sm:gap-6 mb-4 sm:mb-6">
        {{-- Tren Inspeksi Bulanan (Line Chart) --}}
        <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg ring-1 ring-slate-200 hover:shadow-xl transition-shadow">
          <div class="flex items-center justify-between mb-4 sm:mb-5">
            <div>
              <h3 class="text-sm sm:text-base font-bold text-slate-900">Tren Inspeksi Bulanan</h3>
              <p class="text-xs text-slate-600 mt-0.5">Aktivitas inspeksi 12 bulan terakhir</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-lg">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
              </svg>
            </div>
          </div>
          <div class="relative h-64 sm:h-72 md:h-80">
            <canvas id="trendChart"></canvas>
          </div>
        </div>
      </div>

      {{-- Charts Grid - Row 4 --}}
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        {{-- Status Kondisi Semua Modul (Stacked Bar) --}}
        <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg ring-1 ring-slate-200 hover:shadow-xl transition-shadow">
          <div class="flex items-center justify-between mb-4 sm:mb-5">
            <div>
              <h3 class="text-sm sm:text-base font-bold text-slate-900">Status Kondisi per Modul</h3>
              <p class="text-xs text-slate-600 mt-0.5">Baik vs Rusak</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center shadow-lg">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
            </div>
          </div>
          <div class="relative h-48 sm:h-56 md:h-64">
            <canvas id="statusComparisonChart"></canvas>
          </div>
        </div>

        {{-- Persentase Kondisi Baik (Radar Chart) --}}
        <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-lg ring-1 ring-slate-200 hover:shadow-xl transition-shadow">
          <div class="flex items-center justify-between mb-4 sm:mb-5">
            <div>
              <h3 class="text-sm sm:text-base font-bold text-slate-900">Performa Modul</h3>
              <p class="text-xs text-slate-600 mt-0.5">% Kondisi baik per modul</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center shadow-lg">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
            </div>
          </div>
          <div class="relative h-48 sm:h-56 md:h-64">
            <canvas id="radarChart"></canvas>
          </div>
        </div>
      </div>

      {{-- Stats Summary Cards --}}
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mt-4 sm:mt-6" id="moduleStats">
        {{-- Stats will be updated by JavaScript --}}
      </div>
    </section>
  </section>

  {{-- KPI Cards --}}
  <section class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-8">
    @foreach ([
      ['Total Users', $totalUsers ?? 0, $totalAdmins . ' Admin • ' . $totalRegularUsers . ' User', 'blue', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
      ['Total Item', $totalItems ?? 0, 'Semua modul', 'cyan', 'M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z'],
      ['Kondisi Baik', $totalBaik, 'Siap digunakan', 'emerald', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
      ['Perlu Perbaikan', $totalRusak, 'Segera perbaiki', 'rose', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
    ] as [$label, $val, $sub, $tone, $icon])
      <div class="group rounded-lg bg-white p-3 sm:p-6 shadow-md ring-1 ring-slate-200 hover:shadow-xl transition-transform duration-300">
        <div class="flex items-start justify-between mb-2 sm:mb-4">
          <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center 
            @if($tone==='blue') bg-blue-100 
            @elseif($tone==='cyan') bg-cyan-100 
            @elseif($tone==='emerald') bg-emerald-100
            @else bg-rose-100 @endif">
            <svg class="w-6 h-6 
              @if($tone==='blue') text-blue-600 
              @elseif($tone==='cyan') text-cyan-600 
              @elseif($tone==='emerald') text-emerald-600
              @else text-rose-600 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
            </svg>
          </div>
        </div>
        <p class="text-gray-600 text-xs sm:text-sm font-medium">{{ $label }}</p>
        <p class="text-xl sm:text-3xl font-semibold mt-1 sm:mt-2 mb-2 sm:mb-3 
          @if($tone==='blue') text-blue-600 
          @elseif($tone==='cyan') text-cyan-600 
          @elseif($tone==='emerald') text-emerald-600
          @else text-rose-800 @endif">
          {{ $val }}
        </p>
        <div class="flex items-center gap-1.5 text-xs sm:text-sm 
          @if($tone==='blue') text-blue-700 
          @elseif($tone==='cyan') text-cyan-700 
          @elseif($tone==='emerald') text-emerald-700
          @else text-rose-600 @endif">
          <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
          {{ $sub }}
        </div>
      </div>
    @endforeach
  </section>

  {{-- Equipment Breakdown --}}
  <section class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
    {{-- Equipment by Type --}}
    <div class="bg-white rounded-xl p-4 sm:p-6 shadow-lg ring-1 ring-slate-200">
      <h3 class="text-base sm:text-lg font-bold mb-3 sm:mb-4 flex items-center gap-2">
        <span class="w-1.5 h-5 sm:h-6 bg-gradient-to-b from-purple-500 to-indigo-500 rounded-full"></span>
        Equipment by Type
      </h3>
      <div class="space-y-3">
        @foreach($equipmentByType as $item)
          <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 hover:bg-slate-100 transition-colors">
            <span class="font-medium text-gray-700">{{ $item['name'] }}</span>
            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-bold">{{ $item['count'] }}</span>
          </div>
        @endforeach
      </div>
    </div>

    {{-- Recent Users --}}
    <div class="bg-white rounded-xl p-4 sm:p-6 shadow-lg ring-1 ring-slate-200">
      <h3 class="text-base sm:text-lg font-bold mb-3 sm:mb-4 flex items-center gap-2">
        <span class="w-1.5 h-5 sm:h-6 bg-gradient-to-b from-blue-500 to-cyan-500 rounded-full"></span>
        Recent Users
      </h3>
      <div class="space-y-3">
        @forelse($recentUsers as $user)
          <div class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 hover:bg-slate-100 transition-colors">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-bold">
              {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-medium text-gray-900 truncate">{{ $user->name }}</p>
              <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
            </div>
            <span class="px-2 py-1 text-xs font-semibold rounded-full
              @if($user->hasRole('admin')) bg-purple-100 text-purple-700
              @else bg-blue-100 text-blue-700 @endif">
              {{ $user->getRoleNames()->first() ?? 'user' }}
            </span>
          </div>
        @empty
          <p class="text-gray-500 text-center py-4">Belum ada user</p>
        @endforelse
      </div>
    </div>
  </section>

  {{-- Quick Admin Actions --}}
  <section class="mb-6 sm:mb-8">
    <h2 class="text-base sm:text-lg font-bold mb-3 sm:mb-4 flex items-center gap-2">
      <span class="w-1.5 h-5 sm:h-6 bg-gradient-to-b from-purple-500 to-indigo-500 rounded-full"></span>
      Admin Actions
    </h2>
    <div class="grid md:grid-cols-3 gap-5">
      <a href="{{ route('admin.users.index') }}" class="group relative rounded-lg bg-white p-6 shadow-lg ring-1 ring-slate-200 hover:shadow-xl transition-all">
        <div class="flex items-start justify-between mb-3">
          <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-purple-100">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
          </div>
          <span class="text-gray-400 group-hover:text-gray-600 group-hover:translate-x-1 transition-all">→</span>
        </div>
        <h3 class="font-bold text-lg mb-2">Manage Users</h3>
        <p class="text-sm text-gray-600">Kelola akun user dan role</p>
      </a>

      <a href="{{ route('referensi.index') }}" class="group relative rounded-lg bg-white p-6 shadow-lg ring-1 ring-slate-200 hover:shadow-xl transition-all">
        <div class="flex items-start justify-between mb-3">
          <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-indigo-100">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
          </div>
          <span class="text-gray-400 group-hover:text-gray-600 group-hover:translate-x-1 transition-all">→</span>
        </div>
        <h3 class="font-bold text-lg mb-2">Referensi Data</h3>
        <p class="text-sm text-gray-600">Kategori, lokasi, petugas</p>
      </a>

      <a href="{{ route('admin.approvals.index') }}" class="group relative rounded-lg bg-white p-6 shadow-lg ring-1 ring-slate-200 hover:shadow-xl transition-all">
        <div class="flex items-start justify-between mb-3">
          <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-orange-100">
            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <span class="text-gray-400 group-hover:text-gray-600 group-hover:translate-x-1 transition-all">→</span>
        </div>
        <h3 class="font-bold text-lg mb-2">Pending Approvals</h3>
        <p class="text-sm text-gray-600">Approve kartu kendali</p>
      </a>

      <a href="{{ route('admin.signatures.index') }}" class="group relative rounded-lg bg-white p-6 shadow-lg ring-1 ring-slate-200 hover:shadow-xl transition-all">
        <div class="flex items-start justify-between mb-3">
          <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-pink-100">
            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
          <span class="text-gray-400 group-hover:text-gray-600 group-hover:translate-x-1 transition-all">→</span>
        </div>
        <h3 class="font-bold text-lg mb-2">Tanda Tangan</h3>
        <p class="text-sm text-gray-600">TTD pimpinan untuk approval</p>
      </a>

      <a href="{{ route('quick.rekap') }}" class="group relative rounded-lg bg-white p-6 shadow-lg ring-1 ring-slate-200 hover:shadow-xl transition-all">
        <div class="flex items-start justify-between mb-3">
          <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-cyan-100">
            <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 2 0 01-2 2z"/>
            </svg>
          </div>
          <span class="text-gray-400 group-hover:text-gray-600 group-hover:translate-x-1 transition-all">→</span>
        </div>
        <h3 class="font-bold text-lg mb-2">Reports</h3>
        <p class="text-sm text-gray-600">Export dan rekap data</p>
      </a>

      <a href="{{ route('admin.kartu-templates.index') }}" class="group relative rounded-lg bg-white p-6 shadow-lg ring-1 ring-slate-200 hover:shadow-xl transition-all">
        <div class="flex items-start justify-between mb-3">
          <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-indigo-100">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
          <span class="text-gray-400 group-hover:text-gray-600 group-hover:translate-x-1 transition-all">→</span>
        </div>
        <h3 class="font-bold text-lg mb-2">Kartu Kendali Settings</h3>
        <p class="text-sm text-gray-600">Template per modul</p>
      </a>
    </div>
  </section>

  {{-- All Modules Access --}}
  <section class="mb-8">
    <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
      <span class="w-1.5 h-6 bg-gradient-to-b from-blue-500 to-cyan-500 rounded-full"></span>
      Manage Equipment Modules
    </h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      @php
        $modules = [
          ['APAR', 'apar.index', 'from-blue-500 to-teal-500', 'M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z'],
          ['APAT', 'apat.index', 'from-cyan-500 to-sky-500', 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
          ['APAB', 'apab.index', 'from-red-500 to-orange-500', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
          ['Fire Alarm', 'fire-alarm.index', 'from-red-500 to-pink-500', 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
          ['Box Hydrant', 'box-hydrant.index', 'from-blue-700 to-cyan-500', 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'],
          ['Rumah Pompa', 'rumah-pompa.index', 'from-purple-600 to-indigo-600', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
          ['P3K', 'p3k.pilih-jenis', 'from-emerald-500 to-teal-500', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
          ['Referensi', 'referensi.index', 'from-purple-500 to-indigo-500', 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
        ];
      @endphp

      @foreach($modules as [$name, $route, $gradient, $icon])
        @php
          // Admin routes untuk equipment
          $adminRoute = match($name) {
            'APAR' => 'admin.apar.index',
            default => $route
          };
          
          // Map nama modul ke gambar
          $imagePath = match($name) {
            'APAR' => 'images/apar.png',
            'APAT' => 'images/apat.png',
            'APAB' => 'images/apab.png',
            'Fire Alarm' => 'images/fire-alarm.png',
            'Box Hydrant' => 'images/box-hydrant.png',
            'Rumah Pompa' => 'images/box-hydrant.png',
            'P3K' => 'images/p3k.png',
            'Referensi' => 'images/referensi.png',
            default => null
          };
        @endphp
        <a href="{{ route($adminRoute) }}" class="group relative rounded-xl bg-white p-5 shadow-md ring-1 ring-slate-200 hover:shadow-xl transition-all hover:scale-105">
          <div class="flex flex-col items-center text-center gap-3">
            <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform p-2">
              @if($imagePath)
                <img src="{{ asset($imagePath) }}" alt="{{ $name }}" class="w-full h-full object-contain">
              @else
                <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                </svg>
              @endif
            </div>
            <div>
              <h3 class="font-bold text-gray-900">{{ $name }}</h3>
              <p class="text-xs text-gray-500 mt-1">Manage</p>
            </div>
          </div>
        </a>
      @endforeach
    </div>
  </section>

  {{-- System Info --}}
  <section class="bg-gradient-to-r from-slate-50 to-slate-100 rounded-xl p-6 border border-slate-200">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="font-bold text-gray-900 mb-1">System Status</h3>
        <p class="text-sm text-gray-600">All systems operational</p>
      </div>
      <div class="flex items-center gap-2">
        <span class="flex h-3 w-3">
          <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-emerald-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
        </span>
        <span class="text-sm font-semibold text-emerald-600">Online</span>
      </div>
    </div>
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
        color: 'rgb(59, 130, 246)'
      },
      'apat': {
        name: 'APAT',
        fullName: 'Alat Pemadam Api Tradisional',
        baik: {{ $apatData['baik'] ?? 0 }},
        isi_ulang: 0,
        rusak: {{ $apatData['rusak'] ?? 0 }},
        total: {{ $apatData['total'] ?? 0 }},
        color: 'rgb(6, 182, 212)'
      },
      'apab': {
        name: 'APAB',
        fullName: 'Alat Pemadam Api Berat',
        baik: {{ $apabData['baik'] ?? 0 }},
        isi_ulang: 0,
        rusak: {{ $apabData['tidak_baik'] ?? 0 }},
        total: {{ $apabData['total'] ?? 0 }},
        color: 'rgb(239, 68, 68)'
      },
      'fire-alarm': {
        name: 'Fire Alarm',
        fullName: 'Panel & Titik Alarm',
        baik: {{ $fireAlarmData['baik'] ?? 0 }},
        isi_ulang: 0,
        rusak: {{ $fireAlarmData['rusak'] ?? 0 }},
        total: {{ $fireAlarmData['total'] ?? 0 }},
        color: 'rgb(236, 72, 153)'
      },
      'box-hydrant': {
        name: 'Box Hydrant',
        fullName: 'Box, Hose, Nozzle',
        baik: {{ $boxHydrantData['baik'] ?? 0 }},
        isi_ulang: 0,
        rusak: {{ $boxHydrantData['rusak'] ?? 0 }},
        total: {{ $boxHydrantData['total'] ?? 0 }},
        color: 'rgb(14, 165, 233)'
      },
      'rumah-pompa': {
        name: 'Rumah Pompa',
        fullName: 'Hydrant Rumah Pompa',
        baik: {{ $rumahPompaData['baik'] ?? 0 }},
        isi_ulang: 0,
        rusak: {{ $rumahPompaData['rusak'] ?? 0 }},
        total: {{ $rumahPompaData['total'] ?? 0 }},
        color: 'rgb(168, 85, 247)'
      }
    };

    // User data for chart
    const userData = {
      total: {{ $totalUsers }},
      admin: {{ $totalAdmins }},
      user: {{ $totalRegularUsers }}
    };

    let currentModule = 'apar';
    let statusChart = null;
    let userChart = null;
    let comparisonChart = null;
    let distributionChart = null;
    let trendChart = null;
    let statusComparisonChart = null;
    let radarChart = null;

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

    // Initialize User Chart (Doughnut)
    const userCtx = document.getElementById('userChart');
    if (userCtx) {
      userChart = new Chart(userCtx, {
        type: 'doughnut',
        data: {
          labels: ['Admin', 'User'],
          datasets: [{
            data: [userData.admin, userData.user],
            backgroundColor: [
              'rgba(147, 51, 234, 0.85)',
              'rgba(59, 130, 246, 0.85)'
            ],
            borderColor: [
              'rgb(126, 34, 206)',
              'rgb(37, 99, 235)'
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
                  return context.label + ': ' + context.parsed + ' user';
                }
              }
            }
          },
          cutout: '70%'
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

        <div class="bg-gradient-to-br from-rose-50 to-red-50 rounded-xl p-3 sm:p-5 shadow-sm ring-1 ring-rose-100">
          <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 mb-2">
            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-lg sm:rounded-xl bg-gradient-to-br from-rose-500 to-red-500 flex items-center justify-center shadow-md">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-xs text-rose-700 font-medium">Rusak / Tidak Baik</p>
              <p class="text-xl sm:text-2xl font-bold text-rose-900">${data.rusak}</p>
            </div>
          </div>
          <div class="flex items-center justify-between mt-1 sm:mt-2">
            <div class="flex-1 bg-rose-200 rounded-full h-1.5 mr-2">
              <div class="bg-rose-500 h-1.5 rounded-full transition-all duration-500" style="width: ${data.total > 0 ? (data.rusak / data.total) * 100 : 0}%"></div>
            </div>
            <span class="text-xs font-bold text-rose-700">${data.total > 0 ? Math.round((data.rusak / data.total) * 100) : 0}%</span>
          </div>
        </div>
      `;
      document.getElementById('moduleStats').innerHTML = statsHtml;
    }

    // Initialize Comparison Chart (Bar Chart)
    const comparisonCtx = document.getElementById('comparisonChart');
    if (comparisonCtx) {
      comparisonChart = new Chart(comparisonCtx, {
        type: 'bar',
        data: {
          labels: ['APAR', 'APAT', 'APAB', 'Fire Alarm', 'Box Hydrant', 'Rumah Pompa'],
          datasets: [{
            label: 'Total Equipment',
            data: [
              moduleData['apar'].total,
              moduleData['apat'].total,
              moduleData['apab'].total,
              moduleData['fire-alarm'].total,
              moduleData['box-hydrant'].total,
              moduleData['rumah-pompa'].total
            ],
            backgroundColor: [
              'rgba(59, 130, 246, 0.8)',
              'rgba(6, 182, 212, 0.8)',
              'rgba(239, 68, 68, 0.8)',
              'rgba(236, 72, 153, 0.8)',
              'rgba(14, 165, 233, 0.8)',
              'rgba(168, 85, 247, 0.8)'
            ],
            borderColor: [
              'rgb(59, 130, 246)',
              'rgb(6, 182, 212)',
              'rgb(239, 68, 68)',
              'rgb(236, 72, 153)',
              'rgb(14, 165, 233)',
              'rgb(168, 85, 247)'
            ],
            borderWidth: 2,
            borderRadius: 8
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
              titleFont: { size: 13, weight: 'bold' },
              bodyFont: { size: 12 },
              callbacks: {
                label: function(context) {
                  return 'Total: ' + context.parsed.y + ' unit';
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                font: { size: 10 },
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
                font: { size: 10 },
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

    // Initialize Distribution Chart (Pie Chart)
    const distributionCtx = document.getElementById('distributionChart');
    if (distributionCtx) {
      distributionChart = new Chart(distributionCtx, {
        type: 'pie',
        data: {
          labels: ['APAR', 'APAT', 'APAB', 'Fire Alarm', 'Box Hydrant', 'Rumah Pompa'],
          datasets: [{
            data: [
              moduleData['apar'].total,
              moduleData['apat'].total,
              moduleData['apab'].total,
              moduleData['fire-alarm'].total,
              moduleData['box-hydrant'].total,
              moduleData['rumah-pompa'].total
            ],
            backgroundColor: [
              'rgba(59, 130, 246, 0.85)',
              'rgba(6, 182, 212, 0.85)',
              'rgba(239, 68, 68, 0.85)',
              'rgba(236, 72, 153, 0.85)',
              'rgba(14, 165, 233, 0.85)',
              'rgba(168, 85, 247, 0.85)'
            ],
            borderColor: [
              'rgb(59, 130, 246)',
              'rgb(6, 182, 212)',
              'rgb(239, 68, 68)',
              'rgb(236, 72, 153)',
              'rgb(14, 165, 233)',
              'rgb(168, 85, 247)'
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
                font: { size: 11, family: "'Inter', sans-serif" },
                usePointStyle: true,
                pointStyle: 'circle'
              }
            },
            tooltip: {
              backgroundColor: 'rgba(0, 0, 0, 0.85)',
              padding: 12,
              titleFont: { size: 13, weight: 'bold' },
              bodyFont: { size: 12 },
              callbacks: {
                label: function(context) {
                  const total = context.dataset.data.reduce((a, b) => a + b, 0);
                  const percentage = ((context.parsed / total) * 100).toFixed(1);
                  return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                }
              }
            }
          }
        }
      });
    }

    // Initialize Trend Chart (Line Chart)
    const trendCtx = document.getElementById('trendChart');
    if (trendCtx) {
      trendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
          labels: {!! json_encode($monthLabels) !!},
          datasets: [{
            label: 'Inspeksi',
            data: {!! json_encode($monthlyInspections) !!},
            borderColor: 'rgb(16, 185, 129)',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: 'rgb(16, 185, 129)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointHoverBackgroundColor: 'rgb(16, 185, 129)',
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
              titleFont: { size: 13, weight: 'bold' },
              bodyFont: { size: 12 },
              callbacks: {
                label: function(context) {
                  return 'Inspeksi: ' + context.parsed.y + ' kali';
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                font: { size: 10 },
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
                font: { size: 10 },
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

    // Initialize Status Comparison Chart (Stacked Bar)
    const statusComparisonCtx = document.getElementById('statusComparisonChart');
    if (statusComparisonCtx) {
      statusComparisonChart = new Chart(statusComparisonCtx, {
        type: 'bar',
        data: {
          labels: ['APAR', 'APAT', 'APAB', 'Fire Alarm', 'Box Hydrant', 'Rumah Pompa'],
          datasets: [
            {
              label: 'Baik',
              data: [
                moduleData['apar'].baik,
                moduleData['apat'].baik,
                moduleData['apab'].baik,
                moduleData['fire-alarm'].baik,
                moduleData['box-hydrant'].baik,
                moduleData['rumah-pompa'].baik
              ],
              backgroundColor: 'rgba(52, 211, 153, 0.8)',
              borderColor: 'rgb(16, 185, 129)',
              borderWidth: 2,
              borderRadius: 6
            },
            {
              label: 'Rusak',
              data: [
                moduleData['apar'].rusak,
                moduleData['apat'].rusak,
                moduleData['apab'].rusak,
                moduleData['fire-alarm'].rusak,
                moduleData['box-hydrant'].rusak,
                moduleData['rumah-pompa'].rusak
              ],
              backgroundColor: 'rgba(248, 113, 113, 0.8)',
              borderColor: 'rgb(239, 68, 68)',
              borderWidth: 2,
              borderRadius: 6
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                padding: 12,
                font: { size: 11, family: "'Inter', sans-serif" },
                usePointStyle: true,
                pointStyle: 'circle'
              }
            },
            tooltip: {
              backgroundColor: 'rgba(0, 0, 0, 0.85)',
              padding: 12,
              titleFont: { size: 13, weight: 'bold' },
              bodyFont: { size: 12 }
            }
          },
          scales: {
            y: {
              stacked: true,
              beginAtZero: true,
              ticks: {
                font: { size: 10 },
                color: '#64748b'
              },
              grid: {
                color: 'rgba(148, 163, 184, 0.1)',
                drawBorder: false
              }
            },
            x: {
              stacked: true,
              ticks: {
                font: { size: 10 },
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

    // Initialize Radar Chart
    const radarCtx = document.getElementById('radarChart');
    if (radarCtx) {
      radarChart = new Chart(radarCtx, {
        type: 'radar',
        data: {
          labels: ['APAR', 'APAT', 'APAB', 'Fire Alarm', 'Box Hydrant', 'Rumah Pompa'],
          datasets: [{
            label: '% Kondisi Baik',
            data: [
              moduleData['apar'].total > 0 ? (moduleData['apar'].baik / moduleData['apar'].total * 100).toFixed(1) : 0,
              moduleData['apat'].total > 0 ? (moduleData['apat'].baik / moduleData['apat'].total * 100).toFixed(1) : 0,
              moduleData['apab'].total > 0 ? (moduleData['apab'].baik / moduleData['apab'].total * 100).toFixed(1) : 0,
              moduleData['fire-alarm'].total > 0 ? (moduleData['fire-alarm'].baik / moduleData['fire-alarm'].total * 100).toFixed(1) : 0,
              moduleData['box-hydrant'].total > 0 ? (moduleData['box-hydrant'].baik / moduleData['box-hydrant'].total * 100).toFixed(1) : 0,
              moduleData['rumah-pompa'].total > 0 ? (moduleData['rumah-pompa'].baik / moduleData['rumah-pompa'].total * 100).toFixed(1) : 0
            ],
            backgroundColor: 'rgba(6, 182, 212, 0.2)',
            borderColor: 'rgb(6, 182, 212)',
            borderWidth: 2,
            pointBackgroundColor: 'rgb(6, 182, 212)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(6, 182, 212)',
            pointRadius: 5,
            pointHoverRadius: 7
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
              titleFont: { size: 13, weight: 'bold' },
              bodyFont: { size: 12 },
              callbacks: {
                label: function(context) {
                  return context.parsed.r.toFixed(1) + '%';
                }
              }
            }
          },
          scales: {
            r: {
              beginAtZero: true,
              max: 100,
              ticks: {
                stepSize: 20,
                font: { size: 10 },
                color: '#64748b',
                callback: function(value) {
                  return value + '%';
                }
              },
              grid: {
                color: 'rgba(148, 163, 184, 0.2)'
              },
              pointLabels: {
                font: { size: 11 },
                color: '#475569'
              }
            }
          }
        }
      });
    }

    // Initialize with APAR data
    switchModule('apar');
  </script>
</x-layouts.app>
