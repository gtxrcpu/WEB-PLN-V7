<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FloorPlan;
use App\Models\Unit;
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
    public function index()
    {
        $floorPlans = FloorPlan::with('unit')->latest()->get();
        return view('admin.floor-plans.index', compact('floorPlans'));
    }

    public function create()
    {
        $units = Unit::where('is_active', true)->get();
        return view('admin.floor-plans.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:10240',
            'description' => 'nullable|string'
        ]);

        // Simpan langsung ke public/floor-plans (tanpa symlink)
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('floor-plans'), $filename);
        $path = 'floor-plans/' . $filename;
        
        // Get image dimensions
        $imagePath = public_path($path);
        list($width, $height) = getimagesize($imagePath);

        FloorPlan::create([
            'unit_id' => $request->unit_id,
            'name' => $request->name,
            'image_path' => $path,
            'width' => $width,
            'height' => $height,
            'description' => $request->description,
            'is_active' => true
        ]);

        return redirect()->route('admin.floor-plans.index')
            ->with('success', 'Floor plan berhasil diunggah');
    }

    public function edit(FloorPlan $floorPlan)
    {
        $units = Unit::where('is_active', true)->get();
        return view('admin.floor-plans.edit', compact('floorPlan', 'units'));
    }

    public function update(Request $request, FloorPlan $floorPlan)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:10240',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $data = [
            'unit_id' => $request->unit_id,
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ];

        // Handle image upload if new image provided
        if ($request->hasFile('image')) {
            // Delete old image from public folder
            if ($floorPlan->image_path && file_exists(public_path($floorPlan->image_path))) {
                unlink(public_path($floorPlan->image_path));
            }

            // Simpan langsung ke public/floor-plans
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('floor-plans'), $filename);
            $path = 'floor-plans/' . $filename;
            
            // Get image dimensions
            $imagePath = public_path($path);
            list($width, $height) = getimagesize($imagePath);

            $data['image_path'] = $path;
            $data['width'] = $width;
            $data['height'] = $height;
        }

        $floorPlan->update($data);

        return redirect()->route('admin.floor-plans.index')
            ->with('success', 'Floor plan berhasil diperbarui');
    }

    public function destroy(FloorPlan $floorPlan)
    {
        // Delete image file from public folder
        if ($floorPlan->image_path && file_exists(public_path($floorPlan->image_path))) {
            unlink(public_path($floorPlan->image_path));
        }

        $floorPlan->delete();

        return redirect()->route('admin.floor-plans.index')
            ->with('success', 'Floor plan berhasil dihapus');
    }

    /**
     * Show equipment placement page for a floor plan
     */
    public function placement(FloorPlan $floorPlan)
    {
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

        return view('admin.floor-plans.placement', compact('floorPlan', 'equipment', 'placedEquipment'));
    }

    /**
     * Save equipment placement via AJAX
     */
    public function savePlacement(Request $request, FloorPlan $floorPlan)
    {
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
