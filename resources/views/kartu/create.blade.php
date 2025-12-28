<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kartu Kendali APAR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .sheet-a4 {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
            }
        }
    </style>
    <script>
        // Auto-refresh untuk mendapatkan template terbaru
        @if($template)
        let initialTemplateVersion = {{ $template->updated_at->timestamp }};
        @else
        let initialTemplateVersion = 0;
        @endif
        let checkInterval = 5000; // Check setiap 5 detik
        let isReloading = false;
        
        function checkTemplateUpdate() {
            if (isReloading) return;
            
            fetch('/api/template-version/apar', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.version && data.version > initialTemplateVersion) {
                    // Template telah diupdate, reload halaman
                    isReloading = true;
                    showUpdateNotification();
                }
            })
            .catch(err => console.log('Template check:', err));
        }
        
        function showUpdateNotification() {
            const notification = document.createElement('div');
            notification.id = 'template-update-notification';
            notification.className = 'no-print fixed top-4 right-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-4 rounded-xl shadow-2xl z-50 border-2 border-white';
            notification.style.animation = 'slideInRight 0.5s ease-out';
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="animate-spin">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-lg">Template Diperbarui!</p>
                        <p class="text-sm opacity-90">Memuat template terbaru...</p>
                    </div>
                </div>
            `;
            
            // Add animation keyframes
            if (!document.getElementById('notification-styles')) {
                const style = document.createElement('style');
                style.id = 'notification-styles';
                style.textContent = `
                    @keyframes slideInRight {
                        from {
                            transform: translateX(400px);
                            opacity: 0;
                        }
                        to {
                            transform: translateX(0);
                            opacity: 1;
                        }
                    }
                `;
                document.head.appendChild(style);
            }
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        }
        
        // Start checking setelah halaman load
        window.addEventListener('load', function() {
            console.log('🔄 Auto-refresh template aktif (check setiap 5 detik)');
            setInterval(checkTemplateUpdate, checkInterval);
        });
    </script>
</head>
<body class="bg-slate-50">

{{-- HEADER (NO PRINT) --}}
<div class="no-print bg-white border-b shadow-sm sticky top-0 z-10">
    <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold text-gray-900">Kartu Kendali APAR</h1>
            <p class="text-sm text-gray-600">{{ $apar->barcode ?? $apar->serial_no }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('apar.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50 text-sm font-medium">
                ← Kembali
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                🖨️ Cetak
            </button>
        </div>
    </div>
</div>

{{-- CONTENT --}}
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="sheet-a4 bg-white rounded-xl shadow-lg border p-8">
        
        {{-- HEADER KARTU - FROM TEMPLATE --}}
        @if($template)
        {{-- Company Header with Logos --}}
        <div class="border-2 border-gray-800 mb-6">
            <div class="flex items-center justify-between p-4 border-b-2 border-gray-800">
                {{-- Logo PLN Kiri --}}
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logoo.png') }}" alt="PLN Logo" class="h-16 w-auto object-contain">
                    <div class="text-left">
                        @if($template->company_name)
                            <div class="font-bold text-sm">{{ $template->company_name }}</div>
                        @endif
                        @if($template->company_address)
                            <div class="text-xs">{{ $template->company_address }}</div>
                        @endif
                        @if($template->company_phone)
                            <div class="text-xs">{{ $template->company_phone }}</div>
                        @endif
                        @if($template->company_fax)
                            <div class="text-xs">{{ $template->company_fax }}</div>
                        @endif
                        @if($template->company_email)
                            <div class="text-xs">{{ $template->company_email }}</div>
                        @endif
                    </div>
                </div>
                
                {{-- 5 Logo Sertifikasi Kanan --}}
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/logo1.png') }}" alt="Cert 1" class="h-12 w-auto object-contain">
                    <img src="{{ asset('images/logo2.png') }}" alt="Cert 2" class="h-12 w-auto object-contain">
                    <img src="{{ asset('images/logo3.jpg') }}" alt="Cert 3" class="h-12 w-auto object-contain">
                    <img src="{{ asset('images/logo4.png') }}" alt="Cert 4" class="h-12 w-auto object-contain">
                    <img src="{{ asset('images/logo5.png') }}" alt="Cert 5" class="h-12 w-auto object-contain">
                </div>
            </div>
            
            {{-- Title & Document Info --}}
            <table class="w-full text-sm">
                <tr>
                    <td rowspan="{{ count($template->header_fields) }}" class="border-r-2 border-gray-800 p-4 text-center align-middle w-2/3">
                        <div class="font-bold text-2xl">{{ $template->title }}</div>
                        <div class="font-semibold text-lg mt-2">{{ $template->subtitle }}</div>
                        <div class="font-semibold text-base">TAHUN {{ date('Y') }}</div>
                    </td>
                    @php
                        $firstField = $template->header_fields[0] ?? null;
                    @endphp
                    @if($firstField)
                        <td class="border-r border-b border-gray-800 p-2 font-semibold bg-gray-100 w-1/6">{{ $firstField['label'] }}</td>
                        <td class="border-b border-gray-800 p-2">{{ $firstField['value'] }}</td>
                    @endif
                </tr>
                @foreach($template->header_fields as $index => $field)
                    @if($index > 0)
                        <tr>
                            <td class="border-r @if($index < count($template->header_fields) - 1) border-b @endif border-gray-800 p-2 font-semibold bg-gray-100">{{ $field['label'] }}</td>
                            <td class="@if($index < count($template->header_fields) - 1) border-b @endif border-gray-800 p-2">{{ $field['value'] }}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>
        @else
        {{-- FALLBACK HEADER --}}
        <div class="flex items-start justify-between mb-6 pb-4 border-b-2 border-gray-200">
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">KARTU KENDALI APAR</h2>
                <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
                    <div>
                        <span class="text-gray-600">Kode/Barcode:</span>
                        <span class="font-semibold ml-2">{{ $apar->barcode ?? $apar->serial_no }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Lokasi:</span>
                        <span class="font-semibold ml-2">{{ $apar->location_code ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- INFO APAR --}}
        <div class="mb-6 p-4 bg-slate-50 rounded-lg border border-slate-200">
            <h3 class="font-bold text-gray-900 mb-3">Informasi APAR</h3>
            <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
                <div>
                    <span class="text-gray-600">Kode/Barcode:</span>
                    <span class="font-semibold ml-2">{{ $apar->barcode ?? $apar->serial_no }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Lokasi:</span>
                    <span class="font-semibold ml-2">{{ $apar->location_code ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Jenis:</span>
                    <span class="font-semibold ml-2">{{ $apar->type ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Kapasitas:</span>
                    <span class="font-semibold ml-2">{{ $apar->capacity ?? '-' }}</span>
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

        {{-- DEBUG INFO (ONLY IN DEVELOPMENT) --}}
        @if(config('app.debug'))
            <div class="no-print mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="font-semibold text-blue-800 mb-2">Debug Info:</p>
                <ul class="text-sm text-blue-700">
                    <li>Template: {{ $template ? 'Ada' : 'Tidak ada' }}</li>
                    @if($template && $template->inspection_fields)
                        <li>Jumlah Inspection Fields: {{ count($template->inspection_fields) }}</li>
                        <li>Fields: 
                            @foreach($template->inspection_fields as $index => $field)
                                inspection_{{ $index }} ({{ $field['label'] }}){{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </li>
                    @endif
                </ul>
            </div>
        @endif

        {{-- FORM --}}
        <form method="POST" action="{{ route('kartu.store') }}">
            @csrf
            <input type="hidden" name="apar_id" value="{{ $apar->id }}">

            {{-- TABEL PEMERIKSAAN - FROM TEMPLATE --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3">Hasil Pemeriksaan</h3>
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 w-1/3">Komponen</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Kondisi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @if($template && $template->inspection_fields)
                                @foreach($template->inspection_fields as $index => $field)
                                    @php
                                        $fieldName = 'inspection_' . $index;
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $field['label'] }}</td>
                                        <td class="px-4 py-3">
                                            @if($field['type'] === 'checkbox')
                                                <div class="flex gap-4">
                                                    <label class="inline-flex items-center cursor-pointer">
                                                        <input type="radio" name="{{ $fieldName }}" value="baik" 
                                                            {{ old($fieldName) === 'baik' ? 'checked' : '' }}
                                                            class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                                                        <span class="ml-2 text-gray-700">Baik</span>
                                                    </label>
                                                    <label class="inline-flex items-center cursor-pointer">
                                                        <input type="radio" name="{{ $fieldName }}" value="tidak_baik"
                                                            {{ old($fieldName) === 'tidak_baik' ? 'checked' : '' }}
                                                            class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                                                        <span class="ml-2 text-gray-700">Tidak Baik</span>
                                                    </label>
                                                </div>
                                            @elseif($field['type'] === 'text')
                                                <input type="text" name="{{ $fieldName }}" value="{{ old($fieldName) }}"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                            @elseif($field['type'] === 'textarea')
                                                <textarea name="{{ $fieldName }}" rows="2"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old($fieldName) }}</textarea>
                                            @endif
                                            @error($fieldName)
                                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                            @enderror
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                {{-- FALLBACK jika template tidak ada --}}
                                @foreach([
                                    'pressure_gauge' => 'Pressure Gauge',
                                    'pin_segel' => 'Pin & Segel',
                                    'selang' => 'Selang',
                                    'tabung' => 'Tabung',
                                    'label' => 'Label',
                                    'kondisi_fisik' => 'Kondisi Fisik'
                                ] as $field => $label)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $label }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex gap-4">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="radio" name="{{ $field }}" value="baik" 
                                                        {{ old($field) === 'baik' ? 'checked' : '' }}
                                                        class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                                                    <span class="ml-2 text-gray-700">Baik</span>
                                                </label>
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="radio" name="{{ $field }}" value="tidak_baik"
                                                        {{ old($field) === 'tidak_baik' ? 'checked' : '' }}
                                                        class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                                                    <span class="ml-2 text-gray-700">Tidak Baik</span>
                                                </label>
                                            </div>
                                            @error($field)
                                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                            @enderror
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- KESIMPULAN & INFO --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kesimpulan *</label>
                    <select name="kesimpulan" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih Kesimpulan --</option>
                        <option value="baik" {{ old('kesimpulan') === 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="tidak baik" {{ old('kesimpulan') === 'tidak baik' ? 'selected' : '' }}>Tidak Baik</option>
                    </select>
                    @error('kesimpulan')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pemeriksaan *</label>
                    <input type="date" name="tgl_periksa" required
                        value="{{ old('tgl_periksa', now()->toDateString()) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('tgl_periksa')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Petugas Pemeriksa *</label>
                    <input type="text" name="petugas" required
                        value="{{ old('petugas') }}"
                        placeholder="Nama petugas"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('petugas')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pengawas</label>
                    <input type="text" name="pengawas"
                        value="{{ old('pengawas') }}"
                        placeholder="Nama pengawas (opsional)"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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

            {{-- BUTTONS --}}
            <div class="no-print mt-8 pt-6 border-t border-gray-200 flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    <span class="text-red-600">*</span> Wajib diisi
                </p>
                <div class="flex gap-3">
                    <a href="{{ route('apar.index') }}" 
                        class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">
                        Batal
                    </a>
                    <button type="submit" 
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold shadow-md">
                        Simpan Kartu Kendali
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

</body>
</html>
