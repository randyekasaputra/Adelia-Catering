<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\CateringPackage;
use App\Services\PackageOptimizerService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    /**
     * Proses pesan dari chatbot
     * Algoritma: Intent Detection + Keyword Matching + Database Query
     */
    public function chat(Request $request)
    {
        $message = strtolower(trim($request->input('message', '')));

        if (empty($message)) {
            return response()->json(['reply' => 'Silakan ketik pesan Anda 😊']);
        }

        // Intent Detection menggunakan Keyword Matching
        $intent = $this->detectIntent($message);

        switch ($intent) {
            case 'greeting':
                return response()->json(['reply' => $this->handleGreeting()]);

            case 'tracking':
                return response()->json(['reply' => $this->handleTracking($message)]);

            case 'package':
                return response()->json(['reply' => $this->handlePackageRecommendation($message)]);

            case 'menu_list':
                return response()->json(['reply' => $this->handleMenuList()]);

            case 'menu_category':
                return response()->json(['reply' => $this->handleMenuCategory($message)]);

            case 'menu_search':
                return response()->json(['reply' => $this->handleMenuSearch($message)]);

            case 'budget':
                return response()->json(['reply' => $this->handleBudget($message)]);

            case 'order_help':
                return response()->json(['reply' => $this->handleOrderHelp()]);

            case 'payment':
                return response()->json(['reply' => $this->handlePayment()]);

            case 'contact':
                return response()->json(['reply' => $this->handleContact()]);

            case 'hours':
                return response()->json(['reply' => $this->handleHours()]);

            case 'thanks':
                return response()->json(['reply' => $this->handleThanks()]);

            case 'goodbye':
                return response()->json(['reply' => $this->handleGoodbye()]);

            default:
                return response()->json(['reply' => $this->handleDefault()]);
        }
    }

    /**
     * Intent Detection — Priority-based Keyword Matching
     */
    private function detectIntent(string $message): string
    {
        $intents = [
            'greeting'      => ['halo', 'hai', 'hi', 'hello', 'hey', 'selamat pagi', 'selamat siang', 'selamat sore', 'selamat malam', 'pagi', 'siang', 'sore', 'malam', 'assalamualaikum', 'permisi'],
            'package'       => ['paket', 'rekomendasi paket', 'paket menu', 'paket katering', 'paket box', 'paket hemat', 'paket favorit', 'paket murah', 'kombinasi paket', 'combo', 'nasi kotak paket', 'paket nasi'],
            'thanks'        => ['terima kasih', 'makasih', 'thanks', 'thank you', 'thx', 'tq'],
            'goodbye'       => ['bye', 'dadah', 'sampai jumpa', 'selamat tinggal', 'dah'],
            'budget'        => ['budget', 'bujet', 'anggaran', 'harga di bawah', 'harga dibawah', 'murah', 'terjangkau', 'di bawah', 'dibawah', 'kurang dari', 'maksimal', 'max ', 'paling murah', 'termurah'],
            'menu_category' => ['nasi kotak', 'nasi box', 'prasmanan', 'snack box', 'snack', 'tumpeng', 'makanan', 'minuman', 'dessert', 'kue', 'kategori'],
            'menu_search'   => ['menu', 'cari', 'ada', 'apa saja', 'apa aja', 'pilihan', 'rekomendasi', 'recommend', 'populer', 'terlaris', 'favorit', 'best seller'],
            'menu_list'     => ['daftar menu', 'list menu', 'semua menu', 'menu lengkap', 'lihat menu'],
            'order_help'    => ['pesan', 'order', 'beli', 'cara pesan', 'cara order', 'cara beli', 'gimana pesan', 'bagaimana pesan', 'checkout', 'keranjang', 'booking'],
            'payment'       => ['bayar', 'pembayaran', 'dp', 'transfer', 'midtrans', 'lunas', 'cicil', 'payment', 'down payment'],
            'contact'       => ['kontak', 'contact', 'hubungi', 'whatsapp', 'wa', 'telepon', 'telpon', 'alamat', 'lokasi', 'dimana', 'di mana'],
            'hours'         => ['jam', 'buka', 'tutup', 'operasional', 'jadwal', 'waktu', 'kapan'],
        ];

        $lower = strtolower($message);

        // Cek jika pengguna ingin melacak pesanan
        if (str_contains($lower, 'lacak') || str_contains($lower, 'tracking') || str_contains($lower, 'cek status') || str_contains($lower, 'status pesanan') || str_contains($lower, 'sampai mana') || str_contains($lower, 'pesanan saya') || str_contains($lower, 'cek pesanan') || str_contains($lower, 'posisi pesanan')) {
            return 'tracking';
        }

        // Cek jika pesan berisi angka budget langsung (misal: "25000", "25k", "30rb", "paket 25k", "budget 30k")
        if (str_contains($message, 'paket') || str_contains($message, 'budget') || str_contains($message, 'bujet') || is_numeric(trim($message)) || preg_match('/^\s*\d+\s*(?:k|rb|ribu)\s*$/i', $message)) {
            if ($this->extractBudgetAmount($message) > 0 || str_contains($message, 'paket')) {
                return 'package';
            }
        }

        foreach ($intents as $intent => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($message, $keyword)) {
                    return $intent;
                }
            }
        }

        return 'unknown';
    }

    // ============================================================
    // Intent Handlers
    // ============================================================

    /**
     * Ekstrak nominal anggaran cerdas dari pesan (misal: "25k", "25rb", "25 ribu", "25.000", "25000", "30")
     */
    private function extractBudgetAmount(string $message): int
    {
        $clean = strtolower(trim($message));

        // Pola 1: format '25k', '25.5k', '25rb', '25 ribu'
        if (preg_match('/(\d+(?:[.,]\d+)?)\s*(?:k|rb|ribu)\b/i', $clean, $m)) {
            $num = (float) str_replace(',', '.', $m[1]);
            return (int) round($num * 1000);
        }

        // Pola 2: format angka biasa
        $withoutDot = str_replace(['.', ','], '', $clean);
        preg_match_all('/\d+/', $withoutDot, $matches);
        if (!empty($matches[0])) {
            $maxVal = (int) max($matches[0]);
            // Jika angkanya kecil (antara 15 dan 150), kemungkinan pembeli bermaksud ribuan (misal "paket 25", "budget 30")
            if ($maxVal >= 15 && $maxVal <= 150) {
                return $maxVal * 1000;
            }
            return $maxVal;
        }

        return 0;
    }

    private function handleGreeting(): string
    {
        $hour = (int) date('H');
        if ($hour < 11) $greeting = 'Selamat pagi';
        elseif ($hour < 15) $greeting = 'Selamat siang';
        elseif ($hour < 18) $greeting = 'Selamat sore';
        else $greeting = 'Selamat malam';

        return "$greeting! 👋\n\nSaya asisten virtual **Adelia Catering Jepara**.\n"
            . "Bingung memilih hidangan? Berikan budget Anda, dan saya carikan kombinasi 5 hidangan paling optimal dari rekam jejak penjualan 3 tahun! 🍱\n\n"
            . "💡 **Contoh pertanyaan cepat:**\n"
            . "• \"paket 25k\" atau \"paket 30.000\"\n"
            . "• \"menu terlaris\"\n"
            . "• \"cara pesan\" atau \"kontak\"\n\n"
            . "Silakan ketik atau klik pilihan di bawah ya! 😊";
    }

    /**
     * Rekomendasi Paket Menu Berdasarkan Anggaran / Budget Pelanggan
     */
    private function handlePackageRecommendation(string $message): string
    {
        $budget = $this->extractBudgetAmount($message);

        // KASUS 1: Pelanggan menyertakan angka budget tertentu
        if ($budget > 0) {
            if ($budget < 18000) {
                return "🍱 Untuk paket menu lengkap 5 hidangan (karbohidrat, lauk utama, pendamping, sayur, snack), anggaran minimal kami mulai dari **Rp 20.000 / porsi**.\n\n"
                    . "👉 Cek [Katalog Menu Lengkap](/menu) untuk opsi satuan di bawah Rp " . number_format($budget, 0, ',', '.') . ", atau coba ketik **\"paket 25k\"**!";
            }

            // Jalankan optimizer Dynamic Programming (MCKP) secara real-time
            try {
                $optimizer = new PackageOptimizerService();
                $optResult = $optimizer->optimize($budget);

                if ($optResult['success'] && !empty($optResult['items'])) {
                    $total = number_format($optResult['total_price'], 0, ',', '.');
                    $saving = number_format($optResult['remaining_budget'], 0, ',', '.');
                    $budgetFormatted = number_format($budget, 0, ',', '.');
                    $items = $optResult['items'];

                    $reply = "🍱 **Rekomendasi Paket Hemat (Budget ≤ Rp {$budgetFormatted}):**\n\n"
                        . "Berdasarkan analisis empiris penjualan 3 tahun, berikut kombinasi 5 hidangan paling disukai pelanggan:\n\n"
                        . "• 🍚 **Karbohidrat:** {$items['karbohidrat']['name']}\n"
                        . "• 🍗 **Lauk Utama:** {$items['lauk_utama']['name']}\n"
                        . "• 🍳 **Pendamping:** {$items['lauk_pendamping']['name']}\n"
                        . "• 🥗 **Sayuran:** {$items['sayur']['name']}\n"
                        . "• 🥐 **Snack:** {$items['snack']['name']}\n\n"
                        . "💰 **Total Harga:** **Rp {$total} / porsi**\n"
                        . "🏷️ **Efisiensi:** Hemat Rp {$saving} dari batas budget\n"
                        . "⭐ **Skor Kepuasan Pelanggan:** {$optResult['average_popularity']}%\n\n"
                        . "👉 [Lihat Visual Foto & Pesan Paket Ini](/packages)!";

                    return $reply;
                }
            } catch (\Exception $e) {
                // Lanjut ke fallback jika terjadi kendala service
            }

            // Fallback query ke paket database jika ada
            $packages = CateringPackage::with('items.menu')->where('price', '<=', $budget)->orderBy('price', 'desc')->get();
            if ($packages->isNotEmpty()) {
                $pkg = $packages->first();
                $price = number_format($pkg->price, 0, ',', '.');
                $reply = "🍱 **Paket Rekomendasi Sesuai Budget (≤ Rp " . number_format($budget, 0, ',', '.') . "):**\n\n"
                    . "⭐ **{$pkg->name}** — Rp {$price} / porsi\n"
                    . "_{$pkg->description}_\n\n"
                    . "**Isi Paket 5 Hidangan:**\n";
                foreach ($pkg->items as $it) {
                    $reply .= "• " . ($it->menu->name ?? 'Menu') . "\n";
                }
                $reply .= "\n👉 [Pesan Paket Ini di Website](/packages/{$pkg->slug})";
                return $reply;
            }
        }

        // KASUS 2: Pelanggan meminta rekomendasi paket umum tanpa menyebutkan angka budget
        $packages = CateringPackage::with('items.menu')->where('is_recommended', true)->orderBy('price', 'asc')->get();

        if ($packages->isEmpty()) {
            return "Saat ini kami menyediakan layanan peracikan paket kustom otomatis. Kunjungi halaman [Paket Katering](/packages) untuk mengoptimasi menu sesuai budget Anda! 😊";
        }

        $reply = "🍱 **Pilihan Paket Katering Favorit (Berdasarkan Data 3 Tahun):**\n\n";
        foreach ($packages as $pkg) {
            $price = number_format($pkg->price, 0, ',', '.');
            $reply .= "⭐ **{$pkg->name}** — **Rp {$price} / porsi**\n";
            $itemNames = $pkg->items->map(fn($it) => $it->menu->name ?? '')->filter()->join(', ');
            if ($itemNames) {
                $reply .= "   _{$itemNames}_\n\n";
            }
        }

        $reply .= "💡 **Ingin paket sesuai budget pas Anda?**\n"
            . "Ketik nominalnya, misal:\n"
            . "• \"paket 25k\"\n"
            . "• \"paket 30.000\"\n"
            . "• \"paket 38rb\"\n\n"
            . "👉 [Buka Simulator Paket Katering](/packages) untuk mencoba slider budget interaktif!";

        return $reply;
    }

    private function handleMenuList(): string
    {
        $menus = Menu::orderBy('category')->orderBy('price')->get();

        if ($menus->isEmpty()) {
            return "Maaf, menu sedang dalam proses update. Silakan cek kembali nanti ya! 🙏";
        }

        $reply = "📋 **Daftar Menu Adelia Catering**\n\n";
        $grouped = $menus->groupBy('category');

        foreach ($grouped as $category => $items) {
            $reply .= "**" . ucfirst($category) . "**\n";
            foreach ($items as $item) {
                $price = number_format($item->price, 0, ',', '.');
                $reply .= "• {$item->name} — Rp {$price}\n";
            }
            $reply .= "\n";
        }

        $reply .= "💡 Ketik nama menu untuk info detail, atau ketik \"budget [nominal]\" untuk cari sesuai anggaran.";
        return $reply;
    }

    private function handleMenuCategory(string $message): string
    {
        // Deteksi kategori dari pesan
        $categoryMap = [
            'nasi kotak' => 'nasi kotak', 'nasi box' => 'nasi kotak',
            'prasmanan'  => 'prasmanan',
            'snack box'  => 'snack box', 'snack' => 'snack box',
            'tumpeng'    => 'tumpeng',
            'minuman'    => 'minuman',
            'makanan'    => 'makanan',
        ];

        $detectedCategory = null;
        foreach ($categoryMap as $keyword => $category) {
            if (str_contains($message, $keyword)) {
                $detectedCategory = $category;
                break;
            }
        }

        if (!$detectedCategory) {
            $categories = Menu::distinct()->pluck('category');
            $list = $categories->map(fn($c) => "• " . ucfirst($c))->join("\n");
            return "📂 **Kategori yang tersedia:**\n\n{$list}\n\nKetik nama kategori untuk melihat menunya!";
        }

        $menus = Menu::where('category', 'LIKE', "%{$detectedCategory}%")->orderBy('price')->get();

        if ($menus->isEmpty()) {
            return "Maaf, belum ada menu di kategori **{$detectedCategory}**. Coba kategori lain ya! 🙏";
        }

        $reply = "🍽️ **Menu " . ucfirst($detectedCategory) . "**\n\n";
        foreach ($menus as $item) {
            $price = number_format($item->price, 0, ',', '.');
            $reply .= "• **{$item->name}** — Rp {$price}\n";
            if ($item->description) {
                $desc = \Illuminate\Support\Str::limit($item->description, 60);
                $reply .= "  _{$desc}_\n";
            }
        }

        $cheapest = number_format($menus->min('price'), 0, ',', '.');
        $expensive = number_format($menus->max('price'), 0, ',', '.');
        $reply .= "\n💰 Rentang harga: **Rp {$cheapest} — Rp {$expensive}**";

        return $reply;
    }

    private function handleMenuSearch(string $message): string
    {
        // Cek apakah mencari rekomendasi/terlaris
        $isRecommendation = str_contains($message, 'rekomendasi') || str_contains($message, 'populer')
            || str_contains($message, 'terlaris') || str_contains($message, 'favorit')
            || str_contains($message, 'best seller') || str_contains($message, 'recommend');

        if ($isRecommendation) {
            // Ambil menu terlaris dari order_items
            $topMenuIds = \App\Models\OrderItem::select('menu_id')
                ->selectRaw('SUM(qty) as total_qty')
                ->groupBy('menu_id')
                ->orderByDesc('total_qty')
                ->limit(5)
                ->pluck('menu_id');

            if ($topMenuIds->isNotEmpty()) {
                $menus = Menu::whereIn('id', $topMenuIds)->get();
                $reply = "⭐ **Menu Terlaris Adelia Catering**\n\n";
                foreach ($menus as $i => $item) {
                    $medal = match($i) { 0 => '🥇', 1 => '🥈', 2 => '🥉', default => '•' };
                    $price = number_format($item->price, 0, ',', '.');
                    $reply .= "{$medal} **{$item->name}** — Rp {$price}\n";
                }
                return $reply;
            }
        }

        // General menu query
        $menus = Menu::inRandomOrder()->limit(5)->get();
        if ($menus->isEmpty()) {
            return "Menu sedang di-update, silakan cek kembali nanti 🙏";
        }

        $reply = "🍽️ **Berikut beberapa menu kami:**\n\n";
        foreach ($menus as $item) {
            $price = number_format($item->price, 0, ',', '.');
            $reply .= "• **{$item->name}** — Rp {$price}\n";
        }
        $reply .= "\n👉 Ketik \"daftar menu\" untuk melihat semua, atau ketik kategori seperti \"nasi kotak\" atau \"snack box\".";
        return $reply;
    }

    private function handleBudget(string $message): string
    {
        $budget = $this->extractBudgetAmount($message);

        // Jika budget di atas Rp 18.000, berikan rekomendasi paket 5 hidangan optimal
        if ($budget >= 18000) {
            return $this->handlePackageRecommendation($message);
        }

        if ($budget <= 0) {
            return "💰 Berapa budget Anda? Ketik langsung seperti:\n\n"
                . "• \"paket 25k\" — paket katering hemat Rp 25.000\n"
                . "• \"paket 30rb\" — paket katering favorit Rp 30.000\n"
                . "• \"budget 15000\" — menu satuan di bawah Rp 15.000";
        }

        $menus = Menu::where('price', '<=', $budget)->orderBy('price', 'desc')->get();

        if ($menus->isEmpty()) {
            $cheapest = Menu::orderBy('price')->first();
            if ($cheapest) {
                $minPrice = number_format($cheapest->price, 0, ',', '.');
                return "😔 Maaf, belum ada menu di bawah **Rp " . number_format($budget, 0, ',', '.') . "**.\n\n"
                    . "Menu termurah kami: **{$cheapest->name}** — Rp {$minPrice}";
            }
            return "Maaf, menu sedang kosong saat ini.";
        }

        $budgetFormatted = number_format($budget, 0, ',', '.');
        $reply = "💰 **Menu dengan budget ≤ Rp {$budgetFormatted}**\n\n";
        foreach ($menus->take(8) as $item) {
            $price = number_format($item->price, 0, ',', '.');
            $reply .= "• **{$item->name}** — Rp {$price}\n";
        }

        $remaining = $menus->count() - 8;
        if ($remaining > 0) {
            $reply .= "\n...dan {$remaining} menu lainnya.";
        }

        $reply .= "\n\n✅ Total **{$menus->count()} menu** sesuai budget Anda!";
        $reply .= "\n\n🍱 **Butuh paket lengkap 5 hidangan?**\nKetik **\"paket {$budget}\"** atau buka [Paket Katering](/packages) untuk melihat paket komplit sesuai budget ini!";
        return $reply;
    }

    private function handleOrderHelp(): string
    {
        return "🛒 **Cara Memesan di Adelia Catering:**\n\n"
            . "**1.** Buka halaman [Daftar Menu](/menu)\n"
            . "**2.** Klik tombol **\"Tambah\"** pada menu yang diinginkan\n"
            . "**3.** Buka **Keranjang** lalu isi data pemesanan:\n"
            . "   • Nama lengkap\n"
            . "   • Nomor WhatsApp\n"
            . "   • Tanggal acara (pilih di kalender)\n"
            . "   • Alamat pengantaran\n"
            . "**4.** Pilih skema pembayaran (DP 30%, 50%, atau Lunas)\n"
            . "**5.** Klik **\"Bayar\"** dan selesaikan pembayaran via Midtrans\n\n"
            . "📅 Pemesanan minimal **H-3** sebelum acara.\n"
            . "📞 Butuh bantuan? Hubungi kami di **0812-3456-7890**";
    }

    private function handlePayment(): string
    {
        return "💳 **Informasi Pembayaran:**\n\n"
            . "Kami menerima berbagai metode pembayaran melalui **Midtrans**:\n\n"
            . "• 🏦 Transfer Bank (BCA, BNI, Mandiri, BRI)\n"
            . "• 📱 E-Wallet (GoPay, ShopeePay, OVO)\n"
            . "• 🏪 Gerai (Indomaret, Alfamart)\n\n"
            . "**Skema Pembayaran:**\n"
            . "• **DP 30%** — bayar 30% dulu, sisa saat acara\n"
            . "• **DP 50%** — bayar 50% dulu, sisa saat acara\n"
            . "• **Lunas 100%** — bayar penuh saat checkout\n\n"
            . "🔒 Semua transaksi aman dan terenkripsi.";
    }

    private function handleContact(): string
    {
        return "📞 **Hubungi Adelia Catering:**\n\n"
            . "📱 WhatsApp: [0812-3456-7890](https://wa.me/6281234567890)\n"
            . "📧 Email: halo@adeliacatering.com\n"
            . "📍 Alamat: Jl. Pemuda No. 123, Jepara, Jawa Tengah\n\n"
            . "🌐 Sosial Media:\n"
            . "• Instagram: @adeliacatering\n"
            . "• Facebook: Adelia Catering Jepara\n"
            . "• TikTok: @adeliacatering";
    }

    private function handleHours(): string
    {
        return "⏰ **Jam Operasional:**\n\n"
            . "📅 Senin – Jumat: **08.00 – 17.00**\n"
            . "📅 Sabtu: **08.00 – 15.00**\n"
            . "🔴 Minggu: **Libur**\n\n"
            . "💡 Pemesanan online bisa dilakukan kapan saja melalui website!";
    }

    private function handleThanks(): string
    {
        $responses = [
            "Sama-sama! 😊 Senang bisa membantu. Ada yang lain?",
            "Terima kasih kembali! 🙏 Jangan ragu bertanya lagi ya!",
            "Dengan senang hati! 😄 Semoga informasinya bermanfaat!",
        ];
        return $responses[array_rand($responses)];
    }

    private function handleGoodbye(): string
    {
        return "Sampai jumpa! 👋 Terima kasih sudah menghubungi Adelia Catering. Semoga harimu menyenangkan! 🌟";
    }

    /**
     * Handler: Pelacakan Progres Pesanan Pelanggan (Milestone Tracking)
     */
    private function handleTracking(string $message): string
    {
        // Cari kemungkinan nomor ID pesanan atau nomor WA di dalam pesan
        preg_match_all('/\d+/', str_replace(['#', '-', ' '], '', $message), $matches);

        $order = null;
        if (!empty($matches[0])) {
            try {
                foreach ($matches[0] as $candidate) {
                    // Jika angka pendek (1 s/d 6 digit), coba sebagai ID pesanan
                    if (strlen($candidate) <= 6 && (int)$candidate > 0) {
                        $order = \App\Models\Order::with('items')->find((int)$candidate);
                        if ($order) break;
                    }

                    // Jika angka panjang (>= 8 digit), coba sebagai nomor WA
                    if (strlen($candidate) >= 8) {
                        $order = \App\Models\Order::with('items')
                            ->where('customer_wa', 'LIKE', "%{$candidate}%")
                            ->orderBy('created_at', 'desc')
                            ->first();
                        if ($order) break;
                    }
                }
            } catch (\Throwable $e) {
                // Fallback jika database belum aktif atau terjadi kendala socket
                return "🚚 **Pelacakan Pesanan Adelia Catering**\n\n"
                    . "Untuk melihat progres pesanan Anda secara lengkap, silakan buka langsung di:\n"
                    . "👉 [Halaman Lacak Status Pesanan](/tracking?search=" . urlencode($message) . ")";
            }
        }

        if ($order) {
            $eventDate = \Carbon\Carbon::parse($order->event_date)->translatedFormat('d F Y, H:i');
            $statusLabel = $order->status_label;
            $statusDesc = $order->status_description;
            $itemsCount = $order->items->sum('qty');
            $totalFmt = number_format($order->total_amount, 0, ',', '.');

            $reply = "📦 **Status Pesanan #ORD-" . str_pad($order->id, 5, '0', STR_PAD_LEFT) . "**\n\n"
                . "👤 **Pelanggan:** {$order->customer_name}\n"
                . "📍 **Tahap Terkini:** **{$statusLabel}**\n"
                . "💬 *{$statusDesc}*\n\n";

            if ($order->tracking_note) {
                $reply .= "🚚 **Catatan Khusus Tim/Kurir:**\n"
                    . "_{$order->tracking_note}_\n\n";
            }

            $reply .= "📅 **Jadwal Acara:** {$eventDate} WIB\n"
                . "🍱 **Total Pesanan:** {$itemsCount} porsi (Rp {$totalFmt})\n"
                . "📍 **Alamat:** {$order->address}\n\n"
                . "👉 [Buka Linimasa Visual & Rincian Lengkap](/tracking?search={$order->id})";

            return $reply;
        }

        return "🚚 **Layanan Pelacakan Pesanan Adelia Catering**\n\n"
            . "Untuk mengecek progres pesanan Anda (apakah sudah dibayar, sedang dimasak di dapur, atau dalam pengiriman kurir):\n\n"
            . "1. Ketik pesan dengan nomor pesanan, contoh: **\"lacak #15\"**\n"
            . "2. Atau ketik dengan nomor WhatsApp Anda, contoh: **\"lacak 08123456789\"**\n\n"
            . "👉 Anda juga bisa langsung membuka halaman [Lacak Pesanan Mandiri](/tracking)!";
    }

    private function handleDefault(): string
    {
        return "Hmm, saya belum mengerti pertanyaan itu 🤔\n\n"
            . "Coba tanya hal berikut:\n"
            . "🍽️ \"menu\" — lihat daftar menu\n"
            . "💰 \"budget 25000\" — cari sesuai anggaran\n"
            . "🛒 \"cara pesan\" — panduan pemesanan\n"
            . "📞 \"kontak\" — info kontak kami\n"
            . "⏰ \"jam buka\" — jam operasional\n\n"
            . "Atau hubungi CS kami langsung di [WhatsApp](https://wa.me/6281234567890)! 😊";
    }
}
