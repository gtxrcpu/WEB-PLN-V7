<?php

namespace App\Models;

use App\Models\Traits\HasUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Apat extends Model
{
    use HasFactory, HasUnit;

    /**
     * Biar fleksibel: semua field boleh diisi via create/update()
     * (validasi tetap di Controller).
     */
    protected $guarded = [];

    protected $casts = [
        'last_inspection_at' => 'datetime',
        'next_inspection_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        // Auto-generate serial_no & barcode APAT A2.xxx
        static::creating(function (Apat $apat) {
            // Kalau belum ada serial_no, generate A2.001, A2.002, dst
            if (empty($apat->serial_no)) {
                $lastSerial = static::where('serial_no', 'like', 'A2.%')
                    ->orderBy('id', 'desc')
                    ->value('serial_no');

                $nextNumber = 1;

                if ($lastSerial && preg_match('/A2\.(\d+)/', $lastSerial, $m)) {
                    $nextNumber = (int) $m[1] + 1;
                }

                $apat->serial_no = 'A2.' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }

            // Barcode default = serial_no kalau belum diisi
            if (empty($apat->barcode)) {
                $apat->barcode = $apat->serial_no;
            }
        });
    }

    /**
     * Generate next serial number for APAT using custom format from settings
     */
    public static function generateNextSerial($unitCode = null): string
    {
        $format = \App\Models\AparSetting::get('apat_kode_format', 'APAT A2.{NNN}');
        $counter = (int) \App\Models\AparSetting::get('apat_kode_counter', 1);
        
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
        \App\Models\AparSetting::set('apat_kode_counter', $counter + 1);
        
        return $serial;
    }

    /**
     * Accessor: $apat->qr_url → Generate QR as SVG data URI (no file, no HTTP request!)
     * This generates QR on-the-fly as base64 encoded SVG (works without imagick)
     */
    public function getQrUrlAttribute(): string
    {
        // Generate QR content with equipment info (not URL)
        $qrContent = json_encode([
            'type' => 'APAT',
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
     * Generate and save QR code as SVG file
     */
    public function generateQrSvg($force = false): void
    {
        // Skip if already exists and not forcing
        if (!$force && $this->qr_svg_path && Storage::disk('public')->exists($this->qr_svg_path)) {
            return;
        }

        $url = route('apat.riwayat', $this->id);
        
        try {
            $qrCode = QrCode::format('svg')
                ->size(300)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($url);
            
            $path = 'qrcodes/apat/' . $this->serial_no . '.svg';
            Storage::disk('public')->put($path, $qrCode);
            
            $this->qr_svg_path = $path;
            $this->saveQuietly();
        } catch (\Exception $e) {
            \Log::error('Failed to generate QR for APAT: ' . $e->getMessage());
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
     * Relasi ke kartu inspeksi APAT
     */
    public function kartuApats()
    {
        return $this->hasMany(\App\Models\KartuApat::class, 'apat_id');
    }

    /**
     * Relasi ke user yang menginput
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the floor plan that this equipment belongs to
     */
    public function floorPlan()
    {
        return $this->belongsTo(FloorPlan::class);
    }
}
