<?php

namespace App\Models;

use App\Models\Traits\HasUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class FireAlarm extends Model
{
    use HasUnit;
    
    protected $fillable = [
        'user_id',
        'unit_id',
        'name',
        'barcode',
        'serial_no',
        'location_code',
        'type',
        'zone',
        'status',
        'notes',
        'qr_svg_path',
    ];

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate QR Code SVG dan simpan ke storage
     */
    public function refreshQrSvg(): void
    {
        $qrContent = $this->barcode ?? $this->serial_no ?? 'FA-' . $this->id;
        
        $svg = QrCode::size(300)
            ->format('svg')
            ->generate($qrContent);

        $filename = 'fire-alarm-' . $this->id . '.svg';
        $path = 'qr/' . $filename;

        Storage::disk('public')->put($path, $svg);

        $this->qr_svg_path = 'storage/' . $path;
        $this->saveQuietly();
    }

    /**
     * Generate next serial number for Fire Alarm using custom format from settings
     */
    public static function generateNextSerial($unitCode = null): string
    {
        $format = \App\Models\AparSetting::get('fire-alarm_kode_format', 'FA.{NNN}');
        $counter = (int) \App\Models\AparSetting::get('fire-alarm_kode_counter', 1);
        
        // Get unit code
        if (!$unitCode && auth()->check() && auth()->user()->unit) {
            $unitCode = auth()->user()->unit->code;
        }
        $unitCode = $unitCode ?? 'INDUK';
        
        // Replace variables (no year/month)
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
        \App\Models\AparSetting::set('fire-alarm_kode_counter', $counter + 1);
        
        return $serial;
    }

    /**
     * Accessor: $fireAlarm->qr_url → Generate QR as SVG data URI (no file, no HTTP request!)
     * This generates QR on-the-fly as base64 encoded SVG (works without imagick)
     */
    public function getQrUrlAttribute(): string
    {
        // Generate QR content with equipment info (not URL)
        $qrContent = json_encode([
            'type' => 'Fire Alarm',
            'code' => $this->barcode ?? $this->serial_no,
            'serial' => $this->serial_no,
            'location' => $this->location_code ?? '-',
            'status' => $this->status ?? '-',
            'zone' => $this->zone ?? '-',
        ], JSON_UNESCAPED_UNICODE);
        
        $svg = QrCode::size(300)
            ->format('svg')
            ->margin(1)
            ->errorCorrection('H')
            ->generate($qrContent);
        
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Generate and save QR code as SVG file
     */
    public function generateQrSvg($force = false): void
    {
        if (!$force && $this->qr_svg_path && Storage::disk('public')->exists($this->qr_svg_path)) {
            return;
        }

        $url = route('fire-alarm.riwayat', $this->id);
        
        try {
            $qrCode = QrCode::format('svg')
                ->size(300)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($url);
            
            $path = 'qrcodes/fire-alarm/' . $this->serial_no . '.svg';
            Storage::disk('public')->put($path, $qrCode);
            
            $this->qr_svg_path = $path;
            $this->saveQuietly();
        } catch (\Exception $e) {
            \Log::error('Failed to generate QR for Fire Alarm: ' . $e->getMessage());
        }
    }

    public function kartuInspeksi()
    {
        return $this->hasMany(KartuFireAlarm::class)->latest('tgl_periksa');
    }

    public function kartuFireAlarms()
    {
        return $this->hasMany(KartuFireAlarm::class, 'fire_alarm_id');
    }

    /**
     * Get the floor plan that this equipment belongs to
     */
    public function floorPlan()
    {
        return $this->belongsTo(FloorPlan::class);
    }
}
