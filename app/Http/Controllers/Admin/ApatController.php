<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apat;
use Illuminate\Http\Request;

class ApatController extends Controller
{
    public function index(Request $request)
    {
        $query = Apat::with(['kartuApats']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('barcode', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('serial_no', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $apats = $query->latest()->get();
        
        return view('admin.apat.index', compact('apats'));
    }

    public function create()
    {
        $nextSerial = Apat::generateNextSerial();
        return view('admin.apat.create', compact('nextSerial'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'lokasi' => 'required|string|max:50',
            'jenis' => 'required|string|max:100',
            'kapasitas' => 'required|string|max:100',
            'status' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $serial = Apat::generateNextSerial();
        $barcode = 'APAT ' . $serial;

        $apat = Apat::create([
            'user_id' => auth()->id(),
            'name' => 'APAT ' . $serial,
            'barcode' => $barcode,
            'serial_no' => $serial,
            'lokasi' => $data['lokasi'],
            'jenis' => $data['jenis'],
            'kapasitas' => $data['kapasitas'],
            'status' => $data['status'],
            'notes' => $data['notes'],
        ]);

        $apat->generateQrSvg(true);

        return redirect()
            ->route('admin.apat.index')
            ->with('success', 'APAT berhasil ditambahkan');
    }

    public function show(Apat $apat)
    {
        $apat->load(['kartuApats.signature', 'kartuApats.approver']);
        return view('admin.apat.show', compact('apat'));
    }

    public function edit(Apat $apat)
    {
        return view('admin.apat.edit', compact('apat'));
    }

    public function update(Request $request, Apat $apat)
    {
        $data = $request->validate([
            'lokasi' => 'required|string|max:50',
            'jenis' => 'required|string|max:100',
            'kapasitas' => 'required|string|max:100',
            'status' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $apat->update($data);

        return redirect()
            ->route('admin.apat.index')
            ->with('success', 'APAT berhasil diupdate');
    }

    public function destroy(Apat $apat)
    {
        $apat->delete();

        return redirect()
            ->route('admin.apat.index')
            ->with('success', 'APAT berhasil dihapus');
    }
}
