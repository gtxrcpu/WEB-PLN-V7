<?php

namespace App\Models;

use App\Models\Traits\HasUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Apar extends Model
{
    use HasUnit;
    protected $table = 'apars';

    protected $fillable = [
        'user_id',
        'unit_id',
        'name',          // contoh: "APAR A1.001"
        'barcode',       // contoh: "APAR A1.001"
        'serial_no',     // contoh: "A1.001"
        'type',          // contoh: "UUV"
        'capacity',      // contoh: "5 Liter"
        'agent',         // contoh: "500"
        'location_code', // contoh: "BDG"
        'status',        // "BAIK" / "ISI ULANG" / "RUSAK"
        'notes',
        'qr_svg_path',
        'floor_plan_id',
        'floor_plan_x',
        'floor_plan_y',
    ];

    /**
     * Generate serial berikutnya berdasarkan format custom dari settings
     * 
     * @param int|null $unitId Unit ID (null = Induk)
     * @param bool $incrementCounter Whether to increment counter (default: true)
     * @return string Generated serial number
     */
    public static function generateNextSerial($unitId = null, $incrementCounter = true): string
    {
        $format = \App\Models\AparSetting::get('apar_kode_format', 'APAR A1.{NNN}');

        // Determine unit from auth user if not provided
        if ($unitId === null && auth()->check() && auth()->user()->unit_id) {
            $unitId = auth()->user()->unit_id;
        }

        // Counter key based on unit (per-unit independent counter)
        $counterKey = $unitId ? "apar_kode_counter_{$unitId}" : "apar_kode_counter_induk";
        $counter = (int) \App\Models\AparSetting::get($counterKey, 1);

        // Get unit code for format
        $unitCode = $unitId ? (\App\Models\Unit::find($unitId)?->code ?? 'INDUK') : 'INDUK';

        // Replace variables (tanpa tahun dan bulan)
        $serial = str_replace([
            '{UNIT}',
            '{NNNN}',
            '{NNN}',
        ], [
            $unitCode,
            str_pad($counter, 4, '0', STR_PAD_LEFT),
            str_pad($counter, 3, '0', STR_PAD_LEFT),
        ], $format);

        // Increment counter only if requested
        if ($incrementCounter) {
            \App\Models\AparSetting::set($counterKey, $counter + 1);
        }

        return $serial;
    }

    /**
     * Generate and save QR code as SVG file
     */
    public function generateQrSvg($force = false): void
    {
        if (!$force && $this->qr_svg_path && Storage::disk('public')->exists($this->qr_svg_path)) {
            return;
        }

        $url = route('apar.riwayat', $this->id);

        try {
            $qrCode = QrCode::format('svg')
                ->size(300)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($url);

            $filename = 'qrcodes/apar_' . $this->id . '.svg';
            Storage::disk('public')->put($filename, $qrCode);

            $this->update(['qr_svg_path' => $filename]);
        } catch (\Exception $e) {
            \Log::error('Failed to generate QR for APAR ' . $this->id . ': ' . $e->getMessage());
        }
    }

    /**
     * Alias for generateQrSvg() for backward compatibility
     */
    public function refreshQrSvg(): void
    {
        $this->generateQrSvg(true);
    }

    /**
     * Accessor: $apar->qr_url → Generate QR as SVG data URI (no file, no HTTP request!)
     * This generates QR on-the-fly as base64 encoded SVG (works without imagick)
     */
    public function getQrUrlAttribute(): string
    {
        // Generate QR content with equipment info (not URL)
        $qrContent = json_encode([
            'type' => 'APAR',
            'code' => $this->barcode ?? $this->serial_no,
            'serial' => $this->serial_no,
            'location' => $this->location_code ?? '-',
            'status' => $this->status ?? '-',
            'capacity' => $this->capacity ?? '-',
            'type_detail' => $this->type ?? '-',
        ], JSON_UNESCAPED_UNICODE);

        try {
            $qrCode = QrCode::format('svg')
                ->size(300)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($qrContent);

            $base64 = base64_encode($qrCode);
            return 'data:image/svg+xml;base64,' . $base64;
        } catch (\Exception $e) {
            // Fallback placeholder
            return 'data:image/svg+xml;base64,' . base64_encode(
                '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300"><rect width="300" height="300" fill="#f3f4f6"/><text x="150" y="150" text-anchor="middle" font-size="14" fill="#6b7280">QR Error</text></svg>'
            );
        }
    }

    /**
     * Generate QR Code as data URI (base64 encoded PNG)
     * This works on any hosting without file storage issues
     * Usage: <img src="{{ $apar->qr_data_uri }}" />
     * 
     * @return string Base64 data URI
     */
    public function getQrDataUriAttribute(): string
    {
        $url = route('apar.riwayat', $this->id);

        try {
            $qrCode = QrCode::format('png')
                ->size(300)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($url);

            $base64 = base64_encode($qrCode);
            return 'data:image/png;base64,' . $base64;
        } catch (\Exception $e) {
            // Fallback placeholder
            return 'data:image/svg+xml;base64,' . base64_encode(
                '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300"><rect width="300" height="300" fill="#f3f4f6"/><text x="150" y="150" text-anchor="middle" font-size="14" fill="#6b7280">QR Code</text></svg>'
            );
        }
    }

    /**
     * Generate QR Code as SVG data URI (smaller size, better quality)
     * Usage: <img src="{{ $apar->qr_svg_data_uri }}" />
     * 
     * @return string SVG data URI
     */
    public function getQrSvgDataUriAttribute(): string
    {
        $url = route('apar.riwayat', $this->id);

        try {
            $qrCode = QrCode::format('svg')
                ->size(300)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($url);

            $base64 = base64_encode($qrCode);
            return 'data:image/svg+xml;base64,' . $base64;
        } catch (\Exception $e) {
            return 'data:image/svg+xml;base64,' . base64_encode(
                '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300"><rect width="300" height="300" fill="#f3f4f6"/><text x="150" y="150" text-anchor="middle" font-size="14" fill="#6b7280">QR Code</text></svg>'
            );
        }
    }

    /**
     * Relasi ke kartu kendali APAR
     */
    public function kartuApars()
    {
        return $this->hasMany(\App\Models\KartuApar::class, 'apar_id');
    }

    /**
     * Get the floor plan that this equipment belongs to
     */
    public function floorPlan()
    {
        return $this->belongsTo(FloorPlan::class);
    }
}
