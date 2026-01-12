<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\FloorPlan;
use App\Models\Apar;
use App\Models\Apat;
use App\Models\FireAlarm;
use App\Models\BoxHydrant;
use App\Models\RumahPompa;
use App\Models\Apab;
use App\Models\P3k;
use Illuminate\Http\Request;

class FloorPlanController extends Controller
{
    /**
     * Display the floor plan index for leader (same as regular user but with edit capability)
     */
    public function index()
    {
        // Get the current user's unit
        $unit = auth()->user()->unit;

        if (!$unit) {
            return view('leader.floor-plans.index', ['floorPlans' => collect()]);
        }

        // Get all floor plans for this unit
        $floorPlans = FloorPlan::where('unit_id', $unit->id)->get();

        return view('leader.floor-plans.index', compact('floorPlans'));
    }

    /**
     * Show equipment placement page for a floor plan (for leader's unit only)
     */
    public function placement(FloorPlan $floorPlan)
    {
        // Verify leader has access to this floor plan's unit
        $user = auth()->user();
        if ($user->unit_id !== $floorPlan->unit_id && !$user->hasRole('superadmin')) {
            abort(403, 'Anda tidak memiliki akses ke denah ini.');
        }

        // Get all equipment for this unit
        $unitId = $floorPlan->unit_id;

        $equipment = [
            'apar' => Apar::where('unit_id', $unitId)->get()->toArray(),
            'apat' => Apat::where('unit_id', $unitId)->get()->toArray(),
            'fire_alarm' => FireAlarm::where('unit_id', $unitId)->get()->toArray(),
            'box_hydrant' => BoxHydrant::where('unit_id', $unitId)->get()->toArray(),
            'rumah_pompa' => RumahPompa::where('unit_id', $unitId)->get()->toArray(),
            'apab' => Apab::where('unit_id', $unitId)->get()->toArray(),
            'p3k' => P3k::where('unit_id', $unitId)->get()->toArray(),
        ];

        // Get already placed equipment - query directly from database
        $placedEquipment = [
            'apar' => Apar::where('floor_plan_id', $floorPlan->id)->whereNotNull('floor_plan_x')->get()->toArray(),
            'apat' => Apat::where('floor_plan_id', $floorPlan->id)->whereNotNull('floor_plan_x')->get()->toArray(),
            'fire_alarm' => FireAlarm::where('floor_plan_id', $floorPlan->id)->whereNotNull('floor_plan_x')->get()->toArray(),
            'box_hydrant' => BoxHydrant::where('floor_plan_id', $floorPlan->id)->whereNotNull('floor_plan_x')->get()->toArray(),
            'rumah_pompa' => RumahPompa::where('floor_plan_id', $floorPlan->id)->whereNotNull('floor_plan_x')->get()->toArray(),
            'apab' => Apab::where('floor_plan_id', $floorPlan->id)->whereNotNull('floor_plan_x')->get()->toArray(),
            'p3k' => P3k::where('floor_plan_id', $floorPlan->id)->whereNotNull('floor_plan_x')->get()->toArray(),
        ];

        return view('leader.floor-plans.placement', compact('floorPlan', 'equipment', 'placedEquipment'));
    }

    /**
     * Save equipment placement via AJAX
     */
    public function savePlacement(Request $request, FloorPlan $floorPlan)
    {
        // Verify leader has access to this floor plan's unit
        $user = auth()->user();
        if ($user->unit_id !== $floorPlan->unit_id && !$user->hasRole('superadmin')) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $request->validate([
            'equipment_type' => 'required|string|in:apar,apat,fire_alarm,box_hydrant,rumah_pompa,apab,p3k',
            'equipment_id' => 'required|integer',
            'x' => 'required|numeric|min:0|max:100',
            'y' => 'required|numeric|min:0|max:100',
        ]);

        $modelClass = $this->getModelClass($request->equipment_type);

        if (!$modelClass) {
            return response()->json(['success' => false, 'message' => 'Invalid equipment type'], 400);
        }

        $equipment = $modelClass::findOrFail($request->equipment_id);

        // Verify equipment belongs to the same unit
        if ($equipment->unit_id !== $floorPlan->unit_id) {
            return response()->json(['success' => false, 'message' => 'Equipment tidak sesuai unit'], 400);
        }

        $equipment->update([
            'floor_plan_id' => $floorPlan->id,
            'floor_plan_x' => $request->x,
            'floor_plan_y' => $request->y,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Posisi berhasil disimpan'
        ]);
    }

    /**
     * Remove equipment from floor plan
     */
    public function removePlacement(Request $request, FloorPlan $floorPlan)
    {
        // Verify leader has access to this floor plan's unit
        $user = auth()->user();
        if ($user->unit_id !== $floorPlan->unit_id && !$user->hasRole('superadmin')) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $request->validate([
            'equipment_type' => 'required|string|in:apar,apat,fire_alarm,box_hydrant,rumah_pompa,apab,p3k',
            'equipment_id' => 'required|integer',
        ]);

        $modelClass = $this->getModelClass($request->equipment_type);

        if (!$modelClass) {
            return response()->json(['success' => false, 'message' => 'Invalid equipment type'], 400);
        }

        $equipment = $modelClass::findOrFail($request->equipment_id);

        $equipment->update([
            'floor_plan_id' => null,
            'floor_plan_x' => null,
            'floor_plan_y' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Peralatan berhasil dihapus dari denah'
        ]);
    }

    /**
     * Get model class by equipment type
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
}
