<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\P3k;
use Illuminate\Http\Request;

class P3kController extends Controller
{
    public function index(Request $request)
    {
        $query = P3k::with(['kartuP3ks']);
        
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
        
        $p3ks = $query->latest()->get();
        
        return view('admin.p3k.index', compact('p3ks'));
    }

    public function create()
    {
        return view('admin.p3k.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'location_code' => 'required|string|max:50',
            'type' => 'required|string|max:100',
            'status' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $serial = P3k::generateNextSerial();
        $barcode = 'P3K ' . $serial;

        $p3k = P3k::create([
            'user_id' => auth()->id(),
            'name' => 'P3K ' . $serial,
            'barcode' => $barcode,
            'serial_no' => $serial,
            'location_code' => $data['location_code'],
            'type' => $data['type'],
            'status' => $data['status'],
            'notes' => $data['notes'],
        ]);

        $p3k->generateQrSvg(true);

        return redirect()
            ->route('admin.p3k.index')
            ->with('success', 'P3K berhasil ditambahkan');
    }

    public function show(P3k $p3k)
    {
        $p3k->load(['kartuP3ks.signature', 'kartuP3ks.approver']);
        return view('admin.p3k.show', compact('p3k'));
    }

    public function edit(P3k $p3k)
    {
        return view('admin.p3k.edit', compact('p3k'));
    }

    public function update(Request $request, P3k $p3k)
    {
        $data = $request->validate([
            'location_code' => 'required|string|max:50',
            'type' => 'required|string|max:100',
            'status' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $p3k->update($data);

        return redirect()
            ->route('admin.p3k.index')
            ->with('success', 'P3K berhasil diupdate');
    }

    public function destroy(P3k $p3k)
    {
        $p3k->delete();

        return redirect()
            ->route('admin.p3k.index')
            ->with('success', 'P3K berhasil dihapus');
    }
}
