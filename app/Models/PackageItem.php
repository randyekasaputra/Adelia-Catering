<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'qty' => 'integer',
        'item_price' => 'integer',
    ];

    public function package()
    {
        return $this->belongsTo(CateringPackage::class, 'package_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}

