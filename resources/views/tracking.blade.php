@extends('layouts.app')

@section('title', 'Lacak Status & Progres Pesanan - Adelia Catering')

@section('content')
<div class="bg-gradient-to-b from-orange-50/60 via-white to-amber-50/40 min-h-screen py-8 sm:py-12">
    <div class="container mx-auto px-4 sm:px-6 max-w-5xl">

        <!-- ===== HEADER SECTION ===== -->
        <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-10 animate-fade-in-down">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700 mb-3 border border-orange-200">
                <i class="fa-solid fa-truck-fast text-[11px]"></i>
                <span>Sistem Pelacakan Mandiri (Self-Service Tracking)</span>
            </span>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-850 tracking-tight">
                Lacak Status & Progres Pesanan
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                Pantau setiap tahapan pesanan katering Anda secara transparan — mulai dari konfirmasi pembayaran, proses memasak di dapur, hingga pengantaran ke lokasi acara.
            </p>
        </div>

        <!-- ===== SEARCH FORM BOX ===== -->
        <div class="bg-white rounded-3xl shadow-xl shadow-orange-500/5 border border-slate-200/80 p-4 sm:p-6 mb-8 max-w-2xl mx-auto">
            <form action="{{ url('/tracking') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" required
                           placeholder="Ketik Nomor Pesanan (misal: #15) atau No. WA..."
                           class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-medium text-slate-800 placeholder-slate-400 outline-none focus:border-orange-500 focus:bg-white focus:ring-4 focus:ring-orange-500/10 transition">
                </div>
                <button type="submit" class="bg-primary hover:bg-primary-hover text-white font-bold px-6 py-3.5 rounded-2xl shadow-lg shadow-orange-500/25 transition active:scale-95 flex items-center justify-center gap-2 text-sm shrink-0">
                    <i class="fa-solid fa-location-crosshairs"></i>
                    <span>Lacak Sekarang</span>
                </button>
            </form>

            <div class="flex items-center justify-between flex-wrap gap-2 mt-3 pt-3 border-t border-slate-100 text-[11px] text-slate-500">
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-info text-orange-500"></i>
                    Bisa melacak menggunakan <strong>ID Pesanan</strong> atau <strong>Nomor WhatsApp</strong> saat checkout.
                </span>
                @if($search)
                <a href="{{ url('/tracking') }}" class="text-orange-600 hover:underline font-semibold">Reset Pencarian</a>
                @endif
            </div>
        </div>

        <!-- ===== RESULT SECTION ===== -->
        @if($order)
            @php
                $step = $order->status_step;
                $isCancelled = $order->status === 'cancelled';
                $eventCarbon = \Carbon\Carbon::parse($order->event_date);
                $isPastEvent = $eventCarbon->isPast();
            @endphp

            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200/80 overflow-hidden mb-8 animate-fade-in-up">
                
                <!-- Order Top Banner -->
                <div class="bg-gradient-to-r from-slate-900 via-slate-850 to-slate-900 text-white p-5 sm:p-7 relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 w-40 h-40 bg-orange-500/10 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 relative z-10">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-orange-400 tracking-wider uppercase">ID Pesanan:</span>
                                <span class="bg-white/10 px-2 py-0.5 rounded-md text-xs font-extrabold tracking-wider border border-white/10">
                                    #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>
                            <h2 class="text-xl sm:text-2xl font-bold text-white flex items-center gap-2">
                                <span>{{ $order->customer_name }}</span>
                            </h2>
                            <p class="text-xs text-slate-400 mt-1 flex items-center gap-2">
                                <span><i class="fa-regular fa-calendar text-[11px]"></i> Tanggal Pesan: {{ $order->created_at->format('d M Y - H:i') }} WIB</span>
                            </p>
                        </div>

                        <!-- Status Badge -->
                        <div class="text-left sm:text-right">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status Progres Terkini</div>
                            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs sm:text-sm font-bold border shadow-sm {{ $order->status_badge_color }}">
                                <i class="fa-solid {{ $order->status_icon }} text-xs"></i>
                                <span>{{ $order->status_label }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                @if(!$isCancelled)
                <!-- ===== 5-STAGE MILESTONE TIMELINE (VISUAL STEPPER) ===== -->
                <div class="p-5 sm:p-8 bg-slate-50/50 border-b border-slate-100">
                    <div class="max-w-4xl mx-auto">
                        <div class="relative">
                            
                            <!-- Desktop Stepper (Horizontal) -->
                            <div class="hidden md:block">
                                <!-- Progress Bar Line Background -->
                                <div class="absolute top-6 left-12 right-12 h-1.5 bg-slate-200 rounded-full -z-0"></div>
                                <!-- Active Line Fill -->
                                @php
                                    $lineWidthPercent = match($step) {
                                        1 => 0,
                                        2 => 25,
                                        3 => 50,
                                        4 => 75,
                                        5 => 100,
                                        default => 0,
                                    };
                                @endphp
                                <div class="absolute top-6 left-12 h-1.5 bg-primary transition-all duration-700 ease-out rounded-full -z-0"
                                     style="width: calc({{ $lineWidthPercent }}% * (100% - 6rem) / 100);"></div>

                                <!-- 5 Milestone Circles -->
                                @php
                                    $stages = [
                                        1 => ['title' => 'Menunggu Pembayaran', 'sub' => 'Tagihan DP dibuat',       'icon' => 'fa-credit-card'],
                                        2 => ['title' => 'Terkonfirmasi',      'sub' => 'Slot acara terkunci',      'icon' => 'fa-circle-check'],
                                        3 => ['title' => 'Sedang Dimasak',     'sub' => 'Bahan disiapkan di dapur', 'icon' => 'fa-fire-burner'],
                                        4 => ['title' => 'Dalam Pengiriman',   'sub' => 'Menuju lokasi acara',      'icon' => 'fa-truck-fast'],
                                        5 => ['title' => 'Selesai & Diterima', 'sub' => 'Pesanan tiba di lokasi',   'icon' => 'fa-box-open'],
                                    ];
                                @endphp

                                <div class="grid grid-cols-5 gap-2 relative z-10">
                                    @foreach($stages as $stageNum => $stageData)
                                    @php
                                        $isCompleted = $step > $stageNum;
                                        $isCurrent = $step === $stageNum;
                                        $isUpcoming = $step < $stageNum;
                                    @endphp
                                    <div class="flex flex-col items-center text-center">
                                        <!-- Circle Node -->
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold text-sm transition-all duration-300 shadow-md
                                            {{ $isCompleted ? 'bg-emerald-500 text-white shadow-emerald-500/20' : '' }}
                                            {{ $isCurrent ? 'bg-primary text-white shadow-orange-500/30 ring-4 ring-orange-100 scale-110 animate-pulse' : '' }}
                                            {{ $isUpcoming ? 'bg-white text-slate-400 border border-slate-200' : '' }}">
                                            @if($isCompleted)
                                                <i class="fa-solid fa-check text-base"></i>
                                            @else
                                                <i class="fa-solid {{ $stageData['icon'] }} text-sm"></i>
                                            @endif
                                        </div>

                                        <!-- Text Details -->
                                        <div class="mt-3">
                                            <div class="text-xs font-bold leading-tight {{ $isCurrent ? 'text-orange-600' : ($isCompleted ? 'text-slate-800' : 'text-slate-400') }}">
                                                {{ $stageData['title'] }}
                                            </div>
                                            <div class="text-[10px] text-slate-500 mt-0.5 leading-snug">
                                                {{ $stageData['sub'] }}
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Mobile Stepper (Vertical Timeline) -->
                            <div class="block md:hidden space-y-4">
                                @foreach($stages as $stageNum => $stageData)
                                @php
                                    $isCompleted = $step > $stageNum;
                                    $isCurrent = $step === $stageNum;
                                    $isUpcoming = $step < $stageNum;
                                @endphp
                                <div class="flex items-start gap-3.5 relative">
                                    @if(!$loop->last)
                                    <div class="absolute left-5 top-10 bottom-0 w-0.5 {{ $isCompleted ? 'bg-emerald-500' : 'bg-slate-200' }}"></div>
                                    @endif
                                    
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 shadow-sm relative z-10
                                        {{ $isCompleted ? 'bg-emerald-500 text-white' : '' }}
                                        {{ $isCurrent ? 'bg-primary text-white ring-4 ring-orange-100 animate-pulse' : '' }}
                                        {{ $isUpcoming ? 'bg-white text-slate-400 border border-slate-200' : '' }}">
                                        @if($isCompleted)
                                            <i class="fa-solid fa-check text-xs"></i>
                                        @else
                                            <i class="fa-solid {{ $stageData['icon'] }} text-xs"></i>
                                        @endif
                                    </div>

                                    <div class="flex-1 pb-4">
                                        <div class="text-xs font-bold {{ $isCurrent ? 'text-orange-600' : ($isCompleted ? 'text-slate-800' : 'text-slate-400') }}">
                                            {{ $stageData['title'] }}
                                            @if($isCurrent)
                                            <span class="ml-1.5 text-[9px] bg-orange-100 text-orange-700 px-1.5 py-0.5 rounded font-extrabold uppercase">Tahap Saat Ini</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-slate-500 mt-0.5">
                                            {{ $stageData['sub'] }}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Stage Explanation Box -->
                <div class="p-5 sm:p-6 bg-orange-50/40 border-b border-orange-100/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-orange-500/10 text-orange-600 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fa-solid fa-info text-sm"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-800">Keterangan Tahapan:</div>
                            <div class="text-xs text-slate-650 mt-0.5 leading-relaxed">{{ $order->status_description }}</div>
                        </div>
                    </div>

                    @if($order->tracking_note)
                    <div class="w-full sm:w-auto bg-white border border-indigo-200/80 p-3 rounded-2xl shadow-sm text-xs shrink-0 sm:max-w-xs">
                        <div class="text-[10px] font-bold text-indigo-700 uppercase tracking-wider flex items-center gap-1.5 mb-1">
                            <i class="fa-solid fa-truck-ramp-box"></i>
                            <span>Catatan Tim Dapur / Kurir:</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-800 leading-snug">{{ $order->tracking_note }}</p>
                    </div>
                    @endif
                </div>
                @else
                <!-- Cancelled Notice -->
                <div class="p-6 bg-red-50 border-b border-red-100 flex items-center gap-3 text-red-800 text-xs sm:text-sm font-medium">
                    <i class="fa-solid fa-circle-exclamation text-lg text-red-600 shrink-0"></i>
                    <span>Pesanan ini telah dibatalkan. Jika ini merupakan kekeliruan atau ingin memesan ulang, silakan hubungi tim admin kami via WhatsApp.</span>
                </div>
                @endif

                <!-- ===== ORDER DETAILS CONTENT ===== -->
                <div class="p-5 sm:p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Left (2 Cols): Rincian Menu Dipesan -->
                    <div class="lg:col-span-2 space-y-6">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2 mb-3">
                                <i class="fa-solid fa-utensils text-orange-500"></i>
                                <span>Rincian Hidangan Pesanan</span>
                            </h3>

                            <div class="space-y-2.5">
                                @foreach($order->items as $it)
                                <div class="flex items-center justify-between p-3.5 bg-slate-50 border border-slate-150 rounded-2xl hover:bg-slate-100/60 transition">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="w-9 h-9 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center font-bold text-xs shrink-0 border border-orange-200">
                                            {{ $it->qty }}x
                                        </span>
                                        <div class="min-w-0">
                                            <div class="font-bold text-slate-800 text-xs sm:text-sm truncate" title="{{ $it->menu_name }}">
                                                {{ $it->menu_name }}
                                            </div>
                                            <div class="text-[11px] text-slate-500">
                                                @ Rp {{ number_format($it->price, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0 font-bold text-slate-800 text-xs sm:text-sm">
                                        Rp {{ number_format($it->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-3 p-3 bg-orange-50/50 rounded-xl border border-orange-100 flex items-center justify-between text-xs font-bold text-slate-700">
                                <span>Total Porsi Keseluruhan</span>
                                <span class="text-orange-600 font-extrabold text-sm">{{ $order->items->sum('qty') }} Porsi</span>
                            </div>
                        </div>

                        <!-- Special Notes if any -->
                        @if($order->notes)
                        <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-4">
                            <div class="text-xs font-bold text-amber-800 flex items-center gap-1.5 mb-1">
                                <i class="fa-solid fa-clipboard-list text-amber-600"></i>
                                <span>Catatan / Preferensi Anda Saat Checkout:</span>
                            </div>
                            <p class="text-xs text-amber-900 leading-relaxed font-medium">{{ $order->notes }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Right (1 Col): Jadwal & Bantuan -->
                    <div class="space-y-6">
                        
                        <!-- Jadwal & Lokasi Card -->
                        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 space-y-4">
                            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                                <i class="fa-solid fa-calendar-check text-orange-500"></i>
                                <span>Waktu & Lokasi Acara</span>
                            </h3>

                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block mb-0.5">Hari & Tanggal Acara</span>
                                <div class="text-xs sm:text-sm font-bold text-slate-800">
                                    {{ $eventCarbon->translatedFormat('l, d F Y') }}
                                </div>
                                <div class="text-xs font-semibold text-orange-600 mt-0.5">
                                    Pukul {{ $eventCarbon->format('H:i') }} WIB
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-200/60">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block mb-0.5">Alamat Pengiriman</span>
                                <div class="text-xs text-slate-700 font-medium leading-relaxed bg-white p-2.5 rounded-xl border border-slate-200/60">
                                    {{ $order->address }}
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-200/60">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block mb-0.5">Ringkasan Pembayaran</span>
                                <div class="flex items-center justify-between text-xs text-slate-600 mt-1">
                                    <span>Skema</span>
                                    <span class="font-bold text-slate-800 uppercase">{{ $order->payment_scheme }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs text-slate-600 mt-1">
                                    <span>Total Tagihan</span>
                                    <span class="font-bold text-slate-800">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs font-bold text-emerald-700 mt-1.5 pt-1.5 border-t border-slate-200">
                                    <span>Nominal DP Masuk</span>
                                    <span>Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Chat WA Admin -->
                        <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-2xl p-5 text-center">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto mb-2 text-lg">
                                <i class="fa-brands fa-whatsapp"></i>
                            </div>
                            <h4 class="text-xs sm:text-sm font-bold text-emerald-900">Ada Perubahan atau Pertanyaan?</h4>
                            <p class="text-[11px] text-emerald-700 mt-1 mb-3 leading-relaxed">
                                Hubungi tim operasional Adelia Catering langsung untuk koordinasi pengiriman pesanan Anda.
                            </p>
                            @php
                                $waText = "Halo Admin Adelia Catering, saya ingin menanyakan status pesanan saya (#ORD-" . str_pad($order->id, 5, '0', STR_PAD_LEFT) . ") atas nama " . $order->customer_name . ".";
                            @endphp
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $adminWa) }}?text={{ urlencode($waText) }}" target="_blank"
                               class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-md transition active:scale-95">
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                <span>Hubungi Admin WhatsApp</span>
                            </a>
                        </div>

                    </div>
                </div>

            </div>

        @elseif($searched && $orderList->isNotEmpty())
            <!-- ===== MULTIPLE ORDERS FOUND BY PHONE NUMBER ===== -->
            <div class="bg-white rounded-3xl shadow-xl border border-slate-200/80 p-6 mb-8 animate-fade-in-up">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-orange-500"></i>
                        <span>Ditemukan {{ $orderList->count() }} Pesanan Terkait Nomor "{{ $search }}"</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Pilih salah satu pesanan di bawah ini untuk melihat rincian progres lengkapnya:</p>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach($orderList as $oItem)
                    <div class="py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-slate-50/80 px-3 rounded-xl transition">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800 text-sm">#ORD-{{ str_pad($oItem->id, 5, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-xs text-slate-400">•</span>
                                <span class="text-xs font-semibold text-slate-700">{{ $oItem->customer_name }}</span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $oItem->status_badge_color }}">
                                    {{ $oItem->status_label }}
                                </span>
                            </div>
                            <div class="text-xs text-slate-500 mt-1 flex items-center gap-3">
                                <span><i class="fa-regular fa-calendar text-[10px]"></i> Acara: {{ \Carbon\Carbon::parse($oItem->event_date)->translatedFormat('d M Y') }}</span>
                                <span><i class="fa-solid fa-boxes-stacked text-[10px]"></i> {{ $oItem->items->sum('qty') }} Porsi</span>
                                <span>Rp {{ number_format($oItem->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <a href="{{ url('/tracking?search=' . $oItem->id) }}" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold text-orange-600 bg-orange-50 hover:bg-orange-100 border border-orange-200 px-4 py-2 rounded-xl transition shrink-0">
                            <span>Buka Progres</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

        @elseif($searched)
            <!-- ===== NOT FOUND STATE ===== -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-8 sm:p-12 text-center max-w-xl mx-auto mb-8 animate-fade-in-up">
                <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-orange-100 text-2xl">
                    <i class="fa-solid fa-magnifying-glass-chart"></i>
                </div>
                <h3 class="text-base sm:text-lg font-bold text-slate-800">Pesanan Tidak Ditemukan</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-md mx-auto leading-relaxed">
                    Tidak ditemukan data pesanan dengan kata kunci <strong>"{{ $search }}"</strong>. Pastikan Anda memasukkan nomor ID pesanan (misal: #15) atau nomor WhatsApp yang aktif saat checkout.
                </p>
                
                <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ url('/tracking') }}" class="text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 px-5 py-2.5 rounded-xl transition">
                        Coba Cari Ulang
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $adminWa) }}?text=Halo%20Admin%20Adelia%20Catering,%20saya%20ingin%20cek%20status%20pesanan%20saya..." target="_blank"
                       class="text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-5 py-2.5 rounded-xl transition flex items-center gap-2">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>Tanya Admin via WhatsApp</span>
                    </a>
                </div>
            </div>

        @else
            <!-- ===== INITIAL EMPTY STATE (INFORMASI TAHAPAN) ===== -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto mb-8 animate-fade-in-up">
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 mb-1">Pelacakan Real-Time</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Ketahui posisi dan kondisi pesanan Anda setiap saat secara mandiri tanpa harus menunggu balasan pesan admin.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-kitchen-set"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 mb-1">Transparansi Dapur</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Pantau kapan hidangan Anda mulai diproses dan dimasak segar oleh tim chef berpengalaman Adelia Catering.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 mb-1">Jaminan Tepat Waktu</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Pengiriman terjadwal dan siap saji sebelum jam acara Anda dimulai di wilayah Jepara dan sekitarnya.
                    </p>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
