{{-- resources/views/p3k/riwayat.blade.php --}}
<x-layouts.app :title="'Riwayat Inspeksi P3K ' . ($p3k->serial_no ?? '')">
  <div class="max-w-7xl mx-auto px-4 py-6 space-y-6">

    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('p3k.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border-2 border-slate-200 text-slate-700 text-sm font-medium hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Daftar P3K</span>
        </a>
    </div>

    {{-- Header --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-600 via-emerald-600 to-green-700 p-8 shadow-xl">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="relative">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">Riwayat Inspeksi</h1>
                    <p class="text-green-100 text-sm">P3K {{ $p3k->barcode ?? $p3k->serial_no }}</p>
                </div>
            </div>
            <div class="grid grid-cols-4 gap-4 mt-6">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                    <p class="text-green-100 text-xs mb-1">Lokasi</p>
                    <p class="text-white font-semibold">{{ $p3k->location_code ?? '-' }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                    <p class="text-green-100 text-xs mb-1">Tipe</p>
                    <p class="text-white font-semibold">{{ $p3k->type ?? '-' }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                    <p class="text-green-100 text-xs mb-1">Total Inspeksi</p>
                    <p class="text-white font-semibold">{{ $riwayatInspeksi->count() }} kali</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                    <a href="{{ route('p3k.pilih-jenis') }}" 
                       class="flex items-center gap-2 text-white hover:text-green-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="font-semibold">Buat Kartu Baru</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filter Riwayat
            </h2>
            @if(request()->hasAny(['creator', 'status', 'jenis']))
                <a href="{{ route('p3k.riwayat', $p3k->id) }}" 
                   class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear Filters
                </a>
            @endif
        </div>
        <form method="GET" action="{{ route('p3k.riwayat', $p3k->id) }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kartu</label>
                <select name="jenis" 
                        id="jenis"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                    <option value="">Semua Jenis</option>
                    <option value="pemeriksaan" {{ request('jenis') === 'pemeriksaan' ? 'selected' : '' }}>Pemeriksaan</option>
                    <option value="pemakaian" {{ request('jenis') === 'pemakaian' ? 'selected' : '' }}>Pemakaian</option>
                    <option value="stock" {{ request('jenis') === 'stock' ? 'selected' : '' }}>Stock</option>
                </select>
            </div>
            <div>
                <label for="creator" class="block text-sm font-medium text-gray-700 mb-1">Dibuat oleh</label>
                <input type="text" 
                       name="creator" 
                       id="creator" 
                       value="{{ request('creator') }}"
                       placeholder="Nama pembuat..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Approval</label>
                <select name="status" 
                        id="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                    <option value="">Semua Status</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-colors text-sm font-semibold flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Riwayat List --}}
    @if($riwayatInspeksi->count() > 0)
        <div class="space-y-4">
            @foreach($riwayatInspeksi as $index => $kartu)
                @php
                    $jenisLabel = match($kartu->jenis ?? 'legacy') {
                        'pemeriksaan' => ['label' => 'Pemeriksaan', 'color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        'pemakaian' => ['label' => 'Pemakaian', 'color' => 'blue', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        'stock' => ['label' => 'Stock', 'color' => 'purple', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                        default => ['label' => 'Legacy', 'color' => 'gray', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    };
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-{{ $jenisLabel['color'] }}-500 to-{{ $jenisLabel['color'] }}-600 flex items-center justify-center text-white font-bold shadow-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $jenisLabel['icon'] }}"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-lg font-bold text-slate-900">
                                            {{ $jenisLabel['label'] }}
                                        </h3>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $jenisLabel['color'] }}-100 text-{{ $jenisLabel['color'] }}-700">
                                            {{ $jenisLabel['label'] }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-600">
                                        {{ $kartu->tanggal->format('d M Y') }} • {{ $kartu->tanggal->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1.5 rounded-full border px-4 py-2 text-sm font-semibold 
                                {{ $kartu->kesimpulan === 'baik' || $kartu->kesimpulan === 'lengkap' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-rose-100 text-rose-700 border-rose-200' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($kartu->kesimpulan === 'baik' || $kartu->kesimpulan === 'lengkap')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    @endif
                                </svg>
                                {{ strtoupper($kartu->kesimpulan) }}
                            </span>
                        </div>

                        {{-- Detail berdasarkan jenis --}}
                        @if(($kartu->jenis ?? '') === 'pemakaian')
                            <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-100">
                                <div class="grid grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <p class="text-blue-600 text-xs mb-1">Item Digunakan</p>
                                        <p class="font-semibold text-blue-900">{{ $kartu->item_digunakan }}</p>
                                    </div>
                                    <div>
                                        <p class="text-blue-600 text-xs mb-1">Jumlah</p>
                                        <p class="font-semibold text-blue-900">{{ $kartu->jumlah }}</p>
                                    </div>
                                    @if($kartu->nama_pengguna)
                                    <div>
                                        <p class="text-blue-600 text-xs mb-1">Pengguna</p>
                                        <p class="font-semibold text-blue-900">{{ $kartu->nama_pengguna }}</p>
                                    </div>
                                    @endif
                                </div>
                                @if($kartu->keperluan)
                                    <div class="mt-2 pt-2 border-t border-blue-200">
                                        <p class="text-blue-600 text-xs mb-1">Keperluan</p>
                                        <p class="text-sm text-blue-900">{{ $kartu->keperluan }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($kartu->catatan)
                            <div class="mb-4 p-3 bg-slate-50 rounded-lg border border-slate-100">
                                <p class="text-xs text-slate-500 mb-1">Catatan</p>
                                <p class="text-sm text-slate-700">{{ $kartu->catatan }}</p>
                            </div>
                        @endif

                        <div class="pt-4 border-t border-slate-200">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Petugas</p>
                                            <p class="text-sm font-semibold text-slate-900">{{ $kartu->petugas }}</p>
                                        </div>
                                    </div>
                                    @if($kartu->user)
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-500 to-slate-600 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-500">Dibuat oleh</p>
                                                <p class="text-sm font-semibold text-slate-900">{{ get_user_display_name($kartu->user, 'Unknown User') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-slate-500">Tanggal</p>
                                    <p class="text-sm font-semibold text-slate-900">{{ $kartu->tanggal->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            @if($kartu->isApproved())
                                <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-xs text-slate-500">Di-approve oleh</p>
                                        <p class="text-sm font-semibold text-slate-900">{{ get_user_display_name($kartu->approver, 'Unknown Approver') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-slate-500">{{ $kartu->approved_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
                                    <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm text-slate-600 italic">Menunggu approval</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="relative rounded-2xl border-2 border-dashed border-slate-300 p-12 text-center bg-gradient-to-br from-slate-50 to-white">
            <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-xl">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Belum Ada Riwayat Inspeksi</h3>
            <p class="text-slate-600 text-sm mb-6">
                P3K ini belum pernah diinspeksi. Buat kartu kendali untuk memulai inspeksi.
            </p>
            <a href="{{ route('p3k.pilih-jenis') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-semibold hover:from-green-700 hover:to-emerald-700 shadow-lg shadow-green-500/30 hover:shadow-xl hover:shadow-green-500/40 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Buat Kartu Kendali Pertama</span>
            </a>
        </div>
    @endif

  </div>
</x-layouts.app>
