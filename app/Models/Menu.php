<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'price'            => 'integer',
        'hpp'              => 'integer',
        'calories'         => 'integer',
        'popularity_score' => 'double',
        'utility_score'    => 'double',
    ];

    /**
     * Harga Pokok Produksi (HPP) efektif:
     * Jika kolom hpp > 0 gunakan nilai tersebut, jika belum diisi gunakan estimasi Full Costing (~68% harga jual, dibulatkan ratusan).
     */
    public function getEffectiveHppAttribute(): int
    {
        if (!empty($this->hpp) && $this->hpp > 0) {
            return (int) $this->hpp;
        }
        return (int) (round(($this->price * 0.68) / 100) * 100);
    }

    /**
     * Keuntungan per porsi (Harga Jual - HPP)
     */
    public function getProfitAttribute(): int
    {
        return max(0, (int) $this->price - $this->effective_hpp);
    }

    /**
     * Persentase margin keuntungan terhadap harga jual
     */
    public function getProfitMarginPercentAttribute(): float
    {
        if ($this->price <= 0) {
            return 0.0;
        }
        return round(($this->profit / $this->price) * 100, 1);
    }

    /**
     * Nilai utilitas gabungan (Weighted Sum Popularitas & Keuntungan)
     */
    public function getEffectiveUtilityAttribute(): float
    {
        if (!empty($this->utility_score) && $this->utility_score > 0) {
            return (float) $this->utility_score;
        }
        return (float) ($this->popularity_score ?: 75.0);
    }

    /**
     * Relasi ke bobot popularitas & keuntungan historis 3 tahun
     */
    public function weight()
    {
        return $this->hasOne(MenuWeight::class);
    }

    /**
     * Relasi ke riwayat transaksi penjualan 3 tahun
     */
    public function historicalSales()
    {
        return $this->hasMany(HistoricalSale::class);
    }

    /**
     * Relasi ke item paket
     */
    public function packageItems()
    {
        return $this->hasMany(PackageItem::class);
    }

    /**
     * Scope untuk mengambil menu berdasarkan komponen paket
     */
    public function scopeByComponent($query, string $componentType)
    {
        return $query->where('component_type', $componentType);
    }
}
