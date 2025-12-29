<x-guest.layouts.guest>
    <x-slot name="title">Riwayat Kartu Kendali APAB - Guest Access</x-slot>

    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Back Button --}}
        <x-guest.back-button href="{{ route('guest.apab') }}" />

        {{-- Header --}}
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-2xl font-bold text-gray-900">Riwayat Kartu Kendali</h1>
            </div>
            <p class="text-sm text-gray-600">{{ $apab->barcode ?? $apab->serial_no }} - {{ $apab->location_code }}</p>
        </div>

        {{-- Equipment Info Card --}}
        <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Informasi Peralatan
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                    <p class="text-xs text-slate-500 mb-1">Serial Number</p>
                    <p class="text-sm font-semibold text-slate-900">{{ $apab->serial_no ?? '—' }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                    <p class="text-xs text-slate-500 mb-1">Tipe</p>
                    <p class="text-sm font-semibold text-slate-900">{{ $apab->type ?? '—' }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                    <p class="text-xs text-slate-500 mb-1">Kapasitas</p>
                    <p class="text-sm font-semibold text-slate-900">{{ $apab->capacity ?? '—' }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4 border border-slate-100">
                    <p class="text-xs text-slate-500 mb-1">Status</p>
                    @php
                        $statusLower = strtolower($apab->status ?? '');
                        $statusBadge = match($statusLower) {
                            'baik' => 'bg-emerald-100 text-emerald-700',
                            'rusak', 'tidak baik' => 'bg-rose-100 text-rose-700',
                            default => 'bg-slate-100 text-slate-600',
                        };
                    @endphp
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusBadge }}">
                        {{ strtoupper($apab->status ?? 'N/A') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- History Table --}}
        <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Riwayat Inspeksi
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Petugas</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Kesimpulan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($riwayatInspeksi as $kartu)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($kartu->tgl_periksa)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $kartu->petugas ?? '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $kesimpulanLower = strtolower($kartu->kesimpulan ?? '');
                                        $kesimpulanBadge = match($kesimpulanLower) {
                                            'baik' => 'bg-green-100 text-green-700',
                                            'tidak baik', 'rusak' => 'bg-red-100 text-red-700',
                                            default => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $kesimpulanBadge }}">
                                        {{ ucfirst($kartu->kesimpulan ?? 'N/A') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="font-medium">Belum ada kartu kendali</p>
                                    <p class="text-sm text-gray-400 mt-1">Riwayat inspeksi akan muncul di sini</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-guest.layouts.guest>
