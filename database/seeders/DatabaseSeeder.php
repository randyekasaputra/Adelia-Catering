<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Administrator Default
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Seeder Katalog Menu, Komponen Optimasi, Transaksi 3 Tahun & Paket
        $this->call([
            MenuSeeder::class,
            MenuComponentSeeder::class,
            HistoricalSalesSeeder::class,
            CateringPackageSeeder::class,
        ]);
    }
}
