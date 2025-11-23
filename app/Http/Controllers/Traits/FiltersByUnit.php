<?php

namespace App\Http\Controllers\Traits;

trait FiltersByUnit
{
    /**
     * Get query dengan filter unit untuk user yang login
     */
    protected function getQueryForAuthUser($model)
    {
        $user = auth()->user();
        
        $query = $model::query();
        
        // Jika user punya unit, filter berdasarkan unit mereka
        if ($user && $user->unit_id) {
            $query->forUnit($user->unit_id);
        }
        // Jika admin dan sedang viewing unit tertentu (dari session)
        elseif ($user && !$user->unit_id && session('viewing_unit_id')) {
            $query->forUnit(session('viewing_unit_id'));
        }
        // Jika admin dan tidak viewing unit tertentu, tampilkan semua
        
        return $query;
    }

    /**
     * Get unit_id untuk user yang login (untuk auto-assign saat create)
     */
    protected function getAuthUserUnitId()
    {
        $user = auth()->user();
        
        // Jika user punya unit, gunakan unit mereka
        if ($user && $user->unit_id) {
            return $user->unit_id;
        }
        
        // Jika admin dan sedang viewing unit tertentu, gunakan unit yang sedang dilihat
        if ($user && !$user->unit_id && session('viewing_unit_id')) {
            return session('viewing_unit_id');
        }
        
        return null;
    }
    
    /**
     * Get current viewing unit (untuk display)
     */
    protected function getCurrentViewingUnit()
    {
        $user = auth()->user();
        
        if ($user && $user->unit_id) {
            return $user->unit;
        }
        
        if ($user && !$user->unit_id && session('viewing_unit_id')) {
            return \App\Models\Unit::find(session('viewing_unit_id'));
        }
        
        return null;
    }
}
