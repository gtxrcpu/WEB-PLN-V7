<x-layouts.app :title="'Admin - Manage APAT'">
  <div class="max-w-7xl mx-auto px-4 py-6 space-y-6">

    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Manage APAT</h1>
        <p class="text-sm text-gray-600 mt-1">Kelola data Alat Pemadam Api Tradisional</p>
      </div>
      <div class="flex gap-3">
        <a href="{{ route('admin.dashboard') }}"
           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium">
          ← Back to Dashboard
        </a>
        <a href="{{ route('admin.apat.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
          + Add New APAT
        </a>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="bg-white rounded-xl border-2 border-blue-200 p-4">
        <div class="text-sm text-gray-600 mb-1">Total APAT</div>
        <div class="text-3xl font-bold text-blue-600">{{ $apats->count() }}</div>
      </div>
      <div class="bg-white rounded-xl border-2 border-green-200 p-4">
        <div class="text-sm text-gray-600 mb-1">Kondisi Baik</div>
        <div class="text-3xl font-bold text-green-600">{{ $apats->where('status', 'baik')->count() }}</div>
      </div>
      <div class="bg-white rounded-xl border-2 border-yellow-200 p-4">
        <div class="text-sm text-gray-600 mb-1">Rusak</div>
        <div class="text-3xl font-bold text-yellow-600">{{ $apats->where('status', 'rusak')->count() }}</div>
      </div>
      <div class="bg-white rounded-xl border-2 border-purple-200 p-4">
        <div class="text-sm text-gray-600 mb-1">Total Inspeksi</div>
        <div class="text-3xl font-bold text-purple-600">{{ \App\Models\KartuApat::count() }}</div>
      </div>
    </div>

    <div class="bg-white rounded-xl border-2 border-slate-200 p-4">
      <form method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-64">
          <label class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
          <input type="text" name="search" value="{{ request('search') }}"
                 placeholder="Cari berdasarkan barcode, lokasi..."
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
          <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg text-sm">
            <option value="">Semua Status</option>
            <option value="baik" {{ request('status') === 'baik' ? 'selected' : '' }}>Baik</option>
            <option value="rusak" {{ request('status') === 'rusak' ? 'selected' : '' }}>Rusak</option>
            <option value="perbaikan" {{ request('status') === 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
          </select>
        </div>
        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
          Filter
        </button>
        @if(request()->hasAny(['search', 'status']))
        <a href="{{ route('admin.apat.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">
          Reset
        </a>
        @endif
      </form>
    </div>

    <div class="bg-white rounded-xl border-2 border-slate-200 shadow-lg overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b-2 border-gray-200">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">#</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Barcode</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Lokasi</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Type</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Kapasitas</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
              <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse($apats as $index => $apat)
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
              <td class="px-6 py-4">
                <div class="text-sm font-semibold text-gray-900">{{ $apat->barcode }}</div>
                <div class="text-xs text-gray-500">{{ $apat->serial_no ?? '-' }}</div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-900">{{ $apat->location_code ?? '-' }}</td>
              <td class="px-6 py-4 text-sm text-gray-900">{{ $apat->type ?? '-' }}</td>
              <td class="px-6 py-4 text-sm text-gray-900">{{ $apat->capacity ?? '-' }}</td>
              <td class="px-6 py-4">
                @if($apat->status === 'baik')
                  <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Baik</span>
                @elseif($apat->status === 'rusak')
                  <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rusak</span>
                @else
                  <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ ucfirst($apat->status) }}</span>
                @endif
              </td>
              <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center gap-2">
                  <a href="{{ route('admin.apat.show', $apat) }}"
                     class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 text-xs font-medium">
                    View
                  </a>
                  <a href="{{ route('admin.apat.edit', $apat) }}"
                     class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 text-xs font-medium">
                    Edit
                  </a>
                  <form method="POST" action="{{ route('admin.apat.destroy', $apat) }}" class="inline"
                        onsubmit="return confirm('Yakin ingin menghapus APAT ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 text-xs font-medium">
                      Delete
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                <p class="text-lg font-medium mb-2">Belum ada data APAT</p>
                <a href="{{ route('admin.apat.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                  + Add New APAT
                </a>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</x-layouts.app>
