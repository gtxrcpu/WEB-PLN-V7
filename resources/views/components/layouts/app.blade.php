<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Inventaris K3 PLN' }}</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>
    [x-cloak] { display: none !important; }
  </style>
</head>
<body class="antialiased bg-slate-50 text-slate-900">
  {{-- Topbar --}}
  <header class="sticky top-0 z-40 bg-white/85 backdrop-blur ring-1 ring-slate-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <img src="{{ asset('images/logoo.png') }}" alt="PLN" class="h-8 w-auto object-contain">
        <div class="flex items-center gap-2">
          <span class="font-semibold">Inventaris K3 PLN — 
            @if(auth()->check() && auth()->user()->hasRole('admin'))
              <span class="text-purple-700">Admin</span>
            @else
              <span class="text-emerald-700">User</span>
            @endif
          </span>
          
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

      <div class="hidden md:flex items-center gap-4">
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
            <span class="text-sm">{{ $u?->name ?? 'User' }}</span>
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
            @if(auth()->check() && auth()->user()->hasRole('admin'))
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
                          {{ $unit->code }}
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

  {{-- Bottom nav (mobile) - Different for Admin & User --}}
  <nav class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white border-t border-slate-200 shadow-lg pb-safe">
    @if(auth()->check() && auth()->user()->hasRole('admin'))
      {{-- Admin Navigation --}}
      <div class="grid grid-cols-3 h-16">
        <a href="{{ route('admin.dashboard') }}" 
           class="relative flex flex-col items-center justify-center gap-1 text-slate-700 hover:text-purple-600 hover:bg-purple-50 transition-all {{ request()->routeIs('admin.dashboard') ? 'text-purple-600 bg-purple-50' : '' }}">
          @if(request()->routeIs('admin.dashboard'))
            <span class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-purple-600 rounded-b-full"></span>
          @endif
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
          <span class="text-xs font-medium">Home</span>
        </a>

        <button type="button" onclick="toggleModulSheet('admin')" 
           class="relative flex flex-col items-center justify-center gap-1 text-slate-700 hover:text-purple-600 hover:bg-purple-50 transition-all {{ request()->routeIs('admin.apar.*') || request()->routeIs('apar.*') || request()->routeIs('apat.*') || request()->routeIs('apab.*') || request()->routeIs('fire-alarm.*') || request()->routeIs('box-hydrant.*') || request()->routeIs('rumah-pompa.*') || request()->routeIs('p3k.*') || request()->routeIs('referensi.*') ? 'text-purple-600 bg-purple-50' : '' }}">
          @if(request()->routeIs('admin.apar.*') || request()->routeIs('apar.*') || request()->routeIs('apat.*') || request()->routeIs('apab.*') || request()->routeIs('fire-alarm.*') || request()->routeIs('box-hydrant.*') || request()->routeIs('rumah-pompa.*') || request()->routeIs('p3k.*') || request()->routeIs('referensi.*'))
            <span class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 bg-purple-600 rounded-b-full"></span>
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

        <button type="button" onclick="toggleModulSheet('user')" 
           class="relative flex flex-col items-center justify-center gap-1 text-slate-700 hover:text-blue-600 hover:bg-blue-50 transition-all {{ request()->routeIs('apar.*') || request()->routeIs('apat.*') || request()->routeIs('apab.*') || request()->routeIs('fire-alarm.*') || request()->routeIs('box-hydrant.*') || request()->routeIs('rumah-pompa.*') || request()->routeIs('p3k.*') || request()->routeIs('referensi.*') ? 'text-blue-600 bg-blue-50' : '' }}">
          @if(request()->routeIs('apar.*') || request()->routeIs('apat.*') || request()->routeIs('apab.*') || request()->routeIs('fire-alarm.*') || request()->routeIs('box-hydrant.*') || request()->routeIs('rumah-pompa.*') || request()->routeIs('p3k.*') || request()->routeIs('referensi.*'))
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
        </div>
      </div>
    </div>
  </div>

  <script>
    // Optimized module sheet toggle with debounce
    let isToggling = false;
    let toggleTimeout = null;
    
    function toggleModulSheet(role) {
      // Prevent rapid clicks
      if (isToggling) return;
      
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
  </script>

</body>
</html>
