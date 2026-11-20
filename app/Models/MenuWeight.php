<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuWeight extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'total_qty_sold'     => 'integer',
        'total_transactions' => 'integer',
        'total_revenue'      => 'integer',
        'hpp'                => 'integer',
        'profit_per_portion' => 'integer',
        'frequency_score'    => 'double',
        'volume_score'       => 'double',
        'profit_score'       => 'double',
        'popularity_score'   => 'double',
        'utility_score'      => 'double',
        'last_calculated_at' => 'datetime',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
