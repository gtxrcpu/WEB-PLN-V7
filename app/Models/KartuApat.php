<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuApat extends Model
{
    use HasFactory;

    protected $table = 'kartu_apats';

    protected $fillable = [
        'apat_id',
        'kondisi_fisik',
        'drum',
        'aduk_pasir',
        'sekop',
        'fire_blanket',
        'ember',
        'kesimpulan',
        'tgl_periksa',
        'tgl_surat',
        'petugas',
        'pengawas',
    ];

    protected $casts = [
        'tgl_periksa' => 'date',
        'approved_at' => 'datetime',
    ];

    public function apat()
    {
        return $this->belongsTo(Apat::class);
    }

    /**
     * Relasi ke User yang menginput
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke User yang approve
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if approved
     */
    public function isApproved()
    {
        return !is_null($this->approved_at);
    }
}
