<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CateringPackage;
use App\Services\PackageOptimizerService;

class PackageController extends Controller
{
    protected PackageOptimizerService $optimizer;

    public function __construct(PackageOptimizerService $optimizer)
    {
        $this->optimizer = $optimizer;
    }

    /**
     * Tampilan Halaman Katalog Paket Katering Rekomendasi & Smart Optimizer (SMCKP + DP)
     */
    public function index()
    {
        $packages = CateringPackage::with(['items.menu'])
            ->where('is_recommended', true)
            ->orderBy('price', 'asc')
            ->get();

        return view('packages.index', compact('packages'));
    }

    /**
     * Detail Tampilan Paket
     */
    public function detail($slug)
    {
        $package = CateringPackage::with(['items.menu'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedPackages = CateringPackage::where('id', '!=', $package->id)
            ->take(3)
            ->get();

        return view('packages.detail', compact('package', 'relatedPackages'));
    }

    /**
     * API: Menjalankan Algoritma Dynamic Programming untuk Selective Multiple-Choice Knapsack Problem (SMCKP)
     * Berdasarkan Anggaran Pelanggan, Kategori Menu Opsional, dan Nilai Utilitas Weighted Sum (Popularitas + Keuntungan HPP)
     */
    public function optimize(Request $request)
    {
        $request->validate([
            'budget'       => 'required|numeric|min:10000|max:100000',
            'preference'   => 'nullable|string|in:all,ayam,daging,ikan',
            'categories'   => 'nullable|array',
            'categories.*' => 'string|in:karbohidrat,lauk_utama,lauk_pendamping,sayur,snack',
            'w_popularity' => 'nullable|numeric|min:0|max:1',
            'w_profit'     => 'nullable|numeric|min:0|max:1',
        ]);

        $budget = (int) $request->input('budget', 25000);
        $preference = $request->input('preference', 'all');
        $categories = $request->input('categories', null);
        $wPopularity = (float) $request->input('w_popularity', 0.55);
        $wProfit = (float) $request->input('w_profit', 0.45);

        $result = $this->optimizer->optimize($budget, $preference, $categories, $wPopularity, $wProfit);

        return response()->json($result);
    }
}
