<?php

namespace App\Models;

use App\Models\Traits\HasUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Apab extends Model
{
    use HasFactory, HasUnit;

    protected $guarded = [];

    protected $casts = [
        'masa_berlaku' => 'date',
    ];

    protected static function booted(): void
    {
        // Auto-generate serial_no & barcode APAB A3.xxx
        static::creating(function (Apab $apab) {
            if (empty($apab->serial_no)) {
                $lastSerial = static::where('serial_no', 'like', 'A3.%')
                    ->orderBy('id', 'desc')
                    ->value('serial_no');

                $nextNumber = 1;

                if ($lastSerial && preg_match('/A3\.(\d+)/', $lastSerial, $m)) {
                    $nextNumber = (int) $m[1] + 1;
                }

                $apab->serial_no = 'A3.' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }

            if (empty($apab->barcode)) {
                $apab->barcode = 'APAB ' . $apab->serial_no;
            }
        });

    }

    /**
     * Accessor: $apab->qr_url → Generate QR as SVG data URI (no file, no HTTP request!)
     * This generates QR on-the-fly as base64 encoded SVG (works without imagick)
     */
    public function getQrUrlAttribute(): string
    {
        // Generate QR content with equipment info (not URL)
        $qrContent = json_encode([
            'type' => 'APAB',
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
     * Generate next serial number for APAB using custom format from settings
     */
    public static function generateNextSerial($unitCode = null): string
    {
        $format = \App\Models\AparSetting::get('apab_kode_format', 'APAB A3.{NNN}');
        $counter = (int) \App\Models\AparSetting::get('apab_kode_counter', 1);
        
        // Get unit code
        if (!$unitCode && auth()->check() && auth()->user()->unit) {
            $unitCode = auth()->user()->unit->code;
        }
        $unitCode = $unitCode ?? 'INDUK';
        
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
        
        // Increment counter
        \App\Models\AparSetting::set('apab_kode_counter', $counter + 1);
        
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

        $url = route('apab.riwayat', $this->id);
        
        try {
            $qrCode = QrCode::format('svg')
                ->size(300)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($url);
            
            $path = 'qrcodes/apab/' . $this->serial_no . '.svg';
            Storage::disk('public')->put($path, $qrCode);
            
            $this->qr_svg_path = $path;
            $this->saveQuietly();
        } catch (\Exception $e) {
            \Log::error('Failed to generate QR for APAB: ' . $e->getMessage());
        }
    }

    /**
     * Alias for generateQrSvg() for backward compatibility
     */
    public function refreshQrSvg(): void
    {
        $this->generateQrSvg(true);
    }

    public function kartuInspeksi()
    {
        return $this->hasMany(KartuApab::class)->latest('tgl_periksa');
    }

    public function kartuApabs()
    {
        return $this->hasMany(KartuApab::class, 'apab_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the floor plan that this equipment belongs to
     */
    public function floorPlan()
    {
        return $this->belongsTo(FloorPlan::class);
    }
}
