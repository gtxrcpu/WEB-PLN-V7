<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kartu Kendali APAR - {{ $apar->barcode }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .sheet-a4 { box-shadow: none !important; border: none !important; margin: 0 !important; }
        }
    </style>
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

        {{-- APPROVAL HISTORY TIMELINE --}}
        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Riwayat Approval
            </h3>
            
            <div class="space-y-4">
                {{-- Created By --}}
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold text-gray-900">Dibuat oleh</span>
                            @if($kartu->user)
                                <span class="px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-700 rounded">
                                    {{ get_user_role_display($kartu->user) }}
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-700 font-medium">
                            {{ get_user_display_name($kartu->user, 'User Deleted') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $kartu->created_at->format('d M Y, H:i') }} WIB
                        </p>
                    </div>
                </div>

                {{-- Approval Status --}}
                @if($kartu->isApproved())
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-gray-900">Di-approve oleh</span>
                                @if($kartu->approver)
                                    <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded">
                                        {{ get_user_role_display($kartu->approver) }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-700 font-medium">
                                {{ get_user_display_name($kartu->approver, 'User Deleted') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $kartu->approved_at->format('d M Y, H:i') }} WIB
                            </p>
                        </div>
                    </div>
                @else
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <span class="font-semibold text-gray-900">Status</span>
                            <p class="text-sm text-yellow-700 font-medium mt-1">
                                Menunggu approval dari Leader/Superadmin
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- TABEL PEMERIKSAAN --}}
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
                        @foreach([
                            'pressure_gauge' => 'Pressure Gauge',
                            'pin_segel' => 'Pin & Segel',
                            'selang' => 'Selang',
                            'tabung' => 'Tabung',
                            'label' => 'Label',
                            'kondisi_fisik' => 'Kondisi Fisik'
                        ] as $field => $label)
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $label }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($kartu->$field === 'baik') bg-green-100 text-green-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $kartu->$field)) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- INFO PEMERIKSAAN --}}
        <div class="grid grid-cols-2 gap-6 mb-6 p-4 bg-gray-50 rounded-lg">
            <div>
                <p class="text-sm text-gray-600">Kesimpulan</p>
                <p class="font-semibold text-lg
                    @if($kartu->kesimpulan === 'baik') text-green-600
                    @else text-red-600 @endif">
                    {{ strtoupper($kartu->kesimpulan) }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal Pemeriksaan</p>
                <p class="font-semibold">{{ \Carbon\Carbon::parse($kartu->tgl_periksa)->format('d M Y') }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-sm text-gray-600">Petugas Pemeriksa</p>
                <p class="font-semibold">{{ $kartu->petugas }}</p>
            </div>
        </div>

        {{-- TTD SECTION - USING TEMPLATE --}}
        <div class="mt-8 pt-6 border-t-2 border-gray-200">
            <div class="flex justify-end">
                <div class="text-center">
                    @php
                        $lokasi = 'Surabaya'; // default
                        if ($template && $template->footer_fields) {
                            $lokasiField = collect($template->footer_fields)->firstWhere('label', 'Lokasi');
                            if ($lokasiField && isset($lokasiField['value'])) {
                                $lokasi = $lokasiField['value'];
                            }
                        }
                    @endphp
                    <p class="text-sm text-gray-600 mb-1">{{ $lokasi }}, {{ \Carbon\Carbon::parse($kartu->tgl_periksa)->format('d-m-Y') }}</p>
                    <p class="text-sm font-semibold text-gray-900 mb-2">
                        @if($kartu->signature)
                            {{ $kartu->signature->position }}
                        @else
                            Team Leader K3L & KAM
                        @endif
                    </p>
                    
                    @if($kartu->signature && $kartu->signature->signature_path)
                        <div class="h-24 flex items-center justify-center mb-2">
                            <img src="{{ asset('storage/' . $kartu->signature->signature_path) }}" 
                                 alt="TTD" 
                                 class="max-h-20 w-auto">
                        </div>
                        <div class="border-t-2 border-gray-400 pt-2 w-56">
                            <p class="text-sm font-bold">{{ $kartu->signature->name }}</p>
                            @if($kartu->signature->nip)
                                <p class="text-xs text-gray-500 mt-1">NIP: {{ $kartu->signature->nip }}</p>
                            @endif
                        </div>
                    @else
                        <div class="h-24 flex items-center justify-center mb-2 bg-yellow-50 border-2 border-dashed border-yellow-300 rounded-lg px-6">
                            <div class="text-center">
                                <svg class="w-8 h-8 mx-auto mb-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm font-medium text-yellow-800">Menunggu Approval Admin</p>
                            </div>
                        </div>
                        <div class="border-t-2 border-gray-300 pt-2 w-56">
                            <p class="text-sm text-gray-400 italic">(Tanda Tangan & Nama)</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
