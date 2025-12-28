<?php

namespace App\Http\Controllers;

use App\Models\FloorPlan;
use App\Models\Apar;
use App\Models\Apat;
use App\Models\FireAlarm;
use App\Models\BoxHydrant;
use App\Models\RumahPompa;
use App\Models\Apab;
use App\Models\P3k;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FloorPlanController extends Controller
{
    /**
     * Display the floor plan view for the current user's unit
     */
    public function index()
    {
        // Get the current user's unit
        $unit = auth()->user()->unit;
        
        if (!$unit) {
            return view('floor-plan.index', ['floorPlan' => null]);
        }
        
        // Get the active floor plan for this unit
        $floorPlan = FloorPlan::where('unit_id', $unit->id)
            ->where('is_active', true)
            ->first();
            
        return view('floor-plan.index', compact('floorPlan'));
    }

    /**
     * Get equipment data as JSON for the floor plan
     */
    public function getEquipmentData(FloorPlan $floorPlan)
    {
        // Get all equipment for this floor plan
        $equipment = $floorPlan->getAllEquipment();
        
        // Format equipment data for map display
        $formattedEquipment = $this->formatEquipmentForMap($equipment);
        
        return response()->json([
            'equipment' => $formattedEquipment
        ]);
    }

    /**
     * Upload a new floor plan image
     */
    public function uploadFloorPlan(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:10240',
            'description' => 'nullable|string'
        ]);

        // Store the uploaded image
        $path = $request->file('image')->store('floor-plans', 'public');
        
        // Get image dimensions
        $imagePath = storage_path('app/public/' . $path);
        list($width, $height) = getimagesize($imagePath);

        // Create the floor plan record
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

    /**
     * Update equipment coordinates on the floor plan
     */
    public function updateEquipmentCoordinates(Request $request)
    {
        $request->validate([
            'equipment_type' => 'required|string',
            'equipment_id' => 'required|integer',
            'floor_plan_id' => 'required|exists:floor_plans,id',
            'x' => 'required|numeric|min:0|max:100',
            'y' => 'required|numeric|min:0|max:100'
        ]);

        // Get the model class for the equipment type
        $modelClass = $this->getModelClass($request->equipment_type);
        
        if (!$modelClass) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid equipment type'
            ], 400);
        }
        
        // Find the equipment
        $equipment = $modelClass::findOrFail($request->equipment_id);
        
        // Update the coordinates
        $equipment->update([
            'floor_plan_id' => $request->floor_plan_id,
            'floor_plan_x' => $request->x,
            'floor_plan_y' => $request->y
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Equipment coordinates updated successfully'
        ]);
    }

    /**
     * Format equipment data for map display
     * 
     * @param array $equipment Array of equipment grouped by type
     * @return array Formatted equipment array
     */
    private function formatEquipmentForMap($equipment)
    {
        $formatted = [];
        
        foreach ($equipment as $type => $items) {
            foreach ($items as $item) {
                $formatted[] = [
                    'id' => $item->id,
                    'type' => $type,
                    'name' => $item->name ?? $item->barcode ?? $item->serial_no,
                    'serial_no' => $item->serial_no ?? $item->barcode,
                    'status' => $item->status ?? 'unknown',
                    'x' => (float) $item->floor_plan_x,
                    'y' => (float) $item->floor_plan_y,
                    'location' => $item->location_code ?? $item->lokasi ?? '-',
                    'url' => $this->getEquipmentUrl($type, $item->id)
                ];
            }
        }
        
        return $formatted;
    }

    /**
     * Get the model class for a given equipment type
     * 
     * @param string $type Equipment type
     * @return string|null Model class name or null if invalid
     */
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

    /**
     * Get the URL for viewing equipment details
     * 
     * @param string $type Equipment type
     * @param int $id Equipment ID
     * @return string URL to equipment details page
     */
    private function getEquipmentUrl($type, $id)
    {
        $routes = [
            'apar' => 'apar.riwayat',
            'apat' => 'apat.riwayat',
            'fire_alarm' => 'fire-alarm.riwayat',
            'box_hydrant' => 'box-hydrant.riwayat',
            'rumah_pompa' => 'rumah-pompa.riwayat',
            'apab' => 'apab.riwayat',
            'p3k' => 'p3k.riwayat',
        ];

        $routeName = $routes[$type] ?? null;
        
        if ($routeName && \Route::has($routeName)) {
            return route($routeName, $id);
        }
        
        return '#';
    }
}
