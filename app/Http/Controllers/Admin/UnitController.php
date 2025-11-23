<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::withCount(['users', 'petugas'])->get();
        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        return view('admin.units.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:units,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        Unit::create($request->all());

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit berhasil ditambahkan');
    }

    public function edit(Unit $unit)
    {
        return view('admin.units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:units,code,' . $unit->id,
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $unit->update($request->all());

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit berhasil diperbarui');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->users()->count() > 0) {
            return back()->with('error', 'Unit tidak bisa dihapus karena masih ada user yang terdaftar');
        }

        $unit->delete();

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit berhasil dihapus');
    }
}
