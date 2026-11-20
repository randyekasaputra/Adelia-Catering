<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\HistoricalSale;
use App\Services\PopularityCalculatorService;
use Carbon\Carbon;

class HistoricalSalesSeeder extends Seeder
{
    public function run(): void
    {
        $menus = Menu::all();
        if ($menus->isEmpty()) {
            return;
        }

        // Hapus data lama jika ada
        HistoricalSale::truncate();

        $customerTypes = ['instansi', 'hajatan', 'kantor', 'perorangan', 'arisan'];
        $startDate = Carbon::create(2023, 1, 1);
        $endDate = Carbon::create(2025, 12, 31);

        $salesBatch = [];
        $invoiceCounter = 1001;

        // Buat ~250 transaksi historis terdistribusi selama 3 tahun (2023 - 2025)
        for ($i = 0; $i < 250; $i++) {
            $randomDays = rand(0, 1095); // 3 tahun = ~1095 hari
            $transDate = $startDate->copy()->addDays($randomDays);
            $invoiceNo = 'INV-' . $transDate->format('Ym') . '-' . str_pad($invoiceCounter++, 5, '0', STR_PAD_LEFT);
            $custType = $customerTypes[array_rand($customerTypes)];

            // Setiap transaksi memesan 2 - 5 jenis menu
            $itemsCount = rand(2, 5);
            $selectedMenus = $menus->random(min($itemsCount, $menus->count()));

            foreach ($selectedMenus as $menu) {
                // Jumlah porsi: jika hajatan/instansi 50 - 200 porsi, perorangan 10 - 50 porsi
                $qty = in_array($custType, ['instansi', 'hajatan']) ? rand(50, 200) : rand(10, 40);
                $totalPrice = $qty * $menu->price;

                $salesBatch[] = [
                    'invoice_no'       => $invoiceNo,
                    'menu_id'          => $menu->id,
                    'menu_name'        => $menu->name,
                    'category'         => $menu->category,
                    'qty'              => $qty,
                    'unit_price'       => $menu->price,
                    'total_price'      => $totalPrice,
                    'transaction_date' => $transDate->format('Y-m-d'),
                    'customer_type'    => $custType,
                    'created_at'       => $transDate,
                    'updated_at'       => $transDate,
                ];

                // Insert chunk per 100 agar hemat memori
                if (count($salesBatch) >= 100) {
                    HistoricalSale::insert($salesBatch);
                    $salesBatch = [];
                }
            }
        }

        if (!empty($salesBatch)) {
            HistoricalSale::insert($salesBatch);
        }

        // Jalankan kalkulator bobot popularitas otomatis
        $calculator = new PopularityCalculatorService();
        $calculator->calculateAll();
    }
}

