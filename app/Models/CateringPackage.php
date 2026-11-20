<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateringPackage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'price' => 'integer',
        'target_budget' => 'integer',
        'fitness_score' => 'double',
        'popularity_score' => 'double',
        'is_recommended' => 'boolean',
        'is_custom' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(PackageItem::class, 'package_id');
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'package_items', 'package_id', 'menu_id')
            ->withPivot('component_type', 'qty', 'item_price')
            ->withTimestamps();
    }
}

