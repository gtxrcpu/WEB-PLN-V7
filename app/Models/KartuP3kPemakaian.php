<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuP3kPemakaian extends Model
{
    use HasFactory;

    protected $table = 'kartu_p3k_pemakaian';

    protected $fillable = [
        'p3k_id',
        'user_id',
        'item_digunakan',
        'jumlah',
        'keperluan',
        'nama_pengguna',
        'kesimpulan',
        'tgl_pemakaian',
        'petugas',
        'catatan',
        'approved_by',
        'approved_at',
        'signature_id',
    ];

    protected $casts = [
        'tgl_pemakaian' => 'date',
        'approved_at' => 'datetime',
    ];

    public function p3k()
    {
        return $this->belongsTo(P3k::class, 'p3k_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function signature()
    {
        return $this->belongsTo(Signature::class);
    }

    public function isApproved()
    {
        return !is_null($this->approved_at);
    }
}
