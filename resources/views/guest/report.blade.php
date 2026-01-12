<x-guest.layouts.guest>
    <x-slot name="title">Laporan Keseluruhan Peralatan</x-slot>

    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Back Button --}}
        <x-guest.back-button href="{{ route('guest.dashboard') }}" />

        {{-- Header --}}
        <div class="mb-6">
            <h1
                class="text-3xl font-bold tracking-tight bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent mb-2">
                Laporan Keseluruhan Peralatan
            </h1>
            <p class="text-sm text-slate-600">
                Ringkasan status semua peralatan K3
            </p>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium mb-1">Total Peralatan</p>
                        <p class="text-4xl font-bold">{{ $summary['total_equipment'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-emerald-100 text-sm font-medium mb-1">Kondisi Baik</p>
                        <p class="text-4xl font-bold">{{ $summary['total_baik'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-rose-100 text-sm font-medium mb-1">Perlu Perbaikan</p>
                        <p class="text-4xl font-bold">{{ $summary['total_rusak'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- APAR Section --}}
        @if($apars->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg ring-1 ring-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-cyan-50 border-b border-slate-200">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                        </svg>
                        APAR ({{ $apars->count() }})
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Serial No
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Inspeksi
                                    Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($apars as $apar)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $apar->serial_no }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $apar->location_code ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $apar->type ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusBadge = match (strtolower($apar->status ?? '')) {
                                                'baik' => 'bg-emerald-100 text-emerald-700',
                                                'isi ulang' => 'bg-amber-100 text-amber-700',
                                                'rusak' => 'bg-rose-100 text-rose-700',
                                                default => 'bg-slate-100 text-slate-600',
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusBadge }}">
                                            {{ strtoupper($apar->status ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        @if($apar->kartuApars->first())
                                            {{ \Carbon\Carbon::parse($apar->kartuApars->first()->tgl_periksa)->format('d M Y') }}
                                        @else
                                            <span class="text-slate-400">Belum ada</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- APAT Section --}}
        @if($apats->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg ring-1 ring-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-slate-200">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        APAT ({{ $apats->count() }})
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Serial No
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Inspeksi
                                    Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($apats as $apat)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $apat->serial_no }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $apat->location_code ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $apat->type ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusBadge = match (strtolower($apat->status ?? '')) {
                                                'baik' => 'bg-emerald-100 text-emerald-700',
                                                'rusak' => 'bg-rose-100 text-rose-700',
                                                default => 'bg-slate-100 text-slate-600',
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusBadge }}">
                                            {{ strtoupper($apat->status ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        @if($apat->kartuApats->first())
                                            {{ \Carbon\Carbon::parse($apat->kartuApats->first()->tgl_periksa)->format('d M Y') }}
                                        @else
                                            <span class="text-slate-400">Belum ada</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- P3K Section --}}
        @if($p3ks->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg ring-1 ring-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-slate-200">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        P3K ({{ $p3ks->count() }})
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Serial No
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Inspeksi
                                    Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($p3ks as $p3k)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $p3k->serial_no }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $p3k->location_code ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $p3k->jenis ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusBadge = match (strtolower($p3k->status ?? '')) {
                                                'baik' => 'bg-emerald-100 text-emerald-700',
                                                'rusak' => 'bg-rose-100 text-rose-700',
                                                default => 'bg-slate-100 text-slate-600',
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusBadge }}">
                                            {{ strtoupper($p3k->status ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        @if($p3k->kartuP3ks->first())
                                            {{ \Carbon\Carbon::parse($p3k->kartuP3ks->first()->tgl_periksa)->format('d M Y') }}
                                        @else
                                            <span class="text-slate-400">Belum ada</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif


        {{-- APAB Section --}}
        @if($apabs->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg ring-1 ring-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-orange-50 to-amber-50 border-b border-slate-200">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        APAB ({{ $apabs->count() }})
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Serial No
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Inspeksi
                                    Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($apabs as $apab)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $apab->serial_no }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $apab->location_code ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $apab->type ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusBadge = match (strtolower($apab->status ?? '')) {
                                                'baik' => 'bg-emerald-100 text-emerald-700',
                                                default => 'bg-rose-100 text-rose-700',
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusBadge }}">
                                            {{ strtoupper($apab->status ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        @if($apab->kartuApabs->first())
                                            {{ \Carbon\Carbon::parse($apab->kartuApabs->first()->tgl_periksa)->format('d M Y') }}
                                        @else
                                            <span class="text-slate-400">Belum ada</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Fire Alarm Section --}}
        @if($fireAlarms->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg ring-1 ring-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-red-50 to-rose-50 border-b border-slate-200">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        Fire Alarm ({{ $fireAlarms->count() }})
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Serial No
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Inspeksi
                                    Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($fireAlarms as $fireAlarm)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $fireAlarm->serial_no }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $fireAlarm->location_code ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $fireAlarm->type ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusBadge = match (strtolower($fireAlarm->status ?? '')) {
                                                'baik' => 'bg-emerald-100 text-emerald-700',
                                                'rusak' => 'bg-rose-100 text-rose-700',
                                                default => 'bg-slate-100 text-slate-600',
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusBadge }}">
                                            {{ strtoupper($fireAlarm->status ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        @if($fireAlarm->kartuFireAlarms->first())
                                            {{ \Carbon\Carbon::parse($fireAlarm->kartuFireAlarms->first()->tgl_periksa)->format('d M Y') }}
                                        @else
                                            <span class="text-slate-400">Belum ada</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Box Hydrant Section --}}
        @if($boxHydrants->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg ring-1 ring-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-cyan-50 to-blue-50 border-b border-slate-200">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Box Hydrant ({{ $boxHydrants->count() }})
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Serial No
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Inspeksi
                                    Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($boxHydrants as $boxHydrant)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $boxHydrant->serial_no }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $boxHydrant->location_code ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $boxHydrant->type ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusBadge = match (strtolower($boxHydrant->status ?? '')) {
                                                'baik' => 'bg-emerald-100 text-emerald-700',
                                                'rusak' => 'bg-rose-100 text-rose-700',
                                                default => 'bg-slate-100 text-slate-600',
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusBadge }}">
                                            {{ strtoupper($boxHydrant->status ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        @if($boxHydrant->kartuBoxHydrants->first())
                                            {{ \Carbon\Carbon::parse($boxHydrant->kartuBoxHydrants->first()->tgl_periksa)->format('d M Y') }}
                                        @else
                                            <span class="text-slate-400">Belum ada</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Rumah Pompa Section --}}
        @if($rumahPompas->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg ring-1 ring-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-slate-200">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Rumah Pompa ({{ $rumahPompas->count() }})
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Serial No
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Inspeksi
                                    Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($rumahPompas as $rumahPompa)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $rumahPompa->serial_no }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $rumahPompa->location_code ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $rumahPompa->type ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusBadge = match (strtolower($rumahPompa->status ?? '')) {
                                                'baik' => 'bg-emerald-100 text-emerald-700',
                                                'rusak' => 'bg-rose-100 text-rose-700',
                                                default => 'bg-slate-100 text-slate-600',
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusBadge }}">
                                            {{ strtoupper($rumahPompa->status ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        @if($rumahPompa->kartuRumahPompas->first())
                                            {{ \Carbon\Carbon::parse($rumahPompa->kartuRumahPompas->first()->tgl_periksa)->format('d M Y') }}
                                        @else
                                            <span class="text-slate-400">Belum ada</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</x-guest.layouts.guest>