{{-- resources/views/apab/index.blade.php --}}
<x-layouts.app :title="'APAB — Alat Pemadam Api Berat'">
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
                <h1 class="text-3xl font-bold tracking-tight bg-gradient-to-r from-red-600 to-orange-600 bg-clip-text text-transparent">
                    Manajemen APAB
                </h1>
                <p class="text-sm text-slate-600 mt-1">
                    Kelola Alat Pemadam Api Berat dan pantau status setiap unit
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('apab.create') }}"
                   class="group inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-red-600 to-orange-600 text-white text-sm font-semibold hover:from-red-700 hover:to-orange-700 shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 transition-all duration-300">
                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah APAB</span>
                </a>
            </div>
        </div>

        {{-- Stats Cards --}}
        @php
            $totalApab = ($apabs ?? collect())->count();
            $statusBaik = ($apabs ?? collect())->where('status', 'baik')->count();
            $statusTidakBaik = ($apabs ?? collect())->where('status', 'tidak_baik')->count();
        @endphp

        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-red-500 to-orange-600 p-5 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-white/80 text-sm font-medium">Total APAB</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $totalApab }}</p>
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
                    <p class="text-white/80 text-sm font-medium">Tidak Baik</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $statusTidakBaik }}</p>
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
               class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
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

    {{-- Grid APAB --}}
    @if(($apabs ?? null) && $apabs->count())
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($apabs as $apab)
          @php
            $kodePendekRaw = $apab->barcode ?? $apab->serial_no ?? '—';
            $kodePendek = trim(preg_replace('/^(APAB\s*)+/i', '', $kodePendekRaw));

            $statusLower = strtolower($apab->status ?? '');
            $statusConfig = match($statusLower) {
                'baik'       => [
                    'badge' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'gradient' => 'from-emerald-500 to-teal-500',
                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                ],
                'tidak_baik' => [
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

            $qrAsset = $apab->qr_svg_path ? asset($apab->qr_svg_path) : null;
          @endphp

          <div data-item 
               data-serial="{{ $apab->serial_no }}" 
               data-barcode="{{ $apab->barcode }}" 
               data-location="{{ $apab->location_code }}"
               class="group relative rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-xl hover:border-slate-300 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r {{ $statusConfig['gradient'] }}"></div>
            
            <div class="p-6">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $statusConfig['gradient'] }} flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-slate-900 truncate">
                                    APAB {{ $kodePendek }}
                                </h3>
                                @if($apab->location_code)
                                    <p class="text-sm text-slate-600 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $apab->location_code }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($apab->status)
                        <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-xs font-semibold {{ $statusConfig['badge'] }} shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusConfig['icon'] }}"/>
                            </svg>
                            {{ strtoupper($apab->status) }}
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-3 mb-5">
                    <div class="rounded-xl bg-slate-50 p-3 border border-slate-100">
                        <p class="text-xs text-slate-500 mb-1">Isi APAB</p>
                        <p class="text-sm font-semibold text-slate-900">{{ $apab->isi_apab ?? '—' }}</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-3 border border-slate-100">
                        <p class="text-xs text-slate-500 mb-1">Kapasitas</p>
                        <p class="text-sm font-semibold text-slate-900">{{ $apab->capacity ?? '—' }}</p>
                    </div>
                </div>

                <div class="relative mb-5">
                    <div class="absolute inset-0 bg-gradient-to-br {{ $statusConfig['gradient'] }} opacity-5 rounded-2xl"></div>
                    <div class="relative flex flex-col items-center justify-center p-5 rounded-2xl border-2 border-dashed border-slate-200 bg-white">
                        @if($qrAsset)
                            <div class="mb-3">
                                <img src="{{ $qrAsset }}"
                                     alt="QR APAB {{ $kodePendek }}"
                                     class="w-40 h-40 object-contain rounded-xl shadow-lg ring-4 ring-white bg-white">
                            </div>
                            <div class="text-center">
                                <p class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Scan QR Code</p>
                                <p class="text-xs text-slate-500 mt-0.5">untuk akses cepat</p>
                            </div>
                        @else
                            <div class="w-40 h-40 flex items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50">
                                <div class="text-center px-4">
                                    <svg class="w-12 h-12 mx-auto text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                    </svg>
                                    <p class="text-xs text-slate-500">QR belum dibuat</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <a href="{{ route('apab.kartu.create', ['apab_id' => $apab->id]) }}"
                       class="group/btn relative overflow-hidden inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-sky-600 to-blue-600 text-white text-sm font-semibold hover:from-sky-700 hover:to-blue-700 shadow-lg shadow-sky-500/30 hover:shadow-xl hover:shadow-sky-500/40 transition-all duration-300">
                        <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Kartu Kendali</span>
                    </a>

                    <a href="{{ route('apab.riwayat', $apab) }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border-2 border-red-200 bg-red-50 text-sm font-medium text-red-700 hover:bg-red-100 hover:border-red-300 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span>Lihat Riwayat</span>
                    </a>

                    <a href="{{ route('apab.edit', $apab) }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border-2 border-slate-200 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Edit APAB</span>
                    </a>
                </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="relative rounded-2xl border-2 border-dashed border-slate-300 p-12 text-center bg-gradient-to-br from-slate-50 to-white overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-red-500/5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-orange-500/5 rounded-full -ml-32 -mb-32"></div>
        
        <div class="relative">
            <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-red-500 to-orange-500 flex items-center justify-center shadow-xl">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            
            <h3 class="text-xl font-bold text-slate-900 mb-2">Belum Ada Data APAB</h3>
            <p class="text-slate-600 text-sm mb-1">
                Mulai kelola APAB dengan menambahkan unit pertama Anda
            </p>
            <p class="text-xs text-slate-500 mb-6">
                Setelah menambahkan APAB, Anda dapat membuat Kartu Kendali untuk setiap unit
            </p>
            
            <a href="{{ route('apab.create') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-red-600 to-orange-600 text-white text-sm font-semibold hover:from-red-700 hover:to-orange-700 shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah APAB Pertama</span>
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
