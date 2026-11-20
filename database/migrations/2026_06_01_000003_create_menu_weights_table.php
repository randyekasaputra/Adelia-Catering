<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('menu_weights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
            $table->bigInteger('total_qty_sold')->default(0); // Total porsi terjual selama 3 tahun
            $table->integer('total_transactions')->default(0); // Frekuensi pemesanan
            $table->bigInteger('total_revenue')->default(0); // Total rupiah penjualan
            $table->double('frequency_score', 8, 4)->default(0); // Normalisasi frekuensi (0 - 1)
            $table->double('volume_score', 8, 4)->default(0);    // Normalisasi volume porsi (0 - 1)
            $table->double('popularity_score', 8, 2)->default(0);// Nilai Utilitas (vi) untuk Algoritma Dynamic Programming (0 - 100)
            $table->timestamp('last_calculated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('menu_weights');
    }
};

