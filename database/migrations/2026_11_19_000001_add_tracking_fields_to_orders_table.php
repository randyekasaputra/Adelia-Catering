<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'tracking_note')) {
                $table->text('tracking_note')->nullable()->after('status');
            }
            if (!Schema::hasColumn('orders', 'driver_name')) {
                $table->string('driver_name', 100)->nullable()->after('tracking_note');
            }
            if (!Schema::hasColumn('orders', 'driver_phone')) {
                $table->string('driver_phone', 30)->nullable()->after('driver_name');
            }
        });
    }

    public function down(): void {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['tracking_note', 'driver_name', 'driver_phone']);
        });
    }
};
