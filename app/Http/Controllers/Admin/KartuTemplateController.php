<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KartuTemplate;
use Illuminate\Http\Request;

class KartuTemplateController extends Controller
{
    public function index()
    {
        $templates = KartuTemplate::all();
        $modules = KartuTemplate::getModules();
        
        return view('admin.kartu-templates.index', compact('templates', 'modules'));
    }

    public function edit($module)
    {
        $template = KartuTemplate::where('module', $module)->firstOrFail();
        $moduleName = KartuTemplate::getModules()[$module] ?? $module;
        
        return view('admin.kartu-templates.edit', compact('template', 'moduleName'));
    }

    public function update(Request $request, $module)
    {
        $template = KartuTemplate::where('module', $module)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'header_fields' => 'required|array',
            'header_fields.*.label' => 'required|string',
            'header_fields.*.value' => 'required|string',
            'inspection_fields' => 'required|array',
            'inspection_fields.*.label' => 'required|string',
            'inspection_fields.*.type' => 'required|string',
            'footer_fields' => 'required|array',
            'footer_fields.*.label' => 'required|string',
            'footer_fields.*.value' => 'required|string',
            'company_name' => 'nullable|string',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string',
            'company_fax' => 'nullable|string',
            'company_email' => 'nullable|email',
            'table_header' => 'nullable|string|max:255',
        ]);

        $template->update($validated);
        
        // Clear cache untuk real-time update
        \Cache::forget('kartu_template_' . $module);

        return redirect()->route('admin.kartu-templates.index')
            ->with('success', 'Template berhasil diupdate!');
    }

    public function create()
    {
        $modules = KartuTemplate::getModules();
        $existingModules = KartuTemplate::pluck('module')->toArray();
        
        // Filter modules yang belum ada template
        $availableModules = array_diff_key($modules, array_flip($existingModules));
        
        return view('admin.kartu-templates.create', compact('availableModules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'module' => 'required|string|unique:kartu_templates,module',
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
        ]);

        // Default fields
        $validated['header_fields'] = [
            ['key' => 'no_dokumen', 'label' => 'No. Dokumen', 'value' => ''],
            ['key' => 'revisi', 'label' => 'Revisi', 'value' => ''],
            ['key' => 'tanggal', 'label' => 'Tanggal', 'value' => ''],
            ['key' => 'halaman', 'label' => 'Halaman', 'value' => ''],
        ];

        $validated['inspection_fields'] = [
            ['key' => 'kondisi', 'label' => 'Kondisi', 'type' => 'checkbox'],
            ['key' => 'catatan', 'label' => 'Catatan', 'type' => 'textarea'],
        ];

        $validated['footer_fields'] = [
            ['key' => 'lokasi', 'label' => 'Lokasi', 'value' => 'Surabaya'],
            ['key' => 'petugas_label', 'label' => 'Label Petugas', 'value' => 'Petugas Pemeriksa'],
        ];

        $validated['is_active'] = true;

        KartuTemplate::create($validated);

        return redirect()->route('admin.kartu-templates.index')
            ->with('success', 'Template berhasil dibuat!');
    }
}
