# Design Document - Real-Time Floor Plan Feature

## Overview

The Real-Time Floor Plan feature provides an interactive visual interface for displaying safety equipment locations on building floor plans. The system overlays equipment markers on uploaded floor plan images, allowing users to quickly identify equipment positions, view details, and filter by type or status. The design emphasizes modern UI/UX with smooth interactions, responsive layout, and real-time data synchronization.

## Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                     Presentation Layer                       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │ Floor Plan   │  │ Equipment    │  │ Admin Floor  │      │
│  │ View (Blade) │  │ Popup Modal  │  │ Plan Manager │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                    Application Layer                         │
│  ┌──────────────────────────────────────────────────────┐   │
│  │         FloorPlanController                          │   │
│  │  - index()                                           │   │
│  │  - getEquipmentData()                                │   │
│  │  - uploadFloorPlan()                                 │   │
│  │  - updateEquipmentCoordinates()                      │   │
│  └──────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                      Domain Layer                            │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │ FloorPlan    │  │ Equipment    │  │ Unit         │      │
│  │ Model        │  │ Models       │  │ Model        │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                    Data Layer (MySQL)                        │
│  - floor_plans table                                         │
│  - apars, apats, fire_alarms, box_hydrants, etc.            │
│  - units table                                               │
└─────────────────────────────────────────────────────────────┘
```

### Technology Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade Templates, Alpine.js for interactivity
- **Styling**: Tailwind CSS
- **Database**: MySQL
- **Image Storage**: Laravel Storage (public disk)
- **JavaScript Libraries**: 
  - Panzoom.js for zoom/pan functionality
  - Alpine.js for reactive UI components

## Components and Interfaces

### 1. Database Schema

#### New Table: `floor_plans`

```php
Schema::create('floor_plans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->string('image_path'); // storage path
    $table->integer('width')->nullable(); // image width in pixels
    $table->integer('height')->nullable(); // image height in pixels
    $table->text('description')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

#### Equipment Tables Modification

Add coordinate columns to all equipment tables:

```php
// Migration to add coordinates to equipment tables
$table->decimal('floor_plan_x', 5, 2)->nullable(); // X coordinate (0-100%)
$table->decimal('floor_plan_y', 5, 2)->nullable(); // Y coordinate (0-100%)
$table->foreignId('floor_plan_id')->nullable()->constrained()->nullOnDelete();
```

Tables to modify:
- `apars`
- `apats`
- `fire_alarms`
- `box_hydrants`
- `rumah_pompas`
- `apabs`
- `p3ks`

### 2. Models

#### FloorPlan Model

```php
class FloorPlan extends Model
{
    protected $fillable = [
        'unit_id',
        'name',
        'image_path',
        'width',
        'height',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function getImageUrlAttribute()
    {
        return Storage::url($this->image_path);
    }

    // Get all equipment for this floor plan
    public function getAllEquipment()
    {
        $equipment = [];
        
        $equipment['apar'] = Apar::where('floor_plan_id', $this->id)
            ->whereNotNull('floor_plan_x')
            ->whereNotNull('floor_plan_y')
            ->get();
            
        $equipment['apat'] = Apat::where('floor_plan_id', $this->id)
            ->whereNotNull('floor_plan_x')
            ->whereNotNull('floor_plan_y')
            ->get();
            
        // ... similar for other equipment types
        
        return $equipment;
    }
}
```

### 3. Controllers

#### FloorPlanController

```php
class FloorPlanController extends Controller
{
    public function index()
    {
        // Show floor plan for current user's unit
        $unit = auth()->user()->unit;
        $floorPlan = FloorPlan::where('unit_id', $unit->id)
            ->where('is_active', true)
            ->first();
            
        return view('floor-plan.index', compact('floorPlan'));
    }

    public function getEquipmentData(FloorPlan $floorPlan)
    {
        // Return JSON data for all equipment on this floor plan
        $equipment = $floorPlan->getAllEquipment();
        
        return response()->json([
            'equipment' => $this->formatEquipmentForMap($equipment)
        ]);
    }

    private function formatEquipmentForMap($equipment)
    {
        $formatted = [];
        
        foreach ($equipment as $type => $items) {
            foreach ($items as $item) {
                $formatted[] = [
                    'id' => $item->id,
                    'type' => $type,
                    'name' => $item->name,
                    'serial_no' => $item->serial_no ?? $item->barcode,
                    'status' => $item->status,
                    'x' => $item->floor_plan_x,
                    'y' => $item->floor_plan_y,
                    'location' => $item->lokasi ?? $item->location_code,
                    'url' => route("{$type}.show", $item->id)
                ];
            }
        }
        
        return $formatted;
    }

    public function uploadFloorPlan(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:10240',
            'description' => 'nullable|string'
        ]);

        $path = $request->file('image')->store('floor-plans', 'public');
        
        // Get image dimensions
        $imagePath = storage_path('app/public/' . $path);
        list($width, $height) = getimagesize($imagePath);

        $floorPlan = FloorPlan::create([
            'unit_id' => $request->unit_id,
            'name' => $request->name,
            'image_path' => $path,
            'width' => $width,
            'height' => $height,
            'description' => $request->description,
            'is_active' => true
        ]);

        return redirect()->route('admin.floor-plans.index')
            ->with('success', 'Floor plan uploaded successfully');
    }

    public function updateEquipmentCoordinates(Request $request)
    {
        $request->validate([
            'equipment_type' => 'required|string',
            'equipment_id' => 'required|integer',
            'floor_plan_id' => 'required|exists:floor_plans,id',
            'x' => 'required|numeric|min:0|max:100',
            'y' => 'required|numeric|min:0|max:100'
        ]);

        $modelClass = $this->getModelClass($request->equipment_type);
        $equipment = $modelClass::findOrFail($request->equipment_id);
        
        $equipment->update([
            'floor_plan_id' => $request->floor_plan_id,
            'floor_plan_x' => $request->x,
            'floor_plan_y' => $request->y
        ]);

        return response()->json(['success' => true]);
    }

    private function getModelClass($type)
    {
        $models = [
            'apar' => Apar::class,
            'apat' => Apat::class,
            'fire_alarm' => FireAlarm::class,
            'box_hydrant' => BoxHydrant::class,
            'rumah_pompa' => RumahPompa::class,
            'apab' => Apab::class,
            'p3k' => P3k::class,
        ];

        return $models[$type] ?? null;
    }
}
```

### 4. Frontend Components

#### Main Floor Plan View (Blade)

```blade
<!-- resources/views/floor-plan/index.blade.php -->
<x-app-layout>
    <div class="py-6" x-data="floorPlanApp()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">Denah Lokasi Peralatan</h1>
                
                <!-- Search -->
                <div class="w-64">
                    <input 
                        type="text" 
                        x-model="searchQuery"
                        @input="filterEquipment"
                        placeholder="Cari peralatan..."
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Sidebar: Filters & Legend -->
                <div class="lg:col-span-1">
                    <!-- Filter Panel -->
                    <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                        <h3 class="text-lg font-semibold mb-3">Filter Peralatan</h3>
                        
                        <div class="space-y-2">
                            <template x-for="(filter, type) in filters" :key="type">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        x-model="filter.enabled"
                                        @change="updateVisibleMarkers"
                                        class="rounded text-blue-600"
                                    >
                                    <span class="flex items-center">
                                        <span 
                                            class="w-4 h-4 rounded-full mr-2" 
                                            :style="`background-color: ${filter.color}`"
                                        ></span>
                                        <span x-text="filter.label"></span>
                                        <span 
                                            class="ml-auto text-sm text-gray-500" 
                                            x-text="`(${getEquipmentCount(type)})`"
                                        ></span>
                                    </span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Legend Panel -->
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <h3 class="text-lg font-semibold mb-3">Keterangan Status</h3>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                <span>Baik</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <span>Perlu Pengecekan</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <span>Rusak</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                                <span>Tidak Diketahui</span>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="bg-white rounded-lg shadow-md p-4 mt-4">
                        <h3 class="text-lg font-semibold mb-3">Statistik</h3>
                        <div class="text-sm space-y-1">
                            <div class="flex justify-between">
                                <span>Total Peralatan:</span>
                                <span class="font-semibold" x-text="allEquipment.length"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Ditampilkan:</span>
                                <span class="font-semibold" x-text="visibleEquipment.length"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Floor Plan Area -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-lg shadow-md p-4">
                        @if($floorPlan)
                            <!-- Floor Plan Container -->
                            <div class="relative bg-gray-100 rounded-lg overflow-hidden" style="height: 700px;">
                                <div id="floor-plan-container" class="w-full h-full">
                                    <img 
                                        src="{{ $floorPlan->image_url }}" 
                                        alt="Floor Plan"
                                        class="w-full h-full object-contain"
                                        id="floor-plan-image"
                                    >
                                    
                                    <!-- Equipment Markers -->
                                    <template x-for="equipment in visibleEquipment" :key="equipment.id + equipment.type">
                                        <div 
                                            class="absolute cursor-pointer transform -translate-x-1/2 -translate-y-1/2 transition-all duration-200 hover:scale-125"
                                            :style="`left: ${equipment.x}%; top: ${equipment.y}%;`"
                                            @click="showEquipmentPopup(equipment)"
                                        >
                                            <!-- Marker Pin -->
                                            <div class="relative">
                                                <!-- Main marker circle -->
                                                <div 
                                                    class="w-8 h-8 rounded-full border-3 border-white shadow-lg flex items-center justify-center"
                                                    :style="`background-color: ${getEquipmentColor(equipment.type)}`"
                                                >
                                                    <!-- Equipment icon -->
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/>
                                                    </svg>
                                                </div>
                                                
                                                <!-- Status indicator -->
                                                <div 
                                                    class="absolute -top-1 -right-1 w-3 h-3 rounded-full border-2 border-white"
                                                    :style="`background-color: ${getStatusColor(equipment.status)}`"
                                                ></div>
                                                
                                                <!-- Pulse animation for critical status -->
                                                <div 
                                                    x-show="equipment.status === 'rusak'"
                                                    class="absolute inset-0 rounded-full animate-ping opacity-75"
                                                    :style="`background-color: ${getEquipmentColor(equipment.type)}`"
                                                ></div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Zoom Controls -->
                                <div class="absolute bottom-4 right-4 flex flex-col space-y-2">
                                    <button 
                                        @click="zoomIn"
                                        class="bg-white rounded-lg shadow-md p-2 hover:bg-gray-50"
                                    >
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                    </button>
                                    <button 
                                        @click="zoomOut"
                                        class="bg-white rounded-lg shadow-md p-2 hover:bg-gray-50"
                                    >
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                    </button>
                                    <button 
                                        @click="resetZoom"
                                        class="bg-white rounded-lg shadow-md p-2 hover:bg-gray-50"
                                    >
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Denah Belum Tersedia</h3>
                                <p class="mt-1 text-sm text-gray-500">Silakan hubungi admin untuk mengunggah denah unit Anda.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Equipment Detail Popup Modal -->
        <div 
            x-show="selectedEquipment"
            x-cloak
            @click.away="selectedEquipment = null"
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
        >
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50"></div>
                
                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                    <button 
                        @click="selectedEquipment = null"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    <template x-if="selectedEquipment">
                        <div>
                            <div class="flex items-center mb-4">
                                <div 
                                    class="w-12 h-12 rounded-full flex items-center justify-center mr-4"
                                    :style="`background-color: ${getEquipmentColor(selectedEquipment.type)}`"
                                >
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2a8 8 0 100 16 8 8 0 000-16z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold" x-text="selectedEquipment.name"></h3>
                                    <p class="text-sm text-gray-500" x-text="getEquipmentTypeLabel(selectedEquipment.type)"></p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Serial Number</label>
                                    <p class="text-gray-900" x-text="selectedEquipment.serial_no"></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Lokasi</label>
                                    <p class="text-gray-900" x-text="selectedEquipment.location || '-'"></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Status</label>
                                    <div class="flex items-center mt-1">
                                        <span 
                                            class="w-3 h-3 rounded-full mr-2"
                                            :style="`background-color: ${getStatusColor(selectedEquipment.status)}`"
                                        ></span>
                                        <span class="capitalize" x-text="selectedEquipment.status"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex space-x-3">
                                <a 
                                    :href="selectedEquipment.url"
                                    class="flex-1 bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition"
                                >
                                    Lihat Detail
                                </a>
                                <button 
                                    @click="selectedEquipment = null"
                                    class="flex-1 bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300 transition"
                                >
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function floorPlanApp() {
            return {
                allEquipment: [],
                visibleEquipment: [],
                selectedEquipment: null,
                searchQuery: '',
                filters: {
                    apar: { enabled: true, label: 'APAR', color: '#EF4444' },
                    apat: { enabled: true, label: 'APAT', color: '#3B82F6' },
                    fire_alarm: { enabled: true, label: 'Fire Alarm', color: '#F97316' },
                    box_hydrant: { enabled: true, label: 'Box Hydrant', color: '#06B6D4' },
                    rumah_pompa: { enabled: true, label: 'Rumah Pompa', color: '#8B5CF6' },
                    apab: { enabled: true, label: 'APAB', color: '#10B981' },
                    p3k: { enabled: true, label: 'P3K', color: '#EC4899' }
                },
                panzoom: null,

                init() {
                    this.loadEquipmentData();
                    this.initPanzoom();
                },

                async loadEquipmentData() {
                    try {
                        const response = await fetch('{{ route("floor-plan.equipment-data", $floorPlan->id ?? 0) }}');
                        const data = await response.json();
                        this.allEquipment = data.equipment;
                        this.updateVisibleMarkers();
                    } catch (error) {
                        console.error('Failed to load equipment data:', error);
                    }
                },

                initPanzoom() {
                    const container = document.getElementById('floor-plan-container');
                    if (container) {
                        this.panzoom = Panzoom(container, {
                            maxScale: 5,
                            minScale: 0.5,
                            contain: 'outside'
                        });
                    }
                },

                updateVisibleMarkers() {
                    this.visibleEquipment = this.allEquipment.filter(equipment => {
                        const filterEnabled = this.filters[equipment.type]?.enabled;
                        const matchesSearch = this.searchQuery === '' || 
                            equipment.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            equipment.serial_no.toLowerCase().includes(this.searchQuery.toLowerCase());
                        
                        return filterEnabled && matchesSearch;
                    });
                },

                filterEquipment() {
                    this.updateVisibleMarkers();
                },

                getEquipmentCount(type) {
                    return this.allEquipment.filter(e => e.type === type).length;
                },

                getEquipmentColor(type) {
                    return this.filters[type]?.color || '#6B7280';
                },

                getStatusColor(status) {
                    const colors = {
                        'baik': '#10B981',
                        'rusak': '#EF4444',
                        'perbaikan': '#F59E0B',
                        'perlu_pengecekan': '#F59E0B'
                    };
                    return colors[status?.toLowerCase()] || '#9CA3AF';
                },

                getEquipmentTypeLabel(type) {
                    return this.filters[type]?.label || type;
                },

                showEquipmentPopup(equipment) {
                    this.selectedEquipment = equipment;
                },

                zoomIn() {
                    if (this.panzoom) {
                        this.panzoom.zoomIn();
                    }
                },

                zoomOut() {
                    if (this.panzoom) {
                        this.panzoom.zoomOut();
                    }
                },

                resetZoom() {
                    if (this.panzoom) {
                        this.panzoom.reset();
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
```

## Data Models

### FloorPlan

```php
{
    id: integer,
    unit_id: integer,
    name: string,
    image_path: string,
    width: integer,
    height: integer,
    description: text,
    is_active: boolean,
    created_at: timestamp,
    updated_at: timestamp
}
```

### Equipment (Extended)

All equipment models will have these additional fields:

```php
{
    // ... existing fields
    floor_plan_id: integer (nullable),
    floor_plan_x: decimal(5,2) (nullable), // 0-100%
    floor_plan_y: decimal(5,2) (nullable)  // 0-100%
}
```

