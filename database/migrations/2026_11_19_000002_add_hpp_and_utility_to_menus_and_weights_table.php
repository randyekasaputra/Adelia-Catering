<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            if (!Schema::hasColumn('menus', 'hpp')) {
                $table->integer('hpp')->default(0)->after('price'); // Harga Pokok Produksi (bahan baku, packing, produksi)
            }
            if (!Schema::hasColumn('menus', 'utility_score')) {
                $table->double('utility_score', 8, 2)->default(0)->after('popularity_score'); // Nilai Utilitas Weighted Sum (Popularitas + Keuntungan)
            }
        });

        Schema::table('menu_weights', function (Blueprint $table) {
            if (!Schema::hasColumn('menu_weights', 'hpp')) {
                $table->integer('hpp')->default(0)->after('total_revenue');
            }
            if (!Schema::hasColumn('menu_weights', 'profit_per_portion')) {
                $table->integer('profit_per_portion')->default(0)->after('hpp');
            }
            if (!Schema::hasColumn('menu_weights', 'profit_score')) {
                $table->double('profit_score', 8, 4)->default(0)->after('volume_score');
            }
            if (!Schema::hasColumn('menu_weights', 'utility_score')) {
                $table->double('utility_score', 8, 2)->default(0)->after('popularity_score');
            }
        });

        // Inisialisasi HPP & Utility Score realistis untuk menu yang sudah ada jika masih 0
        try {
            DB::statement("UPDATE menus SET hpp = ROUND((price * 0.68) / 100) * 100 WHERE hpp = 0 OR hpp IS NULL");
            DB::statement("UPDATE menus SET utility_score = ROUND((0.55 * popularity_score) + (0.45 * 78.0), 2) WHERE utility_score = 0 OR utility_score IS NULL");
        } catch (\Throwable $e) {
            // Abaikan jika tabel belum terisi
        }
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('menus', 'hpp')) $cols[] = 'hpp';
            if (Schema::hasColumn('menus', 'utility_score')) $cols[] = 'utility_score';
            if (!empty($cols)) $table->dropColumn($cols);
        });

        Schema::table('menu_weights', function (Blueprint $table) {
            $cols = [];
            foreach (['hpp', 'profit_per_portion', 'profit_score', 'utility_score'] as $col) {
                if (Schema::hasColumn('menu_weights', $col)) $cols[] = $col;
            }
            if (!empty($cols)) $table->dropColumn($cols);
        });
    }
};
