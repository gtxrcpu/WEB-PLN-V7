<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KartuBoxHydrant extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tgl_periksa' => 'date',
        'approved_at' => 'datetime',
    ];

    public function boxHydrant(): BelongsTo
    {
        return $this->belongsTo(BoxHydrant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function signature(): BelongsTo
    {
        return $this->belongsTo(Signature::class);
    }

    public function isApproved(): bool
    {
        return !is_null($this->approved_at);
    }
}
