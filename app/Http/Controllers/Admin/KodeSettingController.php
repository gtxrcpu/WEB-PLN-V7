<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AparSetting;
use Illuminate\Http\Request;

class KodeSettingController extends Controller
{
    private $modules = [
        'apar' => [
            'name' => 'APAR',
            'full_name' => 'Alat Pemadam Api Ringan',
            'icon' => 'images/apar.png',
        ],
        'apat' => [
            'name' => 'APAT',
            'full_name' => 'Alat Pemadam Api Tradisional',
            'icon' => 'images/apat.png',
        ],
        'apab' => [
            'name' => 'APAB',
            'full_name' => 'Alat Pemadam Api Berat',
            'icon' => 'images/apab.png',
        ],
        'fire-alarm' => [
            'name' => 'Fire Alarm',
            'full_name' => 'Fire Alarm System',
            'icon' => 'images/fire-alarm.png',
        ],
        'box-hydrant' => [
            'name' => 'Box Hydrant',
            'full_name' => 'Box Hydrant',
            'icon' => 'images/box-hydrant.png',
        ],
        'rumah-pompa' => [
            'name' => 'Rumah Pompa',
            'full_name' => 'Rumah Pompa',
            'icon' => 'images/box-hydrant.png',
        ],
        'p3k' => [
            'name' => 'P3K',
            'full_name' => 'Pertolongan Pertama Pada Kecelakaan',
            'icon' => 'images/p3k.png',
        ],
    ];

    public function index()
    {
        $modules = $this->modules;
        
        return view('admin.edit-kode.index', compact('modules'));
    }

    public function edit($module)
    {
        if (!isset($this->modules[$module])) {
            abort(404);
        }

        $moduleInfo = $this->modules[$module];
        $settingKey = $module . '_kode_format';
        $counterKey = $module . '_kode_counter';

        $settings = [
            'kode_format' => AparSetting::where('key', $settingKey)->first(),
            'kode_counter' => AparSetting::where('key', $counterKey)->first(),
        ];

        // Set default jika belum ada (format seperti: APAR A1.001)
        if (!$settings['kode_format']) {
            $defaultFormat = match($module) {
                'apar' => 'APAR A1.{NNN}',
                'apat' => 'APAT A2.{NNN}',
                'apab' => 'APAB A3.{NNN}',
                'fire-alarm' => 'FA.{NNN}',
                'box-hydrant' => 'BH.{NNN}',
                'rumah-pompa' => 'RP.{NNN}',
                'p3k' => 'P3K.{NNN}',
                default => strtoupper($module) . '.{NNN}',
            };
            
            $settings['kode_format'] = (object)[
                'key' => $settingKey,
                'value' => $defaultFormat,
            ];
        }

        if (!$settings['kode_counter']) {
            $settings['kode_counter'] = (object)[
                'key' => $counterKey,
                'value' => '1',
            ];
        }

        return view('admin.edit-kode.edit', compact('module', 'moduleInfo', 'settings'));
    }

    public function update(Request $request, $module)
    {
        if (!isset($this->modules[$module])) {
            abort(404);
        }

        $validated = $request->validate([
            'kode_format' => 'required|string|max:255',
            'kode_counter' => 'required|integer|min:1',
        ]);

        $settingKey = $module . '_kode_format';
        $counterKey = $module . '_kode_counter';

        AparSetting::updateOrCreate(
            ['key' => $settingKey],
            ['value' => $validated['kode_format'], 'type' => 'text']
        );

        AparSetting::updateOrCreate(
            ['key' => $counterKey],
            ['value' => $validated['kode_counter'], 'type' => 'number']
        );

        return redirect()
            ->route('admin.edit-kode.edit', $module)
            ->with('success', 'Settings ' . $this->modules[$module]['name'] . ' berhasil diupdate');
    }

    public function resetCounter($module)
    {
        if (!isset($this->modules[$module])) {
            abort(404);
        }

        $counterKey = $module . '_kode_counter';

        AparSetting::updateOrCreate(
            ['key' => $counterKey],
            ['value' => '1', 'type' => 'number']
        );

        return redirect()
            ->route('admin.edit-kode.edit', $module)
            ->with('success', 'Counter berhasil direset ke 1');
    }
}
