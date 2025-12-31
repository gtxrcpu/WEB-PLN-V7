<x-kartu-layout 
    title="Kartu Kendali APAT" 
    :subtitle="$apat->barcode ?? $apat->serial_no"
    back-route="apat.index"
    module="apat"
    :template="$template">

    {{-- INFO APAT --}}
    <div class="mb-6 p-4 bg-slate-50 rounded-lg border border-slate-200">
        <h3 class="font-bold text-gray-900 mb-3">Informasi APAT</h3>
        <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
            <div>
                <span class="text-gray-600">Kode/Barcode:</span>
                <span class="font-semibold ml-2">{{ $apat->barcode ?? $apat->serial_no }}</span>
            </div>
            <div>
                <span class="text-gray-600">Lokasi:</span>
                <span class="font-semibold ml-2">{{ $apat->location_code ?? '-' }}</span>
            </div>
        </div>
    </div>

        {{-- FORM --}}
        @if ($errors->any())
            <div class="no-print mt-4 mb-4 rounded-xl border-2 border-amber-200 bg-gradient-to-r from-amber-50 to-orange-50 px-5 py-4 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-amber-900 mb-2">Mohon lengkapi data berikut:</h4>
                        <ul class="space-y-1.5 text-sm text-amber-800">
                            @foreach ($errors->all() as $msg)
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $msg }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('apat.kartu.store') }}" class="mt-4 space-y-4">
            @csrf
            <input type="hidden" name="apat_id" value="{{ $apat->id }}">

            @php
                $opsi = [
                    'baik'       => 'Baik',
                    'tidak baik' => 'Tidak Baik',
                ];
            @endphp

            {{-- TABEL PEMERIKSAAN - DYNAMIC FROM TEMPLATE --}}
            <div class="border border-slate-400 text-xs">
                <div class="grid grid-cols-12 bg-slate-100 border-b border-slate-400 font-semibold">
                    <div class="col-span-6 px-3 py-2 border-r border-slate-400">
                        PEMERIKSAAN
                    </div>
                    <div class="col-span-6 px-3 py-2 text-center">
                        KONDISI
                    </div>
                </div>

                @if($template && $template->inspection_fields)
                    {{-- Use dynamic template fields --}}
                    @foreach($template->inspection_fields as $index => $field)
                        <div class="grid grid-cols-12 border-t border-slate-300">
                            <div class="col-span-6 px-3 py-2 border-r border-slate-300">
                                {{ $field['label'] }}
                            </div>
                            <div class="col-span-6 px-3 py-1.5">
                                <div class="flex items-center gap-6">
                                    @foreach ($opsi as $val => $text)
                                        <label class="inline-flex items-center gap-1.5">
                                            <input type="radio"
                                                   name="inspection_{{ $index }}"
                                                   value="{{ $val }}"
                                                   class="border-slate-300 text-sky-600 focus:ring-sky-500"
                                                   {{ old('inspection_' . $index) === $val ? 'checked' : '' }}>
                                            <span>{{ $text }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('inspection_' . $index)
                                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Fallback to hardcoded fields --}}
                    @foreach ([
                        'kondisi_fisik' => 'Kondisi Fisik',
                        'drum'          => 'Drum',
                        'aduk_pasir'    => 'Aduk Pasir',
                        'sekop'         => 'Sekop',
                        'fire_blanket'  => 'Fire Blanket',
                        'ember'         => 'Ember',
                    ] as $field => $label)
                        <div class="grid grid-cols-12 border-t border-slate-300">
                            <div class="col-span-6 px-3 py-2 border-r border-slate-300">
                                {{ $label }}
                            </div>
                            <div class="col-span-6 px-3 py-1.5">
                                <div class="flex items-center gap-6">
                                    @foreach ($opsi as $val => $text)
                                        <label class="inline-flex items-center gap-1.5">
                                            <input type="radio"
                                                   name="{{ $field }}"
                                                   value="{{ $val }}"
                                                   class="border-slate-300 text-sky-600 focus:ring-sky-500"
                                                   {{ old($field) === $val ? 'checked' : '' }}>
                                            <span>{{ $text }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error($field)
                                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- KESIMPULAN --}}
            <div class="border border-slate-400 text-xs">
                <div class="grid grid-cols-12 border-b border-slate-300">
                    <div class="col-span-3 px-3 py-2 border-r border-slate-300 font-semibold">
                        Kesimpulan
                    </div>
                    <div class="col-span-3 px-3 py-2 border-r border-slate-300">
                        <select name="kesimpulan" class="border border-slate-300 rounded-md px-2 py-1 text-xs w-full">
                            <option value="">-- Pilih Kesimpulan --</option>
                            <option value="baik" {{ old('kesimpulan') === 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="tidak_baik" {{ old('kesimpulan') === 'tidak_baik' ? 'selected' : '' }}>Tidak Baik</option>
                        </select>
                        @error('kesimpulan')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-3 px-3 py-2 border-r border-slate-300 font-semibold">
                        Tanggal Pemeriksaan
                    </div>
                    <div class="col-span-3 px-3 py-2">
                        <input type="date"
                               name="tgl_periksa"
                               value="{{ old('tgl_periksa', now()->toDateString()) }}"
                               class="border border-slate-300 rounded-md px-2 py-1 text-xs w-full">
                        @error('tgl_periksa')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-12">
                    <div class="col-span-3 px-3 py-2 border-r border-slate-300 font-semibold">
                        Petugas Pemeriksa
                    </div>
                    <div class="col-span-3 px-3 py-2 border-r border-slate-300">
                        <input type="text"
                               name="petugas"
                               value="{{ old('petugas') }}"
                               placeholder="Nama petugas"
                               class="border border-slate-300 rounded-md px-2 py-1 text-xs w-full">
                        @error('petugas')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-3 px-3 py-2 border-r border-slate-300 font-semibold">
                        Pengawas
                    </div>
                    <div class="col-span-3 px-3 py-2">
                        <input type="text"
                               name="pengawas"
                               value="{{ old('pengawas') }}"
                               placeholder="Nama pengawas (optional)"
                               class="border border-slate-300 rounded-md px-2 py-1 text-xs w-full">
                        @error('pengawas')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- TTD SECTION - USING TEMPLATE --}}
            <div class="mt-8 pt-6 border-t-2 border-gray-200">
                <div class="flex justify-end">
                    <div class="text-center">
                        @php
                            $lokasi = 'Surabaya'; // default
                            $labelPimpinan = 'Team Leader K3L & KAM'; // default
                            if ($template && $template->footer_fields) {
                                $lokasiField = collect($template->footer_fields)->firstWhere('label', 'Lokasi');
                                if ($lokasiField && isset($lokasiField['value'])) {
                                    $lokasi = $lokasiField['value'];
                                }
                                $pimpinanField = collect($template->footer_fields)->firstWhere('label', 'Label Pimpinan');
                                if ($pimpinanField && isset($pimpinanField['value'])) {
                                    $labelPimpinan = $pimpinanField['value'];
                                }
                            }
                        @endphp
                        <p class="text-sm text-gray-600 mb-1">{{ $lokasi }}, {{ now()->format('d-m-Y') }}</p>
                        <p class="text-sm font-semibold text-gray-900 mb-16">{{ $labelPimpinan }}</p>
                        <div class="border-t-2 border-gray-400 pt-2 w-56">
                            <p class="text-sm text-gray-600">(Tanda Tangan & Nama)</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FOOTER TOMBOL (HANYA LAYAR) --}}
            <div class="no-print pt-4 mt-2 border-t border-dashed border-slate-200 flex items-center justify-between gap-3 text-xs">
                <p class="text-slate-500">
                    Data Kartu Kendali akan disimpan dan bisa dicetak ulang dari modul APAT.
                </p>
                <div class="flex gap-2">
                    <a href="{{ route('apat.index') }}"
                       class="px-3 py-2 rounded-lg border text-sm hover:bg-slate-50">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">
                        Simpan Kartu Kendali
                    </button>
                </div>
            </div>
        </form>
</x-kartu-layout>
