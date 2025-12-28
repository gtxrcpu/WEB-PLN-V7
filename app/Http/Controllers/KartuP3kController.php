<?php

namespace App\Http\Controllers;

use App\Models\P3k;
use App\Models\KartuP3k;
use App\Models\KartuP3kPemeriksaan;
use App\Models\KartuP3kPemakaian;
use App\Models\KartuP3kStock;
use App\Models\KartuTemplate;
use Illuminate\Http\Request;

class KartuP3kController extends Controller
{
    public function create(Request $request)
    {
        $jenis = $request->query('jenis', 'stock');
        $lokasi = $request->query('lokasi', 'Area Limbah B3');
        
        // Get template berdasarkan jenis
        $templateModule = 'p3k-' . $jenis;
        $template = KartuTemplate::getTemplate($templateModule);
        
        // Fallback ke template p3k lama jika belum ada
        if (!$template) {
            $template = KartuTemplate::getTemplate('p3k');
        }
        
        // Get P3K berdasarkan lokasi
        $p3ks = P3k::where('location_code', 'like', '%' . $lokasi . '%')
            ->orWhere('name', 'like', '%' . $lokasi . '%')
            ->get();
        
        return view('p3k.kartu.create', compact('jenis', 'lokasi', 'template', 'p3ks'));
    }

    public function store(Request $request)
    {
        $jenis = $request->input('jenis', 'stock');
        
        // Validasi berdasarkan jenis
        if ($jenis === 'pemeriksaan') {
            return $this->storePemeriksaan($request);
        } elseif ($jenis === 'pemakaian') {
            return $this->storePemakaian($request);
        } else {
            return $this->storeStock($request);
        }
    }

    protected function storePemeriksaan(Request $request)
    {
        $validated = $request->validate([
            'p3k_id' => ['required', 'exists:p3ks,id'],
            'checklist' => ['required', 'array'],
            'kesimpulan' => ['required', 'string'],
            'tgl_periksa' => ['required', 'date'],
            'petugas' => ['required', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],
        ]);

        KartuP3kPemeriksaan::create([
            'p3k_id' => $validated['p3k_id'],
            'user_id' => auth()->id(),
            'checklist_items' => $validated['checklist'],
            'kesimpulan' => $validated['kesimpulan'],
            'tgl_periksa' => $validated['tgl_periksa'],
            'petugas' => $validated['petugas'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()
            ->route('p3k.index')
            ->with('success', 'Kartu Pemeriksaan P3K berhasil disimpan.');
    }

    protected function storePemakaian(Request $request)
    {
        $validated = $request->validate([
            'p3k_id' => ['required', 'exists:p3ks,id'],
            'item_digunakan' => ['required', 'string', 'max:255'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'keperluan' => ['nullable', 'string'],
            'nama_pengguna' => ['nullable', 'string', 'max:255'],
            'kesimpulan' => ['required', 'string'],
            'tgl_pemakaian' => ['required', 'date'],
            'petugas' => ['required', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],
        ]);

        KartuP3kPemakaian::create([
            'p3k_id' => $validated['p3k_id'],
            'user_id' => auth()->id(),
            'item_digunakan' => $validated['item_digunakan'],
            'jumlah' => $validated['jumlah'],
            'keperluan' => $validated['keperluan'] ?? null,
            'nama_pengguna' => $validated['nama_pengguna'] ?? null,
            'kesimpulan' => $validated['kesimpulan'],
            'tgl_pemakaian' => $validated['tgl_pemakaian'],
            'petugas' => $validated['petugas'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()
            ->route('p3k.index')
            ->with('success', 'Kartu Pemakaian P3K berhasil disimpan.');
    }

    protected function storeStock(Request $request)
    {
        $validated = $request->validate([
            'p3k_id' => ['required', 'exists:p3ks,id'],
            'stock_items' => ['required', 'array'],
            'kesimpulan' => ['required', 'string'],
            'tgl_periksa' => ['required', 'date'],
            'petugas' => ['required', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],
        ]);

        KartuP3kStock::create([
            'p3k_id' => $validated['p3k_id'],
            'user_id' => auth()->id(),
            'stock_items' => $validated['stock_items'],
            'kesimpulan' => $validated['kesimpulan'],
            'tgl_periksa' => $validated['tgl_periksa'],
            'petugas' => $validated['petugas'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()
            ->route('p3k.index')
            ->with('success', 'Kartu Stock P3K berhasil disimpan.');
    }
}
