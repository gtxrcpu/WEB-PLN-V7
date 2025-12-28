<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50" x-data="floorPlanApp()" x-init="init()">
        <!-- Header Section -->
        <div class="bg-white/80 backdrop-blur-lg border-b border-slate-200 sticky top-16 z-30">
            <div class="max-w-7xl mx-auto px-4 py-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <!-- Title with Back Button -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('user.dashboard') }}" class="w-10 h-10 bg-slate-100 hover:bg-slate-200 rounded-xl flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-slate-800">Denah Lokasi Peralatan</h1>
                            <p class="text-sm text-slate-500">Real-time equipment monitoring</p>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <input 
                                type="text" 
                                x-model="searchQuery"
                                @input.debounce.300ms="filterEquipment()"
                                placeholder="Cari peralatan (nama/serial)..."
                                class="w-full pl-11 pr-10 py-3 bg-slate-100 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-sm"
                            >
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <button 
                                x-show="searchQuery" 
                                @click="searchQuery = ''; filterEquipment()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 hover:bg-slate-200 rounded-full transition"
                            >
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <!-- Search Results Count -->
                        <div x-show="searchQuery" class="mt-2 text-xs" x-cloak>
                            <span x-show="visibleEquipment.length > 0" class="text-green-600 font-medium">
                                <span x-text="visibleEquipment.length"></span> hasil ditemukan
                            </span>
                            <span x-show="visibleEquipment.length === 0" class="text-red-500 font-medium">
                                Tidak ada hasil
                            </span>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-50 px-4 py-2 rounded-xl">
                            <div class="text-xs text-blue-600 font-medium">Total</div>
                            <div class="text-lg font-bold text-blue-700" x-text="allEquipment.length">0</div>
                        </div>
                        <div class="bg-green-50 px-4 py-2 rounded-xl">
                            <div class="text-xs text-green-600 font-medium">Tampil</div>
                            <div class="text-lg font-bold text-green-700" x-text="visibleEquipment.length">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex flex-col lg:flex-row gap-6">
                
                <!-- Sidebar - Filter & Legend -->
                <div class="lg:w-80 flex-shrink-0 space-y-4">
                    <!-- Filter Panel -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-500 px-4 py-3">
                            <h3 class="text-white font-semibold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
                                </svg>
                                Filter Peralatan
                            </h3>
                        </div>
                        <div class="p-4 space-y-2">
                            <template x-for="(filter, type) in filters" :key="type">
                                <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 cursor-pointer transition group">
                                    <input 
                                        type="checkbox" 
                                        x-model="filter.enabled"
                                        @change="updateVisibleMarkers(); saveFilterState()"
                                        class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                    >
                                    <span 
                                        class="w-4 h-4 rounded-full shadow-sm flex-shrink-0"
                                        :style="`background-color: ${filter.color}`"
                                    ></span>
                                    <span class="flex-1 text-sm font-medium text-slate-700" x-text="filter.label"></span>
                                    <span 
                                        class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 group-hover:bg-blue-100 group-hover:text-blue-700 transition"
                                        x-text="getEquipmentCount(type)"
                                    ></span>
                                </label>
                            </template>
                            
                            <!-- Quick Actions -->
                            <div class="flex gap-2 pt-3 border-t border-slate-100">
                                <button @click="selectAllFilters()" class="flex-1 text-xs py-2 px-3 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition font-medium">
                                    Pilih Semua
                                </button>
                                <button @click="deselectAllFilters()" class="flex-1 text-xs py-2 px-3 bg-slate-50 text-slate-600 rounded-lg hover:bg-slate-100 transition font-medium">
                                    Hapus Semua
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Legend Panel -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 px-4 py-3">
                            <h3 class="text-white font-semibold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Status Peralatan
                            </h3>
                        </div>
                        <div class="p-4 space-y-2">
                            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-green-50 cursor-pointer transition" @click="highlightByStatus('baik')">
                                <span class="w-4 h-4 rounded-full bg-green-500 shadow-sm"></span>
                                <span class="text-sm font-medium text-slate-700">Baik</span>
                                <span class="ml-auto text-xs text-slate-500" x-text="getStatusCount('baik')"></span>
                            </div>
                            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-yellow-50 cursor-pointer transition" @click="highlightByStatus('perlu_pengecekan')">
                                <span class="w-4 h-4 rounded-full bg-yellow-500 shadow-sm"></span>
                                <span class="text-sm font-medium text-slate-700">Perlu Pengecekan</span>
                                <span class="ml-auto text-xs text-slate-500" x-text="getStatusCount('perlu_pengecekan')"></span>
                            </div>
                            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-red-50 cursor-pointer transition" @click="highlightByStatus('rusak')">
                                <span class="w-4 h-4 rounded-full bg-red-500 shadow-sm animate-pulse"></span>
                                <span class="text-sm font-medium text-slate-700">Rusak</span>
                                <span class="ml-auto text-xs text-slate-500" x-text="getStatusCount('rusak')"></span>
                            </div>
                            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 cursor-pointer transition" @click="highlightByStatus(null)">
                                <span class="w-4 h-4 rounded-full bg-slate-400 shadow-sm"></span>
                                <span class="text-sm font-medium text-slate-700">Tidak Diketahui</span>
                                <span class="ml-auto text-xs text-slate-500" x-text="getStatusCount(null)"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Equipment Type Legend -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 px-4 py-3">
                            <h3 class="text-white font-semibold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                Jenis Peralatan
                            </h3>
                        </div>
                        <div class="p-4 space-y-2">
                            <template x-for="(filter, type) in filters" :key="'legend-' + type">
                                <div 
                                    class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 cursor-pointer transition"
                                    @click="highlightByType(type)"
                                >
                                    <span 
                                        class="w-4 h-4 rounded-full shadow-sm"
                                        :style="`background-color: ${filter.color}`"
                                    ></span>
                                    <span class="text-sm font-medium text-slate-700" x-text="filter.label"></span>
                                    <span class="ml-auto text-xs text-slate-500" x-text="getEquipmentCount(type)"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Floor Plan Area -->
                <div class="flex-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        @if($floorPlan)
                        <!-- Floor Plan Container -->
                        <div class="relative" style="height: 70vh; min-height: 500px;">
                            <!-- Loading Overlay -->
                            <div 
                                x-show="loading" 
                                x-transition
                                class="absolute inset-0 bg-white/90 backdrop-blur-sm flex items-center justify-center z-40"
                            >
                                <div class="text-center">
                                    <div class="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto"></div>
                                    <p class="mt-4 text-slate-600 font-medium">Memuat data peralatan...</p>
                                </div>
                            </div>

                            <!-- Map Container -->
                            <div 
                                id="floor-plan-wrapper"
                                class="w-full h-full overflow-hidden bg-slate-100 relative"
                                @wheel.prevent="handleWheel($event)"
                                @mousedown="startPan($event)"
                                @mousemove="doPan($event)"
                                @mouseup="endPan()"
                                @mouseleave="endPan()"
                                @touchstart="handleTouchStart($event)"
                                @touchmove="handleTouchMove($event)"
                                @touchend="handleTouchEnd($event)"
                            >
                                <div 
                                    id="floor-plan-container"
                                    class="absolute origin-center transition-transform duration-100"
                                    :style="`transform: translate(${panX}px, ${panY}px) scale(${zoom}); width: 100%; height: 100%;`"
                                >
                                    <!-- Floor Plan Image -->
                                    <img 
                                        src="{{ $floorPlan->image_url }}" 
                                        alt="Floor Plan"
                                        class="w-full h-full object-contain select-none pointer-events-none"
                                        draggable="false"
                                    >
                                    
                                    <!-- Equipment Markers -->
                                    <template x-for="eq in visibleEquipment" :key="eq.id + '-' + eq.type">
                                        <div 
                                            class="absolute cursor-pointer transform -translate-x-1/2 -translate-y-1/2 transition-all duration-200 hover:scale-125 hover:z-50"
                                            :class="{ 
                                                'z-50 scale-150': isHighlighted(eq),
                                                'animate-bounce': isHighlighted(eq)
                                            }"
                                            :style="`left: ${eq.x}%; top: ${eq.y}%;`"
                                            @click.stop="showPopup(eq)"
                                        >
                                            <!-- Marker -->
                                            <div class="relative group">
                                                <!-- Pulse for highlighted -->
                                                <div 
                                                    x-show="isHighlighted(eq)"
                                                    class="absolute inset-0 rounded-full animate-ping"
                                                    :style="`background-color: ${getColor(eq.type)}; opacity: 0.5;`"
                                                ></div>
                                                
                                                <!-- Pulse for rusak status -->
                                                <div 
                                                    x-show="eq.status && eq.status.toLowerCase() === 'rusak'"
                                                    class="absolute -inset-1 rounded-full animate-ping bg-red-500 opacity-50"
                                                ></div>
                                                
                                                <!-- Main Marker -->
                                                <div 
                                                    class="w-8 h-8 rounded-full border-2 border-white shadow-lg flex items-center justify-center relative"
                                                    :class="{ 'ring-4 ring-blue-400': isHighlighted(eq) }"
                                                    :style="`background-color: ${getColor(eq.type)};`"
                                                >
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                
                                                <!-- Status Indicator -->
                                                <div 
                                                    class="absolute -top-1 -right-1 w-3 h-3 rounded-full border-2 border-white"
                                                    :class="{
                                                        'animate-pulse': eq.status && eq.status.toLowerCase() === 'rusak'
                                                    }"
                                                    :style="`background-color: ${getStatusColor(eq.status)};`"
                                                ></div>
                                                
                                                <!-- Tooltip on hover -->
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-slate-800 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition pointer-events-none">
                                                    <span x-text="eq.name || eq.serial_no"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Zoom Controls -->
                            <div class="absolute bottom-4 right-4 flex flex-col gap-2 z-30">
                                <button 
                                    @click="zoomIn()"
                                    class="w-10 h-10 bg-white rounded-lg shadow-lg flex items-center justify-center hover:bg-slate-50 transition"
                                    title="Zoom In"
                                >
                                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </button>
                                <button 
                                    @click="zoomOut()"
                                    class="w-10 h-10 bg-white rounded-lg shadow-lg flex items-center justify-center hover:bg-slate-50 transition"
                                    title="Zoom Out"
                                >
                                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                </button>
                                <button 
                                    @click="resetView()"
                                    class="w-10 h-10 bg-white rounded-lg shadow-lg flex items-center justify-center hover:bg-slate-50 transition"
                                    title="Reset View"
                                >
                                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Zoom Level Indicator -->
                            <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur px-3 py-1.5 rounded-lg shadow text-sm font-medium text-slate-600 z-30">
                                <span x-text="Math.round(zoom * 100) + '%'"></span>
                            </div>
                        </div>
                        @else
                        <!-- Empty State -->
                        <div class="p-12 text-center">
                            <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-2">Denah Belum Tersedia</h3>
                            <p class="text-slate-500 max-w-md mx-auto">
                                Silakan hubungi administrator untuk mengunggah denah unit Anda.
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Equipment Detail Modal -->
        <div 
            x-show="selectedEquipment"
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            @click.self="selectedEquipment = null"
        >
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
            
            <!-- Modal Content -->
            <div 
                x-show="selectedEquipment"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden"
            >
                <!-- Header -->
                <div 
                    class="px-6 py-4"
                    :style="`background: linear-gradient(135deg, ${selectedEquipment ? getColor(selectedEquipment.type) : '#3b82f6'}, ${selectedEquipment ? getColor(selectedEquipment.type) + 'dd' : '#3b82f6dd'});`"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white" x-text="selectedEquipment?.name || 'Equipment'"></h3>
                                <p class="text-white/80 text-sm" x-text="getTypeLabel(selectedEquipment?.type)"></p>
                            </div>
                        </div>
                        <button 
                            @click="selectedEquipment = null"
                            class="p-2 hover:bg-white/20 rounded-lg transition"
                        >
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Serial Number</label>
                            <p class="text-slate-800 font-semibold" x-text="selectedEquipment?.serial_no || '-'"></p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Status</label>
                            <div class="flex items-center gap-2 mt-1">
                                <span 
                                    class="w-3 h-3 rounded-full"
                                    :style="`background-color: ${getStatusColor(selectedEquipment?.status)};`"
                                ></span>
                                <span class="text-slate-800 font-semibold capitalize" x-text="selectedEquipment?.status || 'Tidak Diketahui'"></span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Lokasi</label>
                        <p class="text-slate-800 font-semibold" x-text="selectedEquipment?.location || '-'"></p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50 flex gap-3">
                    <a 
                        :href="selectedEquipment?.url || '#'"
                        class="flex-1 bg-gradient-to-r from-blue-500 to-cyan-500 text-white text-center py-3 rounded-xl font-semibold hover:shadow-lg hover:shadow-blue-500/30 transition"
                    >
                        Lihat Detail
                    </a>
                    <button 
                        @click="selectedEquipment = null"
                        class="px-6 py-3 bg-slate-200 text-slate-700 rounded-xl font-semibold hover:bg-slate-300 transition"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        function floorPlanApp() {
            return {
                // Data
                allEquipment: [],
                visibleEquipment: [],
                selectedEquipment: null,
                searchQuery: '',
                loading: true,
                
                // Highlight state
                highlightedId: null,
                highlightedType: null,
                highlightedStatus: null,
                
                // Zoom & Pan
                zoom: 1,
                panX: 0,
                panY: 0,
                isPanning: false,
                startX: 0,
                startY: 0,
                
                // Touch handling
                lastTouchDistance: 0,
                
                // Filters
                filters: {
                    apar: { enabled: true, label: 'APAR', color: '#EF4444' },
                    apat: { enabled: true, label: 'APAT', color: '#3B82F6' },
                    fire_alarm: { enabled: true, label: 'Fire Alarm', color: '#F97316' },
                    box_hydrant: { enabled: true, label: 'Box Hydrant', color: '#06B6D4' },
                    rumah_pompa: { enabled: true, label: 'Rumah Pompa', color: '#8B5CF6' },
                    apab: { enabled: true, label: 'APAB', color: '#10B981' },
                    p3k: { enabled: true, label: 'P3K', color: '#EC4899' }
                },

                init() {
                    this.loadFilterState();
                    this.loadEquipmentData();
                },

                async loadEquipmentData() {
                    this.loading = true;
                    @if($floorPlan)
                    try {
                        const response = await fetch('{{ route("floor-plan.equipment-data", $floorPlan->id) }}');
                        const data = await response.json();
                        this.allEquipment = data.equipment || [];
                        this.updateVisibleMarkers();
                    } catch (error) {
                        console.error('Failed to load equipment:', error);
                        this.allEquipment = [];
                    }
                    @endif
                    this.loading = false;
                },

                loadFilterState() {
                    const saved = sessionStorage.getItem('floorPlanFilters');
                    if (saved) {
                        try {
                            const parsed = JSON.parse(saved);
                            Object.keys(this.filters).forEach(type => {
                                if (parsed[type] !== undefined) {
                                    this.filters[type].enabled = parsed[type];
                                }
                            });
                        } catch (e) {}
                    }
                },

                saveFilterState() {
                    const state = {};
                    Object.keys(this.filters).forEach(type => {
                        state[type] = this.filters[type].enabled;
                    });
                    sessionStorage.setItem('floorPlanFilters', JSON.stringify(state));
                },

                updateVisibleMarkers() {
                    this.visibleEquipment = this.allEquipment.filter(eq => {
                        const filterEnabled = this.filters[eq.type]?.enabled;
                        const matchesSearch = !this.searchQuery || 
                            (eq.name && eq.name.toLowerCase().includes(this.searchQuery.toLowerCase())) ||
                            (eq.serial_no && eq.serial_no.toLowerCase().includes(this.searchQuery.toLowerCase()));
                        return filterEnabled && matchesSearch;
                    });
                },

                filterEquipment() {
                    this.updateVisibleMarkers();
                    this.highlightedType = null;
                    this.highlightedStatus = null;
                    
                    if (this.searchQuery && this.visibleEquipment.length > 0) {
                        this.highlightedId = this.visibleEquipment[0].id + '-' + this.visibleEquipment[0].type;
                    } else {
                        this.highlightedId = null;
                    }
                },

                selectAllFilters() {
                    Object.keys(this.filters).forEach(type => {
                        this.filters[type].enabled = true;
                    });
                    this.updateVisibleMarkers();
                    this.saveFilterState();
                },

                deselectAllFilters() {
                    Object.keys(this.filters).forEach(type => {
                        this.filters[type].enabled = false;
                    });
                    this.updateVisibleMarkers();
                    this.saveFilterState();
                },

                // Highlight functions
                highlightByType(type) {
                    if (this.highlightedType === type) {
                        this.highlightedType = null;
                    } else {
                        this.highlightedType = type;
                        this.highlightedStatus = null;
                        this.highlightedId = null;
                    }
                },

                highlightByStatus(status) {
                    if (this.highlightedStatus === status) {
                        this.highlightedStatus = null;
                    } else {
                        this.highlightedStatus = status;
                        this.highlightedType = null;
                        this.highlightedId = null;
                    }
                },

                isHighlighted(eq) {
                    if (this.highlightedId === eq.id + '-' + eq.type) return true;
                    if (this.highlightedType === eq.type) return true;
                    if (this.highlightedStatus !== null) {
                        if (this.highlightedStatus === null) {
                            return !eq.status || eq.status === '';
                        }
                        return eq.status && eq.status.toLowerCase() === this.highlightedStatus.toLowerCase();
                    }
                    return false;
                },

                // Getters
                getColor(type) {
                    return this.filters[type]?.color || '#6B7280';
                },

                getStatusColor(status) {
                    if (!status) return '#9CA3AF';
                    const s = status.toLowerCase();
                    if (s === 'baik') return '#10B981';
                    if (s === 'rusak') return '#EF4444';
                    if (s.includes('pengecekan') || s === 'perbaikan') return '#F59E0B';
                    return '#9CA3AF';
                },

                getTypeLabel(type) {
                    return this.filters[type]?.label || type || '';
                },

                getEquipmentCount(type) {
                    return this.allEquipment.filter(e => e.type === type).length;
                },

                getStatusCount(status) {
                    return this.allEquipment.filter(e => {
                        if (status === null) return !e.status || e.status === '';
                        return e.status && e.status.toLowerCase() === status.toLowerCase();
                    }).length;
                },

                // Popup
                showPopup(eq) {
                    this.selectedEquipment = eq;
                },

                // Zoom & Pan
                zoomIn() {
                    this.zoom = Math.min(5, this.zoom + 0.25);
                },

                zoomOut() {
                    this.zoom = Math.max(0.5, this.zoom - 0.25);
                },

                resetView() {
                    this.zoom = 1;
                    this.panX = 0;
                    this.panY = 0;
                },

                handleWheel(e) {
                    const delta = e.deltaY > 0 ? -0.1 : 0.1;
                    this.zoom = Math.max(0.5, Math.min(5, this.zoom + delta));
                },

                startPan(e) {
                    if (e.button !== 0) return;
                    this.isPanning = true;
                    this.startX = e.clientX - this.panX;
                    this.startY = e.clientY - this.panY;
                },

                doPan(e) {
                    if (!this.isPanning) return;
                    this.panX = e.clientX - this.startX;
                    this.panY = e.clientY - this.startY;
                },

                endPan() {
                    this.isPanning = false;
                },

                // Touch handling
                handleTouchStart(e) {
                    if (e.touches.length === 2) {
                        this.lastTouchDistance = this.getTouchDistance(e.touches);
                    } else if (e.touches.length === 1) {
                        this.isPanning = true;
                        this.startX = e.touches[0].clientX - this.panX;
                        this.startY = e.touches[0].clientY - this.panY;
                    }
                },

                handleTouchMove(e) {
                    e.preventDefault();
                    if (e.touches.length === 2) {
                        const dist = this.getTouchDistance(e.touches);
                        if (this.lastTouchDistance > 0) {
                            const scale = dist / this.lastTouchDistance;
                            this.zoom = Math.max(0.5, Math.min(5, this.zoom * scale));
                        }
                        this.lastTouchDistance = dist;
                    } else if (e.touches.length === 1 && this.isPanning) {
                        this.panX = e.touches[0].clientX - this.startX;
                        this.panY = e.touches[0].clientY - this.startY;
                    }
                },

                handleTouchEnd(e) {
                    if (e.touches.length < 2) {
                        this.lastTouchDistance = 0;
                    }
                    if (e.touches.length === 0) {
                        this.isPanning = false;
                    }
                },

                getTouchDistance(touches) {
                    return Math.hypot(
                        touches[0].clientX - touches[1].clientX,
                        touches[0].clientY - touches[1].clientY
                    );
                }
            }
        }
    </script>
</x-layouts.app>
