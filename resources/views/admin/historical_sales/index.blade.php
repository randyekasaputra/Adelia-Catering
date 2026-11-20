@extends('admin.layout')
@section('content')

<!-- Header Halaman -->
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-orange-100 text-orange-700">
                <i class="fa-solid fa-database text-[10px]"></i> Dataset Skripsi
            </span>
            <span class="text-xs text-slate-450 font-medium">Selective Multiple-Choice Knapsack (SMCKP)</span>
        </div>
        <h1 class="text-2xl lg:text-3xl font-bold text-slate-800 tracking-tight">Data Transaksi Penjualan Historis</h1>
        <p class="text-slate-500 mt-1 font-medium text-xs sm:text-sm">Basis data transaksi penjualan dan perhitungan HPP untuk menentukan Nilai Utilitas Weighted Sum (<span class="font-mono text-orange-600">u<sub>ij</sub> = w₁&middot;P̂<sub>ij</sub> + w₂&middot;&Pi;̂<sub>ij</sub></span>) pada Algoritma Dynamic Programming.</p>
    </div>

    <!-- Tombol Aksi Cepat -->
    <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto">
        <a href="/admin/historical-sales/template" 
           class="inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 active:bg-slate-100 text-slate-700 border border-slate-200/80 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold shadow-sm transition active:scale-[0.98]">
            <i class="fa-solid fa-file-arrow-down text-emerald-600"></i>
            <span>Download Template CSV</span>
        </a>

        <form action="/admin/historical-sales/recalculate" method="POST" onsubmit="return confirm('Kalkulasi ulang nilai popularitas, keuntungan HPP, dan nilai utilitas (Weighted Sum) untuk seluruh menu?');">
            @csrf
            <button type="submit" 
                    class="inline-flex items-center justify-center gap-2 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white px-4 py-2 rounded-xl text-xs sm:text-sm font-bold shadow-sm transition active:scale-[0.98]">
                <i class="fa-solid fa-arrows-rotate"></i>
                <span>Hitung Ulang Utilitas (u<sub>ij</sub>)</span>
            </button>
        </form>
    </div>
</div>

<!-- Alert Notifikasi -->
@if(session('success'))
<div class="bg-emerald-50 border border-emerald-200/70 text-emerald-800 p-4 rounded-2xl mb-6 flex items-start gap-3 text-xs sm:text-sm shadow-sm">
    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0 text-base"></i>
    <div class="flex-1">
        <span class="font-bold">Berhasil!</span> {{ session('success') }}
    </div>
</div>
@endif

@if(session('error'))
<div class="bg-rose-50 border border-rose-200/70 text-rose-800 p-4 rounded-2xl mb-6 flex items-start gap-3 text-xs sm:text-sm shadow-sm">
    <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5 shrink-0 text-base"></i>
    <div class="flex-1">
        <span class="font-bold">Perhatian:</span> {{ session('error') }}
    </div>
</div>
@endif

<!-- Metrik Ringkasan Dataset -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <div class="bg-white border border-slate-200/60 rounded-2xl p-4 shadow-sm flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-600 shrink-0">
            <i class="fa-solid fa-receipt text-lg"></i>
        </div>
        <div class="min-w-0">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block truncate">Total Data Baris</span>
            <span class="text-lg sm:text-xl font-bold text-slate-800">{{ number_format($totalTransactions, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="bg-white border border-slate-200/60 rounded-2xl p-4 shadow-sm flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
            <i class="fa-solid fa-box-archive text-lg"></i>
        </div>
        <div class="min-w-0">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block truncate">Total Porsi Terjual</span>
            <span class="text-lg sm:text-xl font-bold text-slate-800">{{ number_format($totalQtySold, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="bg-white border border-slate-200/60 rounded-2xl p-4 shadow-sm flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0">
            <i class="fa-solid fa-sack-dollar text-lg"></i>
        </div>
        <div class="min-w-0">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block truncate">Total Omzet Historis</span>
            <span class="text-lg sm:text-xl font-bold text-slate-800">Rp {{ number_format($totalRevenue / 1000000, 1, ',', '.') }} Jt</span>
        </div>
    </div>

    <div class="bg-white border border-slate-200/60 rounded-2xl p-4 shadow-sm flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 shrink-0">
            <i class="fa-solid fa-utensils text-lg"></i>
        </div>
        <div class="min-w-0">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block truncate">Variasi Menu Terdata</span>
            <span class="text-lg sm:text-xl font-bold text-slate-800">{{ $uniqueMenusCount }} Menu</span>
        </div>
    </div>
</div>

<!-- Layout 2 Kolom: Form Import CSV (Kiri) + Insight Top 5 Terlaris (Kanan) -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
    
    <!-- Form Import CSV -->
    <div class="lg:col-span-7 bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-orange-50 border border-orange-100 text-orange-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-file-import"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Import File CSV Data Penjualan</h3>
                    <p class="text-[11px] text-slate-400">Unggah file rekapan transaksi masa lalu (ekspor Excel / CSV)</p>
                </div>
            </div>
            @if($lastCalculated)
            <div class="text-right hidden sm:block">
                <span class="text-[10px] text-slate-400 block">Kalkulasi Terakhir:</span>
                <span class="text-[11px] font-bold text-slate-600">{{ \Carbon\Carbon::parse($lastCalculated)->translatedFormat('d M Y, H:i') }}</span>
            </div>
            @endif
        </div>

        <form action="/admin/historical-sales/import" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Upload File Input -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Pilih File CSV (.csv / .txt) <span class="text-red-500">*</span>
                </label>
                <div class="relative border-2 border-dashed border-slate-200 hover:border-orange-400 rounded-xl p-4 bg-slate-50/50 hover:bg-orange-50/20 transition-all text-center cursor-pointer group">
                    <input type="file" name="csv_file" accept=".csv,.txt" required
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                           onchange="updateFileName(this)">
                    <div class="space-y-1">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl text-slate-400 group-hover:text-orange-500 transition"></i>
                        <p id="file-label" class="text-xs font-semibold text-slate-700 group-hover:text-orange-600">
                            Klik atau seret file CSV ke area ini
                        </p>
                        <p class="text-[10px] text-slate-450">Maksimum ukuran 15MB. Otomatis mendukung pemisah koma (,) atau titik-koma (;).</p>
                    </div>
                </div>
            </div>

            <!-- Mode Import -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                <label class="relative flex items-start gap-3 p-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50/70 cursor-pointer transition">
                    <input type="radio" name="mode" value="append" checked class="mt-0.5 text-orange-500 focus:ring-orange-400">
                    <div>
                        <span class="block text-xs font-bold text-slate-800">Tambahkan Data (Append)</span>
                        <span class="block text-[10px] text-slate-450 mt-0.5">Pertahankan data transaksi yang sudah ada dan tambahkan baris baru.</span>
                    </div>
                </label>

                <label class="relative flex items-start gap-3 p-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50/70 cursor-pointer transition">
                    <input type="radio" name="mode" value="replace" class="mt-0.5 text-orange-500 focus:ring-orange-400">
                    <div>
                        <span class="block text-xs font-bold text-slate-800 text-rose-600">Reset & Timpa (Replace)</span>
                        <span class="block text-[10px] text-slate-450 mt-0.5">Kosongkan dataset lama dan ganti sepenuhnya dengan file baru ini.</span>
                    </div>
                </label>
            </div>

            <!-- Option Recalculate Weights -->
            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" name="recalculate" id="recalculate" value="1" checked
                       class="rounded border-slate-300 text-orange-500 focus:ring-orange-400 w-4 h-4 cursor-pointer">
                <label for="recalculate" class="text-xs font-medium text-slate-700 cursor-pointer">
                    Otomatis sinkronkan dan hitung ulang skor popularitas (<span class="font-mono text-orange-600 font-bold">v<sub>i</sub></span>) setelah import selesai
                </label>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" 
                        class="w-full bg-slate-900 hover:bg-slate-800 active:bg-black text-white py-2.5 px-4 rounded-xl font-bold text-xs sm:text-sm shadow-sm transition active:scale-[0.99] flex items-center justify-center gap-2">
                    <i class="fa-solid fa-upload"></i>
                    <span>Proses & Simpan Data Penjualan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Insight Top 5 Menu Terlaris Historis -->
    <div class="lg:col-span-5 bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Top 5 Menu Paling Laris</h3>
                        <p class="text-[11px] text-slate-400">Penyumbang utilitas tertinggi pada formulasi Knapsack</p>
                    </div>
                </div>
            </div>

            <div class="space-y-2.5">
                @forelse($topHistoricalMenus as $index => $top)
                <div class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <span class="w-6 h-6 rounded-lg {{ $index === 0 ? 'bg-amber-500 text-white font-bold' : ($index === 1 ? 'bg-slate-300 text-slate-700 font-bold' : 'bg-slate-200 text-slate-600') }} flex items-center justify-center text-[11px] shrink-0">
                            {{ $index + 1 }}
                        </span>
                        <div class="min-w-0">
                            <span class="font-bold text-xs text-slate-800 block truncate">{{ $top->menu_name }}</span>
                            <span class="text-[10px] text-slate-450">{{ $top->trans_count }} kali pemesanan</span>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="font-bold text-xs text-orange-600 block">{{ number_format($top->total_qty, 0, ',', '.') }} porsi</span>
                        <span class="text-[10px] text-slate-450 font-medium">Rp {{ number_format($top->total_rev / 1000, 0, ',', '.') }}k</span>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-slate-400 text-xs">
                    Belum ada data penjualan yang dapat dianalisis.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Formula Callout -->
        <div class="mt-4 p-3 rounded-xl bg-orange-50/60 border border-orange-100 text-[11px] text-orange-900">
            <span class="font-bold flex items-center gap-1.5 mb-1 text-orange-800">
                <i class="fa-solid fa-calculator text-[10px]"></i> Formula Skripsi:
            </span>
            <p class="font-mono text-[10px] text-orange-700 leading-relaxed">
                v<sub>i</sub> = [ 0.6 × (Qty / Qty<sub>max</sub>) + 0.4 × (Freq / Freq<sub>max</sub>) ] × 100
            </p>
        </div>
    </div>
</div>

<!-- Filter & Search Bar -->
<div class="bg-white border border-slate-200/60 rounded-2xl p-4 mb-6 shadow-sm">
    <form method="GET" action="/admin/historical-sales" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
        
        <!-- Search Input -->
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Cari nama hidangan atau nomor invoice..."
                   class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl bg-slate-50/50 text-xs focus:bg-white focus:border-orange-500 outline-none transition font-medium text-slate-700">
        </div>

        <div class="flex items-center gap-2 overflow-x-auto">
            <!-- Filter Tahun -->
            <select name="year" onchange="this.form.submit()"
                    class="border border-slate-200 rounded-xl px-3 py-2 bg-slate-50/50 text-xs font-semibold text-slate-700 outline-none focus:border-orange-500 cursor-pointer">
                <option value="all">🗓️ Semua Tahun</option>
                @foreach($years as $yr)
                    <option value="{{ $yr }}" {{ $selectedYear == $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                @endforeach
            </select>

            <!-- Filter Tipe Pelanggan -->
            <select name="customer_type" onchange="this.form.submit()"
                    class="border border-slate-200 rounded-xl px-3 py-2 bg-slate-50/50 text-xs font-semibold text-slate-700 outline-none focus:border-orange-500 cursor-pointer">
                <option value="all">👥 Semua Pelanggan</option>
                <option value="hajatan" {{ $selectedCustomerType == 'hajatan' ? 'selected' : '' }}>Hajatan / Pernikahan</option>
                <option value="instansi" {{ $selectedCustomerType == 'instansi' ? 'selected' : '' }}>Instansi / Dinas</option>
                <option value="kantor" {{ $selectedCustomerType == 'kantor' ? 'selected' : '' }}>Kantor / Corporate</option>
                <option value="perorangan" {{ $selectedCustomerType == 'perorangan' ? 'selected' : '' }}>Perorangan</option>
            </select>

            <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3.5 py-2 rounded-xl text-xs font-bold transition">
                Terapkan
            </button>

            @if($search || $selectedYear !== 'all' || $selectedCustomerType !== 'all')
                <a href="/admin/historical-sales" class="text-rose-600 hover:text-rose-700 text-xs font-bold px-2 py-2">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Riwayat Penjualan Historis -->
<div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead class="bg-slate-50 border-b border-slate-100 text-slate-450 text-[10px] sm:text-xs font-semibold uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5">No Invoice</th>
                    <th class="px-5 py-3.5">Tanggal</th>
                    <th class="px-5 py-3.5">Nama Menu Terjual</th>
                    <th class="px-5 py-3.5 text-center">Tipe Acara</th>
                    <th class="px-5 py-3.5 text-right">Porsi (Qty)</th>
                    <th class="px-5 py-3.5 text-right">Harga Satuan</th>
                    <th class="px-5 py-3.5 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-705 text-xs sm:text-sm">
                @forelse($sales as $s)
                <tr class="hover:bg-slate-50/40 transition">
                    <td class="px-5 py-3.5 font-mono font-semibold text-slate-700 text-xs">
                        {{ $s->invoice_no ?: '-' }}
                    </td>
                    <td class="px-5 py-3.5 text-slate-500 font-medium text-xs">
                        {{ $s->transaction_date ? $s->transaction_date->translatedFormat('d M Y') : '-' }}
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="font-bold text-slate-800 text-xs sm:text-sm">{{ $s->menu_name }}</div>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[9px] uppercase font-bold text-slate-400">{{ $s->category }}</span>
                            @if($s->menu_id)
                                <span class="inline-flex items-center gap-0.5 text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.2 rounded border border-emerald-100">
                                    <i class="fa-solid fa-link text-[7px]"></i> Terkatalog
                                </span>
                            @else
                                <span class="inline-flex items-center gap-0.5 text-[9px] font-medium text-slate-400 bg-slate-100 px-1.5 py-0.2 rounded">
                                    Menu Kustom
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        @php
                            $badgeClasses = [
                                'hajatan'    => 'bg-amber-50 text-amber-700 border-amber-200/80',
                                'instansi'   => 'bg-blue-50 text-blue-700 border-blue-200/80',
                                'kantor'     => 'bg-purple-50 text-purple-700 border-purple-200/80',
                                'perorangan' => 'bg-slate-100 text-slate-700 border-slate-200',
                            ];
                            $cClass = $badgeClasses[$s->customer_type] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $cClass }}">
                            {{ ucfirst($s->customer_type ?: 'Umum') }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-right font-bold text-slate-800">
                        {{ number_format($s->qty, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3.5 text-right text-slate-600 font-medium">
                        Rp {{ number_format($s->unit_price, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3.5 text-right font-bold text-slate-800">
                        Rp {{ number_format($s->total_price, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-medium">
                        <div class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400 border border-slate-100">
                            <i class="fa-solid fa-box-open text-xl"></i>
                        </div>
                        <h4 class="font-bold text-slate-700 text-sm">Tidak ada data transaksi yang cocok.</h4>
                        <p class="text-xs text-slate-450 mt-1">Gunakan tombol "Import File CSV" di atas untuk menambahkan data.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($sales->hasPages())
    <div class="px-5 py-3.5 border-t border-slate-100 bg-slate-50/50">
        {{ $sales->links() }}
    </div>
    @endif
</div>

<!-- Footer Box: Danger Zone (Reset Data) -->
<div class="bg-white border border-slate-200/60 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <div>
        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Pembersihan Basis Data</h4>
        <p class="text-[11px] text-slate-450">Jika ingin menguji ulang dengan berkas CSV baru dari awal.</p>
    </div>
    <form action="/admin/historical-sales/truncate" method="POST" onsubmit="return confirm('PERINGATAN: Tindakan ini akan mengosongkan seluruh riwayat penjualan dan skor popularitas. Yakin lanjutkan?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex items-center gap-1.5 text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 px-3 py-1.5 rounded-xl text-xs font-bold transition active:scale-95">
            <i class="fa-regular fa-trash-can text-xs"></i>
            <span>Kosongkan Seluruh Data Penjualan</span>
        </button>
    </form>
</div>

<script>
function updateFileName(input) {
    if (input.files && input.files[0]) {
        document.getElementById('file-label').innerHTML = '<i class="fa-solid fa-file-csv text-emerald-500 mr-1"></i> File dipilih: <span class="font-bold text-slate-900">' + input.files[0].name + '</span> (' + (input.files[0].size / 1024).toFixed(1) + ' KB)';
    }
}
</script>

@endsection

