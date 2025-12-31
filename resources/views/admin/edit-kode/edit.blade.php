<x-layouts.app :title="'Edit Kode ' . $moduleInfo['name']">
    {{-- Main Container with Soft Background --}}
    <div class="min-h-screen bg-slate-50/50 pb-20">
        
        {{-- Top Navigation & Header --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{-- Breadcrumb / Back --}}
            <nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
                <a href="{{ route('admin.edit-kode.index') }}" class="hover:text-blue-600 transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-800 font-medium">Edit Format</span>
            </nav>

            {{-- Title Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center p-3">
                        <img src="{{ asset($moduleInfo['icon']) }}" alt="{{ $moduleInfo['name'] }}" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                            Edit Kode {{ $moduleInfo['name'] }}
                        </h1>
                        <p class="text-slate-500 text-base">{{ $moduleInfo['full_name'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Alert Messages --}}
            @if(session('success'))
            <div class="mb-8 bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 animate-fade-in shadow-sm">
                <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            @endif

            <div class="grid lg:grid-cols-12 gap-8 items-start">
                
                {{-- Left Column: Main Form (8 Columns) --}}
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        {{-- Card Header --}}
                        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-lg font-semibold text-slate-800">Konfigurasi Format</h3>
                            <p class="text-sm text-slate-500 mt-1">Sesuaikan format penomoran dokumen otomatis.</p>
                        </div>

                        <form action="{{ route('admin.edit-kode.update', $module) }}" method="POST" class="p-8">
                            @csrf
                            @method('PUT')

                            {{-- Format Input Section --}}
                            <div class="space-y-8">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                                        Pola Format Kode
                                    </label>
                                    <div class="relative">
                                        <input 
                                            type="text" 
                                            name="kode_format" 
                                            value="{{ $settings['kode_format']->value }}"
                                            class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-mono text-lg text-slate-800 placeholder-slate-400"
                                            placeholder="Contoh: {UNIT}/{YYYY}/{NNNN}"
                                            required
                                        >
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('kode_format')
                                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Variables Helper --}}
                                <div class="bg-blue-50/50 rounded-xl border border-blue-100 p-5">
                                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-4 flex items-center gap-2">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                        Variabel Tersedia
                                    </p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                        {{-- Helper Item --}}
                                        <div class="flex items-center gap-3 p-2 bg-white rounded-lg border border-blue-100 shadow-sm">
                                            <code class="text-xs font-bold text-blue-700 bg-blue-50 px-2 py-1 rounded border border-blue-200">{UNIT}</code>
                                            <span class="text-xs text-slate-600">Kode Unit</span>
                                        </div>
                                        <div class="flex items-center gap-3 p-2 bg-white rounded-lg border border-blue-100 shadow-sm">
                                            <code class="text-xs font-bold text-blue-700 bg-blue-50 px-2 py-1 rounded border border-blue-200">{YYYY}</code>
                                            <span class="text-xs text-slate-600">Tahun (2025)</span>
                                        </div>
                                        <div class="flex items-center gap-3 p-2 bg-white rounded-lg border border-blue-100 shadow-sm">
                                            <code class="text-xs font-bold text-blue-700 bg-blue-50 px-2 py-1 rounded border border-blue-200">{YY}</code>
                                            <span class="text-xs text-slate-600">Tahun (25)</span>
                                        </div>
                                        <div class="flex items-center gap-3 p-2 bg-white rounded-lg border border-blue-100 shadow-sm">
                                            <code class="text-xs font-bold text-blue-700 bg-blue-50 px-2 py-1 rounded border border-blue-200">{MM}</code>
                                            <span class="text-xs text-slate-600">Bulan (12)</span>
                                        </div>
                                        <div class="flex items-center gap-3 p-2 bg-white rounded-lg border border-blue-100 shadow-sm">
                                            <code class="text-xs font-bold text-blue-700 bg-blue-50 px-2 py-1 rounded border border-blue-200">{NNN}</code>
                                            <span class="text-xs text-slate-600">Urut (001) - Otomatis</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-slate-100 pt-8"></div>

                                {{-- Counter Section --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                                        Counter Nomor Urut Saat Ini
                                    </label>
                                    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                                        <div class="relative w-full sm:w-48">
                                            <input 
                                                type="number" 
                                                name="kode_counter" 
                                                value="{{ $settings['kode_counter']->value }}"
                                                min="1"
                                                class="w-full pl-5 pr-12 py-3.5 bg-white border border-slate-300 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 font-mono text-lg font-bold text-slate-800"
                                                required
                                            >
                                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs font-bold">
                                                #NEXT
                                            </div>
                                        </div>
                                        
                                        <button 
                                            type="button"
                                            onclick="if(confirm('Yakin ingin mereset counter kembali ke 1?')) document.getElementById('resetForm').submit()"
                                            class="group flex items-center gap-2 px-5 py-3.5 bg-slate-100 hover:bg-red-50 text-slate-600 hover:text-red-600 rounded-xl transition-all border border-slate-200 hover:border-red-200 font-medium text-sm">
                                            <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Reset ke 1
                                        </button>
                                    </div>
                                    <p class="text-slate-500 text-xs mt-2">
                                        *Nomor ini akan digunakan untuk dokumen selanjutnya.
                                    </p>
                                    @error('kode_counter')
                                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Actions Footer --}}
                            <div class="mt-10 flex flex-col-reverse sm:flex-row gap-3 pt-6 border-t border-slate-100">
                                <a href="{{ route('admin.edit-kode.index') }}"
                                   class="px-6 py-3 bg-white text-slate-700 hover:bg-slate-50 border border-slate-300 rounded-xl font-medium transition-colors text-center">
                                    Batal
                                </a>
                                <button type="submit"
                                        class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-lg shadow-blue-600/20 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Right Column: Preview (4 Columns) --}}
                <div class="lg:col-span-4 space-y-6">
                    {{-- Sticky wrapper --}}
                    <div class="sticky top-6">
                        <div class="bg-slate-900 rounded-2xl shadow-xl overflow-hidden ring-1 ring-white/10">
                            {{-- Terminal Header --}}
                            <div class="bg-slate-800/50 px-5 py-3 border-b border-slate-700/50 flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Live Preview</span>
                                <div class="flex gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded-full bg-red-500/80"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-yellow-500/80"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-green-500/80"></div>
                                </div>
                            </div>

                            {{-- Terminal Body --}}
                            <div class="p-6">
                                <div class="mb-2 text-xs text-blue-400 font-mono">Output:</div>
                                <div class="font-mono text-2xl font-bold text-emerald-400 break-all leading-relaxed" id="preview">
                                    Loading...
                                </div>
                                <div class="mt-4 pt-4 border-t border-slate-800 flex items-center gap-2 text-slate-500 text-xs font-mono">
                                    <span class="animate-pulse">_</span>
                                    <span>Menunggu input...</span>
                                </div>
                            </div>
                        </div>

                        {{-- Tips Card --}}
                        <div class="mt-6 bg-white rounded-xl p-5 shadow-sm border border-slate-200">
                            <h4 class="text-sm font-bold text-slate-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Informasi
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex gap-3 text-sm text-slate-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 mt-2 shrink-0"></span>
                                    Preview di atas akan berubah secara realtime saat Anda mengetik format.
                                </li>
                                <li class="flex gap-3 text-sm text-slate-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 mt-2 shrink-0"></span>
                                    Counter akan bertambah otomatis (increment) setiap kali dokumen baru dibuat.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reset Form (hidden) --}}
    <form id="resetForm" action="{{ route('admin.edit-kode.reset-counter', $module) }}" method="POST" class="hidden">
        @csrf
    </form>

    {{-- Script (Tidak berubah logic, hanya selector ID) --}}
    <script>
        function updatePreview() {
            const format = document.querySelector('input[name="kode_format"]').value;
            const counter = document.querySelector('input[name="kode_counter"]').value;
            
            const now = new Date();
            const yyyy = now.getFullYear();
            const yy = String(yyyy).slice(-2);
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            const nnnn = String(counter).padStart(4, '0');
            const nnn = String(counter).padStart(3, '0');
            
            let preview = format
                .replace('{UNIT}', 'UPW2')
                .replace('{YYYY}', yyyy)
                .replace('{YY}', yy)
                .replace('{MM}', mm)
                .replace('{NNNN}', nnnn)
                .replace('{NNN}', nnn);
            
            const previewEl = document.getElementById('preview');
            // Sedikit efek typing
            previewEl.textContent = preview;
        }
        
        document.querySelector('input[name="kode_format"]').addEventListener('input', updatePreview);
        document.querySelector('input[name="kode_counter"]').addEventListener('input', updatePreview);
        
        updatePreview();
    </script>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
    </style>
</x-layouts.app>