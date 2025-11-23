<x-kartu-layout 
    :title="'Kartu ' . ucfirst($jenis) . ' P3K'" 
    :subtitle="$lokasi"
    backRoute="p3k.pilih-lokasi"
    module="p3k"
    :template="$template">

    {{-- INFO LOKASI & JENIS --}}
    <div class="mb-6 p-4 bg-slate-50 rounded-lg border border-slate-200">
        <h3 class="font-bold text-gray-900 mb-3">Informasi</h3>
        <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
            <div>
                <span class="text-gray-600">Jenis Kartu:</span>
                <span class="font-semibold ml-2">{{ ucfirst($jenis) }} P3K</span>
            </div>
            <div>
                <span class="text-gray-600">Lokasi:</span>
                <span class="font-semibold ml-2">{{ $lokasi }}</span>
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
    <form method="POST" action="{{ route('p3k.kartu.store') }}">
        @csrf
        <input type="hidden" name="jenis" value="{{ $jenis }}">
        <input type="hidden" name="lokasi" value="{{ $lokasi }}">

        @if($jenis === 'stock')
            {{-- TABEL STOCK P3K (Seperti APAR) --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Pemeriksaan Stock P3K</h3>
                <div class="border border-gray-400 rounded-lg overflow-hidden text-xs">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-3 py-2 text-center font-bold text-gray-700 border-r border-gray-400 w-12">NO</th>
                                <th class="px-3 py-2 text-left font-bold text-gray-700 border-r border-gray-400">ITEM P3K</th>
                                <th class="px-3 py-2 text-center font-bold text-gray-700 w-48">KONDISI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $items = [
                                    'Kotak P3K',
                                    'Plester',
                                    'Perban',
                                    'Kasa Steril',
                                    'Antiseptik',
                                    'Gunting',
                                    'Sarung Tangan',
                                    'Masker',
                                    'Alkohol 70%',
                                    'Betadine',
                                ];
                            @endphp
                            @foreach($items as $index => $item)
                            <tr class="border-t border-gray-300">
                                <td class="px-3 py-2 text-center border-r border-gray-300">{{ $index + 1 }}</td>
                                <td class="px-3 py-2 border-r border-gray-300">{{ $item }}</td>
                                <td class="px-3 py-2 text-center">
                                    <div class="flex items-center justify-center gap-4">
                                        <label class="inline-flex items-center gap-1.5">
                                            <input type="radio" name="item_{{ $index }}" value="baik"
                                                   class="border-gray-300 text-emerald-600 focus:ring-emerald-500"
                                                   {{ old('item_'.$index) === 'baik' ? 'checked' : '' }}>
                                            <span>Baik</span>
                                        </label>
                                        <label class="inline-flex items-center gap-1.5">
                                            <input type="radio" name="item_{{ $index }}" value="tidak_baik"
                                                   class="border-gray-300 text-emerald-600 focus:ring-emerald-500"
                                                   {{ old('item_'.$index) === 'tidak_baik' ? 'checked' : '' }}>
                                            <span>Tidak Baik</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @elseif($jenis === 'pemeriksaan')
            {{-- FORM PEMERIKSAAN --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Checklist Pemeriksaan</h3>
                <div class="space-y-3">
                    @php
                        $checkItems = [
                            'Kotak P3K dalam kondisi baik dan bersih',
                            'Semua item tersedia lengkap',
                            'Tidak ada item yang kadaluarsa',
                            'Obat-obatan tersimpan dengan baik',
                            'Label dan instruksi terbaca jelas',
                        ];
                    @endphp
                    @foreach($checkItems as $index => $checkItem)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <input type="checkbox" name="check_{{ $index }}" value="1"
                               class="w-5 h-5 border-gray-300 text-emerald-600 focus:ring-emerald-500 rounded"
                               {{ old('check_'.$index) ? 'checked' : '' }}>
                        <label class="text-sm text-gray-700">{{ $checkItem }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

        @else
            {{-- FORM PEMAKAIAN --}}
            <div class="mb-6 space-y-4">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Detail Pemakaian</h3>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Item yang Digunakan</label>
                    <input type="text" name="item_digunakan" value="{{ old('item_digunakan') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm"
                           placeholder="Contoh: Plester, Perban">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm"
                           placeholder="Jumlah item yang digunakan">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keperluan</label>
                    <textarea name="keperluan" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm"
                              placeholder="Jelaskan keperluan penggunaan">{{ old('keperluan') }}</textarea>
                </div>
            </div>
        @endif

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
                                   class="border-gray-300 text-emerald-600 focus:ring-emerald-500"
                                   {{ old('kesimpulan') === 'baik' ? 'checked' : '' }}>
                            <span>Baik</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5">
                            <input type="radio" name="kesimpulan" value="tidak_baik"
                                   class="border-gray-300 text-emerald-600 focus:ring-emerald-500"
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
                    Tanggal {{ ucfirst($jenis) }}
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
                    Catatan
                </div>
                <div class="col-span-9 px-3 py-2">
                    <textarea name="catatan" rows="2"
                              placeholder="Catatan tambahan (opsional)"
                              class="border border-gray-300 rounded-md px-2 py-1 text-xs w-full">{{ old('catatan') }}</textarea>
                </div>
            </div>
        </div>

        {{-- TTD SECTION - USING TEMPLATE --}}
        <div class="mt-8 pt-6 border-t-2 border-gray-200">
            <div class="flex justify-end">
                <div class="text-center">
                    @php
                        $lokasi_ttd = 'Surabaya'; // default
                        $labelPimpinan = 'Team Leader K3L & KAM'; // default
                        if ($template && $template->footer_fields) {
                            $lokasiField = collect($template->footer_fields)->firstWhere('label', 'Lokasi');
                            if ($lokasiField && isset($lokasiField['value'])) {
                                $lokasi_ttd = $lokasiField['value'];
                            }
                            $pimpinanField = collect($template->footer_fields)->firstWhere('label', 'Label Pimpinan');
                            if ($pimpinanField && isset($pimpinanField['value'])) {
                                $labelPimpinan = $pimpinanField['value'];
                            }
                        }
                    @endphp
                    <p class="text-sm text-gray-600 mb-1">{{ $lokasi_ttd }}, {{ now()->format('d-m-Y') }}</p>
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
                Data akan disimpan dan bisa dicetak ulang dari modul P3K.
            </p>
            <div class="flex gap-2">
                <a href="{{ route('p3k.pilih-lokasi', ['jenis' => $jenis]) }}"
                   class="px-3 py-2 rounded-lg border text-sm hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-gradient-to-r from-emerald-600 to-green-600 text-white text-sm font-medium hover:from-emerald-700 hover:to-green-700">
                    Simpan Kartu
                </button>
            </div>
        </div>
    </form>
</x-kartu-layout>
