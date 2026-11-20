<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use Carbon\Carbon;

class MenuComponentSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Update menu-menu snack yang sudah ada
        Menu::where('category', 'snack')->update([
            'component_type' => 'snack',
            'calories' => 180,
        ]);

        // 2. Update menu makanan yang sudah ada
        Menu::where('name', 'like', '%Nasi Kuning%')
            ->orWhere('name', 'like', '%Nasi Uduk%')
            ->update(['component_type' => 'karbohidrat', 'calories' => 200]);

        Menu::where('name', 'like', '%Ayam%')
            ->orWhere('name', 'like', '%Bandeng%')
            ->orWhere('name', 'like', '%Ikan%')
            ->orWhere('name', 'like', '%Rendang%')
            ->orWhere('name', 'like', '%Daging%')
            ->update(['component_type' => 'lauk_utama', 'calories' => 280]);

        // 3. Tambahkan komponen pelengkap katering jika belum ada (Lauk Pendamping & Sayur)
        $extraComponents = [
            // Karbohidrat
            [
                'name' => 'Nasi Putih Pulen Wangi',
                'description' => 'Nasi putih beras pilihan beraroma daun pandan segar.',
                'price' => 4000,
                'image' => '/images/menu_extracted/page37_img03_333x333.jpeg',
                'tag' => 'Wajib',
                'category' => 'makanan',
                'component_type' => 'karbohidrat',
                'calories' => 180,
            ],
            [
                'name' => 'Nasi Liwet Gurih Rempah',
                'description' => 'Nasi liwet kaya rempah tradisional santan gurih.',
                'price' => 5000,
                'image' => '/images/menu_extracted/page36_img03_450x800.jpeg',
                'tag' => 'Spesial',
                'category' => 'makanan',
                'component_type' => 'karbohidrat',
                'calories' => 220,
            ],
            // Lauk Utama Tambahan
            [
                'name' => 'Ayam Bakar Bumbu Rujak',
                'description' => 'Ayam bakar dengan balutan bumbu rujak manis pedas gurih.',
                'price' => 15000,
                'image' => '/images/menu_extracted/page35_img03_450x800.jpeg',
                'tag' => 'Favorit',
                'category' => 'makanan',
                'component_type' => 'lauk_utama',
                'calories' => 290,
            ],
            [
                'name' => 'Rendang Daging Sapi Suwir',
                'description' => 'Olahan rendang daging sapi empuk khas bumbu minang.',
                'price' => 18000,
                'image' => '/images/menu_extracted/page38_img04_360x640.jpeg',
                'tag' => 'Premium',
                'category' => 'makanan',
                'component_type' => 'lauk_utama',
                'calories' => 320,
            ],
            // Lauk Pendamping
            [
                'name' => 'Sambal Goreng Ati Kentang',
                'description' => 'Kentang dadu dan hati sapi dengan sambal santan gurih sedap.',
                'price' => 6000,
                'image' => '/images/menu_extracted/page36_img05_315x560.jpeg',
                'tag' => 'Pelengkap',
                'category' => 'makanan',
                'component_type' => 'lauk_pendamping',
                'calories' => 160,
            ],
            [
                'name' => 'Telur Balado Cabai Merah',
                'description' => 'Telur rebus goreng dengan lumuran bumbu balado pedas manis.',
                'price' => 5000,
                'image' => '/images/menu_extracted/page36_img05_315x560.jpeg',
                'tag' => 'Pelengkap',
                'category' => 'makanan',
                'component_type' => 'lauk_pendamping',
                'calories' => 140,
            ],
            [
                'name' => 'Perkedel Kentang Daging',
                'description' => 'Perkedel kentang lembut dengan cincangan daging sapi gurih.',
                'price' => 4500,
                'image' => '/images/menu_extracted/page37_img03_333x333.jpeg',
                'tag' => 'Pelengkap',
                'category' => 'makanan',
                'component_type' => 'lauk_pendamping',
                'calories' => 130,
            ],
            [
                'name' => 'Tempe Orek Manis Kering',
                'description' => 'Tempe iris garing renyah dibalut kecap dan cabai merah.',
                'price' => 3500,
                'image' => '/images/menu_extracted/page37_img03_333x333.jpeg',
                'tag' => 'Pelengkap',
                'category' => 'makanan',
                'component_type' => 'lauk_pendamping',
                'calories' => 120,
            ],
            // Sayuran
            [
                'name' => 'Capcay Sayur Segar Saus Tiram',
                'description' => 'Campuran wortel, kembang kol, sawi, dan bakso kuah kental saus tiram.',
                'price' => 5000,
                'image' => '/images/menu_extracted/page39_img03_450x800.jpeg',
                'tag' => 'Sayur Segar',
                'category' => 'makanan',
                'component_type' => 'sayur',
                'calories' => 85,
            ],
            [
                'name' => 'Tumis Buncis Jagung Manis',
                'description' => 'Buncis muda renyah ditumis bersama jagung manis segar.',
                'price' => 4000,
                'image' => '/images/menu_extracted/page39_img03_450x800.jpeg',
                'tag' => 'Sayur Segar',
                'category' => 'makanan',
                'component_type' => 'sayur',
                'calories' => 75,
            ],
            [
                'name' => 'Urap Sayur Bumbu Kelapa Gurih',
                'description' => 'Aneka sayuran rebus segar dipadu parutan kelapa bumbu rempah wangi.',
                'price' => 4500,
                'image' => '/images/menu_extracted/page39_img03_450x800.jpeg',
                'tag' => 'Tradisional',
                'category' => 'makanan',
                'component_type' => 'sayur',
                'calories' => 95,
            ],
        ];

        foreach ($extraComponents as $item) {
            Menu::firstOrCreate(
                ['name' => $item['name']],
                array_merge($item, [
                    'created_at' => $now,
                    'updated_at' => $now
                ])
            );
        }
    }
}

