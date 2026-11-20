<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('historical_sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable()->index();
            $table->foreignId('menu_id')->nullable()->constrained('menus')->nullOnDelete();
            $table->string('menu_name');
            $table->string('category')->default('makanan');
            $table->integer('qty')->default(1);
            $table->integer('unit_price')->default(0);
            $table->bigInteger('total_price')->default(0);
            $table->date('transaction_date')->index();
            $table->string('customer_type')->nullable(); // instansi, hajatan, kantor, perorangan
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('historical_sales');
    }
};

