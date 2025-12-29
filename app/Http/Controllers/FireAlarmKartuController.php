<?php

namespace App\Http\Controllers;

use App\Models\FireAlarm;
use Illuminate\Http\Request;

class FireAlarmKartuController extends Controller
{
    /**
     * Tampilkan form Kartu Kendali Fire Alarm
     */
    public function create(Request $request)
    {
        $fireAlarmId = $request->query('fire_alarm_id');
        
        if (!$fireAlarmId) {
            return redirect()
                ->route('fire-alarm.index')
                ->with('error', 'Fire Alarm ID tidak ditemukan');
        }

        $fireAlarm = FireAlarm::findOrFail($fireAlarmId);
        $template = \App\Models\KartuTemplate::getTemplate('fire-alarm');

        return view('fire-alarm.kartu.create', compact('fireAlarm', 'template'));
    }

    /**
     * Simpan Kartu Kendali Fire Alarm
     */
    public function store(Request $request)
    {
        $template = \App\Models\KartuTemplate::getTemplate('fire-alarm');
        
        // Debug: Log request data
        \Log::info('Fire Alarm Kartu Store Request', [
            'all_data' => $request->all(),
            'template_exists' => $template ? 'yes' : 'no',
            'inspection_fields_count' => $template && $template->inspection_fields ? count($template->inspection_fields) : 0
        ]);
        
        // Build validation rules
        $rules = [
            'fire_alarm_id'  => ['required', 'exists:fire_alarms,id'],
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
                'panel_kontrol'      => ['required', 'string', 'max:50'],
                'detector'           => ['required', 'string', 'max:50'],
                'manual_call_point'  => ['required', 'string', 'max:50'],
                'alarm_bell'         => ['required', 'string', 'max:50'],
                'battery_backup'     => ['required', 'string', 'max:50'],
                'uji_fungsi'         => ['required', 'string', 'max:50'],
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
            $fieldMapping = [
                'panel' => 'panel_kontrol',
                'detector' => 'detector',
                'kondisi_fisik' => 'panel_kontrol',
                'fungsi' => 'detector',
                'manual_call_point' => 'manual_call_point',
                'alarm_bell' => 'alarm_bell',
                'battery_backup' => 'battery_backup',
                'uji_fungsi' => 'uji_fungsi',
            ];
            
            // Initialize all required fields with default value
            $requiredFields = ['panel_kontrol', 'detector', 'manual_call_point', 'alarm_bell', 'battery_backup', 'uji_fungsi'];
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
            
            foreach ($template->inspection_fields as $index => $field) {
                $fieldName = 'inspection_' . $index;
                if (isset($data[$fieldName])) {
                    // Map ke kolom database menggunakan KEY
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
        
        // Simpan kartu inspeksi Fire Alarm
        \App\Models\KartuFireAlarm::create($data);
        
        return redirect()
            ->route('fire-alarm.index')
            ->with('success', 'Kartu Kendali Fire Alarm berhasil disimpan dan menunggu approval');
    }
}
