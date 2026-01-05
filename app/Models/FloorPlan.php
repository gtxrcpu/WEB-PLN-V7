<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'width' => 'integer',
        'height' => 'integer',
    ];

    /**
     * Get the unit that owns the floor plan
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the image URL attribute
     */
    public function getImageUrlAttribute()
    {
        // Jika image_path kosong, return placeholder
        if (empty($this->image_path)) {
            return asset('images/placeholder-floor-plan.png');
        }
        
        // Jika path dimulai dengan 'storage/', gunakan asset() langsung
        if (str_starts_with($this->image_path, 'storage/')) {
            return asset($this->image_path);
        }
        
        // Jika path dimulai dengan 'floor-plans/', cek di storage
        if (str_starts_with($this->image_path, 'floor-plans/')) {
            if (Storage::disk('public')->exists($this->image_path)) {
                return asset('storage/' . $this->image_path);
            }
        }
        
        // Cek apakah file exists di public folder
        $fullPath = public_path($this->image_path);
        if (file_exists($fullPath)) {
            return asset($this->image_path) . '?v=' . filemtime($fullPath);
        }
        
        // Jika file tidak ada, return placeholder
        return asset('images/placeholder-floor-plan.png');
    }

    /**
     * Get all equipment for this floor plan
     */
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
            
        $equipment['fire_alarm'] = FireAlarm::where('floor_plan_id', $this->id)
            ->whereNotNull('floor_plan_x')
            ->whereNotNull('floor_plan_y')
            ->get();
            
        $equipment['box_hydrant'] = BoxHydrant::where('floor_plan_id', $this->id)
            ->whereNotNull('floor_plan_x')
            ->whereNotNull('floor_plan_y')
            ->get();
            
        $equipment['rumah_pompa'] = RumahPompa::where('floor_plan_id', $this->id)
            ->whereNotNull('floor_plan_x')
            ->whereNotNull('floor_plan_y')
            ->get();
            
        $equipment['apab'] = Apab::where('floor_plan_id', $this->id)
            ->whereNotNull('floor_plan_x')
            ->whereNotNull('floor_plan_y')
            ->get();
            
        $equipment['p3k'] = P3k::where('floor_plan_id', $this->id)
            ->whereNotNull('floor_plan_x')
            ->whereNotNull('floor_plan_y')
            ->get();
        
        return $equipment;
    }

    /**
     * Get all APAR equipment on this floor plan
     */
    public function apars()
    {
        return $this->hasMany(Apar::class);
    }

    /**
     * Get all APAT equipment on this floor plan
     */
    public function apats()
    {
        return $this->hasMany(Apat::class);
    }

    /**
     * Get all Fire Alarm equipment on this floor plan
     */
    public function fireAlarms()
    {
        return $this->hasMany(FireAlarm::class);
    }

    /**
     * Get all Box Hydrant equipment on this floor plan
     */
    public function boxHydrants()
    {
        return $this->hasMany(BoxHydrant::class);
    }

    /**
     * Get all Rumah Pompa equipment on this floor plan
     */
    public function rumahPompas()
    {
        return $this->hasMany(RumahPompa::class);
    }

    /**
     * Get all APAB equipment on this floor plan
     */
    public function apabs()
    {
        return $this->hasMany(Apab::class);
    }

    /**
     * Get all P3K equipment on this floor plan
     */
    public function p3ks()
    {
        return $this->hasMany(P3k::class);
    }
}
