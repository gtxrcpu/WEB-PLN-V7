<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AparSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'description'];

    // Helper method untuk get setting by key
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    // Helper method untuk set setting
    public static function set($key, $value)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
