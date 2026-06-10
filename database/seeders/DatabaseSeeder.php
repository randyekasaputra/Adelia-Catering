<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Menu;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        $menus = [
            [
                'name' => 'Nasi Kotak Ayam Bakar Madu',
                'description' => 'Nasi putih pulen, ayam bakar madu spesial, sambal terasi pedas, lalapan segar, dan tahu tempe.',
                'price' => 25000,
                'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'tag' => 'Terlaris',
                'category' => 'makanan'
            ],
            [
                'name' => 'Paket Prasmanan Executive',
                'description' => 'Cocok untuk pernikahan/acara resmi. Pilihan lauk pauk premium, sup, hidangan penutup, dan minuman.',
                'price' => 55000,
                'image' => 'https://images.unsplash.com/photo-1530062845289-9109b2c9c868?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'tag' => 'Premium',
                'category' => 'makanan'
            ],
            [
                'name' => 'Tumpeng Kuning Spesial',
                'description' => 'Tumpeng nasi kuning untuk syukuran.',
                'price' => 350000,
                'image' => 'https://images.unsplash.com/photo-1621224403893-6f103b417bbd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'tag' => 'Syukuran',
                'category' => 'makanan'
            ],
            [
                'name' => 'Snack Box Premium',
                'description' => 'Isi 4 kue (manis & asin) ditambah air mineral cup.',
                'price' => 15000,
                'image' => 'https://images.unsplash.com/photo-1601000938259-9e92002320b2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'tag' => 'Event',
                'category' => 'snack'
            ]
        ];

        foreach($menus as $m) { Menu::create($m); }
    }
}
