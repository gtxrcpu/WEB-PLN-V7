<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KartuTemplate extends Model
{
    protected $fillable = [
        'module',
        'title',
        'subtitle',
        'company_name',
        'company_address',
        'company_phone',
        'company_fax',
        'company_email',
        'header_fields',
        'inspection_fields',
        'footer_fields',
        'table_header',
        'is_active'
    ];

    protected $casts = [
        'header_fields' => 'array',
        'inspection_fields' => 'array',
        'footer_fields' => 'array',
        'is_active' => 'boolean',
    ];

    public static function getTemplate($module)
    {
        return \Cache::remember('kartu_template_' . $module, 3600, function () use ($module) {
            return self::where('module', $module)->where('is_active', true)->first();
        });
    }
    
    protected static function boot()
    {
        parent::boot();
        
        // Clear cache saat template di-update atau delete
        static::updated(function ($template) {
            \Cache::forget('kartu_template_' . $template->module);
        });
        
        static::deleted(function ($template) {
            \Cache::forget('kartu_template_' . $template->module);
        });
    }

    public static function getModules()
    {
        return [
            'apar' => 'APAR - Alat Pemadam Api Ringan',
            'apat' => 'APAT - Alat Pemadam Api Tradisional',
            'apab' => 'APAB - Alat Pemadam Api Berat',
            'fire-alarm' => 'Fire Alarm - Panel & Titik Alarm',
            'box-hydrant' => 'Box Hydrant - Box, Hose, Nozzle',
            'rumah-pompa' => 'Rumah Pompa - Hydrant Rumah Pompa',
            'p3k' => 'P3K - Kotak & Isi P3K (Legacy)',
            'p3k-pemeriksaan' => 'P3K Pemeriksaan - Checklist Kondisi Kotak P3K',
            'p3k-pemakaian' => 'P3K Pemakaian - Catatan Penggunaan Obat/Alat',
            'p3k-stock' => 'P3K Stock - Kartu Kendali Stock P3K',
        ];
    }
}
