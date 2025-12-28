<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RumahPompa;
use Illuminate\Http\Request;

class RumahPompaController extends Controller
{
    public function index(Request $request)
    {
        $query = RumahPompa::with(['kartuRumahPompas']);
        
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
        
        $rumahPompas = $query->latest()->get();
        
        return view('admin.rumah-pompa.index', compact('rumahPompas'));
    }

    public function create()
    {
        $nextSerial = RumahPompa::generateNextSerial();
        return view('admin.rumah-pompa.create', compact('nextSerial'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'location_code' => 'required|string|max:50',
            'type' => 'required|string|max:100',
            'status' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $serial = RumahPompa::generateNextSerial();
        $barcode = 'RP ' . $serial;

        $rumahPompa = RumahPompa::create([
            'user_id' => auth()->id(),
            'name' => 'Rumah Pompa ' . $serial,
            'barcode' => $barcode,
            'serial_no' => $serial,
            'location_code' => $data['location_code'],
            'type' => $data['type'],
            'status' => $data['status'],
            'notes' => $data['notes'],
        ]);

        $rumahPompa->generateQrSvg(true);

        return redirect()
            ->route('admin.rumah-pompa.index')
            ->with('success', 'Rumah Pompa berhasil ditambahkan');
    }

    public function show(RumahPompa $rumahPompa)
    {
        $rumahPompa->load(['kartuRumahPompas.signature', 'kartuRumahPompas.approver']);
        return view('admin.rumah-pompa.show', compact('rumahPompa'));
    }

    public function edit(RumahPompa $rumahPompa)
    {
        return view('admin.rumah-pompa.edit', compact('rumahPompa'));
    }

    public function update(Request $request, RumahPompa $rumahPompa)
    {
        $data = $request->validate([
            'location_code' => 'required|string|max:50',
            'type' => 'required|string|max:100',
            'status' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $rumahPompa->update($data);

        return redirect()
            ->route('admin.rumah-pompa.index')
            ->with('success', 'Rumah Pompa berhasil diupdate');
    }

    public function destroy(RumahPompa $rumahPompa)
    {
        $rumahPompa->delete();

        return redirect()
            ->route('admin.rumah-pompa.index')
            ->with('success', 'Rumah Pompa berhasil dihapus');
    }
}
