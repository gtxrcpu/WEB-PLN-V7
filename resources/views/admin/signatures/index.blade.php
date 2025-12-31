<x-layouts.app :title="'Manage Signatures — Admin'">
  <div class="mb-4 sm:mb-6">
    <a href="{{ route('admin.dashboard') }}" 
            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white hover:bg-slate-50 text-slate-700 transition-colors shadow-sm border border-slate-200 mb-4">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
      </svg>
      <span class="text-sm font-medium">Kembali ke Dashboard</span>
    </a>

    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Tanda Tangan Digital</h1>
        <p class="text-sm text-gray-600 mt-1">Kelola tanda tangan pimpinan untuk approval kartu kendali</p>
      </div>
      <a href="{{ route('admin.signatures.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors shadow-md">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah TTD
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
      <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <span class="text-green-800 font-medium">{{ session('success') }}</span>
    </div>
  @endif

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($signatures as $signature)
      <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 overflow-hidden hover:shadow-xl transition-all">
        <div class="p-6">
          <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
              <h3 class="font-bold text-lg text-gray-900">{{ $signature->name }}</h3>
              <p class="text-sm text-gray-600">{{ $signature->position }}</p>
              @if($signature->nip)
                <p class="text-xs text-gray-500 mt-1">NIP: {{ $signature->nip }}</p>
              @endif
            </div>
            <span class="px-2 py-1 text-xs font-semibold rounded-full
              @if($signature->is_active) bg-green-100 text-green-700
              @else bg-gray-100 text-gray-700 @endif">
              {{ $signature->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
          </div>

          <div class="bg-slate-50 rounded-lg p-4 mb-4 flex items-center justify-center min-h-[120px]">
            @if($signature->signature_url)
              <img src="{{ $signature->signature_url }}" 
                   alt="TTD {{ $signature->name }}" 
                   class="max-h-24 w-auto object-contain">
            @else
              <p class="text-gray-400 text-sm">No signature</p>
            @endif
          </div>

          <div class="flex items-center gap-2">
            <a href="{{ route('admin.signatures.edit', $signature) }}" 
               class="flex-1 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-center text-sm font-semibold">
              Edit
            </a>
            <form action="{{ route('admin.signatures.destroy', $signature) }}" method="POST" onsubmit="return confirm('Yakin hapus TTD ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="px-3 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors text-sm font-semibold">
                Hapus
              </button>
            </form>
          </div>
        </div>
      </div>
    @empty
      <div class="col-span-full bg-white rounded-xl shadow-lg ring-1 ring-slate-200 p-12 text-center">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-gray-600 font-medium mb-2">Belum ada tanda tangan</p>
        <p class="text-sm text-gray-500">Tambahkan tanda tangan pimpinan untuk approval</p>
      </div>
    @endforelse
  </div>

  @if($signatures->hasPages())
    <div class="mt-6">
      {{ $signatures->links() }}
    </div>
  @endif
</x-layouts.app>
