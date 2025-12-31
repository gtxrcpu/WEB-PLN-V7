<x-layouts.app :title="'Rekap & Export Laporan'">
  <div class="max-w-7xl mx-auto px-4 py-6 space-y-6">
    <div class="mb-4">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border-2 border-slate-200 text-slate-700 text-sm font-medium hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent">
                Rekap & Export Laporan
            </h1>
            <p class="text-sm text-slate-600 mt-1">Unduh laporan periodik dalam format Excel atau PDF</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex gap-2">
                <a href="{{ route('quick.export.excel', ['module' => 'all', 'type' => 'equipment']) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-sm font-semibold hover:from-emerald-700 hover:to-teal-700 shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Excel Peralatan</span>
                </a>
                <a href="{{ route('quick.export.pdf', ['module' => 'all', 'type' => 'equipment']) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-semibold hover:from-red-700 hover:to-rose-700 shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span>PDF Peralatan</span>
                </a>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('quick.export.excel', ['module' => 'all', 'type' => 'kartu']) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Excel Kartu</span>
                </a>
                <a href="{{ route('quick.export.pdf', ['module' => 'all', 'type' => 'kartu']) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-semibold hover:from-purple-700 hover:to-pink-700 shadow-lg shadow-purple-500/30 hover:shadow-xl hover:shadow-purple-500/40 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>PDF Kartu</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach([
            ['apar', 'APAR', 'from-red-500 to-orange-500'],
            ['apat', 'APAT', 'from-purple-500 to-pink-500'],
            ['apab', 'APAB', 'from-orange-500 to-amber-500'],
            ['fire_alarm', 'Fire Alarm', 'from-rose-500 to-red-500'],
            ['box_hydrant', 'Box Hydrant', 'from-blue-500 to-cyan-500'],
            ['rumah_pompa', 'Rumah Pompa', 'from-indigo-500 to-purple-500'],
        ] as [$key, $name, $gradient])
            <div class="bg-white rounded-2xl shadow-lg ring-1 ring-slate-200 overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                <div class="h-2 bg-gradient-to-r {{ $gradient }}"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-slate-900">{{ $name }}</h3>
                        <div class="flex gap-2">
                            <div class="relative group">
                                <button class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-700 transition-all flex items-center justify-center" title="Export Excel">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <div class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-10 overflow-hidden">
                                    <a href="{{ route('quick.export.excel', ['module' => $key, 'type' => 'equipment']) }}" 
                                       class="flex items-center gap-2 px-4 py-3 text-sm text-slate-700 hover:bg-emerald-50 transition-colors">
                                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/>
                                            <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                        </svg>
                                        <span class="font-medium">Peralatan</span>
                                    </a>
                                    <a href="{{ route('quick.export.excel', ['module' => $key, 'type' => 'kartu']) }}" 
                                       class="flex items-center gap-2 px-4 py-3 text-sm text-slate-700 hover:bg-emerald-50 transition-colors">
                                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="font-medium">Kartu Kendali</span>
                                    </a>
                                </div>
                            </div>
                            <div class="relative group">
                                <button class="w-9 h-9 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-all flex items-center justify-center" title="Export PDF">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <div class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-10 overflow-hidden">
                                    <a href="{{ route('quick.export.pdf', ['module' => $key, 'type' => 'equipment']) }}" 
                                       class="flex items-center gap-2 px-4 py-3 text-sm text-slate-700 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/>
                                            <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                        </svg>
                                        <span class="font-medium">Peralatan</span>
                                    </a>
                                    <a href="{{ route('quick.export.pdf', ['module' => $key, 'type' => 'kartu']) }}" 
                                       class="flex items-center gap-2 px-4 py-3 text-sm text-slate-700 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="font-medium">Kartu Kendali</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-4 border border-slate-200">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <p class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Total</p>
                            </div>
                            <p class="text-3xl font-bold text-slate-900">{{ $stats[$key]['total'] }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-4 border border-emerald-200">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wide">Baik</p>
                            </div>
                            <p class="text-3xl font-bold text-emerald-900">{{ $stats[$key]['baik'] }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-rose-50 to-rose-100 rounded-xl p-4 border border-rose-200">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <p class="text-xs font-semibold text-rose-700 uppercase tracking-wide">Rusak</p>
                            </div>
                            <p class="text-3xl font-bold text-rose-900">{{ $stats[$key]['rusak'] }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide">Inspeksi</p>
                            </div>
                            <p class="text-3xl font-bold text-blue-900">{{ $stats[$key]['inspeksi'] }}</p>
                        </div>
                    </div>

                    <div class="mt-5 pt-5 border-t border-slate-200">
                        <div class="flex items-center justify-between text-sm mb-2">
                            <span class="text-slate-600 font-medium">Kondisi Baik</span>
                            <span class="font-bold text-emerald-600 text-lg">
                                {{ $stats[$key]['total'] > 0 ? round(($stats[$key]['baik'] / $stats[$key]['total']) * 100) : 0 }}%
                            </span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden shadow-inner">
                            <div class="bg-gradient-to-r from-emerald-500 via-emerald-400 to-teal-500 h-3 rounded-full transition-all duration-700 ease-out shadow-lg" 
                                 style="width: {{ $stats[$key]['total'] > 0 ? ($stats[$key]['baik'] / $stats[$key]['total']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Export Options --}}
    <div class="bg-gradient-to-br from-slate-50 to-white rounded-2xl shadow-lg ring-1 ring-slate-200 p-8">
        <h2 class="text-xl font-bold text-slate-900 mb-6">Format Export</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-6 rounded-xl border-2 border-emerald-200 bg-emerald-50 hover:border-emerald-400 transition-all">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 rounded-xl bg-emerald-500 flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-emerald-900">Excel (.xlsx)</h3>
                        <p class="text-sm text-emerald-700">Format Microsoft Excel</p>
                    </div>
                </div>
                <ul class="space-y-2 text-sm text-emerald-800">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Dapat diedit langsung</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Support formula & chart</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Styling & formatting</span>
                    </li>
                </ul>
            </div>

            <div class="p-6 rounded-xl border-2 border-red-200 bg-red-50 hover:border-red-400 transition-all">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 rounded-xl bg-red-500 flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-red-900">PDF (.pdf)</h3>
                        <p class="text-sm text-red-700">Portable Document Format</p>
                    </div>
                </div>
                <ul class="space-y-2 text-sm text-red-800">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Siap cetak langsung</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Format tetap konsisten</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Universal compatibility</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

  </div>
</x-layouts.app>
