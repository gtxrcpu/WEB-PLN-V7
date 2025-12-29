<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Inventaris K3 PLN' }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>[x-cloak]{display:none!important}</style>
</head>
<body class="antialiased bg-slate-50 text-slate-900" data-layout="layouts/app-v1">

  {{-- TOPBAR --}}
  <header class="sticky top-0 z-40 bg-white/85 backdrop-blur ring-1 ring-slate-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="{{ route('user.dashboard') }}" class="hover:opacity-80 transition-opacity">
          <img src="{{ asset('images/logoo.png') }}" alt="PLN" class="h-8 w-auto object-contain">
        </a>
        <span class="font-semibold">Inventaris K3 PLN — <span class="text-emerald-700">User</span></span>
      </div>

      <div class="flex items-center gap-2">
        {{-- PROFILE DROPDOWN --}}
        @php
          $u = auth()->user();
          $avatar = $u?->avatar_path ? asset('storage/'.$u->avatar_path) : null;
          $initial = strtoupper(mb_substr($u?->name ?? 'U', 0, 1));
        @endphp
        <div x-data="{open:false}" class="relative">
          <button type="button" @click="open=!open" class="flex items-center gap-2 rounded-xl px-3 py-2 hover:bg-slate-100 transition">
            @if($avatar)
              <img src="{{ $avatar }}" alt="Avatar" class="h-8 w-8 rounded-full object-cover ring-1 ring-slate-200">
            @else
              <div class="h-8 w-8 rounded-full bg-emerald-100 text-emerald-800 grid place-items-center ring-1 ring-emerald-200">{{ $initial }}</div>
            @endif
            <span class="hidden sm:inline text-sm">{{ $u?->name ?? 'User' }}</span>
          </button>

          <div x-cloak x-show="open" @click.outside="open=false" x-transition
               class="absolute right-0 mt-2 w-60 rounded-xl bg-white shadow-md ring-1 ring-slate-200 p-2">
            <a href="#" class="block rounded-lg px-3 py-2 hover:bg-slate-50 text-slate-700">Profil</a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="w-full text-left rounded-lg px-3 py-2 hover:bg-rose-50 text-rose-700">Logout</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </header>

  {{-- PAGE CONTENT --}}
  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 md:pb-8 pb-[calc(72px+env(safe-area-inset-bottom))]">
    {{ $slot ?? '' }}
    @yield('content')
  </main>

  {{-- BOTTOM NAV (MOBILE) --}}
  <nav class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur
              border-t border-slate-200 shadow-[0_-6px_16px_rgba(2,6,23,0.06)]
              pb-[env(safe-area-inset-bottom)]">
    <div class="grid grid-cols-2 text-[11px] text-slate-600">
      <a href="{{ route('user.dashboard') }}" class="flex flex-col items-center justify-center gap-1 py-3 hover:text-slate-900">
        <svg viewBox="0 0 24 24" class="h-5 w-5"><path d="M3 10.5 12 3l9 7.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1v-10.5Z" fill="currentColor"/></svg>
        <span>Home</span>
      </a>

      <form method="POST" action="{{ route('logout') }}" class="flex flex-col items-center justify-center gap-1 py-3">
        @csrf
        <button type="submit" class="text-rose-700 hover:text-rose-800 flex flex-col items-center">
          <svg viewBox="0 0 24 24" class="h-5 w-5"><path d="M9 21H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3M16 17l5-5-5-5M21 12H9" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <span>Logout</span>
        </button>
      </form>
    </div>
  </nav>

  {{-- PASTIKAN: TIDAK ADA MODAL SEARCH DI FILE INI --}}
  {{-- (sengaja kosong) --}}

</body>
</html>
