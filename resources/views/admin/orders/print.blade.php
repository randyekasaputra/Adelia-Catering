<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
            background-color: #f8fafc;
            color: #1e293b;
        }
        
        .print-container {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            padding: 2.5rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        @media print {
            body {
                background-color: white;
                margin: 0;
                padding: 0;
                color: black;
            }
            .print-container {
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 10px;
                max-width: 100%;
                border-radius: 0;
            }
            .no-print {
                display: none !important;
            }
            @page {
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <!-- Control bar (hidden during print) -->
    <div class="no-print bg-zinc-950 text-white p-3.5 flex justify-between items-center fixed top-0 w-full z-10 shadow-sm">
        <div class="text-xs font-bold flex items-center gap-2">
            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
            <span>PRATINJAU DOKUMEN: #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="flex gap-2">
            <button onclick="window.close()" class="px-3.5 py-1.5 bg-zinc-800 hover:bg-zinc-700 text-xs font-bold rounded-lg transition active:scale-95">
                Tutup
            </button>
            <button onclick="window.print()" class="px-3.5 py-1.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold rounded-lg shadow-sm transition active:scale-95 flex items-center gap-1.5">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Invoice</span>
            </button>
        </div>
    </div>

    <!-- Spacer for content -->
    <div class="no-print h-14"></div>

    <div class="print-container">
        
        <!-- Header -->
        <div class="flex justify-between items-start border-b border-slate-200 pb-5 mb-6">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo.png') }}" class="h-16 w-auto object-contain shrink-0" alt="Logo">
                <div>
                    <h1 class="text-lg font-bold text-slate-805">Adelia Catering</h1>
                    <p class="text-[10px] text-slate-400 font-semibold tracking-wide uppercase">Catering & Snacking Services</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-slate-900 tracking-tight mb-0.5">INVOICE PESANAN</h2>
                <div class="text-xs text-slate-500 font-medium">No. Registrasi: <span class="text-slate-800 font-bold">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span></div>
                <div class="text-[10px] text-slate-400 font-medium mt-1">Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y - H:i') }} WIB</div>
            </div>
        </div>

        <!-- Information Info Grid -->
        <div class="grid grid-cols-2 gap-8 mb-6">
            
            <!-- Customer Details -->
            <div>
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 border-b border-slate-100 pb-1.5">Identitas Pemesan</h3>
                <div class="font-bold text-slate-800 text-base mb-1">{{ $order->customer_name }}</div>
                <div class="text-xs text-slate-600 font-semibold mb-2"><i class="fa-brands fa-whatsapp text-slate-400 text-sm"></i> WA: {{ $order->customer_wa }}</div>
                
                <div class="text-xs text-slate-600 leading-relaxed bg-slate-50 border border-slate-150/50 p-3 rounded-lg mt-2">
                    <span class="block text-[9px] font-bold text-slate-400 uppercase mb-0.5">Lokasi Pengiriman:</span>
                    <span class="font-medium">{{ $order->address }}</span>
                </div>
            </div>

            <!-- Event Details -->
            <div class="flex flex-col justify-between">
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 border-b border-slate-100 pb-1.5">Jadwal Pengiriman</h3>
                    <table class="w-full text-xs">
                        <tbody>
                            <tr>
                                <td class="py-1 text-slate-450 font-semibold w-1/3">Hari & Tanggal</td>
                                <td class="py-1 font-bold text-slate-800">: {{ \Carbon\Carbon::parse($order->event_date)->translatedFormat('l, d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 text-slate-450 font-semibold">Pukul Target</td>
                                <td class="py-1 font-bold text-slate-900 text-sm">: {{ \Carbon\Carbon::parse($order->event_date)->format('H:i') }} WIB</td>
                            </tr>
                            <tr>
                                <td class="py-1 text-slate-450 font-semibold">Fase Produksi</td>
                                <td class="py-1 font-bold uppercase text-slate-800">: {{ $order->status == 'completed' ? 'Selesai' : 'Diproses' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($order->notes)
        <!-- Notes callout -->
        <div class="mb-6 bg-red-50/50 border border-red-100 rounded-xl p-3.5">
            <h4 class="text-[10px] font-bold text-red-700 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Catatan Penting Untuk Dapur</span>
            </h4>
            <div class="font-bold text-red-900 text-xs leading-normal">{{ $order->notes }}</div>
        </div>
        @endif

        <!-- Items Table -->
        <div class="mb-6">
            <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 border-b border-slate-100 pb-1.5">Daftar Item Menu</h3>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-y border-slate-200 text-slate-600 text-[10px] uppercase tracking-wider">
                        <th class="py-2.5 px-3 font-bold w-12 text-center">No</th>
                        <th class="py-2.5 px-3 font-bold">Nama Menu Makanan / Snack</th>
                        <th class="py-2.5 px-3 font-bold text-center w-28">Kuantitas</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-slate-150/70 border-b border-slate-200">
                    @foreach($order->items as $index => $item)
                    <tr>
                        <td class="py-3 px-3 text-center text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-3 px-3 font-semibold text-slate-800">{{ $item->menu_name }}</td>
                        <td class="py-3 px-3 text-center font-bold text-slate-800 bg-slate-50/50">{{ $item->qty }} porsi</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary & Totals -->
        <div class="flex flex-col sm:flex-row justify-between items-end gap-6 mb-8">
            <div class="w-full sm:w-1/2 pr-0 sm:pr-4 text-xs text-slate-400">
                <p class="leading-normal">
                    <strong>Pemberitahuan Dapur:</strong><br>
                    Harap persiapkan bahan makanan sesuai dengan target jumlah porsi dan selesaikan pengemasan 30 menit sebelum pukul pengiriman yang dijadwalkan di atas.
                </p>
                <div class="mt-6 pt-4 border-t border-slate-200 inline-block font-semibold text-slate-650">
                    Hormat Kami,<br><br><br>
                    <span>( Manajemen Adelia Catering )</span>
                </div>
            </div>

            <div class="w-full sm:w-1/2 bg-slate-50 rounded-xl border border-slate-200 p-4">
                <div class="flex justify-between mb-2 text-xs">
                    <span class="text-slate-500 font-medium">Skema Pembayaran</span>
                    <span class="font-bold text-slate-800 uppercase">{{ $order->payment_scheme }}</span>
                </div>
                <div class="flex justify-between mb-2 text-xs">
                    <span class="text-slate-500 font-medium">Subtotal Harga Jual</span>
                    <span class="font-bold text-slate-800">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="h-px bg-slate-200 my-2"></div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-700 font-bold uppercase tracking-wider text-[10px]">Down Payment (DP)</span>
                    <span class="font-bold text-lg text-slate-900">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <!-- Footer Print -->
        <div class="text-center text-[10px] text-slate-400 border-t border-slate-100 pt-3">
            Invoice ini sah dan di-generate otomatis melalui Sistem Administrasi Adelia Catering.
        </div>
    </div>
</body>
</html>
