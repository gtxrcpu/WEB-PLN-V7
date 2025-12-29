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
                                {{ $template->table_header ?? 'KONDISI' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Kelompokkan inspection fields berdasarkan section
                            $inspectionFields = $template->inspection_fields ?? [];
                            $groupedFields = [];
                            foreach ($inspectionFields as $index => $field) {
                                $section = $field['section'] ?? 'A';
                                if (!isset($groupedFields[$section])) {
                                    $groupedFields[$section] = [];
                                }
                                $groupedFields[$section][] = [
                                    'index' => $index,
                                    'field' => $field
                                ];
                            }
                            ksort($groupedFields); // Sort by section A, B, C, etc.
                            
                            $globalIndex = 0;
                        @endphp

                        @foreach($groupedFields as $section => $fields)
                            {{-- SECTION HEADER --}}
                            @if(count($fields) > 0 && !empty($fields[0]['field']['section_title']))
                            <tr class="bg-gray-200">
                                <td colspan="3" class="px-3 py-2 font-bold text-gray-900 border-t border-gray-400">
                                    {{ $section }}. {{ strtoupper($fields[0]['field']['section_title']) }}
                                </td>
                            </tr>
                            @endif

                            {{-- SECTION ITEMS --}}
                            @foreach($fields as $item)
                            @php
                                $globalIndex++;
                                $field = $item['field'];
                                $fieldIndex = $item['index'];
                                $fieldName = 'inspection_' . $fieldIndex;
                            @endphp
                            <tr class="border-t border-gray-300 hover:bg-gray-50">
                                <td class="px-3 py-2 text-center border-r border-gray-300">{{ $globalIndex }}</td>
                                <td class="px-3 py-2 border-r border-gray-300">{{ $field['label'] }}</td>
                                <td class="px-3 py-2 text-center">
                                    @if($field['type'] === 'checkbox')
                                        <div class="flex items-center justify-center gap-4">
                                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                <input type="radio" name="{{ $fieldName }}" value="baik"
                                                       class="w-4 h-4 border-gray-300 text-green-600 focus:ring-green-500"
                                                       {{ old($fieldName) === 'baik' ? 'checked' : '' }}>
                                                <span>Baik</span>
                                            </label>
                                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                <input type="radio" name="{{ $fieldName }}" value="tidak_baik"
                                                       class="w-4 h-4 border-gray-300 text-red-600 focus:ring-red-500"
                                                       {{ old($fieldName) === 'tidak_baik' ? 'checked' : '' }}>
                                                <span>Tidak Baik</span>
                                            </label>
                                        </div>
                                    @elseif($field['type'] === 'text')
                                        <input type="text" name="{{ $fieldName }}" value="{{ old($fieldName) }}"
                                               class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-blue-500">
                                    @elseif($field['type'] === 'textarea')
                                        <textarea name="{{ $fieldName }}" rows="2"
                                                  class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-blue-500">{{ old($fieldName) }}</textarea>
                                    @endif
                                    @error($fieldName)
                                        <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                                    @enderror
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
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="kesimpulan" value="baik" required
                                   class="border-gray-300 text-green-600 focus:ring-green-500"
                                   {{ old('kesimpulan') === 'baik' ? 'checked' : '' }}>
                            <span>Baik</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="kesimpulan" value="tidak_baik" required
                                   class="border-gray-300 text-red-600 focus:ring-red-500"
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
                           required
                           value="{{ old('tgl_periksa', now()->toDateString()) }}"
                           class="border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-blue-500">
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
                           required
                           value="{{ old('petugas') }}"
                           placeholder="Nama Petugas"
                           class="border border-gray-300 rounded-md px-2 py-1 text-xs w-64 focus:ring-2 focus:ring-blue-500">
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
                           placeholder="Nama Pengawas (opsional)"
                           class="border border-gray-300 rounded-md px-2 py-1 text-xs w-64 focus:ring-2 focus:ring-blue-500">
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
                <span class="text-red-600">*</span> Wajib diisi. Data akan disimpan dan menunggu approval.
            </p>
            <div class="flex gap-2">
                <a href="{{ route('rumah-pompa.index') }}"
                   class="px-3 py-2 rounded-lg border text-sm hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700 shadow-md">
                    Simpan Kartu Kendali
                </button>
            </div>
        </div>
    </form>
</x-kartu-layout>
