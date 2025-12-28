<x-layouts.app :title="'Tambah APAB — Admin'">
  <div class="max-w-4xl mx-auto px-4 py-6">
    
    {{-- Header Card --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 to-cyan-600 p-8 mb-6 shadow-2xl">
      <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
      <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>
      
      <div class="relative flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
          </div>
          <div>
            <h1 class="text-3xl font-bold text-white">Tambah Apab Baru</h1>
            <p class="text-white/80 text-sm mt-1">Input data Apab baru. Serial akan otomatis dibuat.</p>
          </div>
        </div>
        <a href="{{ route('admin.apab.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/20 backdrop-blur-sm border border-white/30 text-white text-sm font-medium hover:bg-white/30 transition-all duration-200">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          <span>Kembali</span>
        </a>
      </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-3xl shadow-xl border-2 border-slate-100 overflow-hidden">
      
      {{-- Section Header --}}
      <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-8 py-6 border-b-2 border-slate-100">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg">
            <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
          <div>
            <h2 class="text-xl font-bold text-slate-900">Informasi Apab</h2>
            <p class="text-sm text-slate-600">Lengkapi form di bawah untuk menambahkan Apab</p>
          </div>
        </div>
      </div>

      <form action="{{ route('admin.apab.store') }}" method="POST" class="p-8 space-y-6">
        @csrf

        {{-- Serial / Kode --}}
        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
            </svg>
            Serial / Kode
          </label>
          <div class="relative">
            <input type="text" 
                   value="{{ $nextSerial }}" 
                   readonly
                   class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-slate-900 font-semibold text-lg">
            <div class="absolute right-3 top-1/2 -translate-y-1/2">
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-100 text-blue-700 text-xs font-semibold">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Auto
              </span>
            </div>
          </div>
          <p class="flex items-center gap-1.5 text-xs text-slate-500">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Serial otomatis: A3.xxx (increment). QR Code akan mengikuti serial ini.
          </p>
        </div>

        {{-- location_code & type --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-2">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
              <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              location_code
            </label>
            <input type="text" 
                   name="location_code" 
                   value="{{ old('location_code') }}" 
                   required
                   placeholder="BDG"
                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all @error('location_code') border-red-500 @enderror">
            @error('location_code')
              <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="space-y-2">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
              <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
              </svg>
              type
            </label>
            <input type="text" 
                   name="type" 
                   value="{{ old('type') }}" 
                   required
                   placeholder="Karung Pasir"
                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all @error('type') border-red-500 @enderror">
            @error('type')
              <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        {{-- capacity --}}
        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            capacity
          </label>
          <input type="text" 
                 name="capacity" 
                 value="{{ old('capacity') }}" 
                 required
                 placeholder="10 Karung"
                 class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all @error('capacity') border-red-500 @enderror">
          @error('capacity')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
          @enderror
        </div>

        {{-- Status Kondisi --}}
        <div class="space-y-3">
          <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Status Kondisi
          </label>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <label class="relative cursor-pointer group">
              <input type="radio" name="status" value="baik" {{ old('status') === 'baik' ? 'checked' : '' }} required class="peer sr-only">
              <div class="p-6 rounded-2xl border-2 border-slate-200 bg-white peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:border-emerald-300 transition-all duration-200">
                <div class="flex flex-col items-center gap-3">
                  <div class="w-12 h-12 rounded-full bg-emerald-100 peer-checked:bg-emerald-500 flex items-center justify-center transition-colors">
                    <svg class="w-6 h-6 text-emerald-600 peer-checked:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </div>
                  <span class="text-sm font-semibold text-slate-700 peer-checked:text-emerald-700">BAIK</span>
                </div>
              </div>
            </label>

            <label class="relative cursor-pointer group">
              <input type="radio" name="status" value="rusak" {{ old('status') === 'rusak' ? 'checked' : '' }} class="peer sr-only">
              <div class="p-6 rounded-2xl border-2 border-slate-200 bg-white peer-checked:border-rose-500 peer-checked:bg-rose-50 hover:border-rose-300 transition-all duration-200">
                <div class="flex flex-col items-center gap-3">
                  <div class="w-12 h-12 rounded-full bg-rose-100 peer-checked:bg-rose-500 flex items-center justify-center transition-colors">
                    <svg class="w-6 h-6 text-rose-600 peer-checked:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                  </div>
                  <span class="text-sm font-semibold text-slate-700 peer-checked:text-rose-700">RUSAK</span>
                </div>
              </div>
            </label>
          </div>
          @error('status')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
          @enderror
        </div>

        {{-- Catatan --}}
        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Catatan
            <span class="text-xs text-slate-500 font-normal">(opsional)</span>
          </label>
          <textarea name="notes" 
                    rows="4"
                    placeholder="Tambahkan catatan atau informasi tambahan..."
                    class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all resize-none">{{ old('notes') }}</textarea>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-3 pt-6 border-t-2 border-slate-100">
          <button type="submit" 
                  class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-bold hover:from-blue-700 hover:to-cyan-700 shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>Simpan Apab</span>
          </button>
          <a href="{{ route('admin.apab.index') }}"
             class="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl border-2 border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50 hover:border-slate-300 transition-all duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span>Batal</span>
          </a>
        </div>
      </form>
    </div>

  </div>
</x-layouts.app>
