<x-layouts.app :title="'Manage Floor Plans — Admin'">
  {{-- Header with Back Button --}}
  <div class="mb-4 sm:mb-6">
    <a href="{{ route('admin.dashboard') }}" 
            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white hover:bg-slate-50 text-slate-700 transition-colors shadow-sm border border-slate-200 mb-4">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
      </svg>
      <span class="text-sm font-medium">Kembali ke Dashboard</span>
    </a>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Manage Floor Plans</h1>
        <p class="text-sm text-gray-600 mt-1">Kelola denah gedung untuk setiap unit</p>
      </div>
      <a href="{{ route('admin.floor-plans.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span>Upload Floor Plan</span>
      </a>
    </div>
  </div>

  {{-- Success/Error Messages --}}
  @if(session('success'))
    <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-lg flex items-center gap-3 shadow-sm">
      <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <span class="text-green-800 font-medium">{{ session('success') }}</span>
    </div>
  @endif

  @if(session('error'))
    <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 rounded-lg flex items-center gap-3 shadow-sm">
      <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <span class="text-red-800 font-medium">{{ session('error') }}</span>
    </div>
  @endif

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($floorPlans as $floorPlan)
      <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 overflow-hidden hover:shadow-xl transition-all">
        {{-- Floor Plan Image Preview --}}
        <div class="relative h-48 bg-gray-100 overflow-hidden">
          <img src="{{ Storage::url($floorPlan->image_path) }}" 
               alt="{{ $floorPlan->name }}"
               class="w-full h-full object-contain">
          
          {{-- Status Badge --}}
          <div class="absolute top-3 right-3">
            @if($floorPlan->is_active)
              <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-lg bg-green-100 text-green-700 ring-1 ring-green-200">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Active
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-lg bg-gray-100 text-gray-700 ring-1 ring-gray-200">
                Inactive
              </span>
            @endif
          </div>
        </div>

        {{-- Floor Plan Info --}}
        <div class="p-4">
          <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $floorPlan->name }}</h3>
          
          <div class="flex items-center gap-2 mb-3">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg bg-gradient-to-r from-emerald-100 to-teal-100 text-emerald-700 ring-1 ring-emerald-200">
              <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
              </svg>
              {{ $floorPlan->unit->code }}
            </span>
          </div>

          @if($floorPlan->description)
            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $floorPlan->description }}</p>
          @endif

          <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
            <span>{{ $floorPlan->width }} × {{ $floorPlan->height }} px</span>
            <span>{{ $floorPlan->created_at->format('d M Y') }}</span>
          </div>

          {{-- Actions --}}
          <div class="flex items-center gap-2">
            <a href="{{ route('admin.floor-plans.placement', $floorPlan) }}" 
               class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-all font-medium text-sm"
               title="Atur Lokasi Peralatan">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              Atur Lokasi
            </a>
            <a href="{{ route('admin.floor-plans.edit', $floorPlan) }}" 
               class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
               title="Edit Floor Plan">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
            </a>
            <form action="{{ route('admin.floor-plans.destroy', $floorPlan) }}" method="POST" onsubmit="return confirm('⚠️ Yakin hapus floor plan {{ $floorPlan->name }}?\n\nDenah akan dihapus permanen!')">
              @csrf
              @method('DELETE')
              <button type="submit" 
                      class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all"
                      title="Hapus Floor Plan">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
              </button>
            </form>
          </div>
        </div>
      </div>
    @empty
      <div class="col-span-full">
        <div class="bg-white rounded-xl shadow-lg ring-1 ring-slate-200 p-12 text-center">
          <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
          </svg>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Floor Plan</h3>
          <p class="text-sm text-gray-600 mb-4">Mulai dengan mengunggah denah gedung pertama Anda</p>
          <a href="{{ route('admin.floor-plans.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Upload Floor Plan
          </a>
        </div>
      </div>
    @endforelse
  </div>
</x-layouts.app>
