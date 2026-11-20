<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('menus', function (Blueprint $table) {
            $table->string('component_type')->default('lauk_utama')->after('category');
            // Nilai: 'karbohidrat', 'lauk_utama', 'lauk_pendamping', 'sayur', 'snack', 'paket_lengkap'
            $table->integer('calories')->default(0)->after('price');
            $table->double('popularity_score', 8, 2)->default(0)->after('tag');
        });
    }

    public function down(): void {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn(['component_type', 'calories', 'popularity_score']);
        });
    }
};

