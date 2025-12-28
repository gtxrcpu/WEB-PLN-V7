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
            ['apar', 'APAR', 'from-blue-500 to-teal-500', $stats['apar']],
            ['apat', 'APAT', 'from-cyan-500 to-sky-500', $stats['apat']],
            ['apab', 'APAB', 'from-red-500 to-orange-500', $stats['apab']],
            ['fire_alarm', 'Fire Alarm', 'from-red-500 to-pink-500', $stats['fire_alarm']],
            ['box_hydrant', 'Box Hydrant', 'from-blue-700 to-cyan-500', $stats['box_hydrant']],
            ['rumah_pompa', 'Rumah Pompa', 'from-purple-600 to-indigo-600', $stats['rumah_pompa']],
        ] as [$key, $name, $gradient, $data])
            <div class="bg-white rounded-2xl shadow-lg ring-1 ring-slate-200 overflow-hidden hover:shadow-xl transition-all">
                <div class="h-2 bg-gradient-to-r {{ $gradient }}"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-slate-900">{{ $name }}</h3>
                        <div class="flex gap-2">
                            <div class="relative group">
                                <button class="text-emerald-600 hover:text-emerald-700 transition-colors" title="Export Excel">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </button>
                                <div class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-10">
                                    <a href="{{ route('quick.export.excel', ['module' => $key, 'type' => 'equipment']) }}" 
                                       class="block px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 rounded-t-lg">Peralatan</a>
                                    <a href="{{ route('quick.export.excel', ['module' => $key, 'type' => 'kartu']) }}" 
                                       class="block px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 rounded-b-lg">Kartu Kendali</a>
                                </div>
                            </div>
                            <div class="relative group">
                                <button class="text-red-600 hover:text-red-700 transition-colors" title="Export PDF">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                                <div class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-10">
                                    <a href="{{ route('quick.export.pdf', ['module' => $key, 'type' => 'equipment']) }}" 
                                       class="block px-4 py-2 text-sm text-slate-700 hover:bg-red-50 rounded-t-lg">Peralatan</a>
                                    <a href="{{ route('quick.export.pdf', ['module' => $key, 'type' => 'kartu']) }}" 
                                       class="block px-4 py-2 text-sm text-slate-700 hover:bg-red-50 rounded-b-lg">Kartu Kendali</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-xs text-slate-600 mb-1">Total Unit</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $data['total'] }}</p>
                        </div>
                        <div class="bg-emerald-50 rounded-xl p-3">
                            <p class="text-xs text-emerald-700 mb-1">Baik</p>
                            <p class="text-2xl font-bold text-emerald-900">{{ $data['baik'] }}</p>
                        </div>
                        <div class="bg-rose-50 rounded-xl p-3">
                            <p class="text-xs text-rose-700 mb-1">Rusak</p>
                            <p class="text-2xl font-bold text-rose-900">{{ $data['rusak'] }}</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-3">
                            <p class="text-xs text-blue-700 mb-1">Inspeksi</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $data['inspeksi'] }}</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-slate-200">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-600">Persentase Baik</span>
                            <span class="font-bold text-emerald-600">
                                {{ $data['total'] > 0 ? round(($data['baik'] / $data['total']) * 100) : 0 }}%
                            </span>
                        </div>
                        <div class="mt-2 w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-2 rounded-full transition-all duration-500" 
                                 style="width: {{ $data['total'] > 0 ? ($data['baik'] / $data['total']) * 100 : 0 }}%"></div>
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
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Dapat diedit langsung
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Support formula & chart
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Styling & formatting
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
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Siap cetak langsung
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Format tetap konsisten
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Universal compatibility
                    </li>
                </ul>
            </div>
        </div>
    </div>

  </div>
</x-layouts.app>
