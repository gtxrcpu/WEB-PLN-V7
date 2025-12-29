<x-layouts.app :title="'Pending Approvals — Admin'">
  <div class="mb-4 sm:mb-6">
    <a href="{{ route('admin.dashboard') }}" 
            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white hover:bg-slate-50 text-slate-700 transition-colors shadow-sm border border-slate-200 mb-4">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
      </svg>
      <span class="text-sm font-medium">Kembali ke Dashboard</span>
    </a>

    <h1 class="text-2xl font-bold text-gray-900">Pending Approvals</h1>
    <p class="text-sm text-gray-600 mt-1">Kartu kendali yang menunggu approval dari admin</p>
  </div>

  @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
      <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <span class="text-green-800 font-medium">{{ session('success') }}</span>
    </div>
  @endif

  <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-slate-50 border-b border-slate-200">
          <tr>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Modul</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Equipment</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Tanggal Periksa</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Petugas</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Kesimpulan</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Dibuat Oleh</th>
            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-700 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse($pendingApprovals as $kartu)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="px-6 py-4">
                <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-lg
                  @if($kartu->module === 'APAR') bg-red-100 text-red-700
                  @elseif($kartu->module === 'APAT') bg-cyan-100 text-cyan-700
                  @elseif($kartu->module === 'APAB') bg-orange-100 text-orange-700
                  @elseif($kartu->module === 'Fire Alarm') bg-rose-100 text-rose-700
                  @elseif($kartu->module === 'Box Hydrant') bg-blue-100 text-blue-700
                  @elseif($kartu->module === 'Rumah Pompa') bg-purple-100 text-purple-700
                  @elseif($kartu->module === 'P3K') bg-emerald-100 text-emerald-700
                  @else bg-slate-100 text-slate-700 @endif">
                  {{ $kartu->module }}
                </span>
              </td>
              <td class="px-6 py-4">
                <p class="font-semibold text-gray-900">{{ $kartu->equipment_name }}</p>
              </td>
              <td class="px-6 py-4 text-sm text-gray-700">
                {{ \Carbon\Carbon::parse($kartu->tgl_periksa)->format('d M Y') }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-700">{{ $kartu->petugas }}</td>
              <td class="px-6 py-4">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                  @if($kartu->kesimpulan === 'baik') bg-green-100 text-green-700
                  @elseif($kartu->kesimpulan === 'rusak') bg-red-100 text-red-700
                  @else bg-yellow-100 text-yellow-700 @endif">
                  {{ ucfirst($kartu->kesimpulan) }}
                </span>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm">
                  <p class="font-medium text-gray-900">{{ get_user_display_name($kartu->user, 'Unknown User') }}</p>
                  @if($kartu->user)
                    <p class="text-xs text-gray-500">{{ $kartu->user->username ?? '-' }}</p>
                    <p class="text-xs text-gray-500">{{ get_user_role_display($kartu->user) }}</p>
                  @endif
                </div>
              </td>
              <td class="px-6 py-4 text-right">
                <a href="{{ $kartu->route_show }}" 
                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm font-semibold">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  Approve
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="font-medium">Tidak ada kartu kendali yang menunggu approval</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</x-layouts.app>
