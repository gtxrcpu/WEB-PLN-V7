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

        // Setelah data berhasil dibuat, langsung generate QR
        static::created(function (Apat $apat) {
            $apat->refreshQrSvg();
        });
    }

    /**
     * Generate / regenerate QR SVG untuk APAT ini.
     * - File disimpan di: storage/app/public/qr/apat-{id}.svg
     * - Kolom qr_svg_path akan berisi: "storage/qr/apat-{id}.svg"
     */
    public function refreshQrSvg(): void
    {
        $value = $this->barcode ?: ($this->serial_no ?: ('APAT-' . $this->id));

        // Pastikan folder qr/ ada di disk "public"
        $disk = Storage::disk('public');
        $dir  = 'qr';

        if (! $disk->exists($dir)) {
            $disk->makeDirectory($dir);
        }

        $fileName = 'apat-' . $this->id . '.svg';
        $relativePath = $dir . '/' . $fileName;          // qr/apat-1.svg
        $publicPath   = 'storage/' . $relativePath;      // storage/qr/apat-1.svg

        // Generate SVG pakai simple-qrcode
        $svg = QrCode::format('svg')
            ->size(256)
            ->errorCorrection('M')
            ->generate($value);

        $disk->put($relativePath, $svg);

        // Simpan path ke kolom qr_svg_path (biar di Blade tinggal asset($apat->qr_svg_path))
        $this->qr_svg_path = $publicPath;
        $this->saveQuietly();
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
}
