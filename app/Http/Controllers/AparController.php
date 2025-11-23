<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\FiltersByUnit;
use App\Models\Apar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AparController extends Controller
{
    use FiltersByUnit;
    /**
     * Tampilkan daftar APAR.
     */
    public function index()
    {
        $apars = $this->getQueryForAuthUser(Apar::class)
            ->orderBy('id')
            ->get();

        return view('apar.index', compact('apars'));
    }

    /**
     * Form tambah APAR.
     */
    public function create()
    {
        $nextSerial = Apar::generateNextSerial();

        // default value kalau mau ditampilkan di form
        $default = [
            'serial_no'     => $nextSerial,
            'name'          => 'APAR ' . $nextSerial,
            'barcode'       => 'APAR ' . $nextSerial,
            'status'        => 'BAIK',
            'location_code' => 'BDG',
            'type'          => 'UUV',
            'capacity'      => '5 Liter',
            'agent'         => '500',
        ];

        return view('apar.create', compact('nextSerial', 'default'));
    }

    /**
     * Simpan APAR baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'location_code' => 'required|string|max:50',
            'type'          => 'required|string|max:100',
            'capacity'      => 'required|string|max:100',
            'agent'         => 'nullable|string|max:100',
            'status'        => 'required|string|max:20',
            'notes'         => 'nullable|string',
        ]);

        $serial = Apar::generateNextSerial();
        $barcode = 'APAR ' . $serial;

        $apar = Apar::create([
            'user_id'       => Auth::id(),
            'unit_id'       => $this->getAuthUserUnitId(), // Auto-assign unit dari user
            'name'          => 'APAR ' . $serial,
            'barcode'       => $barcode,
            'serial_no'     => $serial,
            'location_code' => $request->location_code,
            'type'          => $request->type,
            'capacity'      => $request->capacity,
            'agent'         => $request->agent,
            'status'        => $request->status,
            'notes'         => $request->notes,
        ]);

        // generate QR untuk APAR baru
        $apar->generateQrSvg(true);

        return redirect()
            ->route('apar.index')
            ->with('success', 'APAR baru berhasil ditambahkan dengan barcode ' . $apar->serial_no);
    }

    /**
     * Form edit APAR.
     */
    public function edit(Apar $apar)
    {
        return view('apar.edit', compact('apar'));
    }

    /**
     * Update APAR.
     */
    public function update(Request $request, Apar $apar)
    {
        $request->validate([
            'location_code' => 'required|string|max:50',
            'type'          => 'required|string|max:100',
            'capacity'      => 'required|string|max:100',
            'agent'         => 'nullable|string|max:100',
            'status'        => 'required|string|max:20',
            'notes'         => 'nullable|string',
        ]);

        $apar->update([
            'location_code' => $request->location_code,
            'type'          => $request->type,
            'capacity'      => $request->capacity,
            'agent'         => $request->agent,
            'status'        => $request->status,
            'notes'         => $request->notes,
        ]);

        // kalau mau bisa regenerate QR (opsional, tapi nggak masalah)
        $apar->generateQrSvg(true);

        return redirect()
            ->route('apar.index')
            ->with('success', 'Data APAR ' . $apar->serial_no . ' berhasil diperbarui.');
    }

    /**
     * Tampilkan riwayat kartu kendali APAR
     */
    public function riwayat(Apar $apar)
    {
        $kartuKendali = $apar->kartuApars()->with(['signature', 'approver'])->latest()->get();
        
        return view('apar.riwayat', compact('apar', 'kartuKendali'));
    }

    /**
     * View detail kartu kendali dengan TTD
     */
    public function viewKartu($aparId, $kartuId)
    {
        $apar = Apar::findOrFail($aparId);
        $kartu = \App\Models\KartuApar::with(['signature', 'approver'])->findOrFail($kartuId);
        
        // Get template for APAR module
        $template = \App\Models\KartuTemplate::getTemplate('apar');
        
        // Fill template with real data
        if ($template) {
            // Map data berdasarkan label field
            $labelMap = [
                'No. Dokumen' => 'APAR-' . str_pad($kartu->id, 4, '0', STR_PAD_LEFT),
                'Revisi' => '00',
                'Tanggal' => \Carbon\Carbon::parse($kartu->tgl_periksa)->format('d F Y'),
                'Halaman' => '1 dari 1',
            ];
            
            // Update header fields dengan data real
            $headerFields = collect($template->header_fields)->map(function($field) use ($labelMap) {
                // Cek apakah label ada di map
                if (isset($labelMap[$field['label']])) {
                    $field['value'] = $labelMap[$field['label']];
                }
                return $field;
            })->toArray();
            
            $template->header_fields = $headerFields;
            
            // Update footer fields dengan data real (lokasi tetap dari template)
            // Footer fields sudah OK dari template
        }
        
        return view('apar.view-kartu', compact('apar', 'kartu', 'template'));
    }
}
