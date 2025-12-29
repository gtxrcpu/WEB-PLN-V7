<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Inventaris K3 PLN' }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  
  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  
  <style>
    [x-cloak] { display: none !important; }
  </style>
</head>
<body class="antialiased bg-slate-50 text-slate-900">
  {{-- Topbar --}}
  <header class="sticky top-0 z-40 bg-white/85 backdrop-blur ring-1 ring-slate-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        @php
          $dashboardRoute = 'user.dashboard';
          if (auth()->check()) {
            if (auth()->user()->hasRole('superadmin')) {
              $dashboardRoute = 'admin.dashboard';
            } elseif (auth()->user()->hasRole('leader')) {
              $dashboardRoute = 'leader.dashboard';
            } elseif (auth()->user()->hasRole('inspector')) {
              $dashboardRoute = 'inspector.dashboard';
            }
          }
        @endphp
        <a href="{{ route($dashboardRoute) }}" class="hover:opacity-80 transition-opacity">
          <img src="{{ asset('images/logoo.png') }}" alt="PLN" class="h-8 w-auto object-contain">
        </a>
        <div class="flex items-center gap-2">
          <span class="font-semibold">Inventaris K3 PLN</span>
          @if(auth()->check())
            @if(auth()->user()->hasRole('superadmin'))
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-bold rounded-md bg-purple-100 text-purple-700">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Admin
              </span>
            @elseif(auth()->user()->hasRole('leader'))
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-bold rounded-md bg-green-100 text-green-700">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                Leader
              </span>
            @elseif(auth()->user()->hasRole('inspector'))
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-bold rounded-md bg-blue-100 text-blue-700">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Inspector
              </span>
            @else
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-bold rounded-md bg-emerald-100 text-emerald-700">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
                User
              </span>
            @endif
          @endif
          
          {{-- Unit Badge --}}
          @if(auth()->check())
            @php
              $currentUser = auth()->user();
              $displayUnit = null;
              
              // Jika user punya unit, tampilkan unit mereka
              if ($currentUser->unit_id) {
                $displayUnit = $currentUser->unit;
              }
              // Jika admin sedang viewing unit tertentu
              elseif (!$currentUser->unit_id && session('viewing_unit_id')) {
                $displayUnit = \App\Models\Unit::find(session('viewing_unit_id'));
              }
            @endphp
            
            @if($displayUnit)
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-bold rounded-lg bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-md">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                </svg>
                {{ $displayUnit->code }}
              </span>
            @endif
          @endif
        </div>
      </div>

      {{-- Quick Switch for Admin/Leader (Desktop) --}}
      @if(auth()->check() && auth()->user()->hasRole('superadmin'))
        <div class="hidden md:flex items-center gap-2">
          <a href="{{ route('admin.dashboard') }}" 
             class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ request()->routeIs('admin.*') ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
            Admin View
          </a>
          <a href="{{ route('user.dashboard') }}" 
             class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ request()->routeIs('user.dashboard') || (!request()->routeIs('admin.*') && !request()->routeIs('dashboard')) ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
            User View
          </a>
        </div>
      @elseif(auth()->check() && auth()->user()->hasRole('leader'))
        <div class="hidden md:flex items-center gap-2">
          <a href="{{ route('leader.dashboard') }}" 
             class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ request()->routeIs('leader.*') ? 'bg-green-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
            Leader View
          </a>
          <a href="{{ route('user.dashboard') }}" 
             class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ request()->routeIs('user.dashboard') || (!request()->routeIs('leader.*') && !request()->routeIs('dashboard')) ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
            User View
          </a>
        </div>
      @endif

      {{-- Profile Dropdown (Desktop & Mobile) --}}
      <div class="flex items-center gap-4">
        @php
          $u = auth()->user();
          $avatar = $u?->avatar ? asset('storage/'.$u->avatar) : null;
          $initial = strtoupper(mb_substr($u?->name ?? 'U', 0, 1));
        @endphp
        <div x-data="{open:false}" class="relative">
          <button @click.prevent="open=!open" type="button" class="flex items-center gap-2 rounded-xl px-3 py-2 hover:bg-slate-100 transition">
            @if($avatar)
              <img src="{{ $avatar }}" alt="Avatar" class="h-8 w-8 rounded-full object-cover ring-1 ring-slate-200">
            @else
              <div class="h-8 w-8 rounded-full bg-emerald-100 text-emerald-800 grid place-items-center ring-1 ring-emerald-200">{{ $initial }}</div>
            @endif
            <span class="text-sm hidden sm:inline">{{ $u?->name ?? 'User' }}</span>
            <svg class="w-4 h-4 text-slate-600 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>

          <div x-cloak x-show="open" @click.outside="open=false" 
               x-transition:enter="transition ease-out duration-100"
               x-transition:enter-start="opacity-0 scale-95"
               x-transition:enter-end="opacity-100 scale-100"
               x-transition:leave="transition ease-in duration-75"
               x-transition:leave-start="opacity-100 scale-100"
               x-transition:leave-end="opacity-0 scale-95"
               class="absolute right-0 mt-2 w-60 rounded-xl bg-white shadow-md ring-1 ring-slate-200 p-2">
            @if(auth()->check() && auth()->user()->hasRole('superadmin'))
              <a href="{{ route('admin.dashboard') }}" class="block rounded-lg px-3 py-2 hover:bg-purple-50 text-purple-700 font-medium">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Admin Panel
              </a>
              <a href="{{ route('user.dashboard') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-50 text-slate-700">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                User Dashboard
              </a>
              <div class="border-t border-slate-200 my-2"></div>
              
              {{-- Unit Switcher untuk Admin --}}
              @if(!auth()->user()->unit_id)
                <div class="px-2 py-1">
                  <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Lihat Data Unit</p>
                  <form method="POST" action="{{ route('unit.switch') }}" class="space-y-1">
                    @csrf
                    <select name="unit_id" onchange="this.form.submit()" class="w-full text-sm px-2 py-1.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                      <option value="">Semua Unit</option>
                      @foreach(\App\Models\Unit::where('is_active', true)->get() as $unit)
                        <option value="{{ $unit->id }}" {{ session('viewing_unit_id') == $unit->id ? 'selected' : '' }}>
                          {{ $unit->name }}
                        </option>
                      @endforeach
                    </select>
                  </form>
                  @if(session('viewing_unit_id'))
                    @php $viewingUnit = \App\Models\Unit::find(session('viewing_unit_id')); @endphp
                    <div class="mt-2 text-xs text-emerald-700 bg-emerald-50 px-2 py-1 rounded">
                      📍 Viewing: {{ $viewingUnit->code }}
                    </div>
                  @endif
                </div>
                <div class="border-t border-slate-200 my-2"></div>
              @endif
            @elseif(auth()->check() && auth()->user()->hasRole('leader'))
              <a href="{{ route('leader.dashboard') }}" class="block rounded-lg px-3 py-2 hover:bg-green-50 text-green-700 font-medium">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Leader Panel
              </a>
              <a href="{{ route('user.dashboard') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-50 text-slate-700">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                User Dashboard
              </a>
              <div class="border-t border-slate-200 my-2"></div>
            @endif
            <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 hover:bg-slate-50 text-slate-700">
              <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Profil
            </a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="w-full text-left rounded-lg px-3 py-2 hover:bg-rose-50 text-rose-700">Logout</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </header>

  {{-- Page: beri padding bawah agar konten tidak tertutup nav --}}
  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6
               md:pb-8
               pb-[calc(72px+env(safe-area-inset-bottom))]">
    {{ $slot }}
  </main>

  {{-- Bottom nav (mobile) - Different for Admin/Leader & User --}}
  <nav class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white border-t border-slate-200 shadow-lg pb-safe">
    @if(auth()->check() && auth()->user()->hasRole('superadmin'))
      {{-- Admin Navigation --}}
      <div class="grid grid-cols-4 h-16">
        <a href="{{ route('admin.dashboard') }}" 
           class="relative flex flex-col items-center justify-center gap-1 text-slate-700 hover:text-purple-600 hover:bg-purple-50 transition-all {{ request()->routeIs('admin.dashboard') ? 'text-purple-600 bg-purple-50' : '' }}">
          @if(request()->routeIs('admin.dashboard'))
            <span class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-purple-600 rounded-b-full"></span>
          @endif
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
          <span class="text-xs font-medium">Admin</span>
        </a>

        <a href="{{ route('user.dashboard') }}" 
           class="relative flex flex-col items-center justify-center gap-1 text-slate-700 hover:text-blue-600 hover:bg-blue-50 transition-all {{ request()->routeIs('user.dashboard') || (!request()->routeIs('admin.*') && !request()->routeIs('dashboard')) ? 'text-blue-600 bg-blue-50' : '' }}">
          @if(request()->routeIs('user.dashboard') || (!request()->routeIs('admin.*') && !request()->routeIs('dashboard')))
            <span class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-blue-600 rounded-b-full"></span>
          @endif
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
          <span class="text-xs font-medium">User</span>
        </a>

        <button type="button" onclick="event.preventDefault(); toggleModulSheet('admin');" 
           class="relative flex flex-col items-center justify-center gap-1 text-slate-700 hover:text-purple-600 hover:bg-purple-50 transition-all {{ request()->routeIs('admin.apar.*') || request()->routeIs('apar.*') || request()->routeIs('apat.*') || request()->routeIs('apab.*') || request()->routeIs('fire-alarm.*') || request()->routeIs('box-hydrant.*') || request()->routeIs('rumah-pompa.*') || request()->routeIs('p3k.*') || request()->routeIs('referensi.*') || request()->routeIs('floor-plan.*') ? 'text-purple-600 bg-purple-50' : '' }}">
          @if(request()->routeIs('admin.apar.*') || request()->routeIs('apar.*') || request()->routeIs('apat.*') || request()->routeIs('apab.*') || request()->routeIs('fire-alarm.*') || request()->routeIs('box-hydrant.*') || request()->routeIs('rumah-pompa.*') || request()->routeIs('p3k.*') || request()->routeIs('referensi.*') || request()->routeIs('floor-plan.*'))
            <span class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-purple-600 rounded-b-full"></span>
          @endif
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
          </svg>
          <span class="text-xs font-medium">Modul</span>
        </button>

        <button type="button" onclick="event.preventDefault(); toggleProfileSheet();" 
           class="relative flex flex-col items-center justify-center gap-1 text-slate-700 hover:text-slate-900 hover:bg-slate-50 transition-all">
          @php
            $u = auth()->user();
            $avatar = $u?->avatar ? asset('storage/'.$u->avatar) : null;
            $initial = strtoupper(mb_substr($u?->name ?? 'U', 0, 1));
          @endphp
          @if($avatar)
            <img src="{{ $avatar }}" alt="Avatar" class="w-6 h-6 rounded-full object-cover ring-1 ring-slate-200">
          @else
            <div class="w-6 h-6 rounded-full bg-purple-100 text-purple-800 grid place-items-center ring-1 ring-purple-200 text-xs font-bold">{{ $initial }}</div>
          @endif
          <span class="text-xs font-medium">Profil</span>
        </button>
      </div>
    @elseif(auth()->check() && auth()->user()->hasRole('leader'))
      {{-- Leader Navigation --}}
      <div class="grid grid-cols-4 h-16">
        <a href="{{ route('leader.dashboard') }}" 
           class="relative flex flex-col items-center justify-center gap-1 text-slate-700 hover:text-green-600 hover:bg-green-50 transition-all {{ request()->routeIs('leader.dashboard') ? 'text-green-600 bg-green-50' : '' }}">
          @if(request()->routeIs('leader.dashboard'))
            <span class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-green-600 rounded-b-full"></span>
          @endif
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
          <span class="text-xs font-medium">Leader</span>
        </a>

        <a href="{{ route('user.dashboard') }}" 
           class="relative flex flex-col items-center justify-center gap-1 text-slate-700 hover:text-blue-600 hover:bg-blue-50 transition-all {{ request()->routeIs('user.dashboard') || (!request()->routeIs('leader.*') && !request()->routeIs('dashboard')) ? 'text-blue-600 bg-blue-50' : '' }}">
          @if(request()->routeIs('user.dashboard') || (!request()->routeIs('leader.*') && !request()->routeIs('dashboard')))
            <span class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-blue-600 rounded-b-full"></span>
          @endif
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
          <span class="text-xs font-medium">User</span>
        </a>

        <button type="button" onclick="event.preventDefault(); toggleModulSheet('leader');" 
           class="relative flex flex-col items-center justify-center gap-1 text-slate-700 hover:text-green-600 hover:bg-green-50 transition-all {{ request()->routeIs('leader.apar.*') || request()->routeIs('apar.*') || request()->routeIs('apat.*') || request()->routeIs('apab.*') || request()->routeIs('fire-alarm.*') || request()->routeIs('box-hydrant.*') || request()->routeIs('rumah-pompa.*') || request()->routeIs('p3k.*') || request()->routeIs('referensi.*') || request()->routeIs('floor-plan.*') ? 'text-green-600 bg-green-50' : '' }}">
          @if(request()->routeIs('leader.apar.*') || request()->routeIs('apar.*') || request()->routeIs('apat.*') || request()->routeIs('apab.*') || request()->routeIs('fire-alarm.*') || request()->routeIs('box-hydrant.*') || request()->routeIs('rumah-pompa.*') || request()->routeIs('p3k.*') || request()->routeIs('referensi.*') || request()->routeIs('floor-plan.*'))
            <span class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-green-600 rounded-b-full"></span>
          @endif
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
          </svg>
          <span class="text-xs font-medium">Modul</span>
        </button>

        <button type="button" onclick="event.preventDefault(); toggleProfileSheet();" 
           class="relative flex flex-col items-center justify-center gap-1 text-slate-700 hover:text-slate-900 hover:bg-slate-50 transition-all">
          @php
            $u = auth()->user();
            $avatar = $u?->avatar ? asset('storage/'.$u->avatar) : null;
            $initial = strtoupper(mb_substr($u?->name ?? 'U', 0, 1));
          @endphp
          @if($avatar)
            <img src="{{ $avatar }}" alt="Avatar" class="w-6 h-6 rounded-full object-cover ring-1 ring-slate-200">
          @else
            <div class="w-6 h-6 rounded-full bg-green-100 text-green-800 grid place-items-center ring-1 ring-green-200 text-xs font-bold">{{ $initial }}</div>
          @endif
          <span class="text-xs font-medium">Profil</span>
        </button>
      </div>
    @elseif(auth()->check() && auth()->user()->hasRole('inspector'))
      {{-- Inspector Navigation --}}
      <div class="grid grid-cols-3 h-16">
        <a href="{{ route('inspector.dashboard') }}" 
           class="relative flex flex-col items-center justify-center gap-1 text-slate-700 hover:text-blue-600 hover:bg-blue-50 transition-all {{ request()->routeIs('inspector.dashboard') ? 'text-blue-600 bg-blue-50' : '' }}">
          @if(request()->routeIs('inspector.dashboard'))
            <span class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-blue-600 rounded-b-full"></span>
          @endif
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
          <span class="text-xs font-medium">Home</span>
        </a>

        <button type="button" onclick="event.preventDefault(); toggleModulSheet('user');" 
           class="relative flex flex-col items-center justify-center gap-1 text-slate-700 hover:text-blue-600 hover:bg-blue-50 transition-all {{ request()->routeIs('inspector.apar.*') ? 'text-blue-600 bg-blue-50' : '' }}">
          @if(request()->routeIs('inspector.apar.*'))
            <span class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-blue-600 rounded-b-full"></span>
          @endif
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
          </svg>
          <span class="text-xs font-medium">Inspeksi</span>
        </button>

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="w-full h-full flex flex-col items-center justify-center gap-1 text-rose-600 hover:text-rose-700 hover:bg-rose-50 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            <span class="text-xs font-medium">Logout</span>
          </button>
        </form>
      </div>
    @else
      {{-- User Navigation --}}
      <div class="grid grid-cols-3 h-16">
        <a href="{{ route('user.dashboard') }}" 
           class="relative flex flex-col items-center justify-center gap-1 text-slate-700 hover:text-blue-600 hover:bg-blue-50 transition-all {{ request()->routeIs('user.dashboard') ? 'text-blue-600 bg-blue-50' : '' }}">
          @if(request()->routeIs('user.dashboard'))
            <span class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-blue-600 rounded-b-full"></span>
          @endif
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
          <span class="text-xs font-medium">Home</span>
        </a>

        <button type="button" onclick="event.preventDefault(); toggleModulSheet('user');" 
           class="relative flex flex-col items-center justify-center gap-1 text-slate-700 hover:text-blue-600 hover:bg-blue-50 transition-all {{ request()->routeIs('apar.*') || request()->routeIs('apat.*') || request()->routeIs('apab.*') || request()->routeIs('fire-alarm.*') || request()->routeIs('box-hydrant.*') || request()->routeIs('rumah-pompa.*') || request()->routeIs('p3k.*') || request()->routeIs('referensi.*') || request()->routeIs('floor-plan.*') ? 'text-blue-600 bg-blue-50' : '' }}">
          @if(request()->routeIs('apar.*') || request()->routeIs('apat.*') || request()->routeIs('apab.*') || request()->routeIs('fire-alarm.*') || request()->routeIs('box-hydrant.*') || request()->routeIs('rumah-pompa.*') || request()->routeIs('p3k.*') || request()->routeIs('referensi.*') || request()->routeIs('floor-plan.*'))
            <span class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-blue-600 rounded-b-full"></span>
          @endif
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
          </svg>
          <span class="text-xs font-medium">Modul</span>
        </button>

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="w-full h-full flex flex-col items-center justify-center gap-1 text-rose-600 hover:text-rose-700 hover:bg-rose-50 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            <span class="text-xs font-medium">Logout</span>
          </button>
        </form>
      </div>
    @endif
  </nav>

  {{-- Module Bottom Sheet --}}
  <div id="modulSheet" class="md:hidden fixed inset-0 z-50 pointer-events-none">
    {{-- Backdrop --}}
    <div id="modulBackdrop" class="absolute inset-0 bg-black/50 opacity-0 transition-opacity duration-300"></div>
    
    {{-- Sheet --}}
    <div id="modulSheetContent" class="absolute bottom-0 inset-x-0 bg-white rounded-t-3xl shadow-2xl transform translate-y-full transition-transform duration-300 max-h-[85vh] overflow-y-auto pb-safe">
      <div class="p-4 sm:p-6">
        {{-- Handle --}}
        <div class="w-12 h-1.5 bg-slate-300 rounded-full mx-auto mb-4"></div>
        
        <h3 class="text-base sm:text-lg font-bold mb-3 sm:mb-4">Pilih Modul</h3>
        
        {{-- Module List --}}
        <div id="adminModules" class="grid grid-cols-2 gap-3 hidden">
          {{-- APAR --}}
          <a href="{{ route('admin.apar.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl transition-all group {{ request()->routeIs('admin.apar.*') || request()->routeIs('apar.*') ? 'bg-red-50 ring-2 ring-red-500' : 'bg-slate-50 hover:bg-red-50' }}">
            <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md p-2">
              <img src="{{ asset('images/apar.png') }}" alt="APAR" class="w-full h-full object-contain">
            </div>
            <div class="text-center">
              <p class="font-bold text-slate-900 text-sm">APAR</p>
              @if(request()->routeIs('admin.apar.*') || request()->routeIs('apar.*'))
                <svg class="w-4 h-4 text-red-600 mx-auto mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              @endif
            </div>
          </a>

          {{-- APAT --}}
          <a href="{{ route('apat.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl transition-all group {{ request()->routeIs('apat.*') ? 'bg-cyan-50 ring-2 ring-cyan-500' : 'bg-slate-50 hover:bg-cyan-50' }}">
            <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md p-2">
              <img src="{{ asset('images/apat.png') }}" alt="APAT" class="w-full h-full object-contain">
            </div>
            <div class="text-center">
              <p class="font-bold text-slate-900 text-sm">APAT</p>
              @if(request()->routeIs('apat.*'))
                <svg class="w-4 h-4 text-cyan-600 mx-auto mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              @endif
            </div>
          </a>

          {{-- APAB --}}
          <a href="{{ route('apab.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl transition-all group {{ request()->routeIs('apab.*') ? 'bg-orange-50 ring-2 ring-orange-500' : 'bg-slate-50 hover:bg-orange-50' }}">
            <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md p-2">
              <img src="{{ asset('images/apab.png') }}" alt="APAB" class="w-full h-full object-contain">
            </div>
            <div class="text-center">
              <p class="font-bold text-slate-900 text-sm">APAB</p>
              @if(request()->routeIs('apab.*'))
                <svg class="w-4 h-4 text-orange-600 mx-auto mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              @endif
            </div>
          </a>

          {{-- Fire Alarm --}}
          <a href="{{ route('fire-alarm.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl transition-all group {{ request()->routeIs('fire-alarm.*') ? 'bg-yellow-50 ring-2 ring-yellow-500' : 'bg-slate-50 hover:bg-yellow-50' }}">
            <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md p-2">
              <img src="{{ asset('images/fire-alarm.png') }}" alt="Fire Alarm" class="w-full h-full object-contain">
            </div>
            <div class="text-center">
              <p class="font-bold text-slate-900 text-sm">Fire Alarm</p>
              @if(request()->routeIs('fire-alarm.*'))
                <svg class="w-4 h-4 text-yellow-600 mx-auto mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              @endif
            </div>
          </a>

          {{-- Box Hydrant --}}
          <a href="{{ route('box-hydrant.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl transition-all group {{ request()->routeIs('box-hydrant.*') ? 'bg-blue-50 ring-2 ring-blue-500' : 'bg-slate-50 hover:bg-blue-50' }}">
            <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md p-2">
              <img src="{{ asset('images/box-hydrant.png') }}" alt="Box Hydrant" class="w-full h-full object-contain">
            </div>
            <div class="text-center">
              <p class="font-bold text-slate-900 text-sm">Box Hydrant</p>
              @if(request()->routeIs('box-hydrant.*'))
                <svg class="w-4 h-4 text-blue-600 mx-auto mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              @endif
            </div>
          </a>

          {{-- Rumah Pompa --}}
          <a href="{{ route('rumah-pompa.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl transition-all group {{ request()->routeIs('rumah-pompa.*') ? 'bg-indigo-50 ring-2 ring-indigo-500' : 'bg-slate-50 hover:bg-indigo-50' }}">
            <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md p-2">
              <img src="{{ asset('images/box-hydrant.png') }}" alt="Rumah Pompa" class="w-full h-full object-contain">
            </div>
            <div class="text-center">
              <p class="font-bold text-slate-900 text-sm">Rumah Pompa</p>
              @if(request()->routeIs('rumah-pompa.*'))
                <svg class="w-4 h-4 text-indigo-600 mx-auto mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              @endif
            </div>
          </a>

          {{-- P3K --}}
          <a href="{{ route('p3k.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl transition-all group {{ request()->routeIs('p3k.*') ? 'bg-green-50 ring-2 ring-green-500' : 'bg-slate-50 hover:bg-green-50' }}">
            <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md p-2">
              <img src="{{ asset('images/p3k.png') }}" alt="P3K" class="w-full h-full object-contain">
            </div>
            <div class="text-center">
              <p class="font-bold text-slate-900 text-sm">P3K</p>
              @if(request()->routeIs('p3k.*'))
                <svg class="w-4 h-4 text-green-600 mx-auto mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              @endif
            </div>
          </a>

          {{-- Referensi --}}
          <a href="{{ route('referensi.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl transition-all group {{ request()->routeIs('referensi.*') ? 'bg-purple-50 ring-2 ring-purple-500' : 'bg-slate-50 hover:bg-purple-50' }}">
            <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md p-2">
              <img src="{{ asset('images/referensi.png') }}" alt="Referensi" class="w-full h-full object-contain">
            </div>
            <div class="text-center">
              <p class="font-bold text-slate-900 text-sm">Referensi</p>
              @if(request()->routeIs('referensi.*'))
                <svg class="w-4 h-4 text-purple-600 mx-auto mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              @endif
            </div>
          </a>

          {{-- Floor Plan --}}
          <a href="{{ route('floor-plan.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl transition-all group {{ request()->routeIs('floor-plan.*') ? 'bg-teal-50 ring-2 ring-teal-500' : 'bg-slate-50 hover:bg-teal-50' }}">
            <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md p-2">
              <svg class="w-10 h-10 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
              </svg>
            </div>
            <div class="text-center">
              <p class="font-bold text-slate-900 text-sm">Denah</p>
              @if(request()->routeIs('floor-plan.*'))
                <svg class="w-4 h-4 text-teal-600 mx-auto mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              @endif
            </div>
          </a>
        </div>

        <div id="userModules" class="grid grid-cols-2 gap-3 hidden">
          {{-- APAR --}}
          <a href="{{ route('apar.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl transition-all group {{ request()->routeIs('apar.*') ? 'bg-red-50 ring-2 ring-red-500' : 'bg-slate-50 hover:bg-red-50' }}">
            <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md p-2">
              <img src="{{ asset('images/apar.png') }}" alt="APAR" class="w-full h-full object-contain">
            </div>
            <div class="text-center">
              <p class="font-bold text-slate-900 text-sm">APAR</p>
              @if(request()->routeIs('apar.*'))
                <svg class="w-4 h-4 text-red-600 mx-auto mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              @endif
            </div>
          </a>

          {{-- APAT --}}
          <a href="{{ route('apat.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl transition-all group {{ request()->routeIs('apat.*') ? 'bg-cyan-50 ring-2 ring-cyan-500' : 'bg-slate-50 hover:bg-cyan-50' }}">
            <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md p-2">
              <img src="{{ asset('images/apat.png') }}" alt="APAT" class="w-full h-full object-contain">
            </div>
            <div class="text-center">
              <p class="font-bold text-slate-900 text-sm">APAT</p>
              @if(request()->routeIs('apat.*'))
                <svg class="w-4 h-4 text-cyan-600 mx-auto mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              @endif
            </div>
          </a>

          {{-- APAB --}}
          <a href="{{ route('apab.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl transition-all group {{ request()->routeIs('apab.*') ? 'bg-orange-50 ring-2 ring-orange-500' : 'bg-slate-50 hover:bg-orange-50' }}">
            <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md p-2">
              <img src="{{ asset('images/apab.png') }}" alt="APAB" class="w-full h-full object-contain">
            </div>
            <div class="text-center">
              <p class="font-bold text-slate-900 text-sm">APAB</p>
              @if(request()->routeIs('apab.*'))
                <svg class="w-4 h-4 text-orange-600 mx-auto mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              @endif
            </div>
          </a>

          {{-- Fire Alarm --}}
          <a href="{{ route('fire-alarm.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl transition-all group {{ request()->routeIs('fire-alarm.*') ? 'bg-yellow-50 ring-2 ring-yellow-500' : 'bg-slate-50 hover:bg-yellow-50' }}">
            <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md p-2">
              <img src="{{ asset('images/fire-alarm.png') }}" alt="Fire Alarm" class="w-full h-full object-contain">
            </div>
            <div class="text-center">
              <p class="font-bold text-slate-900 text-sm">Fire Alarm</p>
              @if(request()->routeIs('fire-alarm.*'))
                <svg class="w-4 h-4 text-yellow-600 mx-auto mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              @endif
            </div>
          </a>

          {{-- Box Hydrant --}}
          <a href="{{ route('box-hydrant.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl transition-all group {{ request()->routeIs('box-hydrant.*') ? 'bg-blue-50 ring-2 ring-blue-500' : 'bg-slate-50 hover:bg-blue-50' }}">
            <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md p-2">
              <img src="{{ asset('images/box-hydrant.png') }}" alt="Box Hydrant" class="w-full h-full object-contain">
            </div>
            <div class="text-center">
              <p class="font-bold text-slate-900 text-sm">Box Hydrant</p>
              @if(request()->routeIs('box-hydrant.*'))
                <svg class="w-4 h-4 text-blue-600 mx-auto mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              @endif
            </div>
          </a>

          {{-- Rumah Pompa --}}
          <a href="{{ route('rumah-pompa.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl transition-all group {{ request()->routeIs('rumah-pompa.*') ? 'bg-indigo-50 ring-2 ring-indigo-500' : 'bg-slate-50 hover:bg-indigo-50' }}">
            <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md p-2">
              <img src="{{ asset('images/box-hydrant.png') }}" alt="Rumah Pompa" class="w-full h-full object-contain">
            </div>
            <div class="text-center">
              <p class="font-bold text-slate-900 text-sm">Rumah Pompa</p>
              @if(request()->routeIs('rumah-pompa.*'))
                <svg class="w-4 h-4 text-indigo-600 mx-auto mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              @endif
            </div>
          </a>

          {{-- P3K --}}
          <a href="{{ route('p3k.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl transition-all group {{ request()->routeIs('p3k.*') ? 'bg-green-50 ring-2 ring-green-500' : 'bg-slate-50 hover:bg-green-50' }}">
            <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md p-2">
              <img src="{{ asset('images/p3k.png') }}" alt="P3K" class="w-full h-full object-contain">
            </div>
            <div class="text-center">
              <p class="font-bold text-slate-900 text-sm">P3K</p>
              @if(request()->routeIs('p3k.*'))
                <svg class="w-4 h-4 text-green-600 mx-auto mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              @endif
            </div>
          </a>

          {{-- Floor Plan --}}
          <a href="{{ route('floor-plan.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl transition-all group {{ request()->routeIs('floor-plan.*') ? 'bg-teal-50 ring-2 ring-teal-500' : 'bg-slate-50 hover:bg-teal-50' }}">
            <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center group-hover:scale-110 transition-transform shadow-md p-2">
              <svg class="w-10 h-10 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
              </svg>
            </div>
            <div class="text-center">
              <p class="font-bold text-slate-900 text-sm">Denah</p>
              @if(request()->routeIs('floor-plan.*'))
                <svg class="w-4 h-4 text-teal-600 mx-auto mt-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              @endif
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- Profile Bottom Sheet (Mobile Admin) --}}
  <div id="profileSheet" class="md:hidden fixed inset-0 z-50 pointer-events-none">
    <div id="profileBackdrop" class="absolute inset-0 bg-black/50 opacity-0 transition-opacity duration-300"></div>
    
    <div id="profileSheetContent" class="absolute bottom-0 inset-x-0 bg-white rounded-t-3xl shadow-2xl transform translate-y-full transition-transform duration-300 pb-safe">
      <div class="p-6">
        <div class="w-12 h-1.5 bg-slate-300 rounded-full mx-auto mb-4"></div>
        
        {{-- User Info Header --}}
        @php
          $u = auth()->user();
          $avatar = $u?->avatar ? asset('storage/'.$u->avatar) : null;
          $initial = strtoupper(mb_substr($u?->name ?? 'U', 0, 1));
        @endphp
        <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-200">
          @if($avatar)
            <img src="{{ $avatar }}" alt="Avatar" class="w-16 h-16 rounded-full object-cover ring-2 ring-slate-200">
          @else
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-indigo-500 text-white grid place-items-center ring-2 ring-purple-200 text-2xl font-bold shadow-lg">{{ $initial }}</div>
          @endif
          <div class="flex-1 min-w-0">
            <p class="text-lg font-bold text-slate-900 truncate">{{ $u?->name ?? 'User' }}</p>
            <p class="text-sm text-slate-600 truncate">{{ $u?->email ?? '' }}</p>
            @if(auth()->check())
              @if(auth()->user()->hasRole('superadmin'))
                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-bold rounded-md bg-purple-100 text-purple-700 mt-1">
              @elseif(auth()->user()->hasRole('leader'))
                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-bold rounded-md bg-green-100 text-green-700 mt-1">
                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
                  Admin
                </span>
              @elseif(auth()->user()->hasRole('inspector'))
                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-bold rounded-md bg-blue-100 text-blue-700 mt-1">
                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                  </svg>
                  Inspector
                </span>
              @else
                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-bold rounded-md bg-emerald-100 text-emerald-700 mt-1">
                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                  </svg>
                  User
                </span>
              @endif
            @endif
          </div>
        </div>
        
        @if(auth()->check() && (auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('leader')))
          <div class="space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 p-4 rounded-xl bg-purple-50 hover:bg-purple-100 transition-colors">
              <div class="w-10 h-10 rounded-xl bg-purple-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
              </div>
              <div>
                <p class="font-semibold text-slate-900">Admin Panel</p>
                <p class="text-xs text-slate-600">Kelola sistem</p>
              </div>
            </a>

            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 p-4 rounded-xl bg-blue-50 hover:bg-blue-100 transition-colors">
              <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
              </div>
              <div>
                <p class="font-semibold text-slate-900">User Dashboard</p>
                <p class="text-xs text-slate-600">Lihat sebagai user</p>
              </div>
            </a>

            {{-- Unit Switcher untuk Admin --}}
            @if(!auth()->user()->unit_id)
              <div class="border-t border-slate-200 my-3 pt-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3 px-4">Lihat Data Unit</p>
                <form method="POST" action="{{ route('unit.switch') }}" class="px-4">
                  @csrf
                  <select name="unit_id" onchange="this.form.submit()" class="w-full text-sm px-3 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white">
                    <option value="">Semua Unit</option>
                    @foreach(\App\Models\Unit::where('is_active', true)->get() as $unit)
                      <option value="{{ $unit->id }}" {{ session('viewing_unit_id') == $unit->id ? 'selected' : '' }}>
                        {{ $unit->name }}
                      </option>
                    @endforeach
                  </select>
                </form>
                @if(session('viewing_unit_id'))
                  @php $viewingUnit = \App\Models\Unit::find(session('viewing_unit_id')); @endphp
                  <div class="mt-2 mx-4 text-xs text-emerald-700 bg-emerald-50 px-3 py-2 rounded-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">Viewing: {{ $viewingUnit->code }}</span>
                  </div>
                @endif
              </div>
            @endif

            <div class="border-t border-slate-200 my-3"></div>

            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-4 rounded-xl hover:bg-slate-50 transition-colors">
              <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              <span class="font-medium text-slate-900">Profil Saya</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="w-full flex items-center gap-3 p-4 rounded-xl hover:bg-rose-50 transition-colors text-left">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="font-medium text-rose-700">Logout</span>
              </button>
            </form>
          </div>
        @else
          {{-- User Profile Menu --}}
          <div class="space-y-2">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-4 rounded-xl hover:bg-slate-50 transition-colors">
              <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              <span class="font-medium text-slate-900">Profil Saya</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="w-full flex items-center gap-3 p-4 rounded-xl hover:bg-rose-50 transition-colors text-left">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="font-medium text-rose-700">Logout</span>
              </button>
            </form>
          </div>
        @endif
      </div>
    </div>
  </div>

  <script>
    // Optimized module sheet toggle with debounce
    let isToggling = false;
    let toggleTimeout = null;
    let lastClickTime = 0;
    
    function toggleModulSheet(role) {
      // Prevent rapid clicks - require 300ms between clicks
      const now = Date.now();
      if (isToggling || (now - lastClickTime < 300)) return;
      
      lastClickTime = now;
      
      // Clear any pending toggle
      if (toggleTimeout) {
        clearTimeout(toggleTimeout);
      }
      
      isToggling = true;
      
      const sheet = document.getElementById('modulSheet');
      const backdrop = document.getElementById('modulBackdrop');
      const content = document.getElementById('modulSheetContent');
      const adminModules = document.getElementById('adminModules');
      const userModules = document.getElementById('userModules');
      
      if (!sheet || !backdrop || !content) {
        isToggling = false;
        return;
      }
      
      const isOpen = !sheet.classList.contains('pointer-events-none');
      
      if (isOpen) {
        // Close
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        content.classList.remove('translate-y-0');
        content.classList.add('translate-y-full');
        
        toggleTimeout = setTimeout(() => {
          sheet.classList.add('pointer-events-none');
          isToggling = false;
          toggleTimeout = null;
        }, 300);
      } else {
        // Open
        sheet.classList.remove('pointer-events-none');
        
        // Show correct module list immediately
        if (role === 'admin') {
          adminModules?.classList.remove('hidden');
          userModules?.classList.add('hidden');
        } else {
          userModules?.classList.remove('hidden');
          adminModules?.classList.add('hidden');
        }
        
        // Use requestAnimationFrame for smoother animation
        requestAnimationFrame(() => {
          backdrop.classList.remove('opacity-0');
          backdrop.classList.add('opacity-100');
          content.classList.remove('translate-y-full');
          content.classList.add('translate-y-0');
          
          toggleTimeout = setTimeout(() => {
            isToggling = false;
            toggleTimeout = null;
          }, 350);
        });
      }
    }

    // Close sheet when clicking backdrop - optimized
    document.addEventListener('DOMContentLoaded', function() {
      const backdrop = document.getElementById('modulBackdrop');
      
      if (backdrop) {
        backdrop.addEventListener('click', function(e) {
          if (e.target === backdrop && !isToggling) {
            toggleModulSheet();
          }
        }, { passive: true });
      }
      
      // Preload critical images
      const criticalImages = [
        '{{ asset("images/logoo.png") }}'
      ];
      
      criticalImages.forEach(src => {
        const link = document.createElement('link');
        link.rel = 'preload';
        link.as = 'image';
        link.href = src;
        document.head.appendChild(link);
      });
    }, { once: true });
    
    // Profile sheet toggle
    function toggleProfileSheet() {
      const now = Date.now();
      if (isToggling || (now - lastClickTime < 300)) return;
      
      lastClickTime = now;
      
      if (toggleTimeout) {
        clearTimeout(toggleTimeout);
      }
      
      isToggling = true;
      
      const sheet = document.getElementById('profileSheet');
      const backdrop = document.getElementById('profileBackdrop');
      const content = document.getElementById('profileSheetContent');
      
      if (!sheet || !backdrop || !content) {
        isToggling = false;
        return;
      }
      
      const isOpen = !sheet.classList.contains('pointer-events-none');
      
      if (isOpen) {
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        content.classList.remove('translate-y-0');
        content.classList.add('translate-y-full');
        
        toggleTimeout = setTimeout(() => {
          sheet.classList.add('pointer-events-none');
          isToggling = false;
          toggleTimeout = null;
        }, 300);
      } else {
        sheet.classList.remove('pointer-events-none');
        
        requestAnimationFrame(() => {
          backdrop.classList.remove('opacity-0');
          backdrop.classList.add('opacity-100');
          content.classList.remove('translate-y-full');
          content.classList.add('translate-y-0');
          
          toggleTimeout = setTimeout(() => {
            isToggling = false;
            toggleTimeout = null;
          }, 350);
        });
      }
    }
    
    // Close profile sheet when clicking backdrop
    document.addEventListener('DOMContentLoaded', function() {
      const profileBackdrop = document.getElementById('profileBackdrop');
      
      if (profileBackdrop) {
        profileBackdrop.addEventListener('click', function(e) {
          if (e.target === profileBackdrop && !isToggling) {
            toggleProfileSheet();
          }
        }, { passive: true });
      }
    }, { once: true });
    
    // Prevent form double submission
    document.addEventListener('submit', function(e) {
      const form = e.target;
      if (form.dataset.submitting === 'true') {
        e.preventDefault();
        return false;
      }
      form.dataset.submitting = 'true';
      
      // Reset after 3 seconds as fallback
      setTimeout(() => {
        form.dataset.submitting = 'false';
      }, 3000);
    }, { passive: false });
    
    // Prevent double-click on buttons and links
    document.addEventListener('click', function(e) {
      const target = e.target.closest('button, a');
      if (!target) return;
      
      // Skip if it's a button type="button" or has data-no-debounce
      if (target.type === 'button' && target.getAttribute('type') === 'button') return;
      if (target.hasAttribute('data-no-debounce')) return;
      
      // Check if already clicked recently
      if (target.dataset.clicking === 'true') {
        e.preventDefault();
        e.stopPropagation();
        return false;
      }
      
      // Mark as clicked
      target.dataset.clicking = 'true';
      
      // Reset after 1 second
      setTimeout(() => {
        target.dataset.clicking = 'false';
      }, 1000);
    }, { capture: true });
  </script>

</body>
</html>
