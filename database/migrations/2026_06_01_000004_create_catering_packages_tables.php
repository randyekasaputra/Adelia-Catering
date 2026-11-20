<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Tabel Paket Katering (Hasil Optimasi Algoritma Dynamic Programming)
        Schema::create('catering_packages', function (Blueprint $table) {
            $table->id();
            $table->string('package_code')->unique();
            $table->string('name');
            $table->string('slug')->index();
            $table->text('description')->nullable();
            $table->integer('price'); // Total harga per porsi paket
            $table->integer('target_budget')->default(0); // Batas budget acuan
            $table->double('fitness_score', 8, 4)->default(0); // Nilai skor optimalitas Dynamic Programming
            $table->double('popularity_score', 8, 2)->default(0); // Akumulasi bobot popularitas 3 tahun
            $table->string('image')->nullable();
            $table->boolean('is_recommended')->default(false); // Paket unggulan di beranda
            $table->boolean('is_custom')->default(false); // Paket custom dari input pembeli
            $table->string('created_by')->default('algorithm'); // algorithm, admin, customer
            $table->timestamps();
        });

        // Tabel Rincian Menu Penyusun Paket
        Schema::create('package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('catering_packages')->cascadeOnDelete();
            $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
            $table->string('component_type'); // karbohidrat, lauk_utama, lauk_pendamping, sayur, snack
            $table->integer('qty')->default(1);
            $table->integer('item_price')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('package_items');
        Schema::dropIfExists('catering_packages');
    }
};

