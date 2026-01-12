<?php

namespace App\Models;

use App\Models\Traits\HasUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class RumahPompa extends Model
{
    use HasUnit;

    protected $fillable = [
        'user_id',
        'unit_id',
        'name',
        'serial_no',
        'barcode',
        'location_code',
        'type',
        'zone',
        'status',
        'qr_svg_path',
        'notes',
    ];

    protected static function booted(): void
    {
        // Auto-generate serial_no & barcode RP.xxx
        static::creating(function (RumahPompa $rumahPompa) {
            // Kalau belum ada serial_no, generate RP.001, RP.002, dst
            if (empty($rumahPompa->serial_no)) {
                $lastSerial = static::where('serial_no', 'like', 'RP.%')
                    ->orderBy('id', 'desc')
                    ->value('serial_no');

                $nextNumber = 1;

                if ($lastSerial && preg_match('/RP\.(\d+)/', $lastSerial, $m)) {
                    $nextNumber = (int) $m[1] + 1;
                }

                $rumahPompa->serial_no = 'RP.' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }

            // Barcode default = "RUMAH POMPA {serial_no}" kalau belum diisi
            if (empty($rumahPompa->barcode)) {
                $rumahPompa->barcode = 'RUMAH POMPA ' . $rumahPompa->serial_no;
            }
        });

        // Setelah data berhasil dibuat, langsung generate QR
        static::created(function (RumahPompa $rumahPompa) {
            $rumahPompa->refreshQrSvg();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor: $rumahPompa->qr_url → Generate QR as SVG data URI
     */
    public function getQrUrlAttribute(): string
    {
        // Generate QR content with equipment info (not URL)
        $qrContent = json_encode([
            'type' => 'Rumah Pompa',
            'code' => $this->barcode ?? $this->serial_no,
            'serial' => $this->serial_no,
            'location' => $this->location_code ?? '-',
            'status' => $this->status ?? '-',
        ], JSON_UNESCAPED_UNICODE);

        $svg = QrCode::size(300)
            ->format('svg')
            ->margin(1)
            ->errorCorrection('H')
            ->generate($qrContent);

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Generate / regenerate QR SVG untuk Rumah Pompa ini.
     * - File disimpan di: storage/app/public/qr/rumah-pompa-{id}.svg
     * - Kolom qr_svg_path akan berisi: "storage/qr/rumah-pompa-{id}.svg"
     */
    public function refreshQrSvg(): void
    {
        $value = $this->barcode ?: ($this->serial_no ?: ('RUMAH-POMPA-' . $this->id));

        // Pastikan folder qr/ ada di disk "public"
        $disk = Storage::disk('public');
        $dir = 'qr';

        if (!$disk->exists($dir)) {
            $disk->makeDirectory($dir);
        }

        $fileName = 'rumah-pompa-' . $this->id . '.svg';
        $relativePath = $dir . '/' . $fileName;          // qr/rumah-pompa-1.svg
        $publicPath = 'storage/' . $relativePath;      // storage/qr/rumah-pompa-1.svg

        // Generate SVG pakai simple-qrcode
        $svg = QrCode::format('svg')
            ->size(256)
            ->errorCorrection('M')
            ->generate($value);

        $disk->put($relativePath, $svg);

        // Simpan path ke kolom qr_svg_path (biar di Blade tinggal asset($rumahPompa->qr_svg_path))
        $this->qr_svg_path = $publicPath;
        $this->saveQuietly();
    }

    /**
     * Generate next serial number for Rumah Pompa using custom format from settings
     * @param int|null $unitId Unit ID (null = Induk)
     */
    public static function generateNextSerial($unitId = null): string
    {
        $format = \App\Models\AparSetting::get('rumah-pompa_kode_format', 'RP.{NNN}');

        // Determine unit from auth user if not provided
        if ($unitId === null && auth()->check() && auth()->user()->unit_id) {
            $unitId = auth()->user()->unit_id;
        }

        // Counter key based on unit (per-unit independent counter)
        $counterKey = $unitId ? "rumah-pompa_kode_counter_{$unitId}" : "rumah-pompa_kode_counter_induk";
        $counter = (int) \App\Models\AparSetting::get($counterKey, 1);

        // Get unit code for format
        $unitCode = $unitId ? (\App\Models\Unit::find($unitId)?->code ?? 'INDUK') : 'INDUK';

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
        \App\Models\AparSetting::set($counterKey, $counter + 1);

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

        $url = route('rumah-pompa.riwayat', $this->id);

        try {
            $qrCode = QrCode::format('svg')
                ->size(300)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($url);

            $path = 'qrcodes/rumah-pompa/' . $this->serial_no . '.svg';
            Storage::disk('public')->put($path, $qrCode);

            $this->qr_svg_path = $path;
            $this->saveQuietly();
        } catch (\Exception $e) {
            \Log::error('Failed to generate QR for Rumah Pompa: ' . $e->getMessage());
        }
    }

    public function kartuInspeksi()
    {
        return $this->hasMany(KartuRumahPompa::class)->latest('tgl_periksa');
    }

    public function kartuRumahPompas()
    {
        return $this->hasMany(KartuRumahPompa::class, 'rumah_pompa_id');
    }

    /**
     * Get the floor plan that this equipment belongs to
     */
    public function floorPlan()
    {
        return $this->belongsTo(FloorPlan::class);
    }
}
