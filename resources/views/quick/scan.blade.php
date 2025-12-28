<x-layouts.app :title="'Scan QR Code'">
  <div class="max-w-4xl mx-auto px-4 py-6 space-y-6">

    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border-2 border-slate-200 text-slate-700 text-sm font-medium hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

    {{-- Header --}}
    <div class="text-center mb-8">
        <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-xl">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1z"/>
            </svg>
        </div>
        <h1 class="text-3xl font-bold tracking-tight bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent mb-2">
            Scan QR Code
        </h1>
        <p class="text-sm text-slate-600">
            Scan atau input kode QR untuk mencari peralatan
        </p>
    </div>

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <p class="text-sm text-rose-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Scan Area --}}
    <div class="bg-white rounded-2xl shadow-xl ring-1 ring-slate-200 p-8">
        <div class="space-y-6">
            {{-- Camera Scanner --}}
            <div class="relative aspect-square max-w-md mx-auto rounded-2xl overflow-hidden bg-black">
                <div id="qr-video" class="w-full h-full"></div>
                
                {{-- Status Overlay --}}
                <div id="scan-status" class="hidden absolute inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
                    <div class="text-center text-white p-6">
                        <div class="w-16 h-16 mx-auto mb-4 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
                        <p class="font-semibold">Memproses QR Code...</p>
                    </div>
                </div>
            </div>

            {{-- Camera Controls --}}
            <div class="flex justify-center gap-3">
                <button id="start-camera" onclick="startCamera()" 
                        class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-semibold hover:from-blue-700 hover:to-cyan-700 shadow-lg transition-all">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Mulai Scan
                </button>
                <button id="stop-camera" onclick="stopCamera()" disabled
                        class="px-6 py-2.5 rounded-xl bg-slate-300 text-slate-600 text-sm font-semibold cursor-not-allowed">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                    </svg>
                    Stop
                </button>
            </div>

            {{-- Divider --}}
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-slate-500 font-medium">atau input manual</span>
                </div>
            </div>

            {{-- Manual Input Form --}}
            <form action="{{ route('quick.scan.search') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Kode QR / Serial Number</label>
                    <input type="text" name="qr" required autofocus
                           class="w-full px-4 py-3 border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg font-mono"
                           placeholder="Masukkan kode QR atau serial number">
                </div>

                <button type="submit" 
                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-semibold hover:from-blue-700 hover:to-cyan-700 shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Cari Peralatan</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-4 border border-blue-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-blue-700 font-medium">Cepat</p>
                    <p class="text-sm font-bold text-blue-900">Akses Instan</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl p-4 border border-emerald-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-500 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-emerald-700 font-medium">Akurat</p>
                    <p class="text-sm font-bold text-emerald-900">Data Tepat</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl p-4 border border-purple-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-500 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-purple-700 font-medium">Mudah</p>
                    <p class="text-sm font-bold text-purple-900">User Friendly</p>
                </div>
            </div>
        </div>
    </div>

  </div>

  {{-- QR Scanner Library - Optimized --}}
  <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
  
  <script>
    let html5QrCode = null;
    let isScanning = false;
    let isProcessing = false;

    function submitQRCode(qrText) {
        // Show processing status
        const statusOverlay = document.getElementById('scan-status');
        if (statusOverlay) {
            statusOverlay.classList.remove('hidden');
        }
        
        // Redirect directly to search with GET parameter
        window.location.href = '{{ route("quick.scan.search") }}?qr=' + encodeURIComponent(qrText);
    }

    function startCamera() {
        const startBtn = document.getElementById('start-camera');
        const stopBtn = document.getElementById('stop-camera');
        
        if (isScanning || isProcessing) return;

        // Initialize scanner
        html5QrCode = new Html5Qrcode("qr-video");
        
        // Configuration for faster scanning
        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };

        html5QrCode.start(
            { facingMode: "environment" },
            config,
            (decodedText, decodedResult) => {
                // Prevent multiple submissions
                if (isProcessing) return;
                isProcessing = true;
                
                console.log(`QR Code detected: ${decodedText}`);
                
                // Try to stop camera gracefully, but don't wait
                if (html5QrCode && isScanning) {
                    try {
                        html5QrCode.stop().catch(e => console.log('Stop error (ignored):', e));
                    } catch (e) {
                        console.log('Stop exception (ignored):', e);
                    }
                    isScanning = false;
                }
                
                // Submit immediately
                submitQRCode(decodedText);
            },
            (errorMessage) => {
                // Scanning error (ignore, happens frequently)
            }
        )
        .then(() => {
            isScanning = true;
            
            // Update buttons
            if (startBtn) {
                startBtn.disabled = true;
                startBtn.classList.add('bg-slate-300', 'text-slate-600', 'cursor-not-allowed');
                startBtn.classList.remove('bg-gradient-to-r', 'from-blue-600', 'to-cyan-600', 'hover:from-blue-700', 'hover:to-cyan-700');
            }
            
            if (stopBtn) {
                stopBtn.disabled = false;
                stopBtn.classList.remove('bg-slate-300', 'text-slate-600', 'cursor-not-allowed');
                stopBtn.classList.add('bg-gradient-to-r', 'from-red-600', 'to-rose-600', 'hover:from-red-700', 'hover:to-rose-700', 'text-white');
            }
        })
        .catch((err) => {
            console.error('Error starting camera:', err);
            alert('Tidak dapat mengakses kamera. Pastikan Anda memberikan izin akses kamera.');
            isScanning = false;
            isProcessing = false;
        });
    }

    function stopCamera() {
        if (!html5QrCode || !isScanning) return;

        const startBtn = document.getElementById('start-camera');
        const stopBtn = document.getElementById('stop-camera');

        html5QrCode.stop()
            .then(() => {
                isScanning = false;
                html5QrCode = null;
                
                // Update buttons
                if (startBtn) {
                    startBtn.disabled = false;
                    startBtn.classList.remove('bg-slate-300', 'text-slate-600', 'cursor-not-allowed');
                    startBtn.classList.add('bg-gradient-to-r', 'from-blue-600', 'to-cyan-600', 'hover:from-blue-700', 'hover:to-cyan-700');
                }
                
                if (stopBtn) {
                    stopBtn.disabled = true;
                    stopBtn.classList.add('bg-slate-300', 'text-slate-600', 'cursor-not-allowed');
                    stopBtn.classList.remove('bg-gradient-to-r', 'from-red-600', 'to-rose-600', 'hover:from-red-700', 'hover:to-rose-700', 'text-white');
                }
            })
            .catch((err) => {
                console.error('Error stopping camera:', err);
                isScanning = false;
                html5QrCode = null;
            });
    }

    // Auto-focus on manual input
    document.addEventListener('DOMContentLoaded', function() {
        const manualInput = document.querySelector('input[name="qr"]');
        if (manualInput) {
            manualInput.focus();
        }
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (isScanning && html5QrCode) {
            try {
                html5QrCode.stop().catch(e => {});
            } catch (e) {}
        }
    });
  </script>
</x-layouts.app>
