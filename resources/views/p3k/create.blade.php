{{-- resources/views/p3k/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah P3K')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 space-y-6">
    {{-- Header Section --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-600 via-green-600 to-emerald-700 p-8 shadow-xl">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24"></div>
        
        <div class="relative flex items-start justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white mb-1">Tambah P3K Baru</h1>
                    <p class="text-emerald-100 text-sm">Input data Kotak Pertolongan Pertama. Serial otomatis: <span class="font-semibold">P3K.xxx</span></p>
                </div>
            </div>
            <a href="{{ route('p3k.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/20 backdrop-blur-sm text-white text-sm font-medium hover:bg-white/30 transition-all duration-200 border border-white/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-rose-800 mb-1">Periksa kembali:</div>
                    <ul class="list-disc pl-4 space-y-0.5 text-sm text-rose-700">
                        @foreach ($errors->all() as $msg)
                            <li>{{ $msg }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @php
        $last = \App\Models\P3k::orderBy('id', 'desc')->first();
        $lastNumber = 0;

        if ($last && $last->serial_no) {
            $parts = explode('.', $last->serial_no);
            $lastNumber = isset($parts[1]) ? (int) $parts[1] : 0;
        }

        $nextNumber  = $lastNumber + 1;
        $nextSerial  = 'P3K.' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        $nextBarcode = 'P3K ' . $nextSerial;
    @endphp

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
        {{-- Form Header --}}
        <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-8 py-5 border-b border-slate-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-green-500 flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Informasi P3K</h2>
                    <p class="text-xs text-slate-600">Lengkapi form di bawah untuk menambahkan P3K</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('p3k.store') }}" class="p-8">
            @csrf

            <div class="space-y-6">
                {{-- Nama P3K --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Nama P3K
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name', 'P3K ' . $nextSerial) }}"
                           placeholder="Contoh: P3K P3K.001"
                           class="block w-full rounded-xl border-2 border-slate-200 text-slate-900 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all @error('name') border-rose-500 focus:border-rose-500 focus:ring-rose-500/10 @enderror">
                    @error('name')
                        <p class="mt-2 text-xs text-rose-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Serial & Barcode --}}
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="relative">
                        <div class="absolute -left-3 top-0 bottom-0 w-1 bg-gradient-to-b from-emerald-500 to-green-500 rounded-full"></div>
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                            Nomor (Serial)
                        </label>
                        <div class="relative">
                            <input type="text" name="serial_no"
                                   value="{{ old('serial_no', $nextSerial) }}"
                                   readonly
                                   class="block w-full rounded-xl border-2 border-slate-200 bg-gradient-to-br from-slate-50 to-slate-100 text-slate-900 text-base font-mono font-bold px-4 py-3 shadow-sm">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-semibold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Auto
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                            Barcode
                        </label>
                        <input type="text" name="barcode"
                               value="{{ old('barcode', $nextBarcode) }}"
                               readonly
                               class="block w-full rounded-xl border-2 border-slate-200 bg-gradient-to-br from-slate-50 to-slate-100 text-slate-900 text-base font-mono font-bold px-4 py-3 shadow-sm">
                    </div>
                </div>

                <p class="text-xs text-slate-500 flex items-center gap-1.5 -mt-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Serial dan Barcode otomatis: P3K.xxx (increment). QR Code akan mengikuti barcode ini.
                </p>

                {{-- Grid Layout --}}
                <div class="grid md:grid-cols-2 gap-6">
                    {{-- Lokasi --}}
                    <div>
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Lokasi
                        </label>
                        <input type="text" name="location_code"
                               value="{{ old('location_code') }}"
                               placeholder="Misal: Ruang Produksi / Kantor"
                               class="block w-full rounded-xl border-2 border-slate-200 text-slate-900 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all">
                    </div>

                    {{-- Tipe --}}
                    <div>
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Tipe Kotak
                        </label>
                        <input type="text" name="type"
                               value="{{ old('type') }}"
                               placeholder="Misal: Portable / Wall-mounted"
                               class="block w-full rounded-xl border-2 border-slate-200 text-slate-900 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all">
                    </div>

                    {{-- Status --}}
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Status Kelengkapan
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="status" value="lengkap"
                                       {{ old('status', 'lengkap') === 'lengkap' ? 'checked' : '' }}
                                       class="peer sr-only">
                                <div class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-slate-200 bg-white transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:shadow-lg peer-checked:shadow-emerald-500/20 hover:border-emerald-300">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center peer-checked:bg-emerald-500 transition-colors">
                                        <svg class="w-6 h-6 text-emerald-600 peer-checked:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-700 peer-checked:text-emerald-700">LENGKAP</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="status" value="tidak_lengkap"
                                       {{ old('status') === 'tidak_lengkap' ? 'checked' : '' }}
                                       class="peer sr-only">
                                <div class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-slate-200 bg-white transition-all peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:shadow-lg peer-checked:shadow-amber-500/20 hover:border-amber-300">
                                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center peer-checked:bg-amber-500 transition-colors">
                                        <svg class="w-6 h-6 text-amber-600 peer-checked:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-700 peer-checked:text-amber-700">TIDAK LENGKAP</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="status" value="kadaluarsa"
                                       {{ old('status') === 'kadaluarsa' ? 'checked' : '' }}
                                       class="peer sr-only">
                                <div class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-slate-200 bg-white transition-all peer-checked:border-rose-500 peer-checked:bg-rose-50 peer-checked:shadow-lg peer-checked:shadow-rose-500/20 hover:border-rose-300">
                                    <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center peer-checked:bg-rose-500 transition-colors">
                                        <svg class="w-6 h-6 text-rose-600 peer-checked:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-700 peer-checked:text-rose-700">KADALUARSA</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Catatan
                        <span class="text-xs text-slate-500 font-normal">(opsional)</span>
                    </label>
                    <textarea name="notes" rows="4"
                              placeholder="Misal: Perlu pengecekan rutin setiap bulan, dekat pintu darurat, dsb."
                              class="block w-full rounded-xl border-2 border-slate-200 text-slate-900 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all resize-none">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-8 pt-6 border-t border-slate-200 flex items-center justify-between gap-4">
                <a href="{{ route('p3k.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border-2 border-slate-200 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 text-white text-sm font-bold hover:from-emerald-700 hover:to-green-700 shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan P3K
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
