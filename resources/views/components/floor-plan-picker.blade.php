{{-- Floor Plan Coordinate Picker Component --}}
@props(['floorPlanId' => null, 'floorPlanX' => null, 'floorPlanY' => null, 'equipmentType' => 'equipment'])

@php
    $user = auth()->user();
    $floorPlans = \App\Models\FloorPlan::where('unit_id', $user->unit_id)
        ->where('is_active', true)
        ->get();
@endphp

<div x-data="floorPlanPicker({
    floorPlanId: {{ $floorPlanId ? "'{$floorPlanId}'" : 'null' }},
    floorPlanX: {{ $floorPlanX ?? 'null' }},
    floorPlanY: {{ $floorPlanY ?? 'null' }},
    floorPlans: {{ $floorPlans->toJson() }}
})" class="space-y-4">
    {{-- Floor Plan Selection --}}
    <div>
        <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
            </svg>
            Denah Lokasi
            <span class="text-xs text-slate-500 font-normal">(opsional)</span>
        </label>
        <select name="floor_plan_id" x-model="selectedFloorPlanId" @change="onFloorPlanChange"
                class="block w-full rounded-xl border-2 border-slate-200 text-slate-900 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
            <option value="">-- Pilih Denah (Opsional) --</option>
            <template x-for="plan in floorPlans" :key="plan.id">
                <option :value="plan.id" x-text="plan.name"></option>
            </template>
        </select>
        <p class="mt-2 text-xs text-slate-500 flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Pilih denah untuk menentukan posisi peralatan pada peta
        </p>
    </div>

    {{-- Coordinate Inputs --}}
    <div x-show="selectedFloorPlanId" x-transition class="grid md:grid-cols-2 gap-6">
        <div>
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                </svg>
                Koordinat X (%)
            </label>
            <input type="number" name="floor_plan_x" x-model="coordinateX" 
                   min="0" max="100" step="0.01"
                   placeholder="0-100"
                   class="block w-full rounded-xl border-2 border-slate-200 text-slate-900 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
        </div>
        <div>
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                </svg>
                Koordinat Y (%)
            </label>
            <input type="number" name="floor_plan_y" x-model="coordinateY" 
                   min="0" max="100" step="0.01"
                   placeholder="0-100"
                   class="block w-full rounded-xl border-2 border-slate-200 text-slate-900 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
        </div>
    </div>

    {{-- Floor Plan Preview with Click-to-Set --}}
    <div x-show="selectedFloorPlanId && selectedFloorPlan" x-transition class="mt-4">
        <label class="flex items-center gap-2 text-sm font-semibold text-slate-900 mb-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Preview Denah - Klik untuk Set Posisi
        </label>
        <div class="relative bg-slate-100 rounded-xl overflow-hidden border-2 border-slate-200" 
             style="height: 400px;">
            <div class="relative w-full h-full cursor-crosshair" 
                 @click="setCoordinatesFromClick($event)"
                 id="floor-plan-preview">
                <img :src="selectedFloorPlan ? selectedFloorPlan.image_url : ''" 
                     :alt="selectedFloorPlan ? selectedFloorPlan.name : ''"
                     class="w-full h-full object-contain pointer-events-none"
                     @load="onImageLoad">
                
                {{-- Current Marker Position --}}
                <div x-show="coordinateX !== null && coordinateY !== null"
                     class="absolute transform -translate-x-1/2 -translate-y-1/2 pointer-events-none"
                     :style="`left: ${coordinateX}%; top: ${coordinateY}%;`">
                    {{-- Marker Pin --}}
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full border-4 border-white shadow-lg flex items-center justify-center bg-blue-600 animate-pulse">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        {{-- Pulse animation --}}
                        <div class="absolute inset-0 rounded-full bg-blue-600 opacity-75 animate-ping"></div>
                    </div>
                </div>
            </div>
        </div>
        <p class="mt-2 text-xs text-slate-500 flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
            </svg>
            Klik pada denah untuk menentukan posisi peralatan. Koordinat akan otomatis terisi.
        </p>
    </div>
</div>

@push('scripts')
<script>
function floorPlanPicker(config) {
    return {
        floorPlans: config.floorPlans || [],
        selectedFloorPlanId: config.floorPlanId,
        selectedFloorPlan: null,
        coordinateX: config.floorPlanX,
        coordinateY: config.floorPlanY,
        imageElement: null,

        init() {
            if (this.selectedFloorPlanId) {
                this.selectedFloorPlan = this.floorPlans.find(p => p.id == this.selectedFloorPlanId);
            }
        },

        onFloorPlanChange() {
            if (this.selectedFloorPlanId) {
                this.selectedFloorPlan = this.floorPlans.find(p => p.id == this.selectedFloorPlanId);
            } else {
                this.selectedFloorPlan = null;
                this.coordinateX = null;
                this.coordinateY = null;
            }
        },

        onImageLoad(event) {
            this.imageElement = event.target;
        },

        setCoordinatesFromClick(event) {
            const container = event.currentTarget;
            const rect = container.getBoundingClientRect();
            
            // Get click position relative to container
            const clickX = event.clientX - rect.left;
            const clickY = event.clientY - rect.top;
            
            // Convert to percentage (0-100)
            const percentX = (clickX / rect.width) * 100;
            const percentY = (clickY / rect.height) * 100;
            
            // Clamp values between 0 and 100
            this.coordinateX = Math.max(0, Math.min(100, percentX)).toFixed(2);
            this.coordinateY = Math.max(0, Math.min(100, percentY)).toFixed(2);
        }
    }
}
</script>
@endpush
