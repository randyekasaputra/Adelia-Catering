<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen Pesanan #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }} - Adelia Catering</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
        }
        
        .print-container {
            max-width: 860px;
            margin: 1.5rem auto;
            background: white;
            padding: 2.5rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
        }

        .page-break {
            page-break-before: always;
            break-before: page;
        }

        @media print {
            body {
                background-color: white;
                margin: 0;
                padding: 0;
                color: black;
                font-size: 11pt;
            }
            .print-container {
                box-shadow: none !important;
                border: none !important;
                margin: 0 auto !important;
                padding: 12px 18px !important;
                max-width: 100% !important;
                border-radius: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .hide-in-print {
                display: none !important;
            }
            @page {
                margin: 1.2cm 1cm;
                size: A4 portrait;
            }
        }
    </style>
</head>
<body>

    <!-- Control Bar Atas (Hidden Saat Cetak) -->
    <div class="no-print bg-slate-900 text-white p-3 sm:px-6 flex flex-wrap justify-between items-center fixed top-0 w-full z-20 shadow-md gap-2">
        <div class="flex items-center gap-3">
            <span class="w-2.5 h-2.5 bg-emerald-400 rounded-full animate-ping"></span>
            <div>
                <span class="text-xs font-bold tracking-tight">DOKUMEN PESANAN: #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="text-[10px] text-slate-400 block font-medium">Pemesan: {{ $order->customer_name }}</span>
            </div>
        </div>

        <!-- Mode Tampilan Switcher -->
        <div class="flex items-center gap-1.5 bg-slate-800 p-1 rounded-xl text-xs font-bold">
            <button onclick="setViewMode('all')" id="btn-all" class="px-2.5 py-1 rounded-lg bg-orange-500 text-white transition">
                📑 Semua (2 Lembar)
            </button>
            <button onclick="setViewMode('invoice')" id="btn-invoice" class="px-2.5 py-1 rounded-lg text-slate-300 hover:text-white transition">
                🧾 Invoice Saja
            </button>
            <button onclick="setViewMode('kitchen')" id="btn-kitchen" class="px-2.5 py-1 rounded-lg text-slate-300 hover:text-white transition">
                🍳 Lembar Dapur Saja
            </button>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex items-center gap-2">
            <button onclick="window.close()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-bold rounded-xl transition active:scale-95">
                Tutup
            </button>
            <button onclick="window.print()" class="px-4 py-1.5 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white text-xs font-bold rounded-xl shadow-sm transition active:scale-95 flex items-center gap-2">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Dokumen</span>
            </button>
        </div>
    </div>

    <!-- Spacer untuk Bar Atas -->
    <div class="no-print h-16"></div>

    <!-- ========================================================================= -->
    <!-- LEMBAR 1: INVOICE RESMI PESANAN (UNTUK PELANGGAN & ARSIP KEUANGAN) -->
    <!-- ========================================================================= -->
    <div id="section-invoice" class="print-container mb-6">
        
        <!-- Header Invoice -->
        <div class="flex justify-between items-start border-b-2 border-slate-900 pb-5 mb-5">
            <div class="flex items-center gap-3.5">
                <img src="{{ asset('images/logo.png') }}" class="h-16 w-auto object-contain shrink-0" alt="Logo">
                <div>
                    <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">ADELIA CATERING JEPARA</h1>
                    <p class="text-[11px] text-slate-500 font-semibold tracking-wide uppercase">Layanan Prasmanan, Nasi Box & Aneka Snack Event</p>
                    <p class="text-[10px] text-slate-450 mt-0.5"><i class="fa-solid fa-phone text-[9px] text-orange-500"></i> WhatsApp / Hotline: 0812-3456-7890 • Jepara, Jawa Tengah</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-block px-3 py-1 bg-slate-900 text-white rounded-lg text-xs font-extrabold tracking-wider uppercase mb-1">
                    INVOICE PESANAN
                </span>
                <div class="text-xs text-slate-500 font-medium">No. Registrasi: <span class="text-slate-900 font-bold">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span></div>
                <div class="text-[10px] text-slate-450 font-medium mt-0.5">Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d M Y, H:i') }} WIB</div>
            </div>
        </div>

        <!-- Grid Informasi: Pemesan & Jadwal Acara -->
        <div class="grid grid-cols-2 gap-6 mb-5">
            <!-- Data Pemesan -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5">
                <h3 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2 border-b border-slate-200 pb-1 flex items-center gap-1.5">
                    <i class="fa-solid fa-user-tag text-slate-500"></i>
                    <span>Identitas Pemesan</span>
                </h3>
                <div class="font-extrabold text-slate-900 text-sm mb-1">{{ $order->customer_name }}</div>
                <div class="text-xs text-slate-700 font-semibold mb-2">
                    <i class="fa-brands fa-whatsapp text-emerald-600 mr-1"></i> {{ $order->customer_wa }}
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Alamat Pengiriman Acara:</span>
                    <span class="font-medium text-xs text-slate-750 leading-snug">{{ $order->address }}</span>
                </div>
            </div>

            <!-- Jadwal Pengiriman Acara -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 flex flex-col justify-between">
                <div>
                    <h3 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2 border-b border-slate-200 pb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-calendar-check text-slate-500"></i>
                        <span>Waktu Pengiriman & Status</span>
                    </h3>
                    <table class="w-full text-xs">
                        <tbody>
                            <tr>
                                <td class="py-0.5 text-slate-450 font-semibold w-1/3">Hari & Tanggal</td>
                                <td class="py-0.5 font-bold text-slate-900">: {{ \Carbon\Carbon::parse($order->event_date)->translatedFormat('l, d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="py-0.5 text-slate-450 font-semibold">Jam Target Tiba</td>
                                <td class="py-0.5 font-extrabold text-orange-600">: {{ \Carbon\Carbon::parse($order->event_date)->format('H:i') }} WIB</td>
                            </tr>
                            <tr>
                                <td class="py-0.5 text-slate-450 font-semibold">Status Pembayaran</td>
                                <td class="py-0.5 font-bold text-slate-900">
                                    : <span class="uppercase {{ $order->status == 'paid' || $order->status == 'completed' ? 'text-emerald-700 font-extrabold' : 'text-amber-700' }}">{{ $order->status == 'paid' ? 'Lunas / Terverifikasi' : ucfirst($order->status) }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-[10px] text-slate-450 mt-2 font-medium">
                    *Harap hidangan dicek bersama kurir saat tiba di lokasi.
                </div>
            </div>
        </div>

        @if($order->notes)
        <!-- Catatan Khusus Pelanggan -->
        <div class="mb-5 bg-amber-50 border border-amber-200/80 rounded-xl p-3">
            <h4 class="text-[10px] font-extrabold text-amber-800 uppercase tracking-wider mb-0.5 flex items-center gap-1.5">
                <i class="fa-solid fa-comment-dots text-amber-600"></i>
                <span>Catatan Khusus dari Pelanggan:</span>
            </h4>
            <div class="font-bold text-amber-950 text-xs leading-relaxed">{{ $order->notes }}</div>
        </div>
        @endif

        <!-- Tabel Item Pesanan dengan Rincian Komponen Paket Katering -->
        <div class="mb-5">
            <h3 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2 border-b border-slate-200 pb-1">
                Rincian Hidangan & Paket Katering
            </h3>
            <table class="w-full text-left border-collapse border border-slate-200 text-xs">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 text-[10px] uppercase tracking-wider font-extrabold">
                        <th class="py-2.5 px-3 w-10 text-center">No</th>
                        <th class="py-2.5 px-3">Nama Paket / Hidangan</th>
                        <th class="py-2.5 px-3 text-right w-24">Harga Satuan</th>
                        <th class="py-2.5 px-3 text-center w-20">Kuantitas</th>
                        <th class="py-2.5 px-3 text-right w-28">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($order->items as $index => $item)
                    @php
                        $isPkg = isset($packageBreakdowns[$item->id]);
                        $pkgInfo = $isPkg ? $packageBreakdowns[$item->id] : null;
                    @endphp
                    <tr class="align-top {{ $index % 2 == 0 ? 'bg-white' : 'bg-slate-50/50' }}">
                        <td class="py-2.5 px-3 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-2.5 px-3">
                            <div class="font-extrabold text-slate-900 text-xs sm:text-sm flex items-center gap-1.5">
                                <span>{{ $item->menu_name }}</span>
                                @if($isPkg)
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded text-[9px] font-bold bg-orange-100 text-orange-800 border border-orange-200">
                                        <i class="fa-solid fa-box text-[8px]"></i> 5 Komponen
                                    </span>
                                @endif
                            </div>

                            <!-- Tampilan Breakdown 5 Komponen Hidangan jika Paket Katering -->
                            @if($isPkg && !empty($pkgInfo['components']))
                            <div class="mt-2 pl-3 border-l-2 border-orange-300 space-y-1">
                                <span class="block text-[9px] font-extrabold text-slate-500 uppercase">Komposisi Paket Bento (5 Elemen):</span>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-1.5 text-[11px] text-slate-700">
                                    @if(isset($pkgInfo['components']['karbohidrat']))
                                        <div class="flex items-center gap-1 bg-amber-50/80 px-2 py-0.5 rounded border border-amber-200/60">
                                            <span>🍚</span>
                                            <span class="truncate font-semibold">{{ $pkgInfo['components']['karbohidrat']['name'] }}</span>
                                        </div>
                                    @endif
                                    @if(isset($pkgInfo['components']['lauk_utama']))
                                        <div class="flex items-center gap-1 bg-rose-50/80 px-2 py-0.5 rounded border border-rose-200/60">
                                            <span>🍗</span>
                                            <span class="truncate font-bold text-slate-900">{{ $pkgInfo['components']['lauk_utama']['name'] }}</span>
                                        </div>
                                    @endif
                                    @if(isset($pkgInfo['components']['lauk_pendamping']))
                                        <div class="flex items-center gap-1 bg-blue-50/80 px-2 py-0.5 rounded border border-blue-200/60">
                                            <span>🍳</span>
                                            <span class="truncate font-semibold">{{ $pkgInfo['components']['lauk_pendamping']['name'] }}</span>
                                        </div>
                                    @endif
                                    @if(isset($pkgInfo['components']['sayur']))
                                        <div class="flex items-center gap-1 bg-emerald-50/80 px-2 py-0.5 rounded border border-emerald-200/60">
                                            <span>🥗</span>
                                            <span class="truncate font-semibold">{{ $pkgInfo['components']['sayur']['name'] }}</span>
                                        </div>
                                    @endif
                                    @if(isset($pkgInfo['components']['snack']))
                                        <div class="flex items-center gap-1 bg-purple-50/80 px-2 py-0.5 rounded border border-purple-200/60">
                                            <span>🥐</span>
                                            <span class="truncate font-semibold">{{ $pkgInfo['components']['snack']['name'] }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </td>
                        <td class="py-2.5 px-3 text-right font-medium text-slate-700">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>
                        <td class="py-2.5 px-3 text-center font-extrabold text-slate-900 bg-slate-50">
                            {{ $item->qty }} porsi
                        </td>
                        <td class="py-2.5 px-3 text-right font-extrabold text-slate-900">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Ringkasan Finansial & Ketentuan Pelunasan -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-6 mb-6">
            <!-- Informasi Rekening & Catatan -->
            <div class="w-full sm:w-1/2 text-xs text-slate-500 space-y-2">
                <div class="p-3 rounded-xl border border-slate-200 bg-slate-50">
                    <span class="block text-[10px] font-extrabold text-slate-700 uppercase tracking-wider mb-1">Informasi Pembayaran / Pelunasan:</span>
                    <p class="leading-relaxed text-[11px] text-slate-650">
                        • Pelunasan sisa tagihan dapat diselesaikan via transfer Bank / Tunai kepada pengantar saat pesanan diterima.<br>
                        • Rekening Resmi: <strong>BCA 1234-5678-90</strong> a.n <strong>Adelia Catering</strong>.
                    </p>
                </div>
            </div>

            <!-- Kotak Angka Total & DP -->
            <div class="w-full sm:w-1/2 bg-slate-900 text-white rounded-xl p-4 shadow-sm">
                <div class="flex justify-between items-center mb-1.5 text-xs">
                    <span class="text-slate-450 font-medium">Skema Pembayaran</span>
                    <span class="font-extrabold uppercase bg-white/10 px-2 py-0.5 rounded text-[10px]">{{ $order->payment_scheme }}</span>
                </div>
                <div class="flex justify-between items-center mb-2 text-xs">
                    <span class="text-slate-450 font-medium">Total Nilai Pesanan</span>
                    <span class="font-bold text-white">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="h-px bg-white/10 my-2"></div>
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-400">Uang Muka / DP Terbayar</span>
                    <span class="font-extrabold text-base text-emerald-400">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</span>
                </div>
                @php
                    $sisaPelunasan = max(0, $order->total_amount - $order->dp_amount);
                @endphp
                <div class="flex justify-between items-center pt-2 border-t border-white/10 text-xs">
                    <span class="text-slate-450 font-medium">Sisa yang Harus Dilunasi</span>
                    <span class="font-bold text-amber-300">Rp {{ number_format($sisaPelunasan, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Tanda Tangan -->
        <div class="flex justify-between items-end pt-4 border-t border-slate-200 text-xs text-slate-700">
            <div class="text-center w-40">
                <p class="text-[10px] text-slate-450 font-medium mb-12">Penerima / Pemesan,</p>
                <div class="border-b border-slate-400 pb-0.5 font-bold">( {{ $order->customer_name }} )</div>
            </div>

            <div class="text-center w-44">
                <p class="text-[10px] text-slate-450 font-medium mb-12">Jepara, {{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}<br>Hormat Kami,</p>
                <div class="border-b border-slate-400 pb-0.5 font-bold">( Manajemen Adelia Catering )</div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- LEMBAR 2: SURAT JALAN & LEMBAR PRODUKSI DAPUR (KITCHEN & DISPATCH SLIP) -->
    <!-- ========================================================================= -->
    <div id="section-kitchen" class="print-container page-break">
        
        <!-- Header Kitchen Ticket -->
        <div class="flex justify-between items-start border-b-2 border-orange-500 pb-4 mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-orange-500 text-white rounded-xl flex items-center justify-center text-2xl font-bold shadow-sm">
                    <i class="fa-solid fa-utensils"></i>
                </div>
                <div>
                    <span class="text-[10px] font-extrabold bg-orange-100 text-orange-800 px-2 py-0.5 rounded uppercase tracking-wider">
                        TIKET PRODUKSI DAPUR & SURAT JALAN PENGANTARAN
                    </span>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight mt-0.5">LEMBAR PERSIAPAN MEMASAK</h2>
                </div>
            </div>
            <div class="text-right">
                <div class="text-sm font-extrabold text-slate-900">ID: #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
                <div class="text-xs text-orange-600 font-extrabold mt-0.5">
                    <i class="fa-regular fa-clock"></i> TARGET TIBA: {{ \Carbon\Carbon::parse($order->event_date)->format('H:i') }} WIB
                </div>
                <div class="text-[10px] text-slate-450">{{ \Carbon\Carbon::parse($order->event_date)->translatedFormat('l, d F Y') }}</div>
            </div>
        </div>

        <!-- Banner Peringatan Waktu Masak -->
        <div class="bg-amber-500 text-slate-950 px-4 py-2.5 rounded-xl font-extrabold text-xs flex items-center justify-between mb-4 shadow-sm">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-bell text-sm"></i>
                <span>WAKTU STANDAR DAPUR: Makanan selesai dimasak & tuntas di-packing maksimal 45 menit sebelum jam keberangkatan!</span>
            </div>
            <span class="bg-black text-white px-2 py-0.5 rounded text-[10px] uppercase font-bold shrink-0">Wajib Tepat Waktu</span>
        </div>

        <!-- Identitas Lokasi & Kontak Pengiriman (Untuk Driver & Logistik) -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-3">
                <span class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1">Tujuan Pengiriman & Pemesan:</span>
                <div class="font-extrabold text-slate-900 text-sm">{{ $order->customer_name }}</div>
                <div class="text-xs font-bold text-slate-700 mt-0.5">📞 {{ $order->customer_wa }}</div>
                <div class="text-xs text-slate-650 mt-1 font-medium bg-white p-2 rounded-lg border border-slate-200">
                    📍 {{ $order->address }}
                </div>
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 flex flex-col justify-between">
                <div>
                    <span class="block text-[10px] font-extrabold text-slate-400 uppercase mb-1">Instruksi Khusus Dapur / Rasa:</span>
                    @if($order->notes)
                        <div class="text-xs font-bold text-rose-800 bg-rose-50 border border-rose-200 p-2 rounded-lg leading-normal">
                            ⚠️ {{ $order->notes }}
                        </div>
                    @else
                        <div class="text-xs text-slate-450 italic p-2 bg-white rounded-lg border border-slate-200">
                            Tidak ada catatan khusus (Gunakan bumbu standar).
                        </div>
                    @endif
                </div>
                <div class="text-[10px] text-slate-500 font-semibold mt-2">
                    Total Kebutuhan Wadah/Dus: <strong>{{ $order->items->sum('qty') }} Kotak / Box Bento</strong>
                </div>
            </div>
        </div>

        <!-- TABEL REKAP MASAK DAPUR BERDASARKAN 5 KOMPONEN HIDANGAN -->
        <div class="mb-5">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-list-check text-orange-500"></i>
                    <span>Rekapitulasi Bahan Masak per Komponen (Chef Cooking Checklist)</span>
                </h3>
                <span class="text-[10px] text-slate-450 font-medium">Berdasarkan data formulasi pesanan</span>
            </div>

            <table class="w-full text-left border-collapse border border-slate-300 text-xs">
                <thead>
                    <tr class="bg-slate-800 text-white text-[10px] uppercase font-bold tracking-wider">
                        <th class="py-2 px-3 w-36">Kategori Komponen</th>
                        <th class="py-2 px-3">Nama Menu Hidangan Masak</th>
                        <th class="py-2 px-3 text-center w-28">Jumlah Masak</th>
                        <th class="py-2 px-3 text-center w-24">Cek Rasa</th>
                        <th class="py-2 px-3 text-center w-24">Cek Selesai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800 font-medium">
                    
                    @php
                        $componentSections = [
                            'karbohidrat'     => ['title' => 'Karbohidrat / Nasi', 'icon' => '🍚', 'class' => 'bg-amber-50 text-amber-900 font-bold'],
                            'lauk_utama'      => ['title' => 'Lauk Utama (Daging/Ayam)', 'icon' => '🍗', 'class' => 'bg-rose-50 text-rose-900 font-bold'],
                            'lauk_pendamping' => ['title' => 'Lauk Pendamping', 'icon' => '🍳', 'class' => 'bg-blue-50 text-blue-900 font-bold'],
                            'sayur'           => ['title' => 'Sayuran', 'icon' => '🥗', 'class' => 'bg-emerald-50 text-emerald-900 font-bold'],
                            'snack'           => ['title' => 'Snack / Roti / Penutup', 'icon' => '🥐', 'class' => 'bg-purple-50 text-purple-900 font-bold'],
                            'lainnya'         => ['title' => 'Menu Tambahan / Lainnya', 'icon' => '🍽️', 'class' => 'bg-slate-50 text-slate-900 font-bold'],
                        ];
                    @endphp

                    @foreach($componentSections as $cKey => $section)
                        @if(!empty($kitchenChecklist[$cKey]))
                            @foreach($kitchenChecklist[$cKey] as $menuName => $data)
                            <tr class="hover:bg-slate-50">
                                <td class="py-2 px-3 {{ $section['class'] }} border-r border-slate-200">
                                    <span class="mr-1">{{ $section['icon'] }}</span> {{ $section['title'] }}
                                </td>
                                <td class="py-2 px-3 font-extrabold text-slate-900 text-xs sm:text-sm">
                                    {{ $menuName }}
                                </td>
                                <td class="py-2 px-3 text-center font-extrabold text-slate-950 bg-orange-50/50 text-sm">
                                    {{ $data['qty'] }} <span class="text-[10px] text-slate-500 font-normal uppercase">{{ $data['unit'] }}</span>
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <div class="w-5 h-5 mx-auto border-2 border-slate-400 rounded"></div>
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <div class="w-5 h-5 mx-auto border-2 border-slate-400 rounded"></div>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Checklist Kelengkapan Pengemasan (Packing Team) -->
        <div class="mb-5 bg-slate-50 border border-slate-200 rounded-xl p-3.5">
            <span class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-2">
                Kelengkapan Tambahan & Aksesoris Bento (Wajib Ada):
            </span>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs font-semibold text-slate-700">
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="checkbox" class="rounded text-orange-500 w-4 h-4">
                    <span>Sendok & Garpu Plastik</span>
                </label>
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="checkbox" class="rounded text-orange-500 w-4 h-4">
                    <span>Tisu Makan & Tusuk Gigi</span>
                </label>
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="checkbox" class="rounded text-orange-500 w-4 h-4">
                    <span>Sambal Terpisah / Sachet</span>
                </label>
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="checkbox" class="rounded text-orange-500 w-4 h-4">
                    <span>Air Mineral Cup (Bila Ada)</span>
                </label>
            </div>
        </div>

        <!-- Formulir Serah Terima Dapur ke Kurir Pengantar -->
        <div class="border-t-2 border-slate-300 pt-4">
            <span class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-3">
                Serah Terima Pengiriman (Quality Control & Dispatch):
            </span>

            <div class="grid grid-cols-3 gap-4 text-center text-xs">
                <!-- Tim Dapur -->
                <div class="p-2 border border-slate-200 rounded-xl bg-white">
                    <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">1. Dapur / Koki</span>
                    <p class="text-[10px] text-slate-500 mb-8">Rasa & Jumlah Sesuai Standar</p>
                    <div class="border-b border-slate-400 mx-4 pb-0.5 font-bold text-slate-800">( PIC Dapur )</div>
                </div>

                <!-- Kurir / Driver -->
                <div class="p-2 border border-slate-200 rounded-xl bg-white">
                    <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">2. Kurir / Driver</span>
                    <p class="text-[10px] text-slate-500 mb-8">Barang Diterima Lengkap & Rapi</p>
                    <div class="border-b border-slate-400 mx-4 pb-0.5 font-bold text-slate-800">( Driver / Kurir )</div>
                </div>

                <!-- Pelanggan di Lokasi -->
                <div class="p-2 border border-slate-200 rounded-xl bg-white">
                    <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">3. Penerima Acara</span>
                    <p class="text-[10px] text-slate-500 mb-8">Diterima Tepat Waktu di Lokasi</p>
                    <div class="border-b border-slate-400 mx-4 pb-0.5 font-bold text-slate-800">( Pemesan / Panitia )</div>
                </div>
            </div>
        </div>

    </div>

    <!-- Script Pengendali Mode Cetak -->
    <script>
        function setViewMode(mode) {
            const secInvoice = document.getElementById('section-invoice');
            const secKitchen = document.getElementById('section-kitchen');
            const btnAll     = document.getElementById('btn-all');
            const btnInvoice = document.getElementById('btn-invoice');
            const btnKitchen = document.getElementById('btn-kitchen');

            // Reset buttons
            [btnAll, btnInvoice, btnKitchen].forEach(b => {
                b.className = 'px-2.5 py-1 rounded-lg text-slate-300 hover:text-white transition';
            });

            if (mode === 'all') {
                secInvoice.style.display = 'block';
                secKitchen.style.display = 'block';
                secKitchen.classList.add('page-break');
                btnAll.className = 'px-2.5 py-1 rounded-lg bg-orange-500 text-white transition';
            } else if (mode === 'invoice') {
                secInvoice.style.display = 'block';
                secKitchen.style.display = 'none';
                btnInvoice.className = 'px-2.5 py-1 rounded-lg bg-orange-500 text-white transition';
            } else if (mode === 'kitchen') {
                secInvoice.style.display = 'none';
                secKitchen.style.display = 'block';
                secKitchen.classList.remove('page-break');
                btnKitchen.className = 'px-2.5 py-1 rounded-lg bg-orange-500 text-white transition';
            }
        }
    </script>
</body>
</html>
