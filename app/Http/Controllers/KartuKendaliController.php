<?php

namespace App\Http\Controllers;

use App\Models\Apar;
use App\Models\KartuApar;
use Illuminate\Http\Request;

class KartuKendaliController extends Controller
{
    /**
     * Tampilkan form Kartu Kendali untuk 1 APAR.
     * URL: /kartu/create?apar_id=ID
     */
    public function create(Request $request)
    {
        $aparId = $request->query('apar_id');

        // kalau apar_id nggak ada / salah, langsung 404
        $apar = Apar::findOrFail($aparId);

        // Get template for APAR module
        $template = \App\Models\KartuTemplate::getTemplate('apar');

        // pake view yang kamu kirim: resources/views/kartu/create.blade.php
        return view('kartu.create', compact('apar', 'template'));
    }

    /**
     * Simpan kartu kendali ke database.
     * Route: POST /kartu  (name: kartu.store)
     */
    public function store(Request $request)
    {
        // Get template untuk validasi dinamis
        $template = \App\Models\KartuTemplate::getTemplate('apar');
        
        // Debug: Log request data
        \Log::info('Kartu Kendali Store Request', [
            'all_data' => $request->all(),
            'template_exists' => $template ? 'yes' : 'no',
            'inspection_fields_count' => $template && $template->inspection_fields ? count($template->inspection_fields) : 0
        ]);
        
        // Build validation rules
        $rules = [
            'apar_id'        => ['required', 'exists:apars,id'],
            'kesimpulan'     => ['required', 'string', 'max:50'],
            'tgl_periksa'    => ['required', 'date'],
            'petugas'        => ['required', 'string', 'max:100'],
            'pengawas'       => ['nullable', 'string', 'max:100'], // Add pengawas as optional
        ];
        
        // Add dynamic inspection fields validation
        if ($template && $template->inspection_fields) {
            foreach ($template->inspection_fields as $index => $field) {
                $fieldName = 'inspection_' . $index;
                // Customize validation message
                $rules[$fieldName] = ['required', 'string', 'max:255'];
            }
        } else {
            // Fallback ke field lama
            $rules = array_merge($rules, [
                'pressure_gauge' => ['required', 'string', 'max:50'],
                'pin_segel'      => ['required', 'string', 'max:50'],
                'selang'         => ['required', 'string', 'max:50'],
                'tabung'         => ['required', 'string', 'max:50'],
                'label'          => ['required', 'string', 'max:50'],
                'kondisi_fisik'  => ['required', 'string', 'max:50'],
            ]);
        }
        
        // Custom validation messages
        $messages = [];
        if ($template && $template->inspection_fields) {
            foreach ($template->inspection_fields as $index => $field) {
                $fieldName = 'inspection_' . $index;
                $messages[$fieldName . '.required'] = 'Field "' . $field['label'] . '" wajib diisi.';
            }
        }
        
        $data = $request->validate($rules, $messages);
        
        // Jika menggunakan template, map inspection fields ke kolom database lama
        if ($template && $template->inspection_fields) {
            // Mapping KEY template ke kolom database (gunakan key, bukan label)
            $fieldMapping = [
                // Old mapping (for backward compatibility)
                'pressure_gauge' => 'pressure_gauge',
                'pin_segel' => 'pin_segel',
                'selang' => 'selang',
                'tabung' => 'tabung',
                'label' => 'label',
                'kondisi_fisik' => 'kondisi_fisik',
                // New mapping (from template keys)
                'kondisi_tabung' => 'tabung',
                'kondisi_selang' => 'selang',
                'kondisi_pin' => 'pin_segel',
                'tekanan' => 'pressure_gauge',
                'berat' => 'label',
                'catatan' => 'kondisi_fisik',
            ];
            
            // Initialize all required fields with default value
            $requiredFields = ['pressure_gauge', 'pin_segel', 'selang', 'tabung', 'label', 'kondisi_fisik'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    $data[$field] = '-'; // default value
                }
            }
            
            // Log untuk debugging
            \Log::info('Mapping inspection fields', [
                'template_fields' => $template->inspection_fields,
                'data_before_mapping' => $data
            ]);
            
            foreach ($template->inspection_fields as $index => $field) {
                $fieldName = 'inspection_' . $index;
                if (isset($data[$fieldName])) {
                    // Map ke kolom database menggunakan KEY (bukan label)
                    $fieldKey = $field['key'] ?? null;
                    if ($fieldKey && isset($fieldMapping[$fieldKey])) {
                        $dbColumn = $fieldMapping[$fieldKey];
                        $data[$dbColumn] = $data[$fieldName];
                        \Log::info("Mapped {$fieldName} (key: {$fieldKey}, label: {$field['label']}) to {$dbColumn} = {$data[$fieldName]}");
                    } else {
                        \Log::warning("No mapping found for field key: {$fieldKey}, label: {$field['label']}");
                    }
                    unset($data[$fieldName]);
                }
            }
            
            \Log::info('Data after mapping', ['data' => $data]);
        }

        // Tambahkan user_id
        $data['user_id'] = auth()->id();
        
        // Log final data before insert
        \Log::info('Final data before insert', ['data' => $data]);

        // Simpan kartu inspeksi APAR
        KartuApar::create($data);

        return redirect()
            ->route('apar.index')
            ->with('success', 'Kartu Kendali berhasil disimpan untuk APAR ' . $request->apar_id);
    }
}
