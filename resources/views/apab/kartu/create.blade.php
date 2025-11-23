<x-kartu-layout 
    title="Kartu Kendali APAB" 
    :subtitle="$apab->barcode ?? $apab->serial_no"
    back-route="apab.index"
    module="apab"
    :template="$template">

    {{-- INFO APAB --}}
    <div class="mb-6 p-4 bg-slate-50 rounded-lg border border-slate-200">
        <h3 class="font-bold text-gray-900 mb-3">Informasi APAB</h3>
        <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
            <div>
                <span class="text-gray-600">Lokasi:</span>
                <span class="font-semibold ml-2">{{ $apab->location_code ?? 'WORKSHOP G4' }}</span>
            </div>
            <div>
                <span class="text-gray-600">Isi APAB:</span>
                <span class="font-semibold ml-2">{{ $apab->isi_apab ?? 'CO2' }}</span>
            </div>
            <div>
                <span class="text-gray-600">Kapasitas:</span>
                <span class="font-semibold ml-2">{{ $apab->capacity ?? '25 Kg' }}</span>
            </div>
            <div>
                <span class="text-gray-600">Masa Berlaku:</span>
                <span class="font-semibold ml-2">{{ $apab->masa_berlaku ? $apab->masa_berlaku->format('d F Y') : '11 September 2025' }}</span>
            </div>
        </div>
    </div>

        {{-- FORM --}}
        @if ($errors->any())
            <div class="no-print mt-4 mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <div class="font-semibold mb-1">Periksa kembali:</div>
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach ($errors->all() as $msg)
                        <li>{{ $msg }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('apab.kartu.store') }}" class="mt-4 space-y-4">
            @csrf
            <input type="hidden" name="apab_id" value="{{ $apab->id }}">

            {{-- TABEL PEMERIKSAAN --}}
            <div class="border border-slate-400 text-xs">
                <div class="grid grid-cols-12 bg-slate-100 border-b border-slate-400 font-semibold">
                    <div class="col-span-6 px-3 py-2 border-r border-slate-400">PEMERIKSAAN</div>
                    <div class="col-span-6 px-3 py-2 text-center">KONDISI</div>
                </div>

                @foreach ([
                    'pressure_gauge' => 'Pressure Gauge',
                    'pin_segel'      => 'Pin/Segel',
                    'selang'         => 'Selang',
                    'klem_selang'    => 'Klem Selang',
                    'handle'         => 'Handle',
                    'kondisi_fisik'  => 'Kondisi Fisik',
                ] as $field => $label)
                    <div class="grid grid-cols-12 border-t border-slate-300">
                        <div class="col-span-6 px-3 py-2 border-r border-slate-300">{{ $label }}</div>
                        <div class="col-span-6 px-3 py-1.5">
                            <div class="flex items-center gap-6">
                                <label class="inline-flex items-center gap-1.5">
                                    <input type="radio" name="{{ $field }}" value="baik"
                                           class="border-slate-300 text-red-600 focus:ring-red-500"
                                           {{ old($field) === 'baik' ? 'checked' : '' }}>
                                    <span>Baik</span>
                                </label>
                                <label class="inline-flex items-center gap-1.5">
                                    <input type="radio" name="{{ $field }}" value="tidak_baik"
                                           class="border-slate-300 text-red-600 focus:ring-red-500"
                                           {{ old($field) === 'tidak_baik' ? 'checked' : '' }}>
                                    <span>Tidak Baik</span>
                                </label>
                            </div>
                            @error($field)
                                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- KESIMPULAN --}}
            <div class="border border-slate-400 text-xs">
                <div class="grid grid-cols-12 border-b border-slate-300">
                    <div class="col-span-3 px-3 py-2 border-r border-slate-300 font-semibold">KESIMPULAN</div>
                    <div class="col-span-9 px-3 py-2">
                        <div class="flex items-center gap-6">
                            <label class="inline-flex items-center gap-1.5">
                                <input type="radio" name="kesimpulan" value="baik"
                                       class="border-slate-300 text-red-600 focus:ring-red-500"
                                       {{ old('kesimpulan') === 'baik' ? 'checked' : '' }}>
                                <span>Baik</span>
                            </label>
                            <label class="inline-flex items-center gap-1.5">
                                <input type="radio" name="kesimpulan" value="tidak_baik"
                                       class="border-slate-300 text-red-600 focus:ring-red-500"
                                       {{ old('kesimpulan') === 'tidak_baik' ? 'checked' : '' }}>
                                <span>Tidak Baik</span>
                            </label>
                        </div>
                        @error('kesimpulan')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-12 border-b border-slate-300">
                    <div class="col-span-3 px-3 py-2 border-r border-slate-300">Tanggal Pemeriksaan</div>
                    <div class="col-span-9 px-3 py-2">
                        <input type="date" name="tgl_periksa"
                               value="{{ old('tgl_periksa', now()->toDateString()) }}"
                               class="border border-slate-300 rounded-md px-2 py-1 text-xs">
                        @error('tgl_periksa')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-12">
                    <div class="col-span-3 px-3 py-2 border-r border-slate-300">Petugas</div>
                    <div class="col-span-9 px-3 py-2">
                        <input type="text" name="petugas"
                               value="{{ old('petugas') }}"
                               placeholder="Nama Petugas"
                               class="border border-slate-300 rounded-md px-2 py-1 text-xs w-64">
                        @error('petugas')
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

            {{-- FOOTER TOMBOL --}}
            <div class="no-print pt-4 mt-2 border-t border-dashed border-slate-200 flex items-center justify-between gap-3 text-xs">
                <p class="text-slate-500">Data Kartu Kendali akan disimpan dan bisa dicetak ulang dari modul APAB.</p>
                <div class="flex gap-2">
                    <a href="{{ route('apab.index') }}"
                       class="px-3 py-2 rounded-lg border text-sm hover:bg-slate-50">Batal</a>
                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-gradient-to-r from-red-600 to-orange-600 text-white text-sm font-medium hover:from-red-700 hover:to-orange-700">
                        Simpan Kartu Kendali
                    </button>
                </div>
            </div>
        </form>
</x-kartu-layout>
