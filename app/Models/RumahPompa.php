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
     * Generate / regenerate QR SVG untuk Rumah Pompa ini.
     * - File disimpan di: storage/app/public/qr/rumah-pompa-{id}.svg
     * - Kolom qr_svg_path akan berisi: "storage/qr/rumah-pompa-{id}.svg"
     */
    public function refreshQrSvg(): void
    {
        $value = $this->barcode ?: ($this->serial_no ?: ('RUMAH-POMPA-' . $this->id));

        // Pastikan folder qr/ ada di disk "public"
        $disk = Storage::disk('public');
        $dir  = 'qr';

        if (! $disk->exists($dir)) {
            $disk->makeDirectory($dir);
        }

        $fileName = 'rumah-pompa-' . $this->id . '.svg';
        $relativePath = $dir . '/' . $fileName;          // qr/rumah-pompa-1.svg
        $publicPath   = 'storage/' . $relativePath;      // storage/qr/rumah-pompa-1.svg

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

    public function kartuInspeksi()
    {
        return $this->hasMany(KartuRumahPompa::class)->latest('tgl_periksa');
    }
}
