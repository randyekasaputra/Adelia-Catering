<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricalSale extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'transaction_date' => 'date',
        'qty' => 'integer',
        'unit_price' => 'integer',
        'total_price' => 'integer',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}

