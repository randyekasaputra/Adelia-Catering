@extends('layouts.app')

@section('content')
<section class="py-6 sm:py-14 bg-[#fafaf9] min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">

        <!-- Header Minimalis (Mobile-Friendly) -->
        <div class="max-w-2xl mx-auto text-center mb-6 sm:mb-12">
            <span class="inline-flex items-center gap-1.5 bg-amber-100/80 text-amber-800 text-[10px] sm:text-[11px] font-bold px-3 py-1 rounded-full tracking-wide uppercase mb-2.5 sm:mb-3">
                <i class="fa-solid fa-wand-magic-sparkles text-primary"></i>
                Smart Optimizer &bull; SMCKP + DP
            </span>
            <h1 class="text-2xl sm:text-4xl font-serif font-bold text-slate-900 tracking-tight mb-2 sm:mb-3">
                Paket Menu Katering
            </h1>
            <p class="text-slate-500 text-xs sm:text-base leading-relaxed px-2 sm:px-0">
                Sesuaikan anggaran dan komponen hidangan Anda. Sistem otomatis meracik kombinasi menu terbaik tanpa melebihi budget.
            </p>
        </div>

        <!-- ============================================================ -->
        <!-- SMART PACKAGE GENERATOR (RESPONSIVE MOBILE + DESKTOP BENTO) -->
        <!-- ============================================================ -->
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-[0_4px_24px_rgba(15,23,42,0.04)] p-4 sm:p-8 mb-10 sm:mb-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

                <!-- KOLOM KIRI: KONTROL ANGGARAN & KATEGORI -->
                <div class="lg:col-span-5 space-y-5 sm:space-y-6 lg:pr-6 lg:border-r lg:border-slate-100 pb-6 lg:pb-0 border-b lg:border-b-0 border-slate-100">
                    <div>
                        <div class="flex items-baseline justify-between mb-2">
                            <label for="budget-slider" class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500">
                                Anggaran / Porsi
                            </label>
                            <span id="budget-display" class="text-xl sm:text-2xl font-serif font-bold text-slate-900">
                                Rp 25.000
                            </span>
                        </div>

                        <input type="range" id="budget-slider" min="15000" max="50000" step="1000" value="25000"
                            class="w-full h-2.5 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-primary focus:outline-none">

                        <!-- Preset Chips: 3x2 Grid rapi di Mobile, flex wrap di Desktop -->
                        <div class="grid grid-cols-3 sm:flex sm:flex-wrap gap-2 mt-3">
                            @foreach([15000 => '15rb', 20000 => '20rb', 25000 => '25rb', 30000 => '30rb', 35000 => '35rb', 45000 => '45rb'] as $val => $lbl)
                            <button type="button" onclick="setBudget({{ $val }}, true, this)"
                                class="preset-btn px-3 py-2 sm:py-1.5 rounded-xl sm:rounded-full text-xs font-semibold transition-all text-center {{ $val === 25000 ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                {{ $lbl }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Kategori Opsional (SMCKP): Grid 2 kolom rapi di Mobile -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500">
                                Komponen Menu
                            </span>
                            <span class="text-[11px] text-slate-400">Opsional bisa dilewati</span>
                        </div>
                        <div class="grid grid-cols-2 sm:flex sm:flex-wrap gap-2">
                            @php
                                $catOptions = [
                                    'karbohidrat'     => ['label' => 'Karbohidrat', 'icon' => '🍚'],
                                    'lauk_utama'      => ['label' => 'Lauk Utama', 'icon' => '🍗'],
                                    'lauk_pendamping' => ['label' => 'Pendamping', 'icon' => '🍳'],
                                    'sayur'           => ['label' => 'Sayuran', 'icon' => '🥗'],
                                    'snack'           => ['label' => 'Snack / Kue', 'icon' => '🥐'],
                                ];
                            @endphp
                            @foreach($catOptions as $key => $opt)
                            <label class="cursor-pointer select-none {{ $key === 'snack' ? 'col-span-2 sm:col-span-1' : '' }}">
                                <input type="checkbox" name="cust_smckp_cat" value="{{ $key }}" checked class="peer sr-only">
                                <span class="flex sm:inline-flex items-center justify-center sm:justify-start gap-1.5 px-3 py-2.5 sm:py-1.5 rounded-xl text-xs font-semibold border border-slate-200 bg-white text-slate-500 transition-all
                                    peer-checked:bg-amber-50 peer-checked:border-primary/40 peer-checked:text-slate-900">
                                    <span>{{ $opt['icon'] }}</span>
                                    <span>{{ $opt['label'] }}</span>
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Preferensi Lauk -->
                    <div>
                        <label for="preference-select" class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                            Preferensi Lauk Utama
                        </label>
                        <select id="preference-select"
                            class="w-full px-3.5 py-3 sm:py-2.5 rounded-xl border border-slate-200 bg-slate-50/70 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary text-xs sm:text-sm outline-none font-medium text-slate-700 transition">
                            <option value="all">Semua Rekomendasi Terbaik</option>
                            <option value="ayam">Olahan Ayam (Bakar / Katsu / Goreng)</option>
                            <option value="daging">Olahan Daging Sapi / Rendang</option>
                            <option value="ikan">Olahan Ikan / Bandeng Presto</option>
                        </select>
                    </div>

                    <!-- Tombol Eksekusi -->
                    <button type="button" id="btn-run-optimizer" onclick="runOptimization(true)"
                        class="w-full bg-primary hover:bg-amber-700 active:scale-[0.99] text-white font-bold py-3.5 px-6 rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 text-sm">
                        <i class="fa-solid fa-sparkles"></i>
                        <span id="btn-optimizer-text">Optimalkan Paket Sekarang</span>
                    </button>
                </div>

                <!-- KOLOM KANAN: HASIL OPTIMASI -->
                <div class="lg:col-span-7" id="optimizer-result-anchor">
                    <!-- Loading State -->
                    <div id="optimizer-loading" class="hidden flex flex-col items-center justify-center py-16 sm:py-20 text-center">
                        <div class="w-10 h-10 border-4 border-amber-200 border-t-primary rounded-full animate-spin mb-3"></div>
                        <p class="text-sm font-bold text-slate-800">Menghitung kombinasi optimal...</p>
                        <p class="text-xs text-slate-400 mt-1">Dynamic Programming (SMCKP)</p>
                    </div>

                    <!-- Result Card -->
                    <div id="optimizer-card" class="space-y-4 sm:space-y-5">
                        <!-- Top Bar Harga & Sisa Budget -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                            <div>
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <span class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                                        <i class="fa-solid fa-check text-[9px]"></i> Solusi Optimal
                                    </span>
                                    <span id="res-badge-savings" class="text-[10px] sm:text-[11px] font-semibold text-slate-600 bg-slate-100 px-2.5 py-0.5 rounded-full">
                                        Sisa Rp 500
                                    </span>
                                </div>
                                <h2 id="res-package-name" class="text-base sm:text-xl font-serif font-bold text-slate-900">
                                    Rekomendasi Paket Cerdas
                                </h2>
                            </div>

                            <div class="flex sm:block items-baseline justify-between bg-amber-50/60 sm:bg-transparent px-3.5 py-2 sm:p-0 rounded-xl sm:text-right">
                                <span class="text-[11px] text-slate-500 sm:text-slate-400 block">Total per porsi</span>
                                <span id="res-total-price" class="text-xl sm:text-3xl font-serif font-bold text-primary">
                                    Rp 24.500
                                </span>
                            </div>
                        </div>

                        <!-- Grid 5 Komponen Minimalis -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3" id="res-items-grid">
                            @php
                                $slots = [
                                    ['id' => 'karbo', 'label' => 'Karbohidrat'],
                                    ['id' => 'lauk', 'label' => 'Lauk Utama'],
                                    ['id' => 'pendamping', 'label' => 'Pendamping'],
                                    ['id' => 'sayur', 'label' => 'Sayuran'],
                                    ['id' => 'snack', 'label' => 'Snack / Kue'],
                                ];
                            @endphp
                            @foreach($slots as $idx => $slot)
                            <div class="bg-slate-50/70 rounded-2xl p-2.5 sm:p-3 border border-slate-100 flex items-center gap-3 transition-opacity {{ $idx === 4 ? 'sm:col-span-2' : '' }}">
                                <img id="item-img-{{ $slot['id'] }}" src="/images/menu_extracted/page37_img03_333x333.jpeg" alt="{{ $slot['label'] }}"
                                    class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl object-cover bg-white shrink-0 border border-slate-200/60">
                                <div class="min-w-0 flex-1">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">{{ $slot['label'] }}</span>
                                    <h5 id="item-name-{{ $slot['id'] }}" class="font-bold text-xs sm:text-sm text-slate-800 truncate">-</h5>
                                    <span id="item-price-{{ $slot['id'] }}" class="text-xs font-semibold text-primary">Rp 0</span>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Bottom Metrics & CTA -->
                        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="grid grid-cols-3 gap-2 text-center sm:flex sm:items-center sm:gap-5 sm:text-left text-xs text-slate-500 bg-slate-50 sm:bg-transparent p-2.5 sm:p-0 rounded-xl">
                                <div>
                                    <span class="block text-[10px] uppercase font-semibold text-slate-400">Utilitas</span>
                                    <span id="res-popularity" class="font-bold text-slate-800">⭐ 95.5</span>
                                </div>
                                <div class="hidden sm:block h-6 w-px bg-slate-200"></div>
                                <div class="border-x border-slate-200 sm:border-0">
                                    <span class="block text-[10px] uppercase font-semibold text-slate-400">Energi</span>
                                    <span id="res-calories" class="font-bold text-slate-800">~680 kkal</span>
                                </div>
                                <div class="hidden sm:block h-6 w-px bg-slate-200"></div>
                                <div>
                                    <span class="block text-[10px] uppercase font-semibold text-slate-400">Kecepatan</span>
                                    <span id="res-exec-time" class="font-bold text-slate-800">1.5 ms</span>
                                </div>
                            </div>

                            <button type="button" onclick="addOptimizedPackageToCart()"
                                class="w-full sm:w-auto bg-slate-900 hover:bg-slate-800 active:scale-[0.98] text-white font-bold py-3.5 sm:py-3 px-6 rounded-xl transition-all flex items-center justify-center gap-2 text-sm shrink-0">
                                <i class="fa-solid fa-cart-plus"></i>
                                <span>Pesan Paket Ini</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ============================================================ -->
        <!-- KATALOG PAKET UNGGULAN (KARTU SIMPLE & CLEAN) -->
        <!-- ============================================================ -->
        <div>
            <div class="mb-5 sm:mb-8">
                <h2 class="text-xl sm:text-3xl font-serif font-bold text-slate-900">Paket Unggulan Siap Pesan</h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-1">Pilihan paket favorit pelanggan Adelia Catering yang paling sering dipesan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @forelse($packages as $pkg)
                <div class="group bg-white rounded-2xl sm:rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <a href="{{ url('/packages/' . $pkg->slug) }}" class="block relative h-44 sm:h-48 overflow-hidden bg-slate-100">
                            <img src="{{ $pkg->image ?: '/images/menu_extracted/page35_img03_450x800.jpeg' }}"
                                alt="{{ $pkg->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute top-3 left-3 bg-white/95 backdrop-blur text-slate-800 text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm">
                                <i class="fa-solid fa-crown text-amber-500 mr-1"></i> Favorit
                            </div>
                            <div class="absolute bottom-3 right-3 bg-slate-900/85 backdrop-blur text-white text-xs sm:text-sm font-bold px-3 py-1 rounded-xl">
                                Rp {{ number_format($pkg->price, 0, ',', '.') }}
                            </div>
                        </a>

                        <div class="p-4 sm:p-5">
                            <a href="{{ url('/packages/' . $pkg->slug) }}" class="block !min-h-0">
                                <h3 class="font-serif font-bold text-base sm:text-lg text-slate-900 group-hover:text-primary transition-colors mb-1">
                                    {{ $pkg->name }}
                                </h3>
                            </a>
                            <p class="text-xs text-slate-500 line-clamp-2 mb-3 sm:mb-4 leading-relaxed">{{ $pkg->description }}</p>

                            <div class="flex flex-wrap gap-1.5">
                                @foreach($pkg->items as $item)
                                <span class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] font-medium bg-slate-100 text-slate-700 px-2 sm:px-2.5 py-1 rounded-lg">
                                    <i class="fa-solid fa-check text-[9px] text-emerald-600"></i>
                                    {{ $item->menu->name ?? 'Menu' }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="px-4 sm:px-5 pb-4 sm:pb-5 pt-2 flex items-center gap-2">
                        <button type="button" onclick="addFixedPackageToCart({{ $pkg->id }}, '{{ addslashes($pkg->name) }}', {{ $pkg->price }})"
                            class="flex-1 bg-amber-50 hover:bg-primary text-primary hover:text-white font-bold py-2.5 px-4 rounded-xl transition-all flex items-center justify-center gap-2 text-xs sm:text-sm">
                            <i class="fa-solid fa-basket-shopping"></i>
                            <span>Pilih Paket</span>
                        </button>
                        <a href="{{ url('/packages/' . $pkg->slug) }}"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50 text-xs font-semibold transition flex items-center justify-center"
                            title="Lihat Detail">
                            Detail
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12 bg-white rounded-3xl border border-dashed border-slate-200">
                    <p class="text-slate-500 text-sm">Belum ada paket unggulan di katalog. Gunakan Smart Optimizer di atas untuk menyusun paket Anda.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</section>

<!-- Toast Notification Minimalis (Aman tidak menabrak tombol chatbot di Mobile) -->
<div id="pkg-toast" class="fixed left-4 right-4 sm:left-auto sm:right-6 bottom-20 sm:bottom-6 z-50 bg-slate-900 text-white text-xs sm:text-sm font-semibold px-4 py-3 rounded-xl shadow-lg transform translate-y-24 opacity-0 transition-all duration-300 pointer-events-none flex items-center justify-center sm:justify-start gap-2">
    <i class="fa-solid fa-circle-check text-emerald-400 shrink-0"></i>
    <span id="pkg-toast-text" class="truncate">Ditambahkan ke keranjang!</span>
</div>

<script>
    let currentOptimizedPackage = null;
    const budgetSlider = document.getElementById('budget-slider');
    const budgetDisplay = document.getElementById('budget-display');

    budgetSlider.addEventListener('input', function() {
        setBudget(this.value, false);
    });

    function setBudget(val, updateSlider = true, clickedBtn = null) {
        val = parseInt(val);
        if (updateSlider) budgetSlider.value = val;
        budgetDisplay.innerText = formatIDR(val);

        document.querySelectorAll('.preset-btn').forEach(btn => {
            btn.className = 'preset-btn px-3 py-2 sm:py-1.5 rounded-xl sm:rounded-full text-xs font-semibold transition-all text-center bg-slate-100 text-slate-600 hover:bg-slate-200';
        });
        if (clickedBtn) {
            clickedBtn.className = 'preset-btn px-3 py-2 sm:py-1.5 rounded-xl sm:rounded-full text-xs font-semibold transition-all text-center bg-slate-900 text-white shadow-sm';
        }
    }

    function showPackageToast(msg) {
        const toast = document.getElementById('pkg-toast');
        document.getElementById('pkg-toast-text').innerText = msg;
        toast.classList.remove('translate-y-24', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');
        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-24', 'opacity-0');
        }, 2800);
    }

    async function runOptimization(scrollOnMobile = false) {
        const budget = parseInt(budgetSlider.value);
        const preference = document.getElementById('preference-select').value;
        const selectedCategories = Array.from(document.querySelectorAll('input[name="cust_smckp_cat"]:checked')).map(el => el.value);
        if (selectedCategories.length === 0) {
            alert('Silakan pilih minimal 1 komponen menu.');
            return;
        }

        const btn = document.getElementById('btn-run-optimizer');
        const loading = document.getElementById('optimizer-loading');
        const card = document.getElementById('optimizer-card');

        btn.disabled = true;
        document.getElementById('btn-optimizer-text').innerText = 'Mengoptimasi...';
        loading.classList.remove('hidden');
        card.classList.add('hidden');

        if (scrollOnMobile && window.innerWidth < 1024) {
            document.getElementById('optimizer-result-anchor')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            const response = await fetch('/api/packages/optimize', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    budget: budget,
                    preference: preference,
                    categories: selectedCategories
                })
            });

            const data = await response.json();
            loading.classList.add('hidden');
            btn.disabled = false;
            document.getElementById('btn-optimizer-text').innerText = 'Optimalkan Paket Sekarang';

            if (data.success) {
                currentOptimizedPackage = data;
                renderOptimizedResult(data);
                card.classList.remove('hidden');
            } else {
                alert(data.message || 'Gagal mengoptimasi paket.');
                card.classList.remove('hidden');
            }
        } catch (err) {
            console.error(err);
            loading.classList.add('hidden');
            btn.disabled = false;
            document.getElementById('btn-optimizer-text').innerText = 'Optimalkan Paket Sekarang';
            card.classList.remove('hidden');
            alert('Terjadi kesalahan jaringan.');
        }
    }

    function renderOptimizedResult(data) {
        document.getElementById('res-total-price').innerText = formatIDR(data.total_price);
        document.getElementById('res-badge-savings').innerText = `Sisa ${formatIDR(data.remaining_budget)}`;
        document.getElementById('res-popularity').innerText = `⭐ ${data.average_utility || data.average_popularity}`;
        document.getElementById('res-calories').innerText = `~${data.total_calories} kkal`;
        document.getElementById('res-exec-time').innerText = `${data.execution_time_ms} ms`;

        const items = data.items;
        setComponentVisual('karbo', items.karbohidrat);
        setComponentVisual('lauk', items.lauk_utama);
        setComponentVisual('pendamping', items.lauk_pendamping);
        setComponentVisual('sayur', items.sayur);
        setComponentVisual('snack', items.snack);
    }

    function setComponentVisual(type, item) {
        const nameEl = document.getElementById(`item-name-${type}`);
        const priceEl = document.getElementById(`item-price-${type}`);
        const imgEl = document.getElementById(`item-img-${type}`);
        const cardEl = nameEl ? nameEl.closest('.rounded-2xl') : null;

        if (item.is_skipped) {
            nameEl.innerText = 'Dilewati (Opsional)';
            priceEl.innerText = 'Rp 0';
            if (cardEl) cardEl.classList.add('opacity-45');
        } else {
            nameEl.innerText = item.name;
            priceEl.innerText = formatIDR(item.price);
            if (cardEl) cardEl.classList.remove('opacity-45');
        }

        if (imgEl) {
            imgEl.src = item.image || '/images/menu_extracted/page37_img03_333x333.jpeg';
            imgEl.alt = item.name;
        }
    }

    function addOptimizedPackageToCart() {
        if (!currentOptimizedPackage) return;
        const cart = getCart();
        const laukName = currentOptimizedPackage.items.lauk_utama?.is_skipped
            ? 'Kustom'
            : currentOptimizedPackage.items.lauk_utama.name;
        const pkgName = `Paket Kustom Cerdas (${laukName})`;
        const pkgPrice = currentOptimizedPackage.total_price;
        const fakeId = 900000 + Math.floor(Math.random() * 99999);

        cart.push({ id: fakeId, name: pkgName, price: pkgPrice, qty: 1 });
        saveCart(cart);
        showPackageToast(`"${pkgName}" ditambahkan ke keranjang!`);
    }

    function addFixedPackageToCart(pkgId, name, price) {
        const cart = getCart();
        const existing = cart.find(i => i.id === pkgId);
        if (existing) {
            existing.qty += 1;
        } else {
            cart.push({ id: pkgId, name: name, price: price, qty: 1 });
        }
        saveCart(cart);
        showPackageToast(`"${name}" ditambahkan ke keranjang!`);
    }

    document.addEventListener('DOMContentLoaded', () => {
        runOptimization(false);
    });
</script>
@endsection
