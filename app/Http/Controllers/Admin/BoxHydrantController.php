<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoxHydrant;
use Illuminate\Http\Request;

class BoxHydrantController extends Controller
{
    public function index(Request $request)
    {
        $query = BoxHydrant::with(['kartuBoxHydrants']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('barcode', 'like', "%{$search}%")
                  ->orWhere('location_code', 'like', "%{$search}%")
                  ->orWhere('serial_no', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $boxHydrants = $query->latest()->get();
        
        return view('admin.box-hydrant.index', compact('boxHydrants'));
    }

    public function create()
    {
        $nextSerial = BoxHydrant::generateNextSerial();
        return view('admin.box-hydrant.create', compact('nextSerial'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'location_code' => 'required|string|max:50',
            'type' => 'required|string|max:100',
            'status' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $serial = BoxHydrant::generateNextSerial();
        $barcode = 'BH ' . $serial;

        $boxHydrant = BoxHydrant::create([
            'user_id' => auth()->id(),
            'name' => 'Box Hydrant ' . $serial,
            'barcode' => $barcode,
            'serial_no' => $serial,
            'location_code' => $data['location_code'],
            'type' => $data['type'],
            'status' => $data['status'],
            'notes' => $data['notes'],
        ]);

        $boxHydrant->generateQrSvg(true);

        return redirect()
            ->route('admin.box-hydrant.index')
            ->with('success', 'Box Hydrant berhasil ditambahkan');
    }

    public function show(BoxHydrant $boxHydrant)
    {
        $boxHydrant->load(['kartuBoxHydrants.signature', 'kartuBoxHydrants.approver']);
        return view('admin.box-hydrant.show', compact('boxHydrant'));
    }

    public function edit(BoxHydrant $boxHydrant)
    {
        return view('admin.box-hydrant.edit', compact('boxHydrant'));
    }

    public function update(Request $request, BoxHydrant $boxHydrant)
    {
        $data = $request->validate([
            'location_code' => 'required|string|max:50',
            'type' => 'required|string|max:100',
            'status' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $boxHydrant->update($data);

        return redirect()
            ->route('admin.box-hydrant.index')
            ->with('success', 'Box Hydrant berhasil diupdate');
    }

    public function destroy(BoxHydrant $boxHydrant)
    {
        $boxHydrant->delete();

        return redirect()
            ->route('admin.box-hydrant.index')
            ->with('success', 'Box Hydrant berhasil dihapus');
    }
}
