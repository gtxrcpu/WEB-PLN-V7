<?php

namespace App\Http\Controllers;

use App\Models\BoxHydrant;
use Illuminate\Http\Request;

class BoxHydrantKartuController extends Controller
{
    public function create(Request $request)
    {
        $boxHydrantId = $request->query('box_hydrant_id');
        
        if (!$boxHydrantId) {
            return redirect()
                ->route('box-hydrant.index')
                ->with('error', 'Box Hydrant ID tidak ditemukan');
        }

        $boxHydrant = BoxHydrant::findOrFail($boxHydrantId);
        $template = \App\Models\KartuTemplate::getTemplate('box-hydrant');

        return view('box-hydrant.kartu.create', compact('boxHydrant', 'template'));
    }

    public function store(Request $request)
    {
        $template = \App\Models\KartuTemplate::getTemplate('box-hydrant');
        
        // Build validation rules
        $rules = [
            'box_hydrant_id' => ['required', 'exists:box_hydrants,id'],
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
                'pilar_hydrant'  => ['required', 'string', 'max:50'],
                'box_hydrant'    => ['required', 'string', 'max:50'],
                'nozzle'         => ['required', 'string', 'max:50'],
                'selang'         => ['required', 'string', 'max:50'],
                'uji_fungsi'     => ['required', 'string', 'max:50'],
            ]);
        }
        
        $data = $request->validate($rules);
        
        // Jika menggunakan template, map inspection fields ke kolom database lama
        if ($template && $template->inspection_fields) {
            // Mapping label template ke kolom database
            $fieldMapping = [
                'Pilar Hydrant' => 'pilar_hydrant',
                'Box Hydrant' => 'box_hydrant',
                'Hose/Selang' => 'selang',
                'Nozzle' => 'nozzle',
                'Coupling' => 'uji_fungsi',
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
        \App\Models\KartuBoxHydrant::create($data);

        return redirect()
            ->route('box-hydrant.index')
            ->with('success', 'Kartu Kendali Box Hydrant berhasil disimpan');
    }
}
