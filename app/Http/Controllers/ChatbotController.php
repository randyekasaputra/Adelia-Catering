<?php

namespace App\Http\Controllers;

use App\Models\Menu;
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

    private function handleGreeting(): string
    {
        $hour = (int) date('H');
        if ($hour < 11) $greeting = 'Selamat pagi';
        elseif ($hour < 15) $greeting = 'Selamat siang';
        elseif ($hour < 18) $greeting = 'Selamat sore';
        else $greeting = 'Selamat malam';

        return "$greeting! 👋\n\nSaya asisten virtual **Adelia Catering Jepara**. Ada yang bisa saya bantu?\n\n"
            . "Berikut yang bisa saya bantu:\n"
            . "🍽️ **Lihat menu** — ketik \"menu\"\n"
            . "💰 **Cari sesuai budget** — ketik \"budget 25000\"\n"
            . "🛒 **Cara pesan** — ketik \"cara pesan\"\n"
            . "📞 **Kontak kami** — ketik \"kontak\"\n"
            . "⏰ **Jam operasional** — ketik \"jam buka\"";
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
        // Ekstrak angka dari pesan
        preg_match_all('/\d[\d.]*/', str_replace('.', '', $message), $matches);
        $budget = 0;
        if (!empty($matches[0])) {
            $budget = (int) max($matches[0]);
        }

        if ($budget <= 0) {
            return "💰 Berapa budget Anda? Ketik seperti:\n\n"
                . "• \"budget 25000\" — menu di bawah Rp 25.000\n"
                . "• \"budget 50000\" — menu di bawah Rp 50.000\n"
                . "• \"paling murah\" — menu termurah";
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
