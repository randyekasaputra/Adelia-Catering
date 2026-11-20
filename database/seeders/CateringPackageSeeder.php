<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CateringPackage;
use App\Models\PackageItem;
use App\Models\Menu;
use Illuminate\Support\Str;

class CateringPackageSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data lama dengan aman
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PackageItem::truncate();
        CateringPackage::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $menus = Menu::all();
        if ($menus->isEmpty()) {
            return;
        }

        $packages = [
            [
                'package_code'     => 'PKG-HEMAT-25K',
                'name'             => 'Paket Box Hemat Berkah',
                'slug'             => 'paket-box-hemat-berkah',
                'description'      => 'Kombinasi hemat bernutrisi seimbang, sangat cocok untuk konsumsi rapat, seminar, dan pengajian.',
                'target_budget'    => 25000,
                'price'            => 24500,
                'fitness_score'    => 89.5,
                'popularity_score' => 88.0,
                'image'            => '/images/menu_extracted/page36_img05_315x560.jpeg',
                'is_recommended'   => true,
                'is_custom'        => false,
                'items' => [
                    ['search' => 'Nasi Putih Pulen', 'type' => 'karbohidrat', 'fallback_cat' => 'makanan'],
                    ['search' => 'Ayam Katsu', 'type' => 'lauk_utama', 'fallback_cat' => 'makanan'],
                    ['search' => 'Tempe Orek', 'type' => 'lauk_pendamping', 'fallback_cat' => 'makanan'],
                    ['search' => 'Tumis Buncis', 'type' => 'sayur', 'fallback_cat' => 'makanan'],
                    ['search' => 'Roti Buttercream', 'type' => 'snack', 'fallback_cat' => 'snack'],
                ],
            ],
            [
                'package_code'     => 'PKG-FAVORIT-30K',
                'name'             => 'Paket Box Favorit Nusantara',
                'slug'             => 'paket-box-favorit-nusantara',
                'description'      => 'Menu paling laris berdasarkan data 3 tahun Adelia Catering dengan perpaduan rasa rempah autentik.',
                'target_budget'    => 30000,
                'price'            => 29800,
                'fitness_score'    => 96.2,
                'popularity_score' => 95.5,
                'image'            => '/images/menu_extracted/page35_img03_450x800.jpeg',
                'is_recommended'   => true,
                'is_custom'        => false,
                'items' => [
                    ['search' => 'Nasi Kuning', 'type' => 'karbohidrat', 'fallback_cat' => 'makanan'],
                    ['search' => 'Ayam Bakar', 'type' => 'lauk_utama', 'fallback_cat' => 'makanan'],
                    ['search' => 'Sambal Goreng Ati', 'type' => 'lauk_pendamping', 'fallback_cat' => 'makanan'],
                    ['search' => 'Capcay Sayur', 'type' => 'sayur', 'fallback_cat' => 'makanan'],
                    ['search' => 'Roti Boy', 'type' => 'snack', 'fallback_cat' => 'snack'],
                ],
            ],
            [
                'package_code'     => 'PKG-PREMIUM-38K',
                'name'             => 'Paket Box Premium Syukuran',
                'slug'             => 'paket-box-premium-syukuran',
                'description'      => 'Paket istimewa dengan olahan rendang daging sapi empuk untuk acara pernikahan, khitanan, dan gathering resmi.',
                'target_budget'    => 40000,
                'price'            => 38500,
                'fitness_score'    => 94.8,
                'popularity_score' => 93.0,
                'image'            => '/images/menu_extracted/page38_img04_360x640.jpeg',
                'is_recommended'   => true,
                'is_custom'        => false,
                'items' => [
                    ['search' => 'Nasi Liwet', 'type' => 'karbohidrat', 'fallback_cat' => 'makanan'],
                    ['search' => 'Rendang', 'type' => 'lauk_utama', 'fallback_cat' => 'makanan'],
                    ['search' => 'Telur Balado', 'type' => 'lauk_pendamping', 'fallback_cat' => 'makanan'],
                    ['search' => 'Urap Sayur', 'type' => 'sayur', 'fallback_cat' => 'makanan'],
                    ['search' => 'Roti Pisang', 'type' => 'snack', 'fallback_cat' => 'snack'],
                ],
            ],
        ];

        foreach ($packages as $pkgData) {
            $items = $pkgData['items'];
            unset($pkgData['items']);

            $package = CateringPackage::create($pkgData);

            $calculatedPrice = 0;
            foreach ($items as $itemSpec) {
                // Cari menu yang cocok
                $menu = Menu::where('name', 'like', '%' . $itemSpec['search'] . '%')->first();
                if (!$menu) {
                    $menu = Menu::where('category', $itemSpec['fallback_cat'])->first() ?: $menus->first();
                }

                PackageItem::create([
                    'package_id'     => $package->id,
                    'menu_id'        => $menu->id,
                    'component_type' => $itemSpec['type'],
                    'qty'            => 1,
                    'item_price'     => $menu->price,
                ]);

                $calculatedPrice += $menu->price;
            }

            // Update harga riil
            $package->update(['price' => $calculatedPrice]);
        }
    }
}

