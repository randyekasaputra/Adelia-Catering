@extends('admin.layout')
@section('content')

<!-- Header -->
<div class="mb-6 lg:mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <span class="bg-orange-100 text-orange-800 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                <i class="fa-solid fa-bolt mr-1"></i> SMCKP &amp; Dynamic Programming
            </span>
            <span class="text-xs text-slate-400 font-medium">Weighted Sum (Popularitas Transaksi &amp; Keuntungan HPP)</span>
        </div>
        <h1 class="text-2xl lg:text-3xl font-bold text-slate-800 tracking-tight">Sistem Rekomendasi Paket Menu (SMCKP)</h1>
        <p class="text-slate-500 mt-1 font-medium text-xs sm:text-sm">Menentukan kombinasi menu optimal dengan nilai utilitas maksimum berdasarkan popularitas penjualan, keuntungan (Harga Jual &minus; HPP), batas anggaran pelanggan, dan kategori opsional.</p>
    </div>
</div>

<!-- Main Grid -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

    <!-- KIRI: Form Input Parameter Penjual (lg:col-span-5) -->
    <div class="lg:col-span-5 space-y-6">
        <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-slate-200/60">
            <h3 class="text-sm font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fa-solid fa-sliders text-orange-500"></i>
                Parameter Optimasi SMCKP &amp; Anggaran
            </h3>

            <form id="recommender-form" onsubmit="calculateRecommendation(event)" class="space-y-4">
                @csrf

                <!-- Nama Pelanggan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Pelanggan / Acara:
                    </label>
                    <input type="text" id="input-customer-name" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-xs sm:text-sm font-medium text-slate-800 outline-none transition" 
                        placeholder="Contoh: Bu Hj. Siti / Rapat BPN" value="Kak">
                </div>

                <!-- Budget per Porsi -->
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Batas Anggaran per Porsi (B): <span class="text-red-500">*</span>
                        </label>
                        <span id="label-budget-val" class="text-sm font-bold text-orange-600">Rp 25.000</span>
                    </div>
                    
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none font-bold text-slate-400 text-xs sm:text-sm">Rp</span>
                        <input type="number" id="input-budget" required min="10000" max="100000" step="1000" value="25000"
                            oninput="document.getElementById('label-budget-val').innerText = formatIDR(this.value)"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-xs sm:text-sm font-bold text-slate-800 outline-none transition">
                    </div>

                    <!-- Quick Preset Pills -->
                    <div class="flex flex-wrap gap-1.5 mt-2">
                        <button type="button" onclick="setAdminBudget(15000)" class="btn-preset px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-slate-100 hover:bg-orange-50 hover:text-orange-600 transition">15 Ribu</button>
                        <button type="button" onclick="setAdminBudget(20000)" class="btn-preset px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-slate-100 hover:bg-orange-50 hover:text-orange-600 transition">20 Ribu</button>
                        <button type="button" onclick="setAdminBudget(25000)" class="btn-preset px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-orange-50 text-orange-600 border border-orange-200 transition">25 Ribu</button>
                        <button type="button" onclick="setAdminBudget(30000)" class="btn-preset px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-slate-100 hover:bg-orange-50 hover:text-orange-600 transition">30 Ribu</button>
                        <button type="button" onclick="setAdminBudget(35000)" class="btn-preset px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-slate-100 hover:bg-orange-50 hover:text-orange-600 transition">35 Ribu</button>
                    </div>
                </div>

                <!-- Pilihan Kategori Menu (Selective MCKP / SMCKP) -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Kategori Menu Aktif (Selective MCKP):
                        </label>
                        <span class="text-[10px] font-semibold text-orange-600 bg-orange-50 px-2 py-0.5 rounded">&Sigma; x<sub>kj</sub> &le; 1</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 bg-slate-50 p-3 rounded-xl border border-slate-200/80 text-xs">
                        <label class="flex items-center gap-2 font-semibold text-slate-700 cursor-pointer">
                            <input type="checkbox" name="smckp_cat" value="karbohidrat" checked class="rounded text-orange-600 focus:ring-orange-500">
                            <span>🍚 Karbohidrat</span>
                        </label>
                        <label class="flex items-center gap-2 font-semibold text-slate-700 cursor-pointer">
                            <input type="checkbox" name="smckp_cat" value="lauk_utama" checked class="rounded text-orange-600 focus:ring-orange-500">
                            <span>🍗 Lauk Utama</span>
                        </label>
                        <label class="flex items-center gap-2 font-medium text-slate-600 cursor-pointer">
                            <input type="checkbox" name="smckp_cat" value="lauk_pendamping" checked class="rounded text-orange-600 focus:ring-orange-500">
                            <span>🍳 Pendamping <em class="text-[10px] text-slate-400">(Ops)</em></span>
                        </label>
                        <label class="flex items-center gap-2 font-medium text-slate-600 cursor-pointer">
                            <input type="checkbox" name="smckp_cat" value="sayur" checked class="rounded text-orange-600 focus:ring-orange-500">
                            <span>🥗 Sayuran <em class="text-[10px] text-slate-400">(Ops)</em></span>
                        </label>
                        <label class="flex items-center gap-2 font-medium text-slate-600 cursor-pointer col-span-2">
                            <input type="checkbox" name="smckp_cat" value="snack" checked class="rounded text-orange-600 focus:ring-orange-500">
                            <span>🥐 Snack / Kue Penutup <em class="text-[10px] text-slate-400">(Opsional - dapat dilewati)</em></span>
                        </label>
                    </div>
                </div>

                <!-- Bobot Weighted Sum (Popularitas w1 vs Keuntungan HPP w2) -->
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200/80">
                    <div class="flex justify-between items-center mb-1">
                        <label class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                            Bobot Fungsi Tujuan (Weighted Sum):
                        </label>
                        <span id="weight-display" class="text-[11px] font-bold text-orange-600">w₁=55% Pop | w₂=45% Laba</span>
                    </div>
                    <input type="range" id="input-w-popularity" min="10" max="90" step="5" value="55"
                        oninput="updateWeightLabel(this.value)"
                        class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-orange-600">
                    <div class="flex justify-between text-[10px] text-slate-400 font-medium mt-1">
                        <span>Prioritas Keuntungan HPP</span>
                        <span>Seimbang</span>
                        <span>Prioritas Popularitas</span>
                    </div>
                </div>

                <!-- Estimasi Jumlah Porsi & Preferensi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Jumlah Porsi:
                        </label>
                        <input type="number" id="input-portions" min="1" max="5000" value="100"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-xs sm:text-sm font-semibold text-slate-800 outline-none transition" 
                            placeholder="100">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Filter Lauk Utama:
                        </label>
                        <select id="input-preference" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-xs sm:text-sm font-semibold text-slate-800 outline-none transition cursor-pointer">
                            <option value="all">Semua (Optimal)</option>
                            <option value="ayam">Olahan Ayam</option>
                            <option value="daging">Olahan Daging</option>
                            <option value="ikan">Olahan Ikan</option>
                        </select>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" id="btn-submit-calc"
                    class="w-full bg-orange-600 hover:bg-orange-700 active:scale-[0.98] text-white font-bold py-3 px-5 rounded-xl shadow-md shadow-orange-500/20 transition-all flex items-center justify-center gap-2 text-xs sm:text-sm">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span id="btn-calc-text">Optimasi Paket (SMCKP + DP)</span>
                </button>
            </form>
        </div>

        <!-- Info Card Rumus SMCKP & Weighted Sum -->
        <div class="bg-amber-50/70 border border-amber-200/60 rounded-2xl p-4 flex gap-3 items-start">
            <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                <i class="fa-solid fa-square-root-variable text-sm"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-amber-900">Fungsi Tujuan Berbobot (Weighted Sum SMCKP)</h4>
                <p class="text-[11px] text-amber-800 mt-0.5 leading-relaxed">
                    Nilai utilitas setiap alternatif menu dihitung dari normalisasi Popularitas Transaksi (<span class="font-mono">P̂<sub>ij</sub></span>) dan Keuntungan HPP (<span class="font-mono">&Pi;̂<sub>ij</sub> = Harga &minus; HPP</span>) dengan persamaan <span class="font-mono font-bold">u<sub>ij</sub> = w₁&middot;P̂<sub>ij</sub> + w₂&middot;&Pi;̂<sub>ij</sub></span>.
                </p>
            </div>
        </div>
    </div>

    <!-- KANAN: Hasil Rekomendasi & Template WhatsApp (lg:col-span-7) -->
    <div class="lg:col-span-7 space-y-6">
        
        <!-- Loading Placeholder -->
        <div id="result-loading" class="hidden bg-white p-12 rounded-2xl border border-slate-200/60 text-center">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-orange-200 border-t-orange-600 mb-3"></div>
            <h4 class="text-sm font-bold text-slate-800">Menjalankan Dynamic Programming (SMCKP)...</h4>
            <p class="text-xs text-slate-400 mt-1">Menyusun tabel rekurensi Bellman dan mencari kombinasi nilai utilitas tertinggi di bawah anggaran.</p>
        </div>

        <!-- Container Hasil -->
        <div id="result-card" class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            
            <!-- Result Header -->
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">
                            <i class="fa-solid fa-circle-check mr-1"></i> Solusi Optimal SMCKP
                        </span>
                        <span id="badge-savings" class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded-full">
                            Sisa Budget Rp 500
                        </span>
                        <span id="badge-profit" class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full">
                            Laba Rp 7.500/porsi
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Kombinasi Paket Menu Terpilih</h3>
                </div>

                <div class="text-left sm:text-right shrink-0">
                    <span class="text-[10px] text-slate-400 block font-semibold uppercase">Total Harga Jual / Porsi:</span>
                    <span id="display-unit-price" class="text-xl sm:text-2xl font-bold text-orange-600">Rp 24.500</span>
                    <span id="display-unit-hpp" class="text-[11px] text-slate-500 block font-medium">Total HPP: Rp 17.000</span>
                </div>
            </div>

            <!-- Komponen Hidangan -->
            <div class="p-5 space-y-2.5" id="items-container">
                <!-- Diisi otomatis oleh JavaScript -->
            </div>

            <!-- Financial & Utility Summary Bar -->
            <div class="px-5 py-4 bg-slate-50 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                <div>
                    <span class="text-[10px] text-slate-400 block font-semibold uppercase">Total Harga Order</span>
                    <span id="display-total-cost" class="text-xs sm:text-sm font-bold text-slate-800">Rp 2.450.000</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 block font-semibold uppercase">Total Laba Usaha</span>
                    <span id="display-total-profit" class="text-xs sm:text-sm font-bold text-emerald-600">+Rp 750.000</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 block font-semibold uppercase">Nilai Utilitas (U)</span>
                    <span id="display-utility" class="text-xs sm:text-sm font-bold text-amber-600">⭐ 89.4 / 100</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 block font-semibold uppercase">Komputasi DP</span>
                    <span id="display-exec-time" class="text-xs sm:text-sm font-bold text-indigo-600">1.5 ms</span>
                </div>
            </div>

            <!-- Fitur Andalan: WhatsApp Text Template Ready -->
            <div class="p-5 border-t border-slate-100 bg-white">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-brands fa-whatsapp text-emerald-500 text-sm"></i>
                        Format Siap Kirim ke WhatsApp Pelanggan:
                    </label>
                    <button type="button" onclick="copyWhatsAppMessage()" id="btn-copy-wa"
                        class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 text-xs font-bold px-3 py-1.5 rounded-lg transition">
                        <i class="fa-regular fa-copy"></i>
                        <span id="copy-btn-label">Salin Teks Pesan</span>
                    </button>
                </div>

                <div class="relative">
                    <textarea id="wa-template-box" rows="8" readonly
                        class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-700 leading-relaxed outline-none resize-none focus:bg-white"></textarea>
                </div>

                <div class="mt-3 flex gap-2">
                    <button type="button" onclick="copyWhatsAppMessage()" 
                        class="flex-1 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-bold py-2.5 px-4 rounded-xl shadow-sm text-xs sm:text-sm flex items-center justify-center gap-2 transition">
                        <i class="fa-solid fa-copy"></i>
                        <span>Salin Pesan ke Clipboard</span>
                    </button>
                    <a id="btn-open-wa" href="https://web.whatsapp.com" target="_blank"
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 px-4 rounded-xl text-xs sm:text-sm flex items-center justify-center gap-1.5 transition">
                        <i class="fa-brands fa-whatsapp text-emerald-600"></i>
                        <span>Buka WA</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Toast Alert -->
<div id="copy-toast" class="fixed bottom-6 right-6 bg-slate-900 text-white text-xs font-bold px-4 py-3 rounded-xl shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none z-50 flex items-center gap-2">
    <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
    <span>Teks rekomendasi berhasil disalin ke clipboard!</span>
</div>

<script>
    let currentCalculationData = null;

    function updateWeightLabel(popVal) {
        popVal = parseInt(popVal);
        const profVal = 100 - popVal;
        document.getElementById('weight-display').innerText = `w₁=${popVal}% Pop | w₂=${profVal}% Laba`;
    }

    function setAdminBudget(val) {
        document.getElementById('input-budget').value = val;
        document.getElementById('label-budget-val').innerText = formatIDR(val);
        calculateRecommendation();
    }

    const formatIDR = (number) => {
        return new Intl.NumberFormat('id-ID', { 
            style: 'currency', 
            currency: 'IDR', 
            maximumFractionDigits: 0 
        }).format(number);
    };

    async function calculateRecommendation(e) {
        if (e) e.preventDefault();

        const budget = document.getElementById('input-budget').value;
        const portions = document.getElementById('input-portions').value;
        const preference = document.getElementById('input-preference').value;
        const customerName = document.getElementById('input-customer-name').value;
        const wPopPct = parseInt(document.getElementById('input-w-popularity').value || 55);
        const wPopularity = wPopPct / 100;
        const wProfit = (100 - wPopPct) / 100;

        const checkedCats = Array.from(document.querySelectorAll('input[name="smckp_cat"]:checked')).map(el => el.value);
        if (checkedCats.length === 0) {
            alert('Pilih minimal 1 kategori menu untuk dioptimasi.');
            return;
        }

        const loading = document.getElementById('result-loading');
        const card = document.getElementById('result-card');
        const btn = document.getElementById('btn-submit-calc');

        loading.classList.remove('hidden');
        card.classList.add('hidden');
        btn.disabled = true;
        document.getElementById('btn-calc-text').innerText = 'Mengoptimasi...';

        try {
            const csrfToken = '{{ csrf_token() }}';
            const response = await fetch('/admin/recommender/calculate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    budget: budget,
                    portions: portions,
                    preference: preference,
                    customer_name: customerName,
                    categories: checkedCats,
                    w_popularity: wPopularity,
                    w_profit: wProfit
                })
            });

            const data = await response.json();
            loading.classList.add('hidden');
            btn.disabled = false;
            document.getElementById('btn-calc-text').innerText = 'Optimasi Paket (SMCKP + DP)';

            if (data.success) {
                currentCalculationData = data;
                renderAdminResult(data);
                card.classList.remove('hidden');
            } else {
                alert(data.message || 'Gagal menghitung rekomendasi.');
            }
        } catch (err) {
            console.error(err);
            loading.classList.add('hidden');
            btn.disabled = false;
            document.getElementById('btn-calc-text').innerText = 'Optimasi Paket (SMCKP + DP)';
            alert('Terjadi kesalahan jaringan.');
        }
    }

    function renderAdminResult(data) {
        document.getElementById('display-unit-price').innerText = formatIDR(data.total_price);
        document.getElementById('display-unit-hpp').innerText = `Total HPP: ${formatIDR(data.total_hpp || 0)} (${data.profit_margin_percent || 0}% margin)`;
        document.getElementById('badge-savings').innerText = `Sisa Budget ${formatIDR(data.remaining_budget)}/porsi`;
        document.getElementById('badge-profit').innerText = `Laba +${formatIDR(data.total_profit || 0)}/porsi`;
        document.getElementById('display-total-cost').innerText = formatIDR(data.total_cost);
        document.getElementById('display-total-profit').innerText = `+${formatIDR(data.total_profit_all || 0)}`;
        document.getElementById('display-utility').innerText = `⭐ ${data.average_utility || data.average_popularity} (Pop ${data.average_popularity}%)`;
        document.getElementById('display-exec-time').innerText = `${data.execution_time_ms} ms`;
        document.getElementById('wa-template-box').value = data.wa_template;

        // Render 5 Komponen (termasuk status jika dilewati pada SMCKP)
        const items = data.items;
        const container = document.getElementById('items-container');
        container.innerHTML = '';

        const itemConfigs = [
            { key: 'karbohidrat', label: 'Karbohidrat', icon: 'fa-bowl-rice', color: 'text-amber-600 bg-amber-50' },
            { key: 'lauk_utama', label: 'Lauk Utama', icon: 'fa-drumstick-bite', color: 'text-orange-600 bg-orange-50' },
            { key: 'lauk_pendamping', label: 'Lauk Pendamping (Opsional)', icon: 'fa-egg', color: 'text-blue-600 bg-blue-50' },
            { key: 'sayur', label: 'Sayuran (Opsional)', icon: 'fa-carrot', color: 'text-emerald-600 bg-emerald-50' },
            { key: 'snack', label: 'Snack / Kue (Opsional)', icon: 'fa-cookie-bite', color: 'text-purple-600 bg-purple-50' }
        ];

        itemConfigs.forEach(cfg => {
            const item = items[cfg.key];
            if (item) {
                const isSkipped = item.is_skipped === true;
                const row = document.createElement('div');
                row.className = isSkipped
                    ? 'flex items-center justify-between p-2.5 rounded-xl border border-dashed border-slate-200 bg-slate-50/30 opacity-60'
                    : 'flex items-center justify-between p-2.5 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition';

                row.innerHTML = isSkipped ? `
                    <div class="flex items-center gap-3 min-w-0 pr-2">
                        <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 text-xs">
                            <i class="fa-solid fa-ban"></i>
                        </span>
                        <div class="min-w-0">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">${cfg.label}</span>
                            <h5 class="text-xs font-medium text-slate-400 italic">Dilewati pada SMCKP (x<sub>kj</sub> = 0)</h5>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-xs font-semibold text-slate-400">Rp 0</span>
                    </div>
                ` : `
                    <div class="flex items-center gap-3 min-w-0 pr-2">
                        <span class="w-8 h-8 rounded-lg ${cfg.color} flex items-center justify-center shrink-0 text-xs">
                            <i class="fa-solid ${cfg.icon}"></i>
                        </span>
                        <div class="min-w-0">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">${cfg.label}</span>
                            <h5 class="text-xs font-bold text-slate-800 truncate">${item.name}</h5>
                            <span class="text-[10px] text-emerald-600 font-medium">HPP: ${formatIDR(item.hpp || 0)} &bull; Laba: +${formatIDR(item.profit || 0)}</span>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-xs font-bold text-orange-600">${formatIDR(item.price)}</span>
                        <span class="text-[10px] text-slate-500 font-semibold block">Utilitas: ${item.utility_score || item.popularity_score}</span>
                    </div>
                `;
                container.appendChild(row);
            }
        });
    }

    function copyWhatsAppMessage() {
        const textarea = document.getElementById('wa-template-box');
        textarea.select();
        navigator.clipboard.writeText(textarea.value);

        const toast = document.getElementById('copy-toast');
        toast.classList.remove('translate-y-20', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');

        const btnLabel = document.getElementById('copy-btn-label');
        btnLabel.innerText = 'Tersalin!';

        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-20', 'opacity-0');
            btnLabel.innerText = 'Salin Teks Pesan';
        }, 3000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        calculateRecommendation();
    });
</script>

@endsection
