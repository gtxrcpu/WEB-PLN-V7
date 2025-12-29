<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Guest Access' }} - PLN Inventaris</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>[x-cloak]{display:none!important}</style>
</head>
<body class="antialiased bg-slate-50 text-slate-900">

  {{-- TOPBAR --}}
  <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md shadow-sm border-b border-slate-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="{{ route('guest.dashboard') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
          <img src="{{ asset('images/logoo.png') }}" alt="PLN" class="h-9 w-auto object-contain">
          <div class="hidden sm:block">
            <h1 class="font-bold text-slate-900 text-lg">Inventaris K3 PLN</h1>
            <p class="text-xs text-slate-500">Sistem Monitoring Peralatan</p>
          </div>
        </a>
      </div>

      <div class="flex items-center gap-3">
        <a href="{{ route('login') }}" 
           class="group relative inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 hover:-translate-y-0.5">
          <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
          </svg>
          <span>Login</span>
        </a>
      </div>
    </div>
  </header>

  {{-- PAGE CONTENT --}}
  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 md:pb-8 pb-[calc(72px+env(safe-area-inset-bottom))]">
    {{ $slot ?? '' }}
    @yield('content')
  </main>

  {{-- FOOTER --}}
  <footer class="bg-gradient-to-br from-slate-50 to-slate-100 border-t border-slate-200 mt-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <img src="{{ asset('images/logoo.png') }}" alt="PLN" class="h-8 w-auto object-contain opacity-80">
          <div class="text-sm text-slate-600">
            <div class="font-semibold text-slate-900">PLN - Sistem Inventaris K3</div>
            <div class="text-xs text-slate-500">Monitoring Peralatan Keselamatan</div>
          </div>
        </div>
        <div class="text-sm text-slate-500">
          © {{ date('Y') }} PT PLN (Persero)
        </div>
      </div>
    </div>
  </footer>

  {{-- BOTTOM NAV (MOBILE) --}}
  <nav class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md
              border-t border-slate-200 shadow-[0_-6px_16px_rgba(2,6,23,0.08)]
              pb-[env(safe-area-inset-bottom)]">
    <div class="grid grid-cols-2 text-[11px] text-slate-600">
      <a href="{{ route('guest.dashboard') }}" class="flex flex-col items-center justify-center gap-1.5 py-3 hover:text-blue-600 transition-colors">
        <svg viewBox="0 0 24 24" class="h-5 w-5"><path d="M3 10.5 12 3l9 7.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1v-10.5Z" fill="currentColor"/></svg>
        <span class="font-medium">Dashboard</span>
      </a>

      <a href="{{ route('login') }}" class="flex flex-col items-center justify-center gap-1.5 py-3 text-blue-600 hover:text-blue-700 transition-colors">
        <svg viewBox="0 0 24 24" class="h-5 w-5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <span class="font-semibold">Login</span>
      </a>
    </div>
  </nav>

</body>
</html>
