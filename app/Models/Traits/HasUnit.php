<?php

namespace App\Models\Traits;

use App\Models\Unit;

trait HasUnit
{
    /**
     * Relasi ke unit
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Scope untuk filter by unit
     */
    public function scopeForUnit($query, $unitId)
    {
        if ($unitId) {
            return $query->where('unit_id', $unitId);
        }
        return $query;
    }

    /**
     * Scope untuk user yang punya unit
     */
    public function scopeForAuthUser($query)
    {
        $user = auth()->user();
        
        if ($user && $user->unit_id) {
            return $query->where('unit_id', $user->unit_id);
        }
        
        return $query;
    }
}
