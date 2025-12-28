<?php

namespace App\Http\Controllers;

use App\Models\P3k;
use Illuminate\Http\Request;

class P3kController extends Controller
{
    public function index()
    {
        $p3ks = P3k::orderBy('id')->get();
        return view('p3k.index', compact('p3ks'));
    }

    public function create()
    {
        return view('p3k.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'barcode'       => ['required', 'string', 'max:255', 'unique:p3ks,barcode'],
            'serial_no'     => ['required', 'string', 'max:255', 'unique:p3ks,serial_no'],
            'location_code' => ['nullable', 'string', 'max:255'],
            'type'          => ['nullable', 'string', 'max:255'],
            'status'        => ['nullable', 'string', 'max:50'],
            'notes'         => ['nullable', 'string'],
        ]);

        $p3k = new P3k();
        $p3k->user_id       = auth()->id();
        $p3k->name          = $data['name'];
        $p3k->barcode       = $data['barcode'];
        $p3k->serial_no     = $data['serial_no'];
        $p3k->location_code = $data['location_code'] ?? null;
        $p3k->type          = $data['type'] ?? null;
        $p3k->status        = $data['status'] ?? 'lengkap';
        $p3k->notes         = $data['notes'] ?? null;
        $p3k->save();

        return redirect()
            ->route('p3k.index')
            ->with('success', 'P3K baru berhasil ditambahkan dengan barcode ' . $p3k->barcode);
    }

    public function edit(P3k $p3k)
    {
        return view('p3k.edit', compact('p3k'));
    }

    public function update(Request $request, P3k $p3k)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'location_code' => ['nullable', 'string', 'max:255'],
            'type'          => ['nullable', 'string', 'max:255'],
            'status'        => ['nullable', 'string', 'max:50'],
            'notes'         => ['nullable', 'string'],
        ]);

        $p3k->name          = $data['name'];
        $p3k->location_code = $data['location_code'] ?? null;
        $p3k->type          = $data['type'] ?? null;
        $p3k->status        = $data['status'] ?? null;
        $p3k->notes         = $data['notes'] ?? null;
        $p3k->save();

        return redirect()
            ->route('p3k.index')
            ->with('success', 'P3K ' . $p3k->serial_no . ' berhasil diperbarui');
    }

    public function riwayat(Request $request, P3k $p3k)
    {
        // Get riwayat dari 3 jenis kartu
        $riwayatPemeriksaan = $p3k->kartuPemeriksaan()
            ->with(['user', 'approver', 'signature'])
            ->orderBy('tgl_periksa', 'desc')
            ->get()
            ->map(function($item) {
                $item->jenis = 'pemeriksaan';
                $item->tanggal = $item->tgl_periksa;
                return $item;
            });
        
        $riwayatPemakaian = $p3k->kartuPemakaian()
            ->with(['user', 'approver', 'signature'])
            ->orderBy('tgl_pemakaian', 'desc')
            ->get()
            ->map(function($item) {
                $item->jenis = 'pemakaian';
                $item->tanggal = $item->tgl_pemakaian;
                return $item;
            });
        
        $riwayatStock = $p3k->kartuStock()
            ->with(['user', 'approver', 'signature'])
            ->orderBy('tgl_periksa', 'desc')
            ->get()
            ->map(function($item) {
                $item->jenis = 'stock';
                $item->tanggal = $item->tgl_periksa;
                return $item;
            });
        
        // Legacy kartu (jika ada)
        $riwayatLegacy = $p3k->kartuP3ks()
            ->with(['user', 'approver'])
            ->orderBy('tgl_periksa', 'desc')
            ->get()
            ->map(function($item) {
                $item->jenis = 'legacy';
                $item->tanggal = $item->tgl_periksa;
                return $item;
            });
        
        // Filter by jenis if specified
        $filterJenis = $request->query('jenis');
        
        if ($filterJenis === 'pemeriksaan') {
            $riwayatInspeksi = $riwayatPemeriksaan;
        } elseif ($filterJenis === 'pemakaian') {
            $riwayatInspeksi = $riwayatPemakaian;
        } elseif ($filterJenis === 'stock') {
            $riwayatInspeksi = $riwayatStock;
        } else {
            // Gabungkan semua dan sort by tanggal
            $riwayatInspeksi = $riwayatPemeriksaan
                ->concat($riwayatPemakaian)
                ->concat($riwayatStock)
                ->concat($riwayatLegacy)
                ->sortByDesc('tanggal')
                ->values();
        }
        
        // Filter by creator
        if ($request->filled('creator')) {
            $riwayatInspeksi = $riwayatInspeksi->filter(function($item) use ($request) {
                return $item->user && str_contains(strtolower($item->user->name), strtolower($request->creator));
            });
        }
        
        // Filter by approval status
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $riwayatInspeksi = $riwayatInspeksi->filter(fn($item) => !is_null($item->approved_at));
            } elseif ($request->status === 'pending') {
                $riwayatInspeksi = $riwayatInspeksi->filter(fn($item) => is_null($item->approved_at));
            }
        }
        
        return view('p3k.riwayat', compact('p3k', 'riwayatInspeksi', 'filterJenis'));
    }

    public function pilihJenis()
    {
        return view('p3k.pilih-jenis');
    }

    public function pilihLokasi(Request $request)
    {
        $jenis = $request->query('jenis', 'pemeriksaan');
        return view('p3k.pilih-lokasi', compact('jenis'));
    }
}
