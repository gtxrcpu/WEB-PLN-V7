<x-layouts.app :title="'Approve Kartu Kendali — Admin'">
  <div class="mb-6">
    <a href="{{ route('admin.approvals.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors mb-4">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>
      Kembali
    </a>
    <h1 class="text-2xl font-bold text-gray-900">Approve Kartu Kendali</h1>
    <p class="text-sm text-gray-600 mt-1">Pilih tanda tangan untuk approve kartu kendali ini</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Detail Kartu Kendali --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-lg ring-1 ring-slate-200 p-6">
      <h2 class="text-lg font-bold mb-4">Detail Inspeksi</h2>
      
      @php
        // Get equipment info based on module type
        $equipmentName = '-';
        $equipmentLocation = '-';
        
        switch($type ?? 'apar') {
          case 'apar':
            $equipmentName = $kartu->apar->barcode ?? $kartu->apar->serial_no ?? '-';
            $equipmentLocation = $kartu->apar->location_code ?? '-';
            break;
          case 'apat':
            $equipmentName = $kartu->apat->barcode ?? $kartu->apat->serial_no ?? '-';
            $equipmentLocation = $kartu->apat->lokasi ?? '-';
            break;
          case 'apab':
            $equipmentName = $kartu->apab->barcode ?? $kartu->apab->serial_no ?? '-';
            $equipmentLocation = $kartu->apab->lokasi ?? '-';
            break;
          case 'fire-alarm':
            $equipmentName = $kartu->fireAlarm->barcode ?? $kartu->fireAlarm->serial_no ?? '-';
            $equipmentLocation = $kartu->fireAlarm->lokasi ?? '-';
            break;
          case 'box-hydrant':
            $equipmentName = $kartu->boxHydrant->barcode ?? $kartu->boxHydrant->serial_no ?? '-';
            $equipmentLocation = $kartu->boxHydrant->lokasi ?? '-';
            break;
          case 'rumah-pompa':
            $equipmentName = $kartu->rumahPompa->barcode ?? $kartu->rumahPompa->serial_no ?? '-';
            $equipmentLocation = $kartu->rumahPompa->lokasi ?? '-';
            break;
          case 'p3k':
            $equipmentName = $kartu->p3k->barcode ?? $kartu->p3k->serial_no ?? '-';
            $equipmentLocation = $kartu->p3k->lokasi ?? '-';
            break;
        }
      @endphp
      
      <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
          <p class="text-sm text-gray-600">Modul</p>
          <p class="font-semibold">
            <span class="inline-flex px-2.5 py-1 text-xs font-bold rounded-lg
              @if($type === 'apar') bg-red-100 text-red-700
              @elseif($type === 'apat') bg-cyan-100 text-cyan-700
              @elseif($type === 'apab') bg-orange-100 text-orange-700
              @elseif($type === 'fire-alarm') bg-rose-100 text-rose-700
              @elseif($type === 'box-hydrant') bg-blue-100 text-blue-700
              @elseif($type === 'rumah-pompa') bg-purple-100 text-purple-700
              @elseif($type === 'p3k') bg-emerald-100 text-emerald-700
              @else bg-slate-100 text-slate-700 @endif">
              {{ strtoupper($type) }}
            </span>
          </p>
        </div>
        <div>
          <p class="text-sm text-gray-600">Equipment</p>
          <p class="font-semibold">{{ $equipmentName }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-600">Lokasi</p>
          <p class="font-semibold">{{ $equipmentLocation }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-600">Tanggal Periksa</p>
          <p class="font-semibold">{{ \Carbon\Carbon::parse($kartu->tgl_periksa)->format('d M Y') }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-600">Petugas</p>
          <p class="font-semibold">{{ $kartu->petugas }}</p>
        </div>
      </div>

      <div class="border-t border-gray-200 pt-4">
        <h3 class="font-semibold mb-3">Hasil Pemeriksaan</h3>
        <div class="grid grid-cols-2 gap-3 text-sm">
          @if($type === 'apar')
            <div class="flex justify-between p-2 bg-slate-50 rounded">
              <span class="text-gray-600">Pressure Gauge:</span>
              <span class="font-medium">{{ $kartu->pressure_gauge ?? '-' }}</span>
            </div>
            <div class="flex justify-between p-2 bg-slate-50 rounded">
              <span class="text-gray-600">Pin & Segel:</span>
              <span class="font-medium">{{ $kartu->pin_segel ?? '-' }}</span>
            </div>
            <div class="flex justify-between p-2 bg-slate-50 rounded">
              <span class="text-gray-600">Selang:</span>
              <span class="font-medium">{{ $kartu->selang ?? '-' }}</span>
            </div>
            <div class="flex justify-between p-2 bg-slate-50 rounded">
              <span class="text-gray-600">Tabung:</span>
              <span class="font-medium">{{ $kartu->tabung ?? '-' }}</span>
            </div>
            <div class="flex justify-between p-2 bg-slate-50 rounded">
              <span class="text-gray-600">Label:</span>
              <span class="font-medium">{{ $kartu->label ?? '-' }}</span>
            </div>
            <div class="flex justify-between p-2 bg-slate-50 rounded">
              <span class="text-gray-600">Kondisi Fisik:</span>
              <span class="font-medium">{{ $kartu->kondisi_fisik ?? '-' }}</span>
            </div>
          @elseif($type === 'apat')
            <div class="flex justify-between p-2 bg-slate-50 rounded">
              <span class="text-gray-600">Kondisi Fisik:</span>
              <span class="font-medium">{{ $kartu->kondisi_fisik ?? '-' }}</span>
            </div>
            <div class="flex justify-between p-2 bg-slate-50 rounded">
              <span class="text-gray-600">Drum:</span>
              <span class="font-medium">{{ $kartu->drum ?? '-' }}</span>
            </div>
            <div class="flex justify-between p-2 bg-slate-50 rounded">
              <span class="text-gray-600">Aduk Pasir:</span>
              <span class="font-medium">{{ $kartu->aduk_pasir ?? '-' }}</span>
            </div>
            <div class="flex justify-between p-2 bg-slate-50 rounded">
              <span class="text-gray-600">Sekop:</span>
              <span class="font-medium">{{ $kartu->sekop ?? '-' }}</span>
            </div>
            <div class="flex justify-between p-2 bg-slate-50 rounded">
              <span class="text-gray-600">Fire Blanket:</span>
              <span class="font-medium">{{ $kartu->fire_blanket ?? '-' }}</span>
            </div>
            <div class="flex justify-between p-2 bg-slate-50 rounded">
              <span class="text-gray-600">Ember:</span>
              <span class="font-medium">{{ $kartu->ember ?? '-' }}</span>
            </div>
          @else
            <div class="col-span-2 p-4 bg-slate-50 rounded text-center text-gray-500">
              <p>Detail pemeriksaan untuk modul {{ strtoupper($type) }}</p>
            </div>
          @endif
        </div>
      </div>

      <div class="mt-4 p-4 rounded-lg
        @if($kartu->kesimpulan === 'baik') bg-green-50 border border-green-200
        @elseif($kartu->kesimpulan === 'rusak') bg-red-50 border border-red-200
        @else bg-yellow-50 border border-yellow-200 @endif">
        <p class="text-sm font-medium
          @if($kartu->kesimpulan === 'baik') text-green-800
          @elseif($kartu->kesimpulan === 'rusak') text-red-800
          @else text-yellow-800 @endif">
          Kesimpulan: <span class="uppercase">{{ $kartu->kesimpulan }}</span>
        </p>
      </div>

      <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <h3 class="font-semibold text-blue-900 mb-2">Informasi Pembuat</h3>
        <div class="space-y-1 text-sm text-blue-800">
          <p>
            <span class="text-blue-600">Nama:</span> 
            <span class="font-medium">{{ get_user_display_name($kartu->user, 'Unknown User') }}</span>
          </p>
          @if($kartu->user)
            <p>
              <span class="text-blue-600">Username:</span> 
              <span class="font-medium">{{ $kartu->user->username ?? '-' }}</span>
            </p>
            <p>
              <span class="text-blue-600">Role:</span> 
              <span class="font-medium">{{ get_user_role_display($kartu->user) }}</span>
            </p>
          @endif
          <p>
            <span class="text-blue-600">Dibuat pada:</span> 
            <span class="font-medium">{{ $kartu->created_at->format('d M Y H:i') }}</span>
          </p>
        </div>
      </div>
    </div>

    {{-- Pilih Tanda Tangan --}}
    <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 p-6">
      <h2 class="text-lg font-bold mb-4">Pilih Tanda Tangan</h2>
      
      <form action="{{ route('admin.approvals.approve', $kartu->id) }}" method="POST">
        @csrf
        <input type="hidden" name="type" value="{{ $type ?? 'apar' }}">
        
        <div class="space-y-3 mb-6">
          @forelse($signatures as $signature)
            <label class="block cursor-pointer">
              <input type="radio" name="signature_id" value="{{ $signature->id }}" required
                class="peer sr-only">
              <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-purple-600 peer-checked:bg-purple-50 hover:border-purple-300 transition-all">
                <div class="flex items-start gap-3">
                  <div class="flex-shrink-0 w-16 h-16 bg-slate-100 rounded flex items-center justify-center">
                    @if($signature->signature_path)
                      <img src="{{ asset('storage/' . $signature->signature_path) }}" 
                           alt="TTD" 
                           class="max-h-14 w-auto object-contain">
                    @endif
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-900">{{ $signature->name }}</p>
                    <p class="text-sm text-gray-600">{{ $signature->position }}</p>
                    @if($signature->nip)
                      <p class="text-xs text-gray-500 mt-1">NIP: {{ $signature->nip }}</p>
                    @endif
                  </div>
                </div>
              </div>
            </label>
          @empty
            <div class="text-center py-8 text-gray-500">
              <p class="mb-2">Belum ada tanda tangan</p>
              <a href="{{ route('admin.signatures.create') }}" class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                Tambah Tanda Tangan
              </a>
            </div>
          @endforelse
        </div>

        @if($signatures->count() > 0)
          <div class="space-y-3">
            <button type="submit" class="w-full px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-semibold shadow-md">
              <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Approve Kartu Kendali
            </button>
            <a href="{{ route('admin.approvals.index') }}" class="block w-full px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold text-center">
              Batal
            </a>
          </div>
        @endif
      </form>
    </div>
  </div>
</x-layouts.app>
