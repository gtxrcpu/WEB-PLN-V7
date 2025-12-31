<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'nip',
        'signature_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getSignatureUrlAttribute()
    {
        if (empty($this->signature_path)) {
            return null;
        }
        
        // Cek apakah file exists
        $fullPath = public_path('storage/' . $this->signature_path);
        if (file_exists($fullPath)) {
            return url('storage/' . $this->signature_path);
        }
        
        return null;
    }
}
