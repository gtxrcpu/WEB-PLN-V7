@php
    $statusLower = strtolower($equipment->status ?? '');
    
    // Determine status configuration
    if ($statusLower === 'baik') {
        $statusConfig = [
            'badge' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'gradient' => 'from-emerald-500 to-teal-500',
            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
        ];
    } elseif ($statusLower === 'isi ulang') {
        $statusConfig = [
            'badge' => 'bg-amber-100 text-amber-700 border-amber-200',
            'gradient' => 'from-amber-500 to-orange-500',
            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
        ];
    } elseif ($statusLower === 'rusak' || $statusLower === 'tidak_baik') {
        $statusConfig = [
            'badge' => 'bg-rose-100 text-rose-700 border-rose-200',
            'gradient' => 'from-rose-500 to-red-500',
            'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
        ];
    } else {
        $statusConfig = [
            'badge' => 'bg-slate-100 text-slate-600 border-slate-200',
            'gradient' => 'from-slate-500 to-slate-600',
            'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
        ];
    }
@endphp

<x-layouts.app :title="'Hasil Scan - ' . $typeName">
  <div class="max-w-4xl mx-auto px-4 py-6 space-y-6">

    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('quick.scan') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border-2 border-slate-200 text-slate-700 text-sm font-medium hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Scan Lagi</span>
        </a>
    </div>


    {{-- Success Alert --}}
    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm text-emerald-800 font-semibold">Peralatan Ditemukan!</p>
        </div>
    </div>

    {{-- Equipment Card --}}
    <div class="bg-white rounded-2xl shadow-xl ring-1 ring-slate-200 overflow-hidden">
        {{-- Header with Gradient --}}
        <div class="relative bg-gradient-to-r {{ $statusConfig['gradient'] }} p-6 text-white">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-white/80 text-sm font-medium">{{ $typeName }}</p>
                    <h1 class="text-2xl font-bold">{{ $equipment->serial_no }}</h1>
                </div>
                <span class="inline-flex items-center gap-1.5 rounded-full border-2 border-white/30 bg-white/20 backdrop-blur-sm px-4 py-2 text-sm font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusConfig['icon'] }}"/>
                    </svg>
                    {{ strtoupper($equipment->status ?? 'N/A') }}
                </span>
            </div>
        </div>


        {{-- Equipment Details --}}
        <div class="p-6 space-y-6">
            {{-- Info Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-xl bg-slate-50 p-4 border border-slate-200">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium">Serial Number</p>
                            <p class="text-lg font-bold text-slate-900">{{ $equipment->serial_no }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl bg-slate-50 p-4 border border-slate-200">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium">Barcode</p>
                            <p class="text-lg font-bold text-slate-900">{{ $equipment->barcode ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl bg-slate-50 p-4 border border-slate-200">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-lg bg-cyan-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium">Lokasi</p>
                            <p class="text-lg font-bold text-slate-900">{{ $equipment->location_code ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl bg-slate-50 p-4 border border-slate-200">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium">Tipe</p>
                            <p class="text-lg font-bold text-slate-900">{{ $equipment->type ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                @if(isset($equipment->capacity))
                <div class="rounded-xl bg-slate-50 p-4 border border-slate-200">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium">Kapasitas</p>
                            <p class="text-lg font-bold text-slate-900">{{ $equipment->capacity }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if(isset($equipment->zone))
                <div class="rounded-xl bg-slate-50 p-4 border border-slate-200">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium">Zone</p>
                            <p class="text-lg font-bold text-slate-900">{{ $equipment->zone }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>


            {{-- Notes Section --}}
            @if($equipment->notes)
            <div class="rounded-xl bg-amber-50 border border-amber-200 p-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-amber-900 mb-1">Catatan</p>
                        <p class="text-sm text-amber-800">{{ $equipment->notes }}</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- QR Code Display --}}
            <div class="rounded-xl bg-gradient-to-br from-slate-50 to-slate-100 border-2 border-dashed border-slate-300 p-6">
                <div class="text-center">
                    <p class="text-sm font-semibold text-slate-700 mb-4">QR Code Peralatan</p>
                    <div class="inline-block p-4 bg-white rounded-xl shadow-lg">
                        <img src="{{ $equipment->qr_url }}" 
                             alt="QR Code {{ $equipment->serial_no }}"
                             class="w-48 h-48 object-contain">
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @php
                    // Determine riwayat route based on type
                    if ($type === 'apar') {
                        $riwayatRoute = route('apar.riwayat', $equipment);
                    } elseif ($type === 'apat') {
                        $riwayatRoute = route('apat.riwayat', $equipment);
                    } elseif ($type === 'apab') {
                        $riwayatRoute = route('apab.riwayat', $equipment);
                    } elseif ($type === 'fire-alarm') {
                        $riwayatRoute = route('fire-alarm.riwayat', $equipment);
                    } elseif ($type === 'box-hydrant') {
                        $riwayatRoute = route('box-hydrant.riwayat', $equipment);
                    } elseif ($type === 'rumah-pompa') {
                        $riwayatRoute = route('rumah-pompa.riwayat', $equipment);
                    } else {
                        $riwayatRoute = '#';
                    }
                @endphp

                <a href="{{ $riwayatRoute }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-semibold hover:from-purple-700 hover:to-indigo-700 shadow-lg shadow-purple-500/30 hover:shadow-xl hover:shadow-purple-500/40 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Lihat Riwayat Lengkap</span>
                </a>

                <a href="{{ route('quick.scan') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl border-2 border-slate-300 text-slate-700 text-sm font-semibold hover:bg-slate-50 hover:border-slate-400 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    <span>Scan Peralatan Lain</span>
                </a>
            </div>
        </div>
    </div>

  </div>
</x-layouts.app>
