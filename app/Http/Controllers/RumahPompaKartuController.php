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
        
        // Debug: Log request data
        \Log::info('Rumah Pompa Kartu Store Request', [
            'all_data' => $request->all(),
            'template_exists' => $template ? 'yes' : 'no',
            'inspection_fields_count' => $template && $template->inspection_fields ? count($template->inspection_fields) : 0
        ]);
        
        // Build validation rules
        $rules = [
            'rumah_pompa_id' => ['required', 'exists:rumah_pompas,id'],
            'kesimpulan'     => ['required', 'string', 'max:50'],
            'tgl_periksa'    => ['required', 'date'],
            'petugas'        => ['required', 'string', 'max:100'],
            'pengawas'       => ['nullable', 'string', 'max:100'],
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
            // Mapping KEY template ke kolom database
            // Karena database hanya punya 5 kolom, kita ambil 5 field pertama
            $fieldMapping = [
                0 => 'pompa_utama',
                1 => 'pompa_cadangan',
                2 => 'jockey_pump',
                3 => 'panel_kontrol',
                4 => 'uji_fungsi',
            ];
            
            // Initialize all required fields with default value
            $requiredFields = ['pompa_utama', 'pompa_cadangan', 'jockey_pump', 'panel_kontrol', 'uji_fungsi'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    $data[$field] = '-';
                }
            }
            
            // Log untuk debugging
            \Log::info('Mapping inspection fields', [
                'template_fields' => $template->inspection_fields,
                'data_before_mapping' => $data
            ]);
            
            // Map hanya 5 field pertama
            foreach ($template->inspection_fields as $index => $field) {
                if ($index >= 5) break; // Hanya ambil 5 field pertama
                
                $fieldName = 'inspection_' . $index;
                if (isset($data[$fieldName]) && isset($fieldMapping[$index])) {
                    $dbColumn = $fieldMapping[$index];
                    $data[$dbColumn] = $data[$fieldName];
                    \Log::info("Mapped {$fieldName} (label: {$field['label']}) to {$dbColumn} = {$data[$fieldName]}");
                    unset($data[$fieldName]);
                }
            }
            
            // Hapus inspection fields yang tidak terpakai (index 5 ke atas)
            foreach ($data as $key => $value) {
                if (strpos($key, 'inspection_') === 0) {
                    unset($data[$key]);
                }
            }
            
            \Log::info('Data after mapping', ['data' => $data]);
        }

        // Tambahkan user_id
        $data['user_id'] = auth()->id();
        
        // Log final data before insert
        \Log::info('Final data before insert', ['data' => $data]);
        
        // Simpan kartu inspeksi Rumah Pompa
        \App\Models\KartuRumahPompa::create($data);

        return redirect()
            ->route('rumah-pompa.index')
            ->with('success', 'Kartu Kendali Rumah Pompa berhasil disimpan dan menunggu approval');
    }
}
