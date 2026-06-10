<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('booking_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value');
            $table->timestamps();
        });

        // Insert default settings
        DB::table('booking_settings')->insert([
            ['key' => 'max_orders_per_day', 'value' => '5', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'min_advance_days', 'value' => '3', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
    public function down(): void {
        Schema::dropIfExists('booking_settings');
    }
};
