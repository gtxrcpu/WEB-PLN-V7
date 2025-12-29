{{-- resources/views/rumah-pompa/index.blade.php --}}
<x-layouts.app :title="'Rumah Pompa — Hydrant Rumah Pompa'">
  <div class="max-w-7xl mx-auto px-4 py-6 space-y-6">

    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border-2 border-slate-200 text-slate-700 text-sm font-medium hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

    {{-- Header dengan Stats --}}
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                    Manajemen Rumah Pompa
                </h1>
                <p class="text-sm text-slate-600 mt-1">
                    Kelola Hydrant Rumah Pompa dan pantau status setiap unit
                </p>
            </div>

            @hasanyrole('superadmin|leader|petugas')
            <div class="flex items-center gap-3">
                <a href="{{ route('rumah-pompa.create') }}"
                   class="group inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-semibold hover:from-purple-700 hover:to-indigo-700 shadow-lg shadow-purple-500/30 hover:shadow-xl hover:shadow-purple-500/40 transition-all duration-300">
                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Rumah Pompa</span>
                </a>
            </div>
            @endhasanyrole
        </div>

        {{-- Stats Cards --}}
        @php
            $totalRumahPompa = ($rumahPompas ?? collect())->count();
            $statusBaik = ($rumahPompas ?? collect())->where('status', 'baik')->count();
            $statusRusak = ($rumahPompas ?? collect())->where('status', 'rusak')->count();
        @endphp

        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 p-5 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-white/80 text-sm font-medium">Total Rumah Pompa</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $totalRumahPompa }}</p>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 p-5 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-white/80 text-sm font-medium">Kondisi Baik</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $statusBaik }}</p>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 to-rose-600 p-5 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-white/80 text-sm font-medium">Rusak</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $statusRusak }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Search Box --}}
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <input type="text" 
               id="searchInput"
               placeholder="Cari serial number, barcode, atau lokasi..." 
               class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
               onkeyup="filterItems()">
    </div>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-sm text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    {{-- No Results Message --}}
    <div id="noResults" style="display: none;" class="text-center py-12">
        <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <p class="text-lg font-semibold text-slate-900 mb-2">Tidak ada hasil</p>
        <p class="text-sm text-slate-600">Coba kata kunci lain</p>
    </div>

    {{-- Grid Rumah Pompa --}}
    @if(($rumahPompas ?? null) && $rumahPompas->count())
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($rumahPompas as $rumahPompa)
          @php
            $kodePendek = $rumahPompa->barcode ?? $rumahPompa->serial_no ?? '—';
            $statusLower = strtolower($rumahPompa->status ?? '');
            
            $statusConfig = match($statusLower) {
                'baik'       => [
                    'badge' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'gradient' => 'from-emerald-500 to-teal-500',
                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                ],
                'rusak'     => [
                    'badge' => 'bg-rose-100 text-rose-700 border-rose-200',
                    'gradient' => 'from-rose-500 to-red-500',
                    'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
                ],
                default      => [
                    'badge' => 'bg-slate-100 text-slate-600 border-slate-200',
                    'gradient' => 'from-slate-500 to-slate-600',
                    'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                ],
            };
          @endphp

          <div data-item 
               data-serial="{{ $rumahPompa->serial_no }}" 
               data-barcode="{{ $rumahPompa->barcode }}" 
               data-location="{{ $rumahPompa->location_code }}"
               class="group relative rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-xl hover:border-slate-300 transition-all duration-300 overflow-hidden">
            {{-- Gradient accent bar --}}
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r {{ $statusConfig['gradient'] }}"></div>
            
            <div class="p-6">
                {{-- Header dengan Status Badge --}}
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $statusConfig['gradient'] }} flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-slate-900 truncate">
                                    Rumah Pompa {{ $kodePendek }}
                                </h3>
                                @if($rumahPompa->location_code)
                                    <p class="text-sm text-slate-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $rumahPompa->location_code }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($rumahPompa->status)
                        <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-xs font-semibold {{ $statusConfig['badge'] }} shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusConfig['icon'] }}"/>
                            </svg>
                            {{ strtoupper($rumahPompa->status) }}
                        </span>
                    @endif
                </div>

                {{-- Info Grid --}}
                <div class="grid grid-cols-2 gap-3 mb-5">
                    <div class="rounded-xl bg-slate-50 p-3 border border-slate-100">
                        <p class="text-xs text-slate-500 mb-1">Tipe</p>
                        <p class="text-sm font-semibold text-slate-900">{{ $rumahPompa->type ?? '—' }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-3 border border-slate-100">
                        <p class="text-xs text-slate-500 mb-1">Zone</p>
                        <p class="text-sm font-semibold text-slate-900">{{ $rumahPompa->zone ?? '—' }}</p>
                    </div>
                </div>

                {{-- QR Code Section - LEBIH BESAR --}}
                <div class="relative mb-5">
                    <div class="absolute inset-0 bg-gradient-to-br {{ $statusConfig['gradient'] }} opacity-5 rounded-2xl"></div>
                    <div class="relative flex flex-col items-center justify-center p-5 rounded-2xl border-2 border-dashed border-slate-200 bg-white">
                        <div class="mb-3">
                            <img src="{{ $rumahPompa->qr_url }}"
                                 alt="QR Rumah Pompa {{ $kodePendek }}"
                                 class="w-40 h-40 object-contain rounded-xl shadow-lg ring-4 ring-white bg-white"
                                 loading="lazy">
                        </div>
                        <div class="text-center">
                            <p class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Scan QR Code</p>
                            <p class="text-xs text-slate-500 mt-0.5">untuk akses cepat</p>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col gap-2">
                    @hasanyrole('superadmin|leader|petugas')
                    <a href="{{ route('rumah-pompa.kartu.create', ['rumah_pompa_id' => $rumahPompa->id]) }}"
                       class="group/btn relative overflow-hidden inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-sky-600 to-blue-600 text-white text-sm font-semibold hover:from-sky-700 hover:to-blue-700 shadow-lg shadow-sky-500/30 hover:shadow-xl hover:shadow-sky-500/40 transition-all duration-300">
                        <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Kartu Kendali</span>
                    </a>
                    @endhasanyrole

                    <a href="{{ route('rumah-pompa.riwayat', $rumahPompa) }}"
                       class="group/btn inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-semibold hover:from-purple-700 hover:to-indigo-700 shadow-lg shadow-purple-500/30 hover:shadow-xl hover:shadow-purple-500/40 transition-all duration-300">
                        <svg class="w-4 h-4 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Lihat Riwayat</span>
                        @php
                            $jumlahKartu = $rumahPompa->kartuRumahPompas()->count();
                        @endphp
                        @if($jumlahKartu > 0)
                            <span class="ml-1 px-2 py-0.5 bg-white/20 rounded-full text-xs font-bold">
                                {{ $jumlahKartu }}
                            </span>
                        @endif
                    </a>

                    @hasanyrole('superadmin|leader|petugas')
                    <a href="{{ route('rumah-pompa.edit', $rumahPompa) }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border-2 border-slate-200 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Edit Rumah Pompa</span>
                    </a>
                    @endhasanyrole
                </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="relative rounded-2xl border-2 border-dashed border-slate-300 p-12 text-center bg-gradient-to-br from-slate-50 to-white overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-purple-500/5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-500/5 rounded-full -ml-32 -mb-32"></div>
        
        <div class="relative">
            <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-xl">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            
            <h3 class="text-xl font-bold text-slate-900 mb-2">Belum Ada Data Rumah Pompa</h3>
            <p class="text-slate-600 text-sm mb-1">
                Mulai kelola Rumah Pompa dengan menambahkan unit pertama Anda
            </p>
            <p class="text-xs text-slate-500 mb-6">
                Setelah menambahkan Rumah Pompa, Anda dapat membuat Kartu Kendali untuk setiap unit
            </p>
            
            <a href="{{ route('rumah-pompa.create') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-semibold hover:from-purple-700 hover:to-indigo-700 shadow-lg shadow-purple-500/30 hover:shadow-xl hover:shadow-purple-500/40 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Rumah Pompa Pertama</span>
            </a>
        </div>
      </div>
    @endif

  </div>

  <script>
  function filterItems() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const items = document.querySelectorAll('[data-item]');
    let visibleCount = 0;

    items.forEach(item => {
      const serialNo = item.getAttribute('data-serial')?.toLowerCase() || '';
      const barcode = item.getAttribute('data-barcode')?.toLowerCase() || '';
      const location = item.getAttribute('data-location')?.toLowerCase() || '';
      
      const isMatch = serialNo.includes(searchValue) || 
                     barcode.includes(searchValue) || 
                     location.includes(searchValue);
      
      if (isMatch) {
        item.style.display = '';
        visibleCount++;
      } else {
        item.style.display = 'none';
      }
    });

    const noResults = document.getElementById('noResults');
    if (noResults) {
      noResults.style.display = visibleCount === 0 && searchValue.length > 0 ? 'block' : 'none';
    }
  }
  </script>
</x-layouts.app>
