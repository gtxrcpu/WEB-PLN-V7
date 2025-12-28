<x-layouts.app :title="'Edit Kode'">
  {{-- Button Kembali --}}
  <div class="mb-6">
    <a href="{{ route('admin.dashboard') }}" 
       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
      </svg>
      Kembali ke Dashboard
    </a>
  </div>

  {{-- Header --}}
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Edit Kode Serial</h1>
    <p class="text-gray-600 mt-1">Pilih modul untuk mengatur format kode serial</p>
  </div>

  {{-- Module Cards --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($modules as $key => $module)
    <a href="{{ route('admin.edit-kode.edit', $key) }}" 
       class="block bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 border-l-4 border-blue-500 hover:border-blue-600 hover:scale-[1.02]"
       onclick="event.stopPropagation();">
      <div class="flex items-center gap-4 pointer-events-none">
        <img src="{{ asset($module['icon']) }}" alt="{{ $module['name'] }}" class="w-16 h-16 object-contain">
        <div class="flex-1">
          <h3 class="text-lg font-bold text-gray-900">{{ $module['name'] }}</h3>
          <p class="text-sm text-gray-600 mt-1">{{ $module['full_name'] }}</p>
        </div>
        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </div>
    </a>
    @endforeach
  </div>
</x-layouts.app>
