<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSetting extends Model {
    use HasFactory;
    protected $guarded = [];

    /**
     * Get a setting value by key, with optional default.
     */
    public static function getValue(string $key, $default = null) {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function setValue(string $key, $value) {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
