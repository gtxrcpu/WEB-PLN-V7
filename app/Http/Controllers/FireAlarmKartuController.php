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
        
        // Build validation rules
        $rules = [
            'fire_alarm_id'  => ['required', 'exists:fire_alarms,id'],
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
                'panel_kontrol'      => ['required', 'string', 'max:50'],
                'detector'           => ['required', 'string', 'max:50'],
                'manual_call_point'  => ['required', 'string', 'max:50'],
                'alarm_bell'         => ['required', 'string', 'max:50'],
                'battery_backup'     => ['required', 'string', 'max:50'],
                'uji_fungsi'         => ['required', 'string', 'max:50'],
            ]);
        }
        
        $data = $request->validate($rules);
        
        // Jika menggunakan template, map inspection fields ke kolom database lama
        if ($template && $template->inspection_fields) {
            // Mapping label template ke kolom database
            $fieldMapping = [
                'Panel Alarm' => 'panel_kontrol',
                'Detector' => 'detector',
                'Bell/Sirine' => 'alarm_bell',
                'Manual Call Point' => 'manual_call_point',
                'Kabel & Instalasi' => 'battery_backup',
                'Kondisi Fisik' => 'uji_fungsi',
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
        \App\Models\KartuFireAlarm::create($data);
        
        return redirect()
            ->route('fire-alarm.index')
            ->with('success', 'Kartu Kendali Fire Alarm berhasil disimpan');
    }
}
