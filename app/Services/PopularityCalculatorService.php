<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\HistoricalSale;
use App\Models\MenuWeight;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class PopularityCalculatorService
{
    /**
     * Hitung Nilai Popularitas, Keuntungan (Harga Jual - HPP), dan Nilai Utilitas (Weighted Sum)
     * Berdasarkan Data Transaksi Penjualan Historis & Perhitungan HPP (Full Costing).
     *
     * Formula:
     * 1. Popularitas Menu (P_i):
     *    - Volume Normalisasi (V_i) = Qty_i / Max(Qty)
     *    - Frekuensi Normalisasi (F_i) = Trans_i / Max(Trans)
     *    - Popularity Score (P_i) = (0.6 * V_i + 0.4 * F_i) * 100
     *
     * 2. Keuntungan Menu (Pi_i):
     *    - Keuntungan per Porsi (Profit_i) = Harga Jual_i - HPP_i
     *    - Profit Normalisasi per Kategori (ProfitScore_i) = Profit_i / Max_Kategori(Profit)
     *
     * 3. Nilai Utilitas Weighted Sum (U_i):
     *    - U_i = (w_1 * P_i) + (w_2 * (ProfitScore_i * 100))
     *      dengan w_1 + w_2 = 1 (default: w_1 = 0.55 popularitas, w_2 = 0.45 keuntungan)
     */
    public function calculateAll(float $wPopularity = 0.55, float $wProfit = 0.45): array
    {
        // Normalisasi bobot w1 + w2 = 1
        $sumW = $wPopularity + $wProfit;
        if ($sumW <= 0) {
            $wPopularity = 0.55;
            $wProfit = 0.45;
        } else {
            $wPopularity = round($wPopularity / $sumW, 4);
            $wProfit = round($wProfit / $sumW, 4);
        }

        $salesStats = HistoricalSale::select(
                'menu_id',
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('COUNT(DISTINCT invoice_no) as total_trans'),
                DB::raw('SUM(total_price) as total_rev')
            )
            ->whereNotNull('menu_id')
            ->groupBy('menu_id')
            ->get()
            ->keyBy('menu_id');

        $menus = Menu::all();
        if ($menus->isEmpty()) {
            return [
                'success' => false,
                'message' => 'Belum ada data menu di tabel menus.',
                'processed_count' => 0
            ];
        }

        $maxQty = $salesStats->max('total_qty') ?: 1;
        $maxTrans = $salesStats->max('total_trans') ?: 1;

        // Hitung keuntungan maksimum per kategori komponen agar normalisasi keuntungan adil di setiap kategori
        $maxProfitByCategory = [];
        foreach ($menus as $menu) {
            $cat = $menu->component_type ?: 'lauk_utama';
            $profit = $menu->profit;
            if (!isset($maxProfitByCategory[$cat]) || $profit > $maxProfitByCategory[$cat]) {
                $maxProfitByCategory[$cat] = max(1, $profit);
            }
        }

        $hasMenuHppCol = Schema::hasColumn('menus', 'hpp');
        $hasMenuUtilityCol = Schema::hasColumn('menus', 'utility_score');
        $hasWeightHppCol = Schema::hasColumn('menu_weights', 'hpp');

        $processed = 0;

        DB::beginTransaction();
        try {
            foreach ($menus as $menu) {
                $stat = $salesStats->get($menu->id);
                $totalQty = $stat ? (int) $stat->total_qty : 0;
                $totalTrans = $stat ? (int) $stat->total_trans : 0;
                $totalRev = $stat ? (int) $stat->total_rev : 0;

                if ($stat) {
                    $volumeScore = round($totalQty / $maxQty, 4);
                    $freqScore = round($totalTrans / $maxTrans, 4);
                    $popularityScore = round((0.6 * $volumeScore + 0.4 * $freqScore) * 100, 2);
                } else {
                    $popularityScore = (float) ($menu->popularity_score ?: 70.0);
                    $volumeScore = round($popularityScore / 100, 4);
                    $freqScore = round($popularityScore / 100, 4);
                }

                $cat = $menu->component_type ?: 'lauk_utama';
                $hpp = $menu->effective_hpp;
                $profitPerPortion = max(0, (int) $menu->price - $hpp);
                $maxCatProfit = $maxProfitByCategory[$cat] ?? max(1, $profitPerPortion);
                $profitScore = round(min(1.0, $profitPerPortion / $maxCatProfit), 4);

                // Nilai Utilitas Weighted Sum (Skala 0 - 100)
                $utilityScore = round(
                    ($wPopularity * $popularityScore) + ($wProfit * ($profitScore * 100)),
                    2
                );

                $weightData = [
                    'total_qty_sold'     => $totalQty,
                    'total_transactions' => $totalTrans,
                    'total_revenue'      => $totalRev,
                    'volume_score'       => $volumeScore,
                    'frequency_score'    => $freqScore,
                    'popularity_score'   => $popularityScore,
                    'last_calculated_at' => Carbon::now(),
                ];

                if ($hasWeightHppCol) {
                    $weightData['hpp'] = $hpp;
                    $weightData['profit_per_portion'] = $profitPerPortion;
                    $weightData['profit_score'] = $profitScore;
                    $weightData['utility_score'] = $utilityScore;
                }

                MenuWeight::updateOrCreate(
                    ['menu_id' => $menu->id],
                    $weightData
                );

                // Sinkronkan ke tabel menus
                $menuUpdate = [
                    'popularity_score' => $popularityScore,
                ];
                if ($hasMenuHppCol && empty($menu->hpp)) {
                    $menuUpdate['hpp'] = $hpp;
                }
                if ($hasMenuUtilityCol) {
                    $menuUpdate['utility_score'] = $utilityScore;
                }

                Menu::where('id', $menu->id)->update($menuUpdate);

                $processed++;
            }

            DB::commit();

            return [
                'success'         => true,
                'message'         => "Berhasil memperbarui Popularitas, Keuntungan HPP, dan Nilai Utilitas Weighted Sum (w1={$wPopularity}, w2={$wProfit}) untuk {$processed} menu.",
                'processed_count' => $processed,
                'w_popularity'    => $wPopularity,
                'w_profit'        => $wProfit,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success'         => false,
                'message'         => 'Gagal menghitung bobot utilitas: ' . $e->getMessage(),
                'processed_count' => 0
            ];
        }
    }
}
