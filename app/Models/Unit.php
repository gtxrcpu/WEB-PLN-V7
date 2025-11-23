<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke users
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Get leader dari unit ini
    public function leader()
    {
        return $this->hasOne(User::class)->where('position', 'leader');
    }

    // Get petugas dari unit ini
    public function petugas()
    {
        return $this->hasMany(User::class)->where('position', 'petugas');
    }
}
