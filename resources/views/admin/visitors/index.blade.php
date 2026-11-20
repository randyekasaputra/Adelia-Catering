@extends('admin.layout')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<!-- Header Halaman -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 lg:mb-8">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <span class="bg-indigo-100 text-indigo-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                <i class="fa-solid fa-globe mr-1"></i> Web Traffic &amp; Device Analytics
            </span>
        </div>
        <h1 class="text-2xl lg:text-3xl font-bold text-slate-800 tracking-tight">Statistik Pengunjung &amp; Perangkat</h1>
        <p class="text-slate-500 mt-1 font-medium text-xs sm:text-sm">Pantau jumlah pengunjung website, tren akses harian, serta perangkat (Mobile/Desktop), OS, dan browser yang digunakan.</p>
    </div>

    <!-- Filter Rentang Hari -->
    <div class="flex bg-white p-1 rounded-xl border border-slate-200/80 shadow-sm w-fit shrink-0">
        <a href="/admin/visitors?days=7" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition {{ $days === 7 ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">7 Hari</a>
        <a href="/admin/visitors?days=14" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition {{ $days === 14 ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">14 Hari</a>
        <a href="/admin/visitors?days=30" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition {{ $days === 30 ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">30 Hari</a>
    </div>
</div>

@if(!$hasTable)
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6 flex items-start gap-3">
    <i class="fa-solid fa-triangle-exclamation text-amber-600 text-lg mt-0.5"></i>
    <div>
        <h3 class="font-bold text-amber-900 text-sm">Tabel Pelacakan Pengunjung Belum Dimigrasi</h3>
        <p class="text-xs text-amber-800 mt-1">Jalankan perintah <code class="bg-amber-100 px-1.5 py-0.5 rounded font-mono font-bold">php artisan migrate</code> di terminal Laragon untuk mengaktifkan pencatatan pengunjung dan perangkat secara otomatis.</p>
    </div>
</div>
@endif

<!-- 4 Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/60 flex items-center justify-between">
        <div>
            <div class="text-slate-400 font-semibold text-[10px] sm:text-xs uppercase tracking-wider mb-1">Pengunjung Hari Ini</div>
            <div class="text-2xl sm:text-3xl font-bold text-indigo-600">{{ number_format($todayVisitors) }}</div>
            <span class="text-[10px] text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded mt-1.5 inline-block">pengunjung unik</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-user-clock text-lg"></i>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/60 flex items-center justify-between">
        <div>
            <div class="text-slate-400 font-semibold text-[10px] sm:text-xs uppercase tracking-wider mb-1">Total Pengunjung Unik</div>
            <div class="text-2xl sm:text-3xl font-bold text-slate-800">{{ number_format($totalVisitors) }}</div>
            <span class="text-[10px] text-slate-500 font-bold bg-slate-100 px-2 py-0.5 rounded mt-1.5 inline-block">keseluruhan sesi</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-users text-lg"></i>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/60 flex items-center justify-between">
        <div>
            <div class="text-slate-400 font-semibold text-[10px] sm:text-xs uppercase tracking-wider mb-1">Total Halaman Diakses</div>
            <div class="text-2xl sm:text-3xl font-bold text-emerald-600">{{ number_format($totalViews) }}</div>
            <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded mt-1.5 inline-block">page views</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-eye text-lg"></i>
        </div>
    </div>

    <div class="bg-slate-900 text-white p-5 rounded-2xl shadow-sm border border-slate-800 flex items-center justify-between">
        <div>
            <div class="text-slate-400 font-semibold text-[10px] sm:text-xs uppercase tracking-wider mb-1">Perangkat Dominan</div>
            <div class="text-2xl sm:text-3xl font-bold text-white">{{ $dominantDevice }}</div>
            <span class="text-[10px] text-amber-300 font-bold bg-white/10 px-2 py-0.5 rounded mt-1.5 inline-block">{{ $dominantPct }}% dari pengunjung</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-white/10 text-white flex items-center justify-center shrink-0">
            <i class="fa-solid {{ $dominantDevice === 'Mobile' ? 'fa-mobile-screen-button' : ($dominantDevice === 'Tablet' ? 'fa-tablet-screen-button' : 'fa-desktop') }} text-lg"></i>
        </div>
    </div>
</div>

<!-- Row Grafik Utama -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
    <!-- Grafik Tren Pengunjung -->
    <div class="lg:col-span-8 bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-sm sm:text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-chart-column text-indigo-500"></i>
                Tren Pengunjung &amp; Tayangan Halaman ({{ $days }} Hari Terakhir)
            </h2>
            <p class="text-[11px] text-slate-500 mt-0.5">Perbandingan jumlah pengunjung unik dan total halaman yang dibuka per hari.</p>
        </div>
        <div class="p-4 sm:p-6">
            <div id="visitorTrendChart" style="height: 260px;"></div>
        </div>
    </div>

    <!-- Distribusi Tipe Perangkat -->
    <div class="lg:col-span-4 bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden flex flex-col justify-between">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-sm sm:text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-laptop-mobile text-orange-500"></i>
                Jenis Perangkat (Device)
            </h2>
            <p class="text-[11px] text-slate-500 mt-0.5">Proporsi akses dari HP, Laptop/PC, dan Tablet.</p>
        </div>
        <div class="p-5 flex-1 flex flex-col justify-center">
            @if($deviceStats->isEmpty())
                <div class="text-center py-8 text-slate-400 text-xs">Belum ada data perangkat tercatat.</div>
            @else
                <div id="deviceDonutChart" class="mb-4"></div>
                <div class="space-y-2">
                    @foreach($deviceStats as $dev)
                    <div class="flex items-center justify-between text-xs p-2 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-2 font-bold text-slate-700">
                            <i class="fa-solid {{ $dev->device_type === 'Mobile' ? 'fa-mobile-screen-button text-indigo-500' : ($dev->device_type === 'Tablet' ? 'fa-tablet-screen-button text-amber-500' : 'fa-desktop text-emerald-500') }}"></i>
                            <span>{{ $dev->device_type }}</span>
                        </div>
                        <div class="font-bold text-slate-800">
                            {{ $dev->total_users }} org <span class="text-slate-400 font-medium">({{ $dev->percentage }}%)</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Row Breakdown: OS, Browser, & Halaman Populer -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Sistem Operasi (OS) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5">
        <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-microchip text-indigo-500"></i>
            Sistem Operasi (OS)
        </h3>
        @forelse($osStats as $os)
        <div class="mb-3 last:mb-0">
            <div class="flex justify-between text-xs font-semibold mb-1">
                <span class="text-slate-700">{{ $os->os }}</span>
                <span class="text-slate-500">{{ $os->total_users }} ({{ $os->percentage }}%)</span>
            </div>
            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $os->percentage }}%"></div>
            </div>
        </div>
        @empty
        <p class="text-xs text-slate-400 py-4 text-center">Belum ada data OS.</p>
        @endforelse
    </div>

    <!-- Browser -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5">
        <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fa-brands fa-chrome text-orange-500"></i>
            Browser Pengunjung
        </h3>
        @forelse($browserStats as $br)
        <div class="mb-3 last:mb-0">
            <div class="flex justify-between text-xs font-semibold mb-1">
                <span class="text-slate-700">{{ $br->browser }}</span>
                <span class="text-slate-500">{{ $br->total_users }} ({{ $br->percentage }}%)</span>
            </div>
            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-orange-500 rounded-full" style="width: {{ $br->percentage }}%"></div>
            </div>
        </div>
        @empty
        <p class="text-xs text-slate-400 py-4 text-center">Belum ada data browser.</p>
        @endforelse
    </div>

    <!-- Halaman Paling Populer -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5">
        <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-fire text-emerald-500"></i>
            Halaman Paling Sering Diakses
        </h3>
        @forelse($topPages as $pg)
        <div class="flex items-center justify-between py-2 border-b border-slate-100 last:border-0 text-xs">
            <span class="font-mono font-semibold text-slate-700 truncate max-w-[180px]" title="{{ $pg->url_path }}">{{ $pg->url_path }}</span>
            <div class="text-right shrink-0">
                <span class="font-bold text-slate-800">{{ $pg->total_views }}x</span>
                <span class="text-[10px] text-slate-400 ml-1">({{ $pg->unique_visitors }} org)</span>
            </div>
        </div>
        @empty
        <p class="text-xs text-slate-400 py-4 text-center">Belum ada data halaman.</p>
        @endforelse
    </div>
</div>

<!-- Tabel Log Pengunjung Terbaru -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden mb-8">
    <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
        <div>
            <h3 class="text-sm sm:text-base font-bold text-slate-800">Aktivitas Pengunjung Terbaru</h3>
            <p class="text-[11px] text-slate-500">20 akses halaman publik terakhir beserta detail perangkatnya.</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[10px] sm:text-xs font-semibold uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5">Waktu</th>
                    <th class="px-5 py-3.5">Perangkat</th>
                    <th class="px-5 py-3.5">OS &amp; Browser</th>
                    <th class="px-5 py-3.5">Halaman Diakses</th>
                    <th class="px-5 py-3.5">Alamat IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                @forelse($recentLogs as $log)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-5 py-3.5 text-slate-500 font-medium">
                        {{ $log->created_at->format('d M Y, H:i') }}
                        <span class="text-[10px] text-slate-400 block">{{ $log->created_at->diffForHumans() }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg font-bold text-[11px]
                            {{ $log->device_type === 'Mobile' ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : ($log->device_type === 'Tablet' ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-100') }}">
                            <i class="fa-solid {{ $log->device_type === 'Mobile' ? 'fa-mobile-screen-button' : ($log->device_type === 'Tablet' ? 'fa-tablet-screen-button' : 'fa-desktop') }}"></i>
                            <span>{{ $log->device_type }}</span>
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="font-bold text-slate-800">{{ $log->os }}</div>
                        <div class="text-[11px] text-slate-400">{{ $log->browser }}</div>
                    </td>
                    <td class="px-5 py-3.5 font-mono text-indigo-600 font-semibold">{{ $log->url_path }}</td>
                    <td class="px-5 py-3.5 font-mono text-slate-500">{{ $log->ip_address ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-slate-400">Belum ada aktivitas pengunjung tercatat. Coba buka halaman depan website untuk mulai merekam.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    const trendLabels = {!! json_encode($trendData['labels']) !!};
    const trendVisitors = {!! json_encode($trendData['visitors']) !!};
    const trendViews = {!! json_encode($trendData['views']) !!};

    if (document.querySelector('#visitorTrendChart')) {
        new ApexCharts(document.querySelector('#visitorTrendChart'), {
            series: [
                { name: 'Pengunjung Unik', data: trendVisitors },
                { name: 'Total Halaman Dibuka', data: trendViews }
            ],
            chart: { type: 'area', height: 260, fontFamily: 'Outfit, sans-serif', toolbar: { show: false } },
            colors: ['#4f46e5', '#f97316'],
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.25, opacityTo: 0.02 } },
            stroke: { curve: 'smooth', width: 2.5 },
            dataLabels: { enabled: false },
            xaxis: { categories: trendLabels, labels: { style: { colors: '#94a3b8', fontSize: '10px' } } },
            yaxis: { labels: { style: { colors: '#94a3b8', fontSize: '10px' }, formatter: (v) => Math.round(v) } },
            grid: { borderColor: '#f1f5f9', strokeDashArray: 4 }
        }).render();
    }

    const devLabels = {!! json_encode($deviceStats->pluck('device_type')->values()) !!};
    const devSeries = {!! json_encode($deviceStats->pluck('total_users')->map(fn($v) => (int)$v)->values()) !!};

    if (document.querySelector('#deviceDonutChart') && devSeries.length > 0) {
        new ApexCharts(document.querySelector('#deviceDonutChart'), {
            series: devSeries,
            labels: devLabels,
            chart: { type: 'donut', height: 200, fontFamily: 'Outfit, sans-serif' },
            colors: ['#4f46e5', '#10b981', '#f59e0b'],
            legend: { show: false },
            dataLabels: { enabled: true, formatter: (val) => val.toFixed(0) + '%' },
            plotOptions: { pie: { donut: { size: '65%' } } }
        }).render();
    }
</script>
@endsection
