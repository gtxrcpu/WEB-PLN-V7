{{-- Auto-refresh script untuk kartu kendali --}}
@props(['module', 'templateVersion' => 0])

<script>
    // Auto-refresh untuk mendapatkan template terbaru
    let initialTemplateVersion = {{ $templateVersion }};
    let checkInterval = 5000; // Check setiap 5 detik
    let isReloading = false;
    
    function checkTemplateUpdate() {
        if (isReloading) return;
        
        fetch('/api/template-version/{{ $module }}', {
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
        console.log('🔄 Auto-refresh template {{ strtoupper($module) }} aktif (check setiap 5 detik)');
        setInterval(checkTemplateUpdate, checkInterval);
    });
</script>
