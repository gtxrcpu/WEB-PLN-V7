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

    public function kartuInspeksi()
    {
        return $this->hasMany(KartuFireAlarm::class)->latest('tgl_periksa');
    }
}
