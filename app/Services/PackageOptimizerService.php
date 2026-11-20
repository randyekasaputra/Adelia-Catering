<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Facades\Schema;

class PackageOptimizerService
{
    /**
     * Kategori 5 komponen penyusun paket hidangan katering
     */
    protected array $categories = [
        'karbohidrat',
        'lauk_utama',
        'lauk_pendamping',
        'sayur',
        'snack'
    ];

    /**
     * Kategori inti wajib (harus terpilih minimal 1 jika tidak dinonaktifkan manual)
     */
    protected array $coreCategories = [
        'karbohidrat',
        'lauk_utama',
    ];

    /**
     * Optimasi Pemilihan Kombinasi Paket Menu Katering
     * Berbasis Selective Multiple-Choice Knapsack Problem (SMCKP) dan Dynamic Programming (DP)
     * Dengan Fungsi Tujuan Berbobot (Weighted Sum: Popularitas Transaksi & Keuntungan HPP)
     *
     * Formulasi Matematis SMCKP:
     *   Maksimalkan Z = \sum_{k=1}^{K} \sum_{j \in C_k} u_{kj} \cdot x_{kj}
     *   dengan:
     *     u_{kj} = (w_1 \cdot \hat{P}_{kj}) + (w_2 \cdot \hat{\Pi}_{kj})
     *   Kendala:
     *     1. \sum_{k=1}^{K} \sum_{j \in C_k} c_{kj} \cdot x_{kj} \le B   (Batas Anggaran Pelanggan)
     *     2. \sum_{j \in C_k} x_{kj} = 1,   \forall k \in K_{wajib}       (Kategori Inti)
     *     3. \sum_{j \in C_k} x_{kj} \le 1, \forall k \in K_{opsional}    (Kategori Opsional / Selective)
     *     4. x_{kj} \in \{0, 1\}
     *
     * Rekurensi Dynamic Programming (Prinsip Optimalitas Bellman untuk SMCKP):
     *   DP(k, w) = \max \begin{cases}
     *     DP(k-1, w) & \text{jika } k \in K_{opsional} \text{ (dilewati / skip)} \\
     *     \max_{j \in C_k, w \ge c_{kj}} \{ DP(k-1, w - c_{kj}) + u_{kj} \}
     *   \end{cases}
     *
     * @param int        $budget             Anggaran maksimal per porsi (Rp)
     * @param string     $preference         Filter lauk utama ('all', 'ayam', 'daging', 'ikan')
     * @param array|null $activeCategories   Daftar kategori yang diaktifkan pelanggan (null = semua 5 kategori)
     * @param float      $wPopularity        Bobot popularitas (w1, default 0.55)
     * @param float      $wProfit            Bobot keuntungan HPP (w2, default 0.45)
     * @return array Hasil optimasi paket menu secara eksak (global optimum SMCKP)
     */
    public function optimize(
        int $budget,
        string $preference = 'all',
        $activeCategories = null,
        float $wPopularity = 0.55,
        float $wProfit = 0.45
    ): array {
        $startTime = microtime(true);

        // Normalisasi kompatibilitas jika parameter ke-3 dipanggil dengan integer legasi ($populationSize)
        if (!is_array($activeCategories)) {
            $activeCategories = $this->categories;
        }
        $activeCategories = array_values(array_intersect($this->categories, $activeCategories));
        if (empty($activeCategories)) {
            $activeCategories = $this->categories;
        }

        // Normalisasi bobot Weighted Sum (w1 + w2 = 1)
        $sumW = $wPopularity + $wProfit;
        if ($sumW <= 0) {
            $wPopularity = 0.55;
            $wProfit = 0.45;
        } else {
            $wPopularity = round($wPopularity / $sumW, 4);
            $wProfit = round($wProfit / $sumW, 4);
        }

        // 1. Ambil kandidat menu per kategori beserta nilai utilitas Weighted Sum (Popularitas + Keuntungan HPP)
        $candidates = $this->getCandidates($preference, $wPopularity, $wProfit);

        foreach ($activeCategories as $type) {
            if (empty($candidates[$type])) {
                return [
                    'success'   => false,
                    'algorithm' => 'Dynamic Programming (Exact SMCKP - Weighted Sum)',
                    'message'   => "Katalog menu untuk kategori '{$type}' belum mencukupi untuk membentuk paket.",
                ];
            }
        }

        // 2. Hitung Batas Bawah Anggaran Minimum:
        //    - $minCoreBudget: anggaran minimal untuk kategori inti (karbohidrat + lauk_utama yang aktif)
        //    - $minFullBudget: anggaran minimal jika seluruh kategori yang dicentang dipilih semua
        $minCoreBudget = 0;
        $minFullBudget = 0;
        foreach ($activeCategories as $type) {
            $minPriceInCat = min(array_column($candidates[$type], 'price'));
            $minFullBudget += $minPriceInCat;
            if (in_array($type, $this->coreCategories, true)) {
                $minCoreBudget += $minPriceInCat;
            }
        }
        if ($minCoreBudget === 0) {
            $minCoreBudget = $minFullBudget;
        }

        if ($budget < $minCoreBudget) {
            $executionTimeMs = round((microtime(true) - $startTime) * 1000, 2);
            return [
                'success'             => false,
                'algorithm'           => 'Dynamic Programming (Exact SMCKP - Weighted Sum)',
                'message'             => "Budget Rp " . number_format($budget, 0, ',', '.') . " belum mencukupi untuk komponen menu inti. Minimal anggaran yang dibutuhkan adalah Rp " . number_format($minCoreBudget, 0, ',', '.') . " (atau Rp " . number_format($minFullBudget, 0, ',', '.') . " untuk paket lengkap).",
                'min_budget_required' => $minCoreBudget,
                'min_full_budget'     => $minFullBudget,
                'execution_time_ms'   => $executionTimeMs,
            ];
        }

        // Apakah budget mencukupi untuk mengisi seluruh kategori yang diaktifkan pelanggan?
        // Jika cukup, seluruh kategori yang dicentang pelanggan akan terisi penuh.
        // Jika anggaran di antara $minCoreBudget dan $minFullBudget, fitur Selective (SMCKP) otomatis mengizinkan skip kategori opsional.
        $allowAutoSelectiveSkip = ($budget < $minFullBudget);

        // 3. Inisialisasi Tabel Dynamic Programming (Forward SMCKP)
        // State: dp[k][w] = ['utility' => float, 'item' => array|null, 'prev_cost' => int]
        $dp = [];
        $dp[0] = [
            0 => [
                'utility'   => 0.0,
                'item'      => null,
                'prev_cost' => null,
            ]
        ];

        $stageProgress = [];

        // 4. Transisi Dynamic Programming Antar Kategori (Stage k = 1..5)
        foreach ($this->categories as $stageIdx => $catName) {
            $k = $stageIdx + 1;
            $dp[$k] = [];
            $prevStates = $dp[$stageIdx];

            $isCategoryActive = in_array($catName, $activeCategories, true);
            $isOptionalCategory = !in_array($catName, $this->coreCategories, true);

            // A. Transisi Skip Kategori (x_{kj} = 0 untuk semua j di C_k):
            // Berlaku jika kategori tidak dicentang pelanggan (SMCKP Selective)
            // ATAU kategori bersifat opsional saat anggaran terbatas
            if (!$isCategoryActive || ($isOptionalCategory && $allowAutoSelectiveSkip)) {
                foreach ($prevStates as $prevCost => $stateData) {
                    $dp[$k][$prevCost] = [
                        'utility'   => $stateData['utility'],
                        'item'      => $this->createSkippedItemPlaceholder($catName),
                        'prev_cost' => $prevCost,
                    ];
                }
            }

            // B. Transisi Memilih 1 Alternatif Menu pada Kategori Aktif (\sum x_{kj} = 1)
            if ($isCategoryActive) {
                $items = $candidates[$catName];

                foreach ($prevStates as $prevCost => $stateData) {
                    $prevUtility = $stateData['utility'];

                    foreach ($items as $item) {
                        $newCost = $prevCost + (int) $item['price'];

                        // Kendala Kapasitas Anggaran: Total harga jual <= Budget pelanggan
                        if ($newCost > $budget) {
                            continue;
                        }

                        $newUtility = $prevUtility + (float) $item['utility_score'];

                        // Prinsip Optimalitas Bellman:
                        // Simpan kombinasi dengan Nilai Utilitas Weighted Sum tertinggi pada setiap state biaya
                        if (!isset($dp[$k][$newCost]) || $newUtility > $dp[$k][$newCost]['utility']) {
                            $dp[$k][$newCost] = [
                                'utility'   => $newUtility,
                                'item'      => $item,
                                'prev_cost' => $prevCost,
                            ];
                        }
                    }
                }
            }

            if (empty($dp[$k])) {
                $executionTimeMs = round((microtime(true) - $startTime) * 1000, 2);
                return [
                    'success'             => false,
                    'algorithm'           => 'Dynamic Programming (Exact SMCKP - Weighted Sum)',
                    'message'             => "Tidak ditemukan kombinasi menu yang memenuhi batas anggaran Rp " . number_format($budget, 0, ',', '.') . " pada kategori '{$catName}'.",
                    'min_budget_required' => $minCoreBudget,
                    'execution_time_ms'   => $executionTimeMs,
                ];
            }

            $maxStageUtil = max(array_column($dp[$k], 'utility'));
            $stageProgress[] = round($maxStageUtil, 2);
        }

        // 5. Pencarian Solusi Global Optimal pada Stage Terakhir (k = 5)
        $lastStage = count($this->categories);
        $bestCost = null;
        $bestUtility = -1.0;
        $totalStatesExplored = count($dp[$lastStage]);

        foreach ($dp[$lastStage] as $cost => $stateData) {
            $util = $stateData['utility'];

            if ($util > $bestUtility) {
                $bestUtility = $util;
                $bestCost = $cost;
            } elseif (abs($util - $bestUtility) < 0.0001) {
                // Tie-breaker: Jika utilitas sama persis, pilih yang biaya totalnya lebih hemat
                if ($bestCost === null || $cost < $bestCost) {
                    $bestCost = $cost;
                }
            }
        }

        if ($bestCost === null) {
            $executionTimeMs = round((microtime(true) - $startTime) * 1000, 2);
            return [
                'success'             => false,
                'algorithm'           => 'Dynamic Programming (Exact SMCKP - Weighted Sum)',
                'message'             => "Gagal menemukan kombinasi paket menu yang sesuai dengan batas anggaran.",
                'min_budget_required' => $minCoreBudget,
                'execution_time_ms'   => $executionTimeMs,
            ];
        }

        // 6. Backtracking (Penelusuran Balik) untuk Mengambil Kombinasi Menu Terpilih
        $selectedItems = [];
        $currentCost = $bestCost;

        for ($k = $lastStage; $k >= 1; $k--) {
            $catName = $this->categories[$k - 1];
            $state = $dp[$k][$currentCost];
            $selectedItems[$catName] = $state['item'];
            $currentCost = $state['prev_cost'];
        }

        $orderedItems = [
            'karbohidrat'     => $selectedItems['karbohidrat'],
            'lauk_utama'      => $selectedItems['lauk_utama'],
            'lauk_pendamping' => $selectedItems['lauk_pendamping'],
            'sayur'           => $selectedItems['sayur'],
            'snack'           => $selectedItems['snack'],
        ];

        // Pisahkan item yang terpilih vs kategori yang dilewati (skipped)
        $chosenOnly = array_filter($orderedItems, fn($it) => empty($it['is_skipped']));
        $skippedCategories = array_keys(array_filter($orderedItems, fn($it) => !empty($it['is_skipped'])));
        $selectedCount = max(1, count($chosenOnly));

        // 7. Kalkulasi Metrik Agregat (Harga Jual, HPP, Keuntungan, Popularitas, Nilai Utilitas)
        $totalPrice = array_sum(array_column($chosenOnly, 'price'));
        $totalHpp = array_sum(array_column($chosenOnly, 'hpp'));
        $totalProfit = array_sum(array_column($chosenOnly, 'profit'));
        $profitMarginPercent = $totalPrice > 0 ? round(($totalProfit / $totalPrice) * 100, 1) : 0.0;
        $totalCalories = array_sum(array_column($chosenOnly, 'calories'));
        $avgPopularity = round(array_sum(array_column($chosenOnly, 'popularity_score')) / $selectedCount, 1);
        $avgProfitScore = round(array_sum(array_column($chosenOnly, 'profit_score')) / $selectedCount, 1);
        $avgUtility = round($bestUtility / $selectedCount, 2);
        $executionTimeMs = round((microtime(true) - $startTime) * 1000, 2);

        // 8. Kembalikan Response Lengkap Sesuai Rumusan Masalah Skripsi (SMCKP + Weighted Sum + DP)
        return [
            'success'                   => true,
            'algorithm'                 => 'Dynamic Programming (SMCKP - Weighted Sum)',
            'model'                     => 'Selective Multiple-Choice Knapsack Problem (SMCKP)',
            'sdlc_methodology'          => 'Waterfall',
            'budget'                    => $budget,
            'total_price'               => $totalPrice,
            'total_hpp'                 => $totalHpp,
            'total_profit'              => $totalProfit,
            'profit_margin_percent'     => $profitMarginPercent,
            'remaining_budget'          => max(0, $budget - $totalPrice),
            'is_overbudget'             => false,
            'total_calories'            => $totalCalories,
            'average_popularity'        => $avgPopularity,
            'average_profit_score'      => $avgProfitScore,
            'average_utility'           => $avgUtility,
            'total_utility'             => round($bestUtility, 2),
            'fitness_score'             => round($bestUtility, 2), // Kompatibilitas view
            'weights'                   => [
                'w_popularity' => $wPopularity,
                'w_profit'     => $wProfit,
            ],
            'selected_categories_count' => count($chosenOnly),
            'active_categories'         => $activeCategories,
            'skipped_categories'        => $skippedCategories,
            'execution_time_ms'         => $executionTimeMs,
            'dp_states_evaluated'       => $totalStatesExplored,
            'items'                     => $orderedItems,
            'history_fitness'           => $stageProgress,
        ];
    }

    /**
     * Ambil kandidat menu dari database Adelia Catering beserta perhitungan:
     * - Popularitas (dari transaksi penjualan)
     * - Keuntungan (Harga Jual - HPP)
     * - Nilai Utilitas Weighted Sum: u_i = w1 * Popularitas_i + w2 * ProfitScore_i
     */
    private function getCandidates(string $preference, float $wPopularity, float $wProfit): array
    {
        $selectCols = ['id', 'name', 'price', 'image', 'tag', 'component_type', 'popularity_score', 'calories'];
        if (Schema::hasColumn('menus', 'hpp')) {
            $selectCols[] = 'hpp';
        }
        if (Schema::hasColumn('menus', 'utility_score')) {
            $selectCols[] = 'utility_score';
        }

        $allMenus = Menu::select($selectCols)->get();

        // Hitung profit maksimum per kategori untuk normalisasi nilai keuntungan (0 - 100)
        $maxProfitByCat = [];
        foreach ($allMenus as $menu) {
            $type = $menu->component_type ?: 'lauk_utama';
            $profit = $menu->profit;
            if (!isset($maxProfitByCat[$type]) || $profit > $maxProfitByCat[$type]) {
                $maxProfitByCat[$type] = max(1, $profit);
            }
        }

        $candidates = [
            'karbohidrat'     => [],
            'lauk_utama'      => [],
            'lauk_pendamping' => [],
            'sayur'           => [],
            'snack'           => [],
        ];

        foreach ($allMenus as $menu) {
            $type = $menu->component_type ?: 'lauk_utama';
            if (isset($candidates[$type])) {
                if ($type === 'lauk_utama' && $preference !== 'all') {
                    $lowerName = strtolower($menu->name);
                    if ($preference === 'ayam' && !str_contains($lowerName, 'ayam')) {
                        continue;
                    }
                    if ($preference === 'daging' && (!str_contains($lowerName, 'daging') && !str_contains($lowerName, 'rendang') && !str_contains($lowerName, 'sapi'))) {
                        continue;
                    }
                    if ($preference === 'ikan' && (!str_contains($lowerName, 'ikan') && !str_contains($lowerName, 'bandeng') && !str_contains($lowerName, 'nila') && !str_contains($lowerName, 'gurame'))) {
                        continue;
                    }
                }

                $candidates[$type][] = $this->formatMenuCandidate($menu, $type, $maxProfitByCat[$type] ?? 1, $wPopularity, $wProfit);
            }
        }

        // Fallback jika filter lauk_utama terlalu spesifik
        if (empty($candidates['lauk_utama'])) {
            foreach ($allMenus->where('component_type', 'lauk_utama') as $menu) {
                $candidates['lauk_utama'][] = $this->formatMenuCandidate($menu, 'lauk_utama', $maxProfitByCat['lauk_utama'] ?? 1, $wPopularity, $wProfit);
            }
        }

        return $candidates;
    }

    /**
     * Format atribut kandidat menu lengkap dengan HPP, Keuntungan, dan Nilai Utilitas Weighted Sum
     */
    private function formatMenuCandidate(Menu $menu, string $type, int $maxCatProfit, float $wPopularity, float $wProfit): array
    {
        $price = (int) $menu->price;
        $hpp = $menu->effective_hpp;
        $profit = max(0, $price - $hpp);
        $popularityScore = (float) ($menu->popularity_score ?: 70.0);
        $profitScore = round(min(100.0, ($profit / max(1, $maxCatProfit)) * 100), 2);

        // Weighted Sum Utility: u_i = w1 * Popularitas + w2 * Keuntungan
        $utilityScore = round(($wPopularity * $popularityScore) + ($wProfit * $profitScore), 2);

        return [
            'id'               => $menu->id,
            'name'             => $menu->name,
            'price'            => $price,
            'hpp'              => $hpp,
            'profit'           => $profit,
            'image'            => $menu->image ?: '/images/menu_extracted/page37_img03_333x333.jpeg',
            'tag'              => $menu->tag,
            'popularity_score' => $popularityScore,
            'profit_score'     => $profitScore,
            'utility_score'    => $utilityScore,
            'calories'         => (int) ($menu->calories ?: 150),
            'component_type'   => $type,
            'is_skipped'       => false,
        ];
    }

    /**
     * Placeholder item untuk kategori opsional yang dilewati (x_{kj} = 0 pada SMCKP)
     */
    private function createSkippedItemPlaceholder(string $catName): array
    {
        return [
            'id'               => null,
            'name'             => 'Tidak Dipilih (Kategori Opsional)',
            'price'            => 0,
            'hpp'              => 0,
            'profit'           => 0,
            'image'            => '/images/menu_extracted/page37_img03_333x333.jpeg',
            'tag'              => 'Dilewati (SMCKP)',
            'popularity_score' => 0.0,
            'profit_score'     => 0.0,
            'utility_score'    => 0.0,
            'calories'         => 0,
            'component_type'   => $catName,
            'is_skipped'       => true,
        ];
    }
}
