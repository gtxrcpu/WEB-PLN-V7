<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AparSetting;
use Illuminate\Http\Request;

class AparSettingController extends Controller
{
    public function index()
    {
        $settings = AparSetting::all()->keyBy('key');
        
        return view('admin.apar-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'kode_format' => 'required|string|max:255',
            'kode_counter' => 'required|integer|min:1',
        ]);

        AparSetting::set('kode_format', $validated['kode_format']);
        AparSetting::set('kode_counter', $validated['kode_counter']);

        return redirect()
            ->route('admin.apar-settings.index')
            ->with('success', 'Settings APAR berhasil diupdate');
    }

    public function resetCounter()
    {
        AparSetting::set('kode_counter', 1);

        return redirect()
            ->route('admin.apar-settings.index')
            ->with('success', 'Counter berhasil direset ke 1');
    }
}
