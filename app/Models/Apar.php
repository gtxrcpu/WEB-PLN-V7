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
    ];

    /**
     * Generate serial berikutnya: A1.001, A1.002, dst.
     */
    public static function generateNextSerial(): string
    {
        $last = static::orderBy('id', 'desc')->first();

        if (!$last || !$last->serial_no) {
            return 'A1.001';
        }

        if (preg_match('/A1\.(\d+)/', $last->serial_no, $m)) {
            $num = (int) $m[1] + 1;
        } else {
            $num = $last->id + 1;
        }

        return 'A1.' . str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate / regenerate QR SVG untuk APAR ini.
     * Disimpan di storage/app/public/qr/apar-{id}.svg
     */
    public function generateQrSvg(bool $force = false): void
    {
        $disk = Storage::disk('public'); // => storage/app/public

        // nama file relatif di dalam disk "public"
        $relativePath = 'qr/apar-' . $this->id . '.svg';

        // Kalau sudah ada & nggak dipaksa, skip
        if (!$force && $this->qr_svg_path && $disk->exists($relativePath)) {
            return;
        }

        // Pastikan folder qr/ ada
        if (!$disk->exists('qr')) {
            $disk->makeDirectory('qr');
        }

        $value = $this->barcode ?: ('APAR ' . ($this->serial_no ?? $this->id));

        $svg = QrCode::format('svg')
            ->size(300)
            ->margin(1)
            ->generate($value);

        // Simpan ke storage/app/public/qr/apar-{id}.svg
        $disk->put($relativePath, $svg);

        // Path publik untuk dipakai asset()
        $this->qr_svg_path = 'storage/' . $relativePath; // storage/qr/apar-{id}.svg
        $this->save();
    }

    /**
     * Accessor: $apar->qr_url → URL ke file SVG
     */
    public function getQrUrlAttribute(): string
    {
        if ($this->qr_svg_path) {
            return asset($this->qr_svg_path);
        }

        return asset('storage/qr/apar-' . $this->id . '.svg');
    }

    /**
     * Wrapper supaya command apar:qr-backfill yang lama tetap jalan.
     */
    public function refreshQrSvg(): void
    {
        $this->generateQrSvg(true);
    }

    /**
     * Relasi ke kartu kendali APAR
     */
    public function kartuApars()
    {
        return $this->hasMany(\App\Models\KartuApar::class, 'apar_id');
    }
}
