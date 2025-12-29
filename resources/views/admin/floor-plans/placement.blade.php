<x-layouts.app :title="'Atur Lokasi Peralatan — ' . $floorPlan->name">
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50" x-data="placementApp()" x-init="init()">
    <!-- Header -->
    <div class="bg-white/80 backdrop-blur-lg border-b border-slate-200 sticky top-16 z-30">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.floor-plans.index') }}" class="w-10 h-10 bg-slate-100 hover:bg-slate-200 rounded-xl flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">Atur Lokasi Peralatan</h1>
                        <p class="text-sm text-slate-500">{{ $floorPlan->name }} - {{ $floorPlan->unit->code ?? 'Unit' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-slate-600" x-show="saving">
                        <svg class="w-4 h-4 inline animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Menyimpan...
                    </span>
                    <span class="text-sm text-green-600 font-medium" x-show="saved" x-transition>
                        ✓ Tersimpan
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Equipment List Sidebar -->
            <div class="lg:w-80 flex-shrink-0 space-y-4">
                <!-- Instructions -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl p-4 text-white">
                    <h3 class="font-semibold mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Cara Penggunaan
                    </h3>
                    <ul class="text-sm text-white/90 space-y-1.5">
                        <li class="flex items-start gap-2">
                            <span class="bg-white/20 rounded px-1.5 py-0.5 text-xs font-bold">1</span>
                            <span>Klik peralatan dari daftar di bawah</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="bg-white/20 rounded px-1.5 py-0.5 text-xs font-bold">2</span>
                            <span>Klik pada denah untuk menempatkan</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="bg-white/20 rounded px-1.5 py-0.5 text-xs font-bold">3</span>
                            <span>Drag marker untuk memindahkan posisi</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="bg-red-400/80 rounded px-1.5 py-0.5 text-xs font-bold">✕</span>
                            <span>Hover marker lalu klik tombol merah untuk hapus</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Placed Count -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-600">Peralatan Ditandai</span>
                        <span class="text-lg font-bold text-indigo-600" x-text="placedMarkers.length"></span>
                    </div>
                </div>

                <!-- Equipment Type Tabs -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="flex flex-wrap border-b border-slate-200">
                        <template x-for="(items, type) in equipment" :key="type">
                            <button 
                                @click="activeTab = type"
                                :class="activeTab === type ? 'bg-indigo-50 text-indigo-700 border-b-2 border-indigo-500' : 'text-slate-600 hover:bg-slate-50'"
                                class="flex-1 min-w-[80px] px-3 py-2 text-xs font-semibold transition-colors"
                                x-text="getTypeLabel(type) + ' (' + items.length + ')'"
                            ></button>
                        </template>
                    </div>
                    
                    <!-- Equipment List -->
                    <div class="p-3 max-h-[400px] overflow-y-auto">
                        <template x-for="(items, type) in equipment" :key="'list-' + type">
                            <div x-show="activeTab === type" class="space-y-2">
                                <template x-for="item in items" :key="type + '-' + item.id">
                                    <div 
                                        @click="selectEquipment(type, item)"
                                        :class="{
                                            'ring-2 ring-indigo-500 bg-indigo-50': selectedEquipment && selectedEquipment.type === type && selectedEquipment.id === item.id,
                                            'opacity-50': isPlaced(type, item.id)
                                        }"
                                        class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-all"
                                    >
                                        <div 
                                            class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold"
                                            :style="`background-color: ${getColor(type)}`"
                                        >
                                            <span x-text="getTypeLabel(type).charAt(0)"></span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-slate-800 truncate" x-text="item.serial_no || item.barcode || item.name"></p>
                                            <p class="text-xs text-slate-500 truncate" x-text="item.location_code || item.lokasi || '-'"></p>
                                        </div>
                                        <div x-show="isPlaced(type, item.id)" class="text-green-500">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="items.length === 0" class="text-center py-8 text-slate-500 text-sm">
                                    Tidak ada peralatan
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Legend -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4">
                    <h3 class="font-semibold text-slate-800 mb-3">Legenda Warna</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <template x-for="(color, type) in colors" :key="'legend-' + type">
                            <div class="flex items-center gap-2">
                                <span class="w-4 h-4 rounded-full" :style="`background-color: ${color}`"></span>
                                <span class="text-xs text-slate-600" x-text="getTypeLabel(type)"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Floor Plan Canvas -->
            <div class="flex-1">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-4 py-3 border-b border-slate-200 flex items-center justify-between">
                        <span class="text-sm font-semibold text-slate-700">Denah: {{ $floorPlan->name }}</span>
                        <span class="text-xs text-slate-500">Klik untuk menempatkan peralatan</span>
                    </div>
                    
                    <div 
                        class="relative bg-slate-100 overflow-hidden" 
                        style="height: 70vh; min-height: 500px;"
                        id="floor-plan-wrapper"
                    >
                        <!-- Floor Plan Container - markers positioned relative to this -->
                        <div 
                            class="relative w-full h-full"
                            id="floor-plan-container"
                            @click="!dragging && placeEquipment($event)"
                            @mouseup="endDrag()"
                        >
                            <!-- Floor Plan Image -->
                            <img 
                                src="{{ Storage::url($floorPlan->image_path) }}" 
                                alt="{{ $floorPlan->name }}"
                                class="w-full h-full object-contain select-none"
                                draggable="false"
                                id="floor-plan-image"
                            >
                            
                            <!-- Placed Markers - positioned relative to container -->
                            <template x-for="marker in placedMarkers" :key="marker.uid">
                                <div 
                                    class="absolute cursor-grab active:cursor-grabbing transform -translate-x-1/2 -translate-y-1/2 hover:scale-110 z-20 hover:z-50 select-none"
                                    :class="{ 
                                        'animate-bounce': marker.isNew,
                                        'cursor-grabbing scale-125 z-50': dragging && dragging.uid === marker.uid,
                                        'transition-all duration-200': !dragging || dragging.uid !== marker.uid
                                    }"
                                    :style="getMarkerStyle(marker)"
                                    @mousedown.prevent="startDrag($event, marker)"
                                    @contextmenu.prevent="removeMarker(marker)"
                                >
                                    <div class="relative group">
                                        <!-- Pulse effect for new markers -->
                                        <div 
                                            x-show="marker.isNew"
                                            class="absolute inset-0 rounded-full animate-ping"
                                            :style="`background-color: ${getColor(marker.type)}; opacity: 0.5;`"
                                        ></div>
                                        
                                        <!-- Marker -->
                                        <div 
                                            class="w-10 h-10 rounded-full border-3 border-white shadow-lg flex items-center justify-center text-white text-xs font-bold ring-2 ring-offset-1"
                                            :style="`background-color: ${getColor(marker.type)}; --tw-ring-color: ${getColor(marker.type)}40;`"
                                        >
                                            <span x-text="getTypeLabel(marker.type).charAt(0)"></span>
                                        </div>
                                        
                                        <!-- Delete Button (visible on hover) -->
                                        <button 
                                            @click.stop="removeMarker(marker)"
                                            class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full shadow-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-50"
                                            title="Hapus marker"
                                        >
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                        
                                        <!-- Tooltip with name -->
                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 bg-slate-800 text-white text-xs rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition pointer-events-none z-50 shadow-lg">
                                            <span x-text="marker.name"></span>
                                            <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-800"></div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Selection Indicator -->
                        <div 
                            x-show="selectedEquipment" 
                            x-transition
                            class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-indigo-600 text-white px-4 py-2 rounded-full shadow-lg text-sm font-medium z-40"
                        >
                            <span x-text="selectedEquipment ? 'Klik pada denah untuk menempatkan: ' + (selectedEquipment.serial_no || selectedEquipment.name) : ''"></span>
                            <button @click.stop="selectedEquipment = null" class="ml-2 hover:text-indigo-200">✕</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function placementApp() {
    return {
        equipment: @json($equipment),
        placedMarkers: [],
        selectedEquipment: null,
        activeTab: 'apar',
        saving: false,
        saved: false,
        dragging: null,
        dragOffset: { x: 0, y: 0 },
        initialized: false,
        
        colors: {
            apar: '#EF4444',
            apat: '#3B82F6',
            fire_alarm: '#F97316',
            box_hydrant: '#06B6D4',
            rumah_pompa: '#8B5CF6',
            apab: '#10B981',
            p3k: '#EC4899'
        },

        init() {
            // Prevent double initialization
            if (this.initialized) {
                console.log('Already initialized, skipping...');
                return;
            }
            this.initialized = true;
            
            // Load already placed equipment
            const placed = @json($placedEquipment);
            console.log('Placed equipment from server:', placed);
            
            // Process placed equipment
            const types = ['apar', 'apat', 'fire_alarm', 'box_hydrant', 'rumah_pompa', 'apab', 'p3k'];
            let markerIndex = 0;
            types.forEach(type => {
                if (placed[type] && Array.isArray(placed[type])) {
                    placed[type].forEach(item => {
                        if (item && item.id && item.floor_plan_x != null && item.floor_plan_y != null) {
                            this.placedMarkers.push({
                                type: type,
                                id: item.id,
                                uid: type + '_' + item.id + '_' + markerIndex,
                                name: item.serial_no || item.barcode || item.name || 'Equipment',
                                x: parseFloat(item.floor_plan_x),
                                y: parseFloat(item.floor_plan_y),
                                isNew: false
                            });
                            markerIndex++;
                        }
                    });
                }
            });

            // Add mouse move and up listeners for dragging
            const onDragHandler = (e) => {
                if (this.dragging) {
                    this.onDrag(e);
                }
            };
            
            const endDragHandler = () => {
                if (this.dragging) {
                    this.endDrag();
                }
            };
            
            document.addEventListener('mousemove', onDragHandler);
            document.addEventListener('mouseup', endDragHandler);
            
            // Store handlers for cleanup if needed
            this._dragHandlers = { onDragHandler, endDragHandler };
            
            console.log('Loaded markers:', this.placedMarkers);
            console.log('Drag handlers attached');
            console.log('Total markers count:', this.placedMarkers.length);
        },
        
        getMarkerStyle(marker) {
            if (!marker || marker.x === undefined || marker.y === undefined) {
                return 'display: none;';
            }
            return `left: ${marker.x}%; top: ${marker.y}%;`;
        },

        getTypeLabel(type) {
            const labels = {
                apar: 'APAR',
                apat: 'APAT',
                fire_alarm: 'Fire Alarm',
                box_hydrant: 'Box Hydrant',
                rumah_pompa: 'R. Pompa',
                apab: 'APAB',
                p3k: 'P3K'
            };
            return labels[type] || type;
        },

        getColor(type) {
            return this.colors[type] || '#6B7280';
        },

        selectEquipment(type, item) {
            if (this.isPlaced(type, item.id)) {
                // If already placed, highlight it
                return;
            }
            this.selectedEquipment = { ...item, type };
        },

        isPlaced(type, id) {
            return this.placedMarkers.some(m => m.type === type && m.id === id);
        },

        async placeEquipment(event) {
            if (!this.selectedEquipment) return;
            if (this.dragging) return;

            const container = document.getElementById('floor-plan-container');
            const rect = container.getBoundingClientRect();
            
            // Calculate click position as percentage of container
            const x = ((event.clientX - rect.left) / rect.width) * 100;
            const y = ((event.clientY - rect.top) / rect.height) * 100;

            // Add marker
            const marker = {
                type: this.selectedEquipment.type,
                id: this.selectedEquipment.id,
                uid: this.selectedEquipment.type + '_' + this.selectedEquipment.id + '_' + Date.now(),
                name: this.selectedEquipment.serial_no || this.selectedEquipment.barcode || this.selectedEquipment.name || 'Equipment',
                x: Math.max(0, Math.min(100, x)),
                y: Math.max(0, Math.min(100, y)),
                isNew: true
            };

            this.placedMarkers.push(marker);
            console.log('Placed marker:', marker);
            
            // Save to server
            await this.savePosition(marker);
            
            // Remove new flag after animation
            setTimeout(() => {
                marker.isNew = false;
            }, 500);
            
            // Clear selection
            this.selectedEquipment = null;
        },

        startDrag(event, marker) {
            event.preventDefault();
            event.stopPropagation();
            
            console.log('Start dragging:', marker.name);
            this.dragging = marker;
            
            const container = document.getElementById('floor-plan-container');
            if (!container) {
                console.error('Container not found');
                return;
            }
            
            const rect = container.getBoundingClientRect();
            
            // Calculate current marker position in pixels
            const markerX = (marker.x / 100) * rect.width;
            const markerY = (marker.y / 100) * rect.height;
            
            this.dragOffset = {
                x: event.clientX - rect.left - markerX,
                y: event.clientY - rect.top - markerY
            };
            
            console.log('Drag started with offset:', this.dragOffset);
        },

        onDrag(event) {
            if (!this.dragging) return;
            
            event.preventDefault();

            const container = document.getElementById('floor-plan-container');
            if (!container) return;
            
            const rect = container.getBoundingClientRect();
            
            // Calculate new position as percentage
            const x = ((event.clientX - rect.left - this.dragOffset.x) / rect.width) * 100;
            const y = ((event.clientY - rect.top - this.dragOffset.y) / rect.height) * 100;

            this.dragging.x = Math.max(0, Math.min(100, x));
            this.dragging.y = Math.max(0, Math.min(100, y));
            
            // Add visual feedback
            document.body.style.cursor = 'grabbing';
            document.body.style.userSelect = 'none';
        },

        async endDrag() {
            if (this.dragging) {
                console.log('End dragging:', this.dragging.name);
                await this.savePosition(this.dragging);
                this.dragging = null;
                
                // Reset cursor
                document.body.style.cursor = '';
                document.body.style.userSelect = '';
            }
        },

        async removeMarker(marker) {
            if (!confirm('Hapus ' + marker.name + ' dari denah?')) return;

            // Remove from array
            this.placedMarkers = this.placedMarkers.filter(m => !(m.type === marker.type && m.id === marker.id));

            // Save to server
            this.saving = true;
            try {
                await fetch('{{ route("admin.floor-plans.remove-placement", $floorPlan) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        equipment_type: marker.type,
                        equipment_id: marker.id
                    })
                });
                this.showSaved();
            } catch (error) {
                console.error('Failed to remove:', error);
            }
            this.saving = false;
        },

        async savePosition(marker) {
            this.saving = true;
            try {
                await fetch('{{ route("admin.floor-plans.save-placement", $floorPlan) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        equipment_type: marker.type,
                        equipment_id: marker.id,
                        x: marker.x,
                        y: marker.y
                    })
                });
                this.showSaved();
            } catch (error) {
                console.error('Failed to save:', error);
            }
            this.saving = false;
        },

        showSaved() {
            this.saved = true;
            setTimeout(() => this.saved = false, 2000);
        }
    }
}
</script>
</x-layouts.app>
