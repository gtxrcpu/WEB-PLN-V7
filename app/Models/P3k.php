<?php

namespace App\Models;

use App\Models\Traits\HasUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class P3k extends Model
{
    use HasFactory, HasUnit;

    protected $guarded = [];

    protected static function booted(): void
    {
        // Auto-generate serial_no P3K.xxx
        static::creating(function (P3k $p3k) {
            if (empty($p3k->serial_no)) {
                $lastSerial = static::where('serial_no', 'like', 'P3K.%')
                    ->orderBy('id', 'desc')
                    ->value('serial_no');

                $nextNumber = 1;

                if ($lastSerial && preg_match('/P3K\.(\d+)/', $lastSerial, $m)) {
                    $nextNumber = (int) $m[1] + 1;
                }

                $p3k->serial_no = 'P3K.' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }

            // Barcode default = serial_no
            if (empty($p3k->barcode)) {
                $p3k->barcode = 'P3K ' . $p3k->serial_no;
            }
        });

        // Generate QR setelah dibuat
        static::created(function (P3k $p3k) {
            $p3k->refreshQrSvg();
        });
    }

    /**
     * Generate / regenerate QR SVG
     */
    public function refreshQrSvg(): void
    {
        $value = $this->barcode ?: ($this->serial_no ?: ('P3K-' . $this->id));

        $disk = Storage::disk('public');
        $dir  = 'qr';

        if (! $disk->exists($dir)) {
            $disk->makeDirectory($dir);
        }

        $fileName = 'p3k-' . $this->id . '.svg';
        $relativePath = $dir . '/' . $fileName;
        $publicPath   = 'storage/' . $relativePath;

        $svg = QrCode::format('svg')
            ->size(256)
            ->errorCorrection('M')
            ->generate($value);

        $disk->put($relativePath, $svg);

        $this->qr_svg_path = $publicPath;
        $this->saveQuietly();
    }

    /**
     * Relasi ke kartu inspeksi P3K
     */
    public function kartuP3ks()
    {
        return $this->hasMany(KartuP3k::class, 'p3k_id');
    }

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
