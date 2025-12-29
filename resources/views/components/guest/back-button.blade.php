@props(['url' => null])

{{-- Guest Back Button Component --}}
<a href="{{ $url ?? route('guest.dashboard') }}" 
   class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-colors shadow-sm">
  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
  </svg>
  <span>{{ $slot->isEmpty() ? 'Kembali ke Dashboard' : $slot }}</span>
</a>
