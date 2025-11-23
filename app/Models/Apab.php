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

        static::created(function (Apab $apab) {
            $apab->refreshQrSvg();
        });
    }

    public function refreshQrSvg(): void
    {
        $value = $this->barcode ?: ($this->serial_no ?: ('APAB-' . $this->id));

        $disk = Storage::disk('public');
        $dir  = 'qr';

        if (! $disk->exists($dir)) {
            $disk->makeDirectory($dir);
        }

        $fileName = 'apab-' . $this->id . '.svg';
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

    public function kartuInspeksi()
    {
        return $this->hasMany(KartuApab::class)->latest('tgl_periksa');
    }
}
