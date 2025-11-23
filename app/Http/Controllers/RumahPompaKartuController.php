<?php

namespace App\Http\Controllers;

use App\Models\RumahPompa;
use Illuminate\Http\Request;

class RumahPompaKartuController extends Controller
{
    public function create(Request $request)
    {
        $rumahPompaId = $request->query('rumah_pompa_id');
        
        if (!$rumahPompaId) {
            return redirect()
                ->route('rumah-pompa.index')
                ->with('error', 'Rumah Pompa ID tidak ditemukan');
        }

        $rumahPompa = RumahPompa::findOrFail($rumahPompaId);
        $template = \App\Models\KartuTemplate::getTemplate('rumah-pompa');

        return view('rumah-pompa.kartu.create', compact('rumahPompa', 'template'));
    }

    public function store(Request $request)
    {
        $template = \App\Models\KartuTemplate::getTemplate('rumah-pompa');
        
        // Build validation rules
        $rules = [
            'rumah_pompa_id' => ['required', 'exists:rumah_pompas,id'],
            'kesimpulan'     => ['required', 'string', 'max:50'],
            'tgl_periksa'    => ['required', 'date'],
            'petugas'        => ['required', 'string', 'max:100'],
        ];
        
        // Add dynamic inspection fields validation
        if ($template && $template->inspection_fields) {
            foreach ($template->inspection_fields as $index => $field) {
                $fieldName = 'inspection_' . $index;
                $rules[$fieldName] = ['required', 'string', 'max:255'];
            }
        } else {
            // Fallback ke field lama
            $rules = array_merge($rules, [
                'pompa_utama'     => ['required', 'string', 'max:50'],
                'pompa_cadangan'  => ['required', 'string', 'max:50'],
                'jockey_pump'     => ['required', 'string', 'max:50'],
                'panel_kontrol'   => ['required', 'string', 'max:50'],
                'uji_fungsi'      => ['required', 'string', 'max:50'],
            ]);
        }
        
        $data = $request->validate($rules);
        
        // Jika menggunakan template, map inspection fields ke kolom database lama
        if ($template && $template->inspection_fields) {
            // Mapping label template ke kolom database
            $fieldMapping = [
                'Pompa Utama' => 'pompa_utama',
                'Pompa Cadangan' => 'pompa_cadangan',
                'Panel Kontrol' => 'panel_kontrol',
                'Pressure Tank' => 'jockey_pump',
                'Valve & Pipa' => 'uji_fungsi',
                'Kondisi Fisik' => 'kondisi_fisik',
            ];
            
            foreach ($template->inspection_fields as $index => $field) {
                $fieldName = 'inspection_' . $index;
                if (isset($data[$fieldName])) {
                    // Map ke kolom database jika ada mapping
                    if (isset($fieldMapping[$field['label']])) {
                        $dbColumn = $fieldMapping[$field['label']];
                        $data[$dbColumn] = $data[$fieldName];
                    }
                    unset($data[$fieldName]);
                }
            }
        }

        $data['user_id'] = auth()->id();
        \App\Models\KartuRumahPompa::create($data);

        return redirect()
            ->route('rumah-pompa.index')
            ->with('success', 'Kartu Kendali Rumah Pompa berhasil disimpan');
    }
}
