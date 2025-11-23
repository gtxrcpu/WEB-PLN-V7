<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FireAlarm;
use Illuminate\Http\Request;

class FireAlarmController extends Controller
{
    public function index(Request $request)
    {
        $query = FireAlarm::with(['kartuFireAlarms']);
        
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
        
        $fireAlarms = $query->latest()->get();
        
        return view('admin.fire-alarm.index', compact('fireAlarms'));
    }

    public function create()
    {
        return view('admin.fire-alarm.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'location_code' => 'required|string|max:50',
            'type' => 'required|string|max:100',
            'status' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $serial = FireAlarm::generateNextSerial();
        $barcode = 'FA ' . $serial;

        $fireAlarm = FireAlarm::create([
            'user_id' => auth()->id(),
            'name' => 'Fire Alarm ' . $serial,
            'barcode' => $barcode,
            'serial_no' => $serial,
            'location_code' => $data['location_code'],
            'type' => $data['type'],
            'status' => $data['status'],
            'notes' => $data['notes'],
        ]);

        $fireAlarm->generateQrSvg(true);

        return redirect()
            ->route('admin.fire-alarm.index')
            ->with('success', 'Fire Alarm berhasil ditambahkan');
    }

    public function show(FireAlarm $fireAlarm)
    {
        $fireAlarm->load(['kartuFireAlarms.signature', 'kartuFireAlarms.approver']);
        return view('admin.fire-alarm.show', compact('fireAlarm'));
    }

    public function edit(FireAlarm $fireAlarm)
    {
        return view('admin.fire-alarm.edit', compact('fireAlarm'));
    }

    public function update(Request $request, FireAlarm $fireAlarm)
    {
        $data = $request->validate([
            'location_code' => 'required|string|max:50',
            'type' => 'required|string|max:100',
            'status' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $fireAlarm->update($data);

        return redirect()
            ->route('admin.fire-alarm.index')
            ->with('success', 'Fire Alarm berhasil diupdate');
    }

    public function destroy(FireAlarm $fireAlarm)
    {
        $fireAlarm->delete();

        return redirect()
            ->route('admin.fire-alarm.index')
            ->with('success', 'Fire Alarm berhasil dihapus');
    }
}
