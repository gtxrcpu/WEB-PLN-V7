<?php

namespace App\Http\Controllers;

use App\Models\Apat;
use App\Models\KartuApat;
use Illuminate\Http\Request;

class ApatKartuController extends Controller
{
    /**
     * Tampilkan form Kartu Kendali APAT (create).
     * URL: /apat/kartu/create?apat_id=...
     */
    public function create(Request $request)
    {
        $apatId = $request->query('apat_id');

        $apat = Apat::findOrFail($apatId);
        
        // Get template for APAT module
        $template = \App\Models\KartuTemplate::getTemplate('apat');

        return view('apat.kartu.create', [
            'apat' => $apat,
            'template' => $template,
        ]);
    }

    /**
     * Simpan Kartu Kendali APAT.
     */
    public function store(Request $request)
    {
        $template = \App\Models\KartuTemplate::getTemplate('apat');
        
        // Build validation rules
        $rules = [
            'apat_id'       => ['required', 'exists:apats,id'],
            'kesimpulan'    => ['required', 'string', 'max:50'],
            'tgl_periksa'   => ['required', 'date'],
            'petugas'       => ['required', 'string', 'max:100'],
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
                'kondisi_fisik' => ['required', 'string', 'max:50'],
                'drum'          => ['required', 'string', 'max:50'],
                'aduk_pasir'    => ['required', 'string', 'max:50'],
                'sekop'         => ['required', 'string', 'max:50'],
                'fire_blanket'  => ['required', 'string', 'max:50'],
                'ember'         => ['required', 'string', 'max:50'],
            ]);
        }
        
        $data = $request->validate($rules);
        
        // Jika menggunakan template, map inspection fields ke kolom database lama
        if ($template && $template->inspection_fields) {
            // Mapping label template ke kolom database
            $fieldMapping = [
                'Kondisi Fisik' => 'kondisi_fisik',
                'Drum' => 'drum',
                'Aduk Pasir' => 'aduk_pasir',
                'Sekop' => 'sekop',
                'Fire Blanket' => 'fire_blanket',
                'Ember' => 'ember',
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

        // Tambahkan user_id
        $data['user_id'] = auth()->id();

        KartuApat::create($data);

        return redirect()
            ->route('apat.index')
            ->with('success', 'Kartu Kendali APAT berhasil disimpan.');
    }
}
