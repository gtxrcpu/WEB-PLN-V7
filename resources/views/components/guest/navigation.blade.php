{{-- Guest Navigation Component --}}
<nav class="bg-white shadow-sm mb-6 rounded-lg overflow-hidden">
  <div class="flex space-x-1 overflow-x-auto scrollbar-hide">
    <a href="{{ route('guest.dashboard') }}" 
       class="flex-shrink-0 px-4 py-3 text-sm font-medium transition-colors
              {{ request()->routeIs('guest.dashboard') ? 'bg-blue-50 text-blue-700 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
      <div class="flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span>Dashboard</span>
      </div>
    </a>

    <a href="{{ route('guest.apar') }}" 
       class="flex-shrink-0 px-4 py-3 text-sm font-medium transition-colors
              {{ request()->routeIs('guest.apar*') ? 'bg-blue-50 text-blue-700 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
      APAR
    </a>

    <a href="{{ route('guest.apat') }}" 
       class="flex-shrink-0 px-4 py-3 text-sm font-medium transition-colors
              {{ request()->routeIs('guest.apat*') ? 'bg-blue-50 text-blue-700 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
      APAT
    </a>

    <a href="{{ route('guest.p3k') }}" 
       class="flex-shrink-0 px-4 py-3 text-sm font-medium transition-colors
              {{ request()->routeIs('guest.p3k*') ? 'bg-blue-50 text-blue-700 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
      P3K
    </a>

    <a href="{{ route('guest.apab') }}" 
       class="flex-shrink-0 px-4 py-3 text-sm font-medium transition-colors
              {{ request()->routeIs('guest.apab*') ? 'bg-blue-50 text-blue-700 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
      APAB
    </a>

    <a href="{{ route('guest.fire-alarm') }}" 
       class="flex-shrink-0 px-4 py-3 text-sm font-medium transition-colors
              {{ request()->routeIs('guest.fire-alarm*') ? 'bg-blue-50 text-blue-700 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
      Fire Alarm
    </a>

    <a href="{{ route('guest.box-hydrant') }}" 
       class="flex-shrink-0 px-4 py-3 text-sm font-medium transition-colors
              {{ request()->routeIs('guest.box-hydrant*') ? 'bg-blue-50 text-blue-700 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
      Box Hydrant
    </a>

    <a href="{{ route('guest.rumah-pompa') }}" 
       class="flex-shrink-0 px-4 py-3 text-sm font-medium transition-colors
              {{ request()->routeIs('guest.rumah-pompa*') ? 'bg-blue-50 text-blue-700 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
      Rumah Pompa
    </a>
  </div>
</nav>

<style>
  .scrollbar-hide::-webkit-scrollbar {
    display: none;
  }
  .scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
</style>
