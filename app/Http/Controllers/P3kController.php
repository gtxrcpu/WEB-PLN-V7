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

        $p3k->refreshQrSvg();

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

    public function riwayat(P3k $p3k)
    {
        $riwayatInspeksi = $p3k->kartuP3ks()->orderBy('tgl_periksa', 'desc')->get();
        return view('p3k.riwayat', compact('p3k', 'riwayatInspeksi'));
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
