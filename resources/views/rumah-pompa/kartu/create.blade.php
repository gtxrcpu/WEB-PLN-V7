<x-kartu-layout 
    title="Kartu Kendali Rumah Pompa" 
    :subtitle="$rumahPompa->barcode ?? $rumahPompa->serial_no"
    back-route="rumah-pompa.index"
    module="rumah-pompa"
    :template="$template">

    {{-- INFO RUMAH POMPA --}}
    <div class="mb-6 p-4 bg-slate-50 rounded-lg border border-slate-200">
        <h3 class="font-bold text-gray-900 mb-3">Informasi Rumah Pompa</h3>
        <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
            <div>
                <span class="text-gray-600">Kode/Barcode:</span>
                <span class="font-semibold ml-2">{{ $rumahPompa->barcode ?? $rumahPompa->serial_no }}</span>
            </div>
            <div>
                <span class="text-gray-600">Lokasi:</span>
                <span class="font-semibold ml-2">{{ $rumahPompa->location_code ?? '-' }}</span>
            </div>
        </div>
    </div>

    {{-- ERROR MESSAGES --}}
    @if($errors->any())
        <div class="no-print mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="font-semibold text-red-800 mb-2">Terdapat kesalahan:</p>
            <ul class="list-disc list-inside text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM --}}
    <form method="POST" action="{{ route('rumah-pompa.kartu.store') }}">
        @csrf
        <input type="hidden" name="rumah_pompa_id" value="{{ $rumahPompa->id }}">

        {{-- TABEL PEMERIKSAAN CHECKLIST - DINAMIS DARI TEMPLATE --}}
        <div class="mb-6">
            <div class="border border-gray-400 rounded-lg overflow-hidden text-xs">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-center font-bold text-gray-700 border-r border-gray-400 w-12">NO</th>
                            <th class="px-3 py-2 text-left font-bold text-gray-700 border-r border-gray-400">URAIAN PEKERJAAN</th>
                            <th class="px-3 py-2 text-center font-bold text-gray-700 w-48">
                                {{ $template->table_header ?? 'KONDISI OKTOBER MINGGU 2' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Kelompokkan inspection fields berdasarkan section
                            $inspectionFields = $template->inspection_fields ?? [];
                            $groupedFields = [];
                            foreach ($inspectionFields as $field) {
                                $section = $field['section'] ?? 'A';
                                if (!isset($groupedFields[$section])) {
                                    $groupedFields[$section] = [];
                                }
                                $groupedFields[$section][] = $field;
                            }
                            ksort($groupedFields); // Sort by section A, B, C, etc.
                            
                            $globalIndex = 0;
                        @endphp

                        @foreach($groupedFields as $section => $fields)
                            {{-- SECTION HEADER --}}
                            @if(count($fields) > 0 && !empty($fields[0]['section_title']))
                            <tr class="bg-gray-200">
                                <td colspan="3" class="px-3 py-2 font-bold text-gray-900 border-t border-gray-400">
                                    {{ $section }}. {{ strtoupper($fields[0]['section_title']) }}
                                </td>
                            </tr>
                            @endif

                            {{-- SECTION ITEMS --}}
                            @foreach($fields as $index => $field)
                            @php
                                $globalIndex++;
                                $fieldKey = 'field_' . $globalIndex;
                            @endphp
                            <tr class="border-t border-gray-300">
                                <td class="px-3 py-2 text-center border-r border-gray-300">{{ $globalIndex }}</td>
                                <td class="px-3 py-2 border-r border-gray-300">{{ $field['label'] }}</td>
                                <td class="px-3 py-2 text-center">
                                    @if($field['type'] === 'checkbox')
                                        <div class="flex items-center justify-center gap-4">
                                            <label class="inline-flex items-center gap-1.5">
                                                <input type="checkbox" name="{{ $fieldKey }}" value="baik"
                                                       class="w-4 h-4 border-gray-300 text-purple-600 focus:ring-purple-500 rounded"
                                                       {{ old($fieldKey) === 'baik' ? 'checked' : '' }}>
                                                <span>Baik</span>
                                            </label>
                                            <label class="inline-flex items-center gap-1.5">
                                                <input type="checkbox" name="{{ $fieldKey }}_tidak" value="tidak_baik"
                                                       class="w-4 h-4 border-gray-300 text-purple-600 focus:ring-purple-500 rounded"
                                                       {{ old($fieldKey.'_tidak') === 'tidak_baik' ? 'checked' : '' }}>
                                                <span>Tidak Baik</span>
                                            </label>
                                        </div>
                                    @elseif($field['type'] === 'text')
                                        <input type="text" name="{{ $fieldKey }}" value="{{ old($fieldKey) }}"
                                               class="w-full px-2 py-1 border border-gray-300 rounded text-xs">
                                    @elseif($field['type'] === 'textarea')
                                        <textarea name="{{ $fieldKey }}" rows="2"
                                                  class="w-full px-2 py-1 border border-gray-300 rounded text-xs">{{ old($fieldKey) }}</textarea>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @endforeach

                        @if(count($inspectionFields) === 0)
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                <p class="text-sm">Belum ada inspection fields yang dikonfigurasi.</p>
                                <p class="text-xs mt-1">Silakan hubungi admin untuk mengatur template.</p>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- KESIMPULAN & INFO TAMBAHAN --}}
        <div class="border border-gray-400 text-xs rounded-lg overflow-hidden">
            <div class="grid grid-cols-12 border-b border-gray-300">
                <div class="col-span-3 px-3 py-2 border-r border-gray-300 font-semibold bg-gray-100">
                    KESIMPULAN
                </div>
                <div class="col-span-9 px-3 py-2">
                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center gap-1.5">
                            <input type="radio" name="kesimpulan" value="baik"
                                   class="border-gray-300 text-purple-600 focus:ring-purple-500"
                                   {{ old('kesimpulan') === 'baik' ? 'checked' : '' }}>
                            <span>Baik</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5">
                            <input type="radio" name="kesimpulan" value="tidak_baik"
                                   class="border-gray-300 text-purple-600 focus:ring-purple-500"
                                   {{ old('kesimpulan') === 'tidak_baik' ? 'checked' : '' }}>
                            <span>Tidak Baik</span>
                        </label>
                    </div>
                    @error('kesimpulan')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-12 border-b border-gray-300">
                <div class="col-span-3 px-3 py-2 border-r border-gray-300 bg-gray-50">
                    Tanggal Pemeriksaan
                </div>
                <div class="col-span-9 px-3 py-2">
                    <input type="date"
                           name="tgl_periksa"
                           value="{{ old('tgl_periksa', now()->toDateString()) }}"
                           class="border border-gray-300 rounded-md px-2 py-1 text-xs">
                    @error('tgl_periksa')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-12 border-b border-gray-300">
                <div class="col-span-3 px-3 py-2 border-r border-gray-300 bg-gray-50">
                    Petugas
                </div>
                <div class="col-span-9 px-3 py-2">
                    <input type="text"
                           name="petugas"
                           value="{{ old('petugas') }}"
                           placeholder="Nama Petugas"
                           class="border border-gray-300 rounded-md px-2 py-1 text-xs w-64">
                    @error('petugas')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-12">
                <div class="col-span-3 px-3 py-2 border-r border-gray-300 bg-gray-50">
                    Pengawas
                </div>
                <div class="col-span-9 px-3 py-2">
                    <input type="text"
                           name="pengawas"
                           value="{{ old('pengawas') }}"
                           placeholder="Nama Pengawas"
                           class="border border-gray-300 rounded-md px-2 py-1 text-xs w-64">
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
                Data Kartu Kendali akan disimpan dan bisa dicetak ulang dari modul Rumah Pompa.
            </p>
            <div class="flex gap-2">
                <a href="{{ route('rumah-pompa.index') }}"
                   class="px-3 py-2 rounded-lg border text-sm hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-medium hover:from-purple-700 hover:to-indigo-700">
                    Simpan Kartu Kendali
                </button>
            </div>
        </div>
    </form>
</x-kartu-layout>
