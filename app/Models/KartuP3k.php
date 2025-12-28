<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuP3k extends Model
{
    use HasFactory;

    protected $table = 'kartu_p3ks';

    protected $fillable = [
        'p3k_id',
        'user_id',
        'kotak_p3k',
        'plester',
        'perban',
        'kasa_steril',
        'antiseptik',
        'gunting',
        'sarung_tangan',
        'masker',
        'obat_luka',
        'buku_panduan',
        'kesimpulan',
        'tgl_periksa',
        'petugas',
    ];

    protected $casts = [
        'tgl_periksa' => 'date',
        'approved_at' => 'datetime',
    ];

    /**
     * Relasi ke P3K
     */
    public function p3k()
    {
        return $this->belongsTo(P3k::class, 'p3k_id');
    }

    /**
     * Relasi ke User
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
