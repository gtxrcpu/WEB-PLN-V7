<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apab;
use Illuminate\Http\Request;

class ApabController extends Controller
{
    public function index(Request $request)
    {
        $query = Apab::with(['kartuApabs']);
        
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
        
        $apabs = $query->latest()->get();
        
        return view('admin.apab.index', compact('apabs'));
    }

    public function create()
    {
        return view('admin.apab.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'location_code' => 'required|string|max:50',
            'type' => 'required|string|max:100',
            'capacity' => 'required|string|max:100',
            'status' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $serial = Apab::generateNextSerial();
        $barcode = 'APAB ' . $serial;

        $apab = Apab::create([
            'user_id' => auth()->id(),
            'name' => 'APAB ' . $serial,
            'barcode' => $barcode,
            'serial_no' => $serial,
            'location_code' => $data['location_code'],
            'type' => $data['type'],
            'capacity' => $data['capacity'],
            'status' => $data['status'],
            'notes' => $data['notes'],
        ]);

        $apab->generateQrSvg(true);

        return redirect()
            ->route('admin.apab.index')
            ->with('success', 'APAB berhasil ditambahkan');
    }

    public function show(Apab $apab)
    {
        $apab->load(['kartuApabs.signature', 'kartuApabs.approver']);
        return view('admin.apab.show', compact('apab'));
    }

    public function edit(Apab $apab)
    {
        return view('admin.apab.edit', compact('apab'));
    }

    public function update(Request $request, Apab $apab)
    {
        $data = $request->validate([
            'location_code' => 'required|string|max:50',
            'type' => 'required|string|max:100',
            'capacity' => 'required|string|max:100',
            'status' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $apab->update($data);

        return redirect()
            ->route('admin.apab.index')
            ->with('success', 'APAB berhasil diupdate');
    }

    public function destroy(Apab $apab)
    {
        $apab->delete();

        return redirect()
            ->route('admin.apab.index')
            ->with('success', 'APAB berhasil dihapus');
    }
}
