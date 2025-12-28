<?php

namespace App\Http\Controllers;

use App\Models\FireAlarm;
use Illuminate\Http\Request;

class FireAlarmController extends Controller
{
    /**
     * List semua Fire Alarm.
     */
    public function index()
    {
        $fireAlarms = FireAlarm::orderBy('id')->get();

        return view('fire-alarm.index', compact('fireAlarms'));
    }

    /**
     * Tampilkan form tambah Fire Alarm.
     */
    public function create()
    {
        return view('fire-alarm.create');
    }

    /**
     * Simpan Fire Alarm baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'barcode'       => ['required', 'string', 'max:255', 'unique:fire_alarms,barcode'],
            'serial_no'     => ['required', 'string', 'max:255', 'unique:fire_alarms,serial_no'],
            'location_code' => ['nullable', 'string', 'max:255'],
            'type'          => ['nullable', 'string', 'max:255'],
            'zone'          => ['nullable', 'string', 'max:255'],
            'status'        => ['nullable', 'string', 'max:50'],
            'notes'         => ['nullable', 'string'],
        ]);

        $fireAlarm = new FireAlarm();
        $fireAlarm->user_id       = auth()->id();
        $fireAlarm->name          = $data['name'];
        $fireAlarm->barcode       = $data['barcode'];
        $fireAlarm->serial_no     = $data['serial_no'];
        $fireAlarm->location_code = $data['location_code'] ?? null;
        $fireAlarm->type          = $data['type'] ?? null;
        $fireAlarm->zone          = $data['zone'] ?? null;
        $fireAlarm->status        = $data['status'] ?? null;
        $fireAlarm->notes         = $data['notes'] ?? null;
        $fireAlarm->save();

        // Generate QR Code
        $fireAlarm->refreshQrSvg();

        return redirect()
            ->route('fire-alarm.index')
            ->with('success', 'Fire Alarm baru berhasil ditambahkan dengan barcode ' . $fireAlarm->barcode);
    }

    /**
     * Tampilkan form edit Fire Alarm.
     */
    public function edit(FireAlarm $fireAlarm)
    {
        return view('fire-alarm.edit', compact('fireAlarm'));
    }

    /**
     * Update Fire Alarm yang sudah ada.
     */
    public function update(Request $request, FireAlarm $fireAlarm)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'location_code' => ['nullable', 'string', 'max:255'],
            'type'          => ['nullable', 'string', 'max:255'],
            'zone'          => ['nullable', 'string', 'max:255'],
            'status'        => ['nullable', 'string', 'max:50'],
            'notes'         => ['nullable', 'string'],
        ]);

        $fireAlarm->name          = $data['name'];
        $fireAlarm->location_code = $data['location_code'] ?? null;
        $fireAlarm->type          = $data['type'] ?? null;
        $fireAlarm->zone          = $data['zone'] ?? null;
        $fireAlarm->status        = $data['status'] ?? null;
        $fireAlarm->notes         = $data['notes'] ?? null;
        $fireAlarm->save();

        return redirect()
            ->route('fire-alarm.index')
            ->with('success', 'Fire Alarm ' . $fireAlarm->serial_no . ' berhasil diperbarui');
    }

    public function riwayat(Request $request, FireAlarm $fireAlarm)
    {
        $query = $fireAlarm->kartuInspeksi()->with(['user', 'approver', 'signature']);
        
        // Filter by creator
        if ($request->filled('creator')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->creator . '%');
            });
        }
        
        // Filter by approver
        if ($request->filled('approver')) {
            $query->whereHas('approver', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->approver . '%');
            });
        }
        
        // Filter by approval status
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->whereNotNull('approved_at');
            } elseif ($request->status === 'pending') {
                $query->whereNull('approved_at');
            }
        }
        
        $riwayatInspeksi = $query->orderBy('tgl_periksa', 'desc')->get();
        
        return view('fire-alarm.riwayat', compact('fireAlarm', 'riwayatInspeksi'));
    }
}
