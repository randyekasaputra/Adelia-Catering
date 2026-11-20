<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'event_date'   => 'datetime',
        'total_amount' => 'integer',
        'dp_amount'    => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Label Status Ramah Pengguna (Bahasa Indonesia)
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'Menunggu Pembayaran',
            'paid'      => 'Pembayaran Diterima (Slot Terkunci)',
            'cooking'   => 'Sedang Dimasak di Dapur',
            'shipping'  => 'Dalam Pengiriman Kurir',
            'completed' => 'Pesanan Selesai & Diterima',
            'cancelled' => 'Pesanan Dibatalkan',
            default     => ucfirst($this->status),
        };
    }

    /**
     * Deskripsi Detail Tahapan untuk Tracking Pelanggan
     */
    public function getStatusDescriptionAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'Pesanan Anda telah dicatat. Silakan lakukan pembayaran DP untuk mengamankan kuota dan tanggal acara.',
            'paid'      => 'Pembayaran DP telah berhasil diverifikasi. Tanggal acara Anda telah terkunci di kalender produksi katering.',
            'cooking'   => 'Chef & tim dapur Adelia Catering sedang meracik bahan segar dan memasak hidangan pesanan Anda dengan higienis.',
            'shipping'  => 'Pesanan telah dipacking rapi dan dalam perjalanan pengiriman oleh kurir menuju alamat lokasi acara Anda.',
            'completed' => 'Pesanan telah tiba dengan selamat dan diterima di lokasi acara. Terima kasih telah mempercayai Adelia Catering!',
            'cancelled' => 'Pesanan ini telah dibatalkan. Silakan hubungi admin kami via WhatsApp jika memerlukan bantuan.',
            default     => 'Status pesanan sedang diperbarui.',
        };
    }

    /**
     * Indeks Stepper (1 s/d 5) untuk Timeline Tracking
     */
    public function getStatusStepAttribute(): int
    {
        return match ($this->status) {
            'pending'   => 1,
            'paid'      => 2,
            'cooking'   => 3,
            'shipping'  => 4,
            'completed' => 5,
            default     => 0, // cancelled or unknown
        };
    }

    /**
     * Ikon FontAwesome untuk Tiap Tahapan
     */
    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'fa-credit-card',
            'paid'      => 'fa-circle-check',
            'cooking'   => 'fa-fire-burner',
            'shipping'  => 'fa-truck-fast',
            'completed' => 'fa-box-open',
            'cancelled' => 'fa-circle-xmark',
            default     => 'fa-clock',
        };
    }

    /**
     * Style Badge Tailwind
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'bg-amber-50 text-amber-700 border-amber-200',
            'paid'      => 'bg-blue-50 text-blue-700 border-blue-200',
            'cooking'   => 'bg-orange-50 text-orange-700 border-orange-200',
            'shipping'  => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
            default     => 'bg-gray-50 text-gray-700 border-gray-200',
        };
    }
}
