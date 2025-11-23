<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apar;
use Illuminate\Http\Request;

class AparController extends Controller
{
    public function index(Request $request)
    {
        $query = Apar::with(['kartuApars']);
        
        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('barcode', 'like', "%{$search}%")
                  ->orWhere('location_code', 'like', "%{$search}%")
                  ->orWhere('serial_no', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $apars = $query->latest()->get();
        
        return view('admin.apar.index', compact('apars'));
    }

    public function create()
    {
        $nextSerial = Apar::generateNextSerial();
        return view('admin.apar.create', compact('nextSerial'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'location_code' => 'required|string|max:50',
            'type' => 'required|string|max:100',
            'capacity' => 'required|string|max:100',
            'agent' => 'nullable|string|max:100',
            'status' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $serial = Apar::generateNextSerial();
        $barcode = 'APAR ' . $serial;

        $apar = Apar::create([
            'user_id' => auth()->id(),
            'name' => 'APAR ' . $serial,
            'barcode' => $barcode,
            'serial_no' => $serial,
            'location_code' => $data['location_code'],
            'type' => $data['type'],
            'capacity' => $data['capacity'],
            'agent' => $data['agent'],
            'status' => $data['status'],
            'notes' => $data['notes'],
        ]);

        $apar->generateQrSvg(true);

        return redirect()
            ->route('admin.apar.index')
            ->with('success', 'APAR berhasil ditambahkan');
    }

    public function show(Apar $apar)
    {
        $apar->load(['kartuApars.signature', 'kartuApars.approver']);
        return view('admin.apar.show', compact('apar'));
    }

    public function edit(Apar $apar)
    {
        return view('admin.apar.edit', compact('apar'));
    }

    public function update(Request $request, Apar $apar)
    {
        $data = $request->validate([
            'location_code' => 'required|string|max:50',
            'type' => 'required|string|max:100',
            'capacity' => 'required|string|max:100',
            'agent' => 'nullable|string|max:100',
            'status' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $apar->update($data);

        return redirect()
            ->route('admin.apar.index')
            ->with('success', 'APAR berhasil diupdate');
    }

    public function destroy(Apar $apar)
    {
        $apar->delete();

        return redirect()
            ->route('admin.apar.index')
            ->with('success', 'APAR berhasil dihapus');
    }
}
