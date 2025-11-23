<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitSwitchController extends Controller
{
    /**
     * Switch ke unit tertentu (untuk admin)
     */
    public function switch(Request $request)
    {
        $unitId = $request->input('unit_id');
        
        // Validasi unit exists
        if ($unitId && !Unit::find($unitId)) {
            return back()->with('error', 'Unit tidak ditemukan');
        }
        
        // Simpan ke session
        if ($unitId) {
            session(['viewing_unit_id' => $unitId]);
            $unit = Unit::find($unitId);
            $message = 'Sekarang melihat data unit: ' . $unit->code;
        } else {
            session()->forget('viewing_unit_id');
            $message = 'Sekarang melihat semua unit';
        }
        
        return back()->with('success', $message);
    }
    
    /**
     * Clear unit filter (kembali ke view all)
     */
    public function clear()
    {
        session()->forget('viewing_unit_id');
        return back()->with('success', 'Sekarang melihat semua unit');
    }
}
