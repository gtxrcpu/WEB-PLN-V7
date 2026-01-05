<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name','username','email','password','avatar','unit_id','position'];
    protected $hidden = ['password','remember_token'];
    protected $casts = ['email_verified_at' => 'datetime','password' => 'hashed'];

    /**
     * Eager load relationships by default untuk optimasi performa
     */
    protected $with = ['roles'];

    /**
     * Get the avatar URL attribute
     */
    public function getAvatarUrlAttribute()
    {
        // Jika avatar kosong, return default avatar
        if (empty($this->avatar)) {
            return asset('images/default-avatar.png');
        }
        
        // Jika avatar dimulai dengan 'http', return as is (external URL)
        if (str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }
        
        // Jika avatar dimulai dengan 'storage/', gunakan asset() langsung
        if (str_starts_with($this->avatar, 'storage/')) {
            return asset($this->avatar);
        }
        
        // Jika avatar dimulai dengan 'avatars/', cek di storage
        if (str_starts_with($this->avatar, 'avatars/')) {
            return asset('storage/' . $this->avatar);
        }
        
        // Default: gunakan asset()
        return asset($this->avatar);
    }

    // Relasi ke unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // Check apakah user adalah leader
    public function isLeader()
    {
        return $this->position === 'leader';
    }

    // Check apakah user adalah petugas
    public function isPetugas()
    {
        return $this->position === 'petugas';
    }
}
