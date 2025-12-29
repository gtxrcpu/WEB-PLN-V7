<x-layouts.app :title="'Riwayat Kartu Kendali — ' . $boxHydrant->barcode">
  <div class="mb-6">
    <a href="{{ route('box-hydrant.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors mb-4">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>
      Kembali
    </a>
    <h1 class="text-2xl font-bold text-gray-900">Riwayat Kartu Kendali</h1>
    <p class="text-sm text-gray-600 mt-1">{{ $boxHydrant->barcode ?? $boxHydrant->serial_no }} - {{ $boxHydrant->location_code }}</p>
  </div>

  {{-- Filter Form --}}
  <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
        </svg>
        Filter Riwayat
      </h2>
      @if(request()->hasAny(['creator', 'approver', 'status']))
        <a href="{{ route('box-hydrant.riwayat', $boxHydrant->id) }}" 
           class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
          Clear Filters
        </a>
      @endif
    </div>
    <form method="GET" action="{{ route('box-hydrant.riwayat', $boxHydrant->id) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div>
        <label for="creator" class="block text-sm font-medium text-gray-700 mb-1">Dibuat oleh</label>
        <input type="text" 
               name="creator" 
               id="creator" 
               value="{{ request('creator') }}"
               placeholder="Nama pembuat..."
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
      </div>
      <div>
        <label for="approver" class="block text-sm font-medium text-gray-700 mb-1">Di-approve oleh</label>
        <input type="text" 
               name="approver" 
               id="approver" 
               value="{{ request('approver') }}"
               placeholder="Nama approver..."
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
      </div>
      <div>
        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Approval</label>
        <select name="status" 
                id="status"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
          <option value="">Semua Status</option>
          <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
          <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
        </select>
      </div>
      <div class="flex items-end">
        <button type="submit" 
                class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold flex items-center justify-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          Filter
        </button>
      </div>
    </form>
  </div>

  <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-slate-50 border-b border-slate-200">
          <tr>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Tanggal</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Petugas</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Dibuat oleh</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Kesimpulan</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Status</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Di-approve oleh</th>
            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-700 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse($riwayatInspeksi as $kartu)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="px-6 py-4 text-sm text-gray-700">
                {{ \Carbon\Carbon::parse($kartu->tgl_periksa)->format('d M Y') }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ $kartu->petugas }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">
                <div class="flex items-center gap-2">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                  <span>{{ get_user_display_name($kartu->user, 'Unknown User') }}</span>
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                  @if($kartu->kesimpulan === 'baik') bg-green-100 text-green-700
                  @else bg-red-100 text-red-700 @endif">
                  {{ ucfirst($kartu->kesimpulan) }}
                </span>
              </td>
              <td class="px-6 py-4">
                @if($kartu->isApproved())
                  <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Approved
                  </span>
                @else
                  <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Pending
                  </span>
                @endif
              </td>
              <td class="px-6 py-4 text-sm text-gray-700">
                @if($kartu->isApproved())
                  <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                      <div class="font-medium">{{ get_user_display_name($kartu->approver, 'Unknown Approver') }}</div>
                      <div class="text-xs text-gray-500">{{ $kartu->approved_at->format('d M Y, H:i') }}</div>
                    </div>
                  </div>
                @else
                  <span class="text-gray-400 italic">Belum di-approve</span>
                @endif
              </td>
              <td class="px-6 py-4 text-right">
                <a href="{{ route('box-hydrant.view-kartu', [$boxHydrant->id, $kartu->id]) }}" 
                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                  Lihat
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="font-medium">Belum ada kartu kendali</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</x-layouts.app>
