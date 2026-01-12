{{-- resources/views/fire-alarm/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Fire Alarm ' . ($fireAlarm->serial_no ?? ''))

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 space-y-6">
    {{-- Header Section --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-red-600 via-pink-600 to-rose-600 p-8 shadow-xl">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24"></div>
        
        <div class="relative flex items-start justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white mb-1">Edit Fire Alarm {{ $fireAlarm->serial_no }}</h1>
                    <p class="text-pink-100 text-sm">Perbarui informasi dan status Fire Alarm</p>
                </div>
            </div>
            <a href="{{ route('fire-alarm.index') }}"
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

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-8 py-5 border-b border-slate-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-pink-500 flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Informasi Fire Alarm</h2>
                    <p class="text-xs text-slate-600">Update data Fire Alarm yang sudah ada</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('fire-alarm.update', $fireAlarm) }}" class="p-8">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Serial & Barcode (readonly) --}}
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="relative">
                        <div class="absolute -left-3 top-0 bottom-0 w-1 bg-gradient-to-b from-red-500 to-pink-500 rounded-full"></div>
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                            Serial / Nomor
                        </label>
                        <input type="text" value="{{ $fireAlarm->serial_no }}" disabled
                               class="block w-full rounded-xl border-2 border-slate-200 bg-gradient-to-br from-slate-50 to-slate-100 text-slate-900 text-base font-mono font-bold px-4 py-3 shadow-sm">
                    </div>

                    <div>
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                            </svg>
                            Barcode
                        </label>
                        <input type="text" value="{{ $fireAlarm->barcode }}" disabled
                               class="block w-full rounded-xl border-2 border-slate-200 bg-gradient-to-br from-slate-50 to-slate-100 text-slate-900 text-base font-mono font-bold px-4 py-3 shadow-sm">
                    </div>
                </div>

                <p class="text-xs text-slate-500 flex items-center gap-1.5 -mt-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Serial dan Barcode tidak dapat diubah setelah Fire Alarm dibuat
                </p>

                {{-- Nama --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Nama Fire Alarm
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name', $fireAlarm->name) }}"
                           placeholder="Contoh: Fire Alarm F1.001"
                           class="block w-full rounded-xl border-2 border-slate-200 text-slate-900 px-4 py-3 text-sm focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all @error('name') border-rose-500 @enderror">
                    @error('name')
                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Grid Fields --}}
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Lokasi
                        </label>
                        <input type="text" name="location_code"
                               value="{{ old('location_code', $fireAlarm->location_code) }}"
                               placeholder="Misal: Lantai 1 / Ruang Server"
                               class="block w-full rounded-xl border-2 border-slate-200 text-slate-900 px-4 py-3 text-sm focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all @error('location_code') border-rose-500 @enderror">
                    </div>

                    <div>
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Tipe
                        </label>
                        <input type="text" name="type"
                               value="{{ old('type', $fireAlarm->type) }}"
                               placeholder="Misal: Panel / Detector / Manual Call Point"
                               class="block w-full rounded-xl border-2 border-slate-200 text-slate-900 px-4 py-3 text-sm focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all @error('type') border-rose-500 @enderror">
                    </div>

                    <div>
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            Zone
                        </label>
                        <input type="text" name="zone"
                               value="{{ old('zone', $fireAlarm->zone) }}"
                               placeholder="Misal: Zone 1 / Zone A"
                               class="block w-full rounded-xl border-2 border-slate-200 text-slate-900 px-4 py-3 text-sm focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all @error('zone') border-rose-500 @enderror">
                    </div>

                    <div>
                        <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Status Kondisi
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach([
                                ['BAIK', 'emerald', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                ['RUSAK', 'rose', 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z']
                            ] as [$status, $color, $icon])
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="status" value="{{ $status }}"
                                           {{ old('status', $fireAlarm->status) === $status ? 'checked' : '' }}
                                           class="peer sr-only">
                                    <div class="flex flex-col items-center gap-2 p-3 rounded-xl border-2 border-slate-200 bg-white transition-all peer-checked:border-{{ $color }}-500 peer-checked:bg-{{ $color }}-50 peer-checked:shadow-lg hover:border-{{ $color }}-300">
                                        <div class="w-8 h-8 rounded-lg bg-{{ $color }}-100 flex items-center justify-center peer-checked:bg-{{ $color }}-500 transition-colors">
                                            <svg class="w-5 h-5 text-{{ $color }}-600 peer-checked:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-semibold text-slate-700 peer-checked:text-{{ $color }}-700">{{ $status }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Catatan
                        <span class="text-xs text-slate-500 font-normal">(opsional)</span>
                    </label>
                    <textarea name="notes" rows="4"
                              placeholder="Tambahkan catatan atau informasi tambahan..."
                              class="block w-full rounded-xl border-2 border-slate-200 text-slate-900 px-4 py-3 text-sm focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all resize-none @error('notes') border-rose-500 @enderror">{{ old('notes', $fireAlarm->notes) }}</textarea>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-8 pt-6 border-t border-slate-200 flex items-center justify-between gap-4">
                <a href="{{ route('fire-alarm.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border-2 border-slate-200 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-gradient-to-r from-red-600 to-pink-600 text-white text-sm font-bold hover:from-red-700 hover:to-pink-700 shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
