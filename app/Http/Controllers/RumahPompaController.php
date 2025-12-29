<?php

namespace App\Http\Controllers;

use App\Models\RumahPompa;
use Illuminate\Http\Request;

class RumahPompaController extends Controller
{
    public function index()
    {
        $rumahPompas = RumahPompa::orderBy('id')->get();
        return view('rumah-pompa.index', compact('rumahPompas'));
    }

    public function create()
    {
        return view('rumah-pompa.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'barcode'       => ['nullable', 'string', 'max:255', 'unique:rumah_pompas,barcode'],
            'serial_no'     => ['nullable', 'string', 'max:255', 'unique:rumah_pompas,serial_no'],
            'location_code' => ['nullable', 'string', 'max:255'],
            'type'          => ['nullable', 'string', 'max:255'],
            'zone'          => ['nullable', 'string', 'max:255'],
            'status'        => ['nullable', 'string', 'max:50'],
            'notes'         => ['nullable', 'string'],
        ]);

        $data['user_id'] = auth()->id();
        
        // Model akan auto-generate serial_no dan barcode via booted()
        $rumahPompa = RumahPompa::create($data);

        return redirect()
            ->route('rumah-pompa.index')
            ->with('success', 'Rumah Pompa baru berhasil ditambahkan dengan serial ' . $rumahPompa->serial_no);
    }

    public function edit(RumahPompa $rumahPompa)
    {
        return view('rumah-pompa.edit', compact('rumahPompa'));
    }

    public function update(Request $request, RumahPompa $rumahPompa)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'location_code' => ['nullable', 'string', 'max:255'],
            'type'          => ['nullable', 'string', 'max:255'],
            'zone'          => ['nullable', 'string', 'max:255'],
            'status'        => ['nullable', 'string', 'max:50'],
            'notes'         => ['nullable', 'string'],
        ]);

        $rumahPompa->update($data);

        return redirect()
            ->route('rumah-pompa.index')
            ->with('success', 'Rumah Pompa ' . $rumahPompa->serial_no . ' berhasil diperbarui');
    }

    public function riwayat(Request $request, RumahPompa $rumahPompa)
    {
        $query = $rumahPompa->kartuInspeksi()->with(['user', 'approver', 'signature']);
        
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
        
        return view('rumah-pompa.riwayat', compact('rumahPompa', 'riwayatInspeksi'));
    }

    /**
     * View detail kartu kendali dengan TTD
     */
    public function viewKartu($rumahPompaId, $kartuId)
    {
        $rumahPompa = RumahPompa::findOrFail($rumahPompaId);
        $kartu = \App\Models\KartuRumahPompa::with(['signature', 'user', 'approver'])->findOrFail($kartuId);
        
        // Get template for Rumah Pompa module
        $template = \App\Models\KartuTemplate::getTemplate('rumah-pompa');
        
        // Fill template with real data
        if ($template) {
            // Map data berdasarkan label field
            $labelMap = [
                'No. Dokumen' => 'RP-' . str_pad($kartu->id, 4, '0', STR_PAD_LEFT),
                'Revisi' => '00',
                'Tanggal' => \Carbon\Carbon::parse($kartu->tgl_periksa)->format('d F Y'),
                'Halaman' => '1 dari 1',
            ];
            
            // Update header fields dengan data real
            $headerFields = collect($template->header_fields)->map(function($field) use ($labelMap) {
                if (isset($labelMap[$field['label']])) {
                    $field['value'] = $labelMap[$field['label']];
                }
                return $field;
            })->toArray();
            
            $template->header_fields = $headerFields;
        }
        
        return view('rumah-pompa.view-kartu', compact('rumahPompa', 'kartu', 'template'));
    }
}
