<x-layouts.app :title="'Detail APAR — Admin'">
  <div class="mb-6">
    <a href="{{ route('admin.apar.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors mb-4">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>
      Kembali
    </a>
    <h1 class="text-2xl font-bold text-gray-900">Detail APAR</h1>
    <p class="text-sm text-gray-600 mt-1">{{ $apar->serial_no }} - {{ $apar->barcode }}</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Info APAR --}}
    <div class="lg:col-span-2 space-y-6">
      <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 p-6">
        <h2 class="text-lg font-bold mb-4">Informasi APAR</h2>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <p class="text-sm text-gray-600">Serial Number</p>
            <p class="font-semibold">{{ $apar->serial_no }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Barcode</p>
            <p class="font-semibold">{{ $apar->barcode }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Lokasi</p>
            <p class="font-semibold">{{ $apar->location_code }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Tipe</p>
            <p class="font-semibold">{{ $apar->type }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Kapasitas</p>
            <p class="font-semibold">{{ $apar->capacity }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Agent</p>
            <p class="font-semibold">{{ $apar->agent ?? '-' }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Status</p>
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
              @if(strtolower($apar->status) === 'baik') bg-green-100 text-green-700
              @elseif(strtolower($apar->status) === 'isi ulang') bg-yellow-100 text-yellow-700
              @else bg-red-100 text-red-700 @endif">
              {{ ucfirst($apar->status) }}
            </span>
          </div>
        </div>
        @if($apar->notes)
          <div class="mt-4 pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600 mb-1">Catatan</p>
            <p class="text-sm">{{ $apar->notes }}</p>
          </div>
        @endif
      </div>

      {{-- Riwayat Kartu Kendali --}}
      <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 p-6">
        <h2 class="text-lg font-bold mb-4">Riwayat Kartu Kendali</h2>
        @if($apar->kartuApars->count() > 0)
          <div class="space-y-3">
            @foreach($apar->kartuApars as $kartu)
              <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($kartu->tgl_periksa)->format('d M Y') }}</p>
                    <p class="text-sm text-gray-600">Petugas: {{ $kartu->petugas }}</p>
                    <p class="text-sm">
                      Kesimpulan: 
                      <span class="font-semibold
                        @if($kartu->kesimpulan === 'baik') text-green-600
                        @else text-red-600 @endif">
                        {{ ucfirst($kartu->kesimpulan) }}
                      </span>
                    </p>
                  </div>
                  <div class="text-right">
                    @if($kartu->isApproved())
                      <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Approved
                      </span>
                      <p class="text-xs text-gray-500 mt-1">{{ get_user_display_name($kartu->approver, 'Admin') }}</p>
                    @else
                      <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pending
                      </span>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <p class="text-gray-500 text-center py-8">Belum ada kartu kendali</p>
        @endif
      </div>
    </div>

    {{-- QR Code & Actions --}}
    <div class="space-y-6">
      <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 p-6">
        <h2 class="text-lg font-bold mb-4">QR Code</h2>
        <div class="flex justify-center">
          <img src="{{ $apar->qr_url }}" alt="QR Code APAR {{ $apar->serial_no }}" class="w-48 h-48 border-2 border-gray-300 rounded-lg p-2 bg-white">
        </div>
        <p class="text-xs text-center text-gray-500 mt-2">Scan untuk akses cepat</p>
      </div>

      <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 p-6">
        <h2 class="text-lg font-bold mb-4">Actions</h2>
        <div class="space-y-3">
          <a href="{{ route('admin.apar.edit', $apar) }}" class="block w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-center font-semibold">
            Edit APAR
          </a>
          <form action="{{ route('admin.apar.destroy', $apar) }}" method="POST" onsubmit="return confirm('Yakin hapus APAR ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="block w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-center font-semibold">
              Hapus APAR
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-layouts.app>
