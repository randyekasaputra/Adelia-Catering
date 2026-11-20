@extends('admin.layout')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<!-- Page Header -->
<div class="mb-6 lg:mb-8">
    <h1 class="text-2xl lg:text-3xl font-bold text-slate-800 tracking-tight">Ikhtisar Bisnis</h1>
    <p class="text-slate-500 mt-1 font-medium text-xs sm:text-sm">Pantau performa operasional dan metrik penjualan hari ini.</p>
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Card 1 -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/60 flex items-center justify-between hover:shadow-md transition-shadow duration-200">
        <div class="min-w-0">
            <div class="text-slate-400 font-semibold text-[10px] sm:text-xs uppercase tracking-wider mb-1">Total Transaksi</div>
            <div class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight">{{ $totalOrders }}</div>
            <span class="text-[10px] text-slate-450 font-bold bg-slate-50 border border-slate-100 px-2 py-0.5 rounded mt-1.5 inline-block">transaksi masuk</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-orange-550/10 text-orange-500 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-chart-line text-lg"></i>
        </div>
    </div>
    
    <!-- Card 2 -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/60 flex items-center justify-between hover:shadow-md transition-shadow duration-200">
        <div class="min-w-0">
            <div class="text-slate-450 font-semibold text-[10px] sm:text-xs uppercase tracking-wider mb-1">Status Menunggu</div>
            <div class="text-2xl sm:text-3xl font-bold text-amber-600 tracking-tight">{{ $pendingOrders }}</div>
            <span class="text-[10px] text-amber-600 font-bold bg-amber-50 border border-amber-100/50 px-2 py-0.5 rounded mt-1.5 inline-block">butuh diproses</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-clock-rotate-left text-lg"></i>
        </div>
    </div>

    <!-- Card 3: Pengunjung & Device -->
    <a href="/admin/visitors" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/60 flex items-center justify-between hover:shadow-md hover:border-indigo-200 transition-all duration-200 group">
        <div class="min-w-0">
            <div class="text-slate-400 font-semibold text-[10px] sm:text-xs uppercase tracking-wider mb-1">Pengunjung Web</div>
            <div class="text-2xl sm:text-3xl font-bold text-indigo-600 tracking-tight">{{ $visitorStats['total_visitors'] ?? 0 }}</div>
            <span class="text-[10px] text-indigo-600 font-bold bg-indigo-50 border border-indigo-100/70 px-2 py-0.5 rounded mt-1.5 inline-block">
                Hari ini: {{ $visitorStats['today_visitors'] ?? 0 }} &bull; HP {{ $visitorStats['mobile_pct'] ?? 0 }}%
            </span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-500 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center shrink-0 transition-colors">
            <i class="fa-solid fa-users-viewfinder text-lg"></i>
        </div>
    </a>
    
    <!-- Card 4: Revenue -->
    <div class="bg-slate-900 text-white p-5 rounded-2xl shadow-sm border border-slate-800 flex items-center justify-between hover:shadow-md transition-shadow duration-200">
        <div class="min-w-0">
            <div class="text-slate-400 font-semibold text-[10px] sm:text-xs uppercase tracking-wider mb-1">Potensi Pendapatan</div>
            <div class="text-xl sm:text-2xl font-bold text-white tracking-tight">Rp {{ number_format($revenue,0,',','.') }}</div>
            <span class="text-[10px] text-slate-400 font-bold bg-white/10 px-2 py-0.5 rounded mt-1.5 inline-block">estimasi omset</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-white/10 border border-white/10 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid fa-wallet text-lg"></i>
        </div>
    </div>
</div>

<!-- Banner Quick Budget Recommender (Alat Asisten Penjual) -->
<div class="bg-gradient-to-r from-orange-500 to-amber-600 rounded-2xl p-5 sm:p-6 mb-6 text-white shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center shrink-0 text-white text-xl">
            <i class="fa-solid fa-calculator"></i>
        </div>
        <div>
            <div class="flex items-center gap-2">
                <span class="bg-white/20 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Fitur Penjual</span>
                <span class="text-xs text-orange-100 font-medium">Dynamic Programming &amp; Data 3 Tahun</span>
            </div>
            <h3 class="text-base sm:text-lg font-bold text-white mt-0.5">Asisten Rekomendasi Budget Cepat</h3>
            <p class="text-xs text-orange-100 mt-0.5">Pelanggan tanya paket sesuai budget via WA/telepon? Cukup klik nominalnya, sistem langsung racik paket 5 hidangan lengkap siap kirim.</p>
        </div>
    </div>
    <a href="/admin/recommender" class="bg-white hover:bg-orange-50 text-orange-600 font-bold px-5 py-2.5 rounded-xl shadow-sm transition-all text-xs sm:text-sm shrink-0 flex items-center gap-2 active:scale-95">
        <span>Buka Asisten Budget</span>
        <i class="fa-solid fa-arrow-right text-xs"></i>
    </a>
</div>

<!-- Grafik Pemasukan -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 mb-6 overflow-hidden">
    <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
        <div>
            <h2 class="text-sm sm:text-base font-bold text-slate-850 tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-chart-area text-orange-500"></i>
                Statistik Pemasukan
            </h2>
            <p class="text-[10px] sm:text-xs text-slate-500 font-medium mt-1">Pantau pergerakan omset katering berdasarkan periode.</p>
        </div>
        
        <!-- Segmented Tab Controls -->
        <div class="flex bg-slate-100 p-0.5 rounded-xl border border-slate-200/40 w-full sm:w-fit gap-0.5 shrink-0">
            <button onclick="updateChart('weekly')" id="btn-weekly" class="flex-1 sm:flex-none px-4 py-1.5 text-xs font-bold rounded-lg transition bg-white text-slate-800 shadow-sm border border-slate-200/20">Mingguan</button>
            <button onclick="updateChart('monthly')" id="btn-monthly" class="flex-1 sm:flex-none px-4 py-1.5 text-xs font-bold rounded-lg transition text-slate-500 hover:text-slate-800 hover:bg-white/40">Bulanan</button>
            <button onclick="updateChart('yearly')" id="btn-yearly" class="flex-1 sm:flex-none px-4 py-1.5 text-xs font-bold rounded-lg transition text-slate-500 hover:text-slate-800 hover:bg-white/40">Tahunan</button>
        </div>
    </div>
    <div class="p-4 sm:p-6">
        <div id="revenueChart" class="w-full" style="height: 250px;"></div>
    </div>
</div>

<!-- ANALITIK PREDIKTIF -->
<div class="mb-4">
    <h2 class="text-base sm:text-lg font-bold text-slate-850 tracking-tight flex items-center gap-2">
        <i class="fa-solid fa-brain text-indigo-500"></i>
        Analitik Prediktif
    </h2>
    <p class="text-slate-550 font-medium text-[10px] sm:text-xs">Insight otomatis berdasarkan data transaksi katering Anda.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

    <!-- CARD 1: PREDIKSI PENDAPATAN -->
    <div class="bg-gradient-to-br from-indigo-950 via-slate-900 to-slate-950 text-white rounded-2xl p-5 shadow-sm border border-slate-800 relative overflow-hidden flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center border border-white/5">
                        <i class="fa-solid fa-chart-line text-white text-xs"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-[11px] uppercase tracking-wider">Prediksi Pendapatan</h3>
                        <p class="text-slate-400 text-[9px] font-medium">{{ $predictionData['next_month'] }}</p>
                    </div>
                </div>
                <span class="bg-white/10 text-white text-[8px] sm:text-[9px] font-bold px-2 py-0.5 rounded border border-white/5">
                    SMA-{{ $predictionData['window'] }}
                </span>
            </div>

            <div class="mb-4">
                @if($predictionData['prediction'] > 0)
                    <p class="text-white font-bold text-xl sm:text-2xl tracking-tight">
                        Rp {{ number_format($predictionData['prediction'], 0, ',', '.') }}
                    </p>
                @else
                    <p class="text-slate-400 font-bold text-xs">Belum cukup data transaksi</p>
                @endif
            </div>
        </div>

        <div>
            <div class="grid grid-cols-3 gap-2 mb-3">
                <div class="bg-white/5 rounded-lg p-2 text-center border border-white/5">
                    <div class="flex items-center justify-center gap-0.5 mb-0.5">
                        @if($predictionData['growth_rate'] > 0)
                            <i class="fa-solid fa-arrow-trend-up text-emerald-400 text-[9px]"></i>
                        @elseif($predictionData['growth_rate'] < 0)
                            <i class="fa-solid fa-arrow-trend-down text-red-400 text-[9px]"></i>
                        @else
                            <i class="fa-solid fa-minus text-slate-400 text-[9px]"></i>
                        @endif
                        <span class="text-white font-bold text-xs">{{ $predictionData['growth_rate'] }}%</span>
                    </div>
                    <span class="text-slate-400 text-[8px] font-semibold uppercase">Tren</span>
                </div>
                <div class="bg-white/5 rounded-lg p-2 text-center border border-white/5">
                    <span class="text-white font-bold text-xs block">{{ $predictionData['window'] }} Bln</span>
                    <span class="text-slate-400 text-[8px] font-semibold uppercase">Window</span>
                </div>
                <div class="bg-white/5 rounded-lg p-2 text-center border border-white/5">
                    <span class="text-white font-bold text-xs block">{{ $predictionData['confidence'] }}%</span>
                    <span class="text-slate-400 text-[8px] font-semibold uppercase">Conf.</span>
                </div>
            </div>
            
            <div class="w-full h-1 bg-white/10 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $predictionData['confidence'] }}%"></div>
            </div>
        </div>
    </div>

    <!-- CARD 2: MENU TERLARIS -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-orange-50 text-orange-500 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-trophy text-xs"></i>
                </div>
                <div>
                    <h3 class="text-slate-800 font-bold text-xs">Menu Terlaris</h3>
                    <p class="text-slate-450 text-[9px] font-medium">Ranking penjualan</p>
                </div>
            </div>
            <span class="bg-slate-50 text-slate-655 text-[9px] font-bold px-2 py-0.5 rounded border border-slate-100">
                Top {{ $topMenus->count() }}
            </span>
        </div>

        @if($topMenus->isEmpty())
            <div class="text-center py-6">
                <div class="text-slate-350 mb-2"><i class="fa-solid fa-chart-bar text-xl"></i></div>
                <p class="text-slate-400 font-medium text-xs">Belum ada data penjualan.</p>
            </div>
        @else
            <div class="space-y-3.5">
                @foreach($topMenus as $index => $menu)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-slate-700 font-bold text-xs truncate max-w-[160px]">{{ $menu->menu_name }}</span>
                        <span class="text-slate-500 font-bold text-[10px] shrink-0">{{ $menu->total_qty }} pcs</span>
                    </div>
                    <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-orange-500 rounded-full" style="width: {{ $menu->percentage }}%"></div>
                    </div>
                    <div class="flex justify-between mt-0.5 text-[8px] font-medium text-slate-400">
                        <span>Rp {{ number_format($menu->total_revenue, 0, ',', '.') }}</span>
                        <span>{{ $menu->percentage }}%</span>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- CARD 3: HARI TERSIBUK -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-50 text-indigo-500 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-calendar-week text-xs"></i>
                    </div>
                    <div>
                        <h3 class="text-slate-800 font-bold text-xs">Hari Tersibuk</h3>
                        <p class="text-slate-450 text-[9px] font-medium">Distribusi frekuensi</p>
                    </div>
                </div>
                @if($peakDay && $peakDay->total_orders > 0)
                <span class="bg-indigo-50 text-indigo-650 text-[9px] font-bold px-2 py-0.5 rounded border border-indigo-100">
                    {{ $peakDay->day_name }}
                </span>
                @endif
            </div>

            @if(!$peakDay || $peakDay->total_orders == 0)
                <div class="text-center py-6">
                    <div class="text-slate-350 mb-2"><i class="fa-solid fa-calendar-days text-xl"></i></div>
                    <p class="text-slate-400 font-medium text-xs">Belum ada data pesanan.</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach($busiestDays->sortBy('day_number') as $day)
                    <div class="flex items-center gap-2">
                        <span class="text-[9px] font-bold w-8 text-right text-slate-400">
                            {{ substr($day->day_name, 0, 3) }}
                        </span>
                        <div class="flex-1 h-3.5 bg-slate-100 rounded overflow-hidden relative">
                            <div class="h-full rounded {{ $day->day_name === $peakDay->day_name ? 'bg-indigo-500' : 'bg-slate-300' }}"
                                style="width: {{ max($day->percentage, 4) }}%">
                            </div>
                            @if($day->total_orders > 0)
                            <span class="absolute right-1.5 top-1/2 -translate-y-1/2 text-[8px] font-bold {{ $day->percentage > 60 ? 'text-white' : 'text-slate-600' }}">
                                {{ $day->total_orders }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        @if($peakDay && $peakDay->total_orders > 0)
        <div class="mt-4 bg-indigo-50/50 border border-indigo-100/50 rounded-xl p-3 text-[10px] text-indigo-750 leading-normal flex items-start gap-1.5">
            <i class="fa-solid fa-lightbulb text-indigo-550 mt-0.5 shrink-0"></i>
            <span>
                <strong>{{ $peakDay->day_name }}</strong> adalah hari tersibuk dengan <strong>{{ $peakDay->total_orders }} order</strong>. Pertimbangkan kapasitas produksi.
            </span>
        </div>
        @endif
    </div>

</div>

<!-- Transaksi Terakhir -->
<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">Transaksi Terakhir</h2>
        <p class="text-slate-500 text-[10px] sm:text-xs">Riwayat 5 transaksi katering masuk terakhir di sistem.</p>
    </div>
    <a href="/admin/orders" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-3 py-1.5 rounded-xl hover:bg-slate-50 shadow-sm active:scale-95 transition">
        <span>Semua Pesanan</span>
        <i class="fa-solid fa-arrow-right text-[9px]"></i>
    </a>
</div>

<!-- Table Container -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden mb-8">
    
    <!-- Mobile View -->
    <div class="md:hidden divide-y divide-slate-100">
        @forelse($recentOrders as $o)
        <div class="p-4 hover:bg-slate-50/30 transition flex flex-col gap-2">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($o->customer_name) }}&background=f8fafc&color=334155&bold=true" class="w-8 h-8 rounded-lg border border-slate-100 shrink-0" alt="Avatar">
                    <div>
                        <div class="font-bold text-slate-800 text-xs">#ORD-{{ str_pad($o->id, 4, '0', STR_PAD_LEFT) }}</div>
                        <div class="text-[10px] text-slate-400 font-semibold truncate max-w-[120px]">{{ $o->customer_name }}</div>
                    </div>
                </div>
                
                @if($o->status == 'pending')
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-700 border border-amber-500/10 gap-1 shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100/50 gap-0.5 shrink-0">
                        <i class="fa-solid fa-check text-[9px]"></i> Selesai
                    </span>
                @endif
            </div>
            
            <div class="flex items-center justify-between mt-1 text-[10px] font-medium">
                <div class="text-slate-400">{{ $o->created_at->diffForHumans() }}</div>
                <div class="font-bold text-slate-800">Rp {{ number_format($o->total_amount,0,',','.') }}</div>
            </div>
        </div>
        @empty
        <div class="p-8 text-center">
            <p class="text-slate-400 font-medium text-xs">Belum ada transaksi.</p>
        </div>
        @endforelse
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead class="bg-slate-50 border-b border-slate-100 text-slate-450 text-[10px] sm:text-xs font-semibold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">ID Transaksi</th>
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4">Pelanggan</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Nilai Belanja</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700 text-xs sm:text-sm">
                @forelse($recentOrders as $o)
                <tr class="hover:bg-slate-50/40 transition duration-150">
                    <td class="px-6 py-4 font-bold text-slate-800">#ORD-{{ str_pad($o->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-6 py-4 text-slate-400 font-medium">{{ $o->created_at->diffForHumans() }}</td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-750 flex items-center gap-2.5">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($o->customer_name) }}&background=f8fafc&color=334155&bold=true" class="w-7 h-7 rounded-lg border border-slate-150 shrink-0" alt="Avatar">
                            <span>{{ $o->customer_name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($o->status == 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-700 border border-amber-500/10 gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> PENDING
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100/50 gap-1">
                                <i class="fa-solid fa-check text-[9px]"></i> SELESAI
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right font-bold text-slate-800">
                        Rp {{ number_format($o->total_amount,0,',','.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium">
                        <div class="text-slate-350 mb-2"><i class="fa-solid fa-folder-open text-3xl"></i></div>
                        <span>Belum ada transaksi masuk.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    const chartData = {
        weekly: {
            labels: {!! json_encode($weeklyData['labels']) !!},
            data: {!! json_encode($weeklyData['data']) !!}
        },
        monthly: {
            labels: {!! json_encode($monthlyData['labels']) !!},
            data: {!! json_encode($monthlyData['data']) !!}
        },
        yearly: {
            labels: {!! json_encode($yearlyData['labels']) !!},
            data: {!! json_encode($yearlyData['data']) !!}
        }
    };

    let options = {
        series: [{ name: 'Pemasukan (Rp)', data: chartData.weekly.data }],
        chart: {
            type: 'area',
            height: 250,
            fontFamily: 'Outfit, sans-serif',
            toolbar: { show: false },
            zoom: { enabled: false }
        },
        colors: ['#f97316'],
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.25, opacityTo: 0.02, stops: [0, 95, 100] }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        xaxis: {
            categories: chartData.weekly.labels,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 500 } }
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    if (value === 0) return "Rp 0";
                    if (value >= 1000000) return "Rp " + (value / 1000000).toFixed(1) + " Jt";
                    if (value >= 1000) return "Rp " + (value / 1000).toFixed(0) + " Rb";
                    return "Rp " + value;
                },
                style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 500 }
            }
        },
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 4,
            yaxis: { lines: { show: true } },
            xaxis: { lines: { show: false } },
            padding: { top: 0, right: 0, bottom: 0, left: 5 }
        },
        tooltip: {
            theme: 'light',
            y: { formatter: function (val) { return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); } }
        }
    };

    let apexChart = new ApexCharts(document.querySelector("#revenueChart"), options);
    apexChart.render();

    function updateChart(period) {
        const active = "flex-1 sm:flex-none px-4 py-1.5 text-xs font-bold rounded-lg transition bg-white text-slate-800 shadow-sm border border-slate-200/20";
        const inactive = "flex-1 sm:flex-none px-4 py-1.5 text-xs font-bold rounded-lg transition text-slate-500 hover:text-slate-800 hover:bg-white/40";
        
        ['weekly','monthly','yearly'].forEach(p => {
            const btn = document.getElementById('btn-' + p);
            if(btn) btn.className = inactive;
        });
        
        const activeBtn = document.getElementById('btn-' + period);
        if(activeBtn) activeBtn.className = active;
        
        apexChart.updateSeries([{ name: 'Pemasukan (Rp)', data: chartData[period].data }]);
        apexChart.updateOptions({ xaxis: { categories: chartData[period].labels } });
    }
</script>
@endsection
