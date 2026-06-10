@extends('layouts.app')

@section('content')
<section class="py-12 sm:py-16 bg-gradient-to-b from-secondary to-gray-50 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 md:px-8 max-w-7xl">
        <!-- Section Title -->
        <div class="text-center mb-8 sm:mb-10">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-serif font-bold text-dark mb-3 sm:mb-4">Pilihan Menu Kami</h1>
            <div class="w-20 sm:w-24 h-1.5 bg-primary mx-auto rounded-full mb-4 sm:mb-6"></div>
            <p class="text-gray-600 max-w-2xl mx-auto text-sm sm:text-base md:text-lg px-4">Jelajahi ragam hidangan lezat kami mulai dari nasi kotak praktis hingga prasmanan mewah yang disiapkan khusus untuk Anda.</p>
        </div>

        @if($menus->isEmpty())
        <!-- Kosong: belum ada menu -->
        <div class="text-center py-20">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300 shadow-sm border border-gray-100">
                <i class="fa-solid fa-utensils text-5xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-700 mb-2">Belum ada menu tersedia</h2>
            <p class="text-gray-500">Silakan kunjungi kembali nanti.</p>
        </div>
        @else

        <!-- Filter Buttons -->
        <div class="flex flex-wrap justify-center gap-2 sm:gap-3 mb-8 sm:mb-12 px-2" id="filter-buttons">
            <button onclick="filterMenu('all', this)" class="filter-btn active px-4 sm:px-6 py-2 sm:py-2.5 rounded-full font-medium transition-all duration-300 bg-primary text-white shadow-md text-sm sm:text-base">
                Semua Menu <span class="ml-1 bg-white/20 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $menus->count() }}</span>
            </button>
            <button onclick="filterMenu('makanan', this)" class="filter-btn px-4 sm:px-6 py-2 sm:py-2.5 rounded-full font-medium transition-all duration-300 bg-white text-gray-600 hover:bg-primary/10 border border-gray-200 text-sm sm:text-base">
                Makanan Utama <span class="ml-1 bg-gray-100 text-gray-500 text-xs font-bold px-2 py-0.5 rounded-full" id="count-makanan">{{ $menus->where('category','makanan')->count() }}</span>
            </button>
            <button onclick="filterMenu('snack', this)" class="filter-btn px-4 sm:px-6 py-2 sm:py-2.5 rounded-full font-medium transition-all duration-300 bg-white text-gray-600 hover:bg-primary/10 border border-gray-200 text-sm sm:text-base">
                Snack &amp; Jajanan <span class="ml-1 bg-gray-100 text-gray-500 text-xs font-bold px-2 py-0.5 rounded-full" id="count-snack">{{ $menus->where('category','snack')->count() }}</span>
            </button>
        </div>

        <!-- Menu Grid — dirender dari Blade (bukan JS hardcoded) -->
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8" id="menu-container">
            @foreach($menus as $menu)
            <div class="menu-card bg-surface rounded-xl sm:rounded-2xl lg:rounded-3xl overflow-hidden shadow-lg border border-gray-100 hover-lift animate-fade-in-up"
                 data-category="{{ $menu->category }}">
                <a href="{{ url('/menu/' . $menu->id) }}" class="flex flex-col group h-full">
                    <!-- Image -->
                    <div class="relative h-32 sm:h-48 lg:h-60 overflow-hidden">
                        <img src="{{ $menu->image }}" alt="{{ $menu->name }}"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                             loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                        
                        <!-- Kategori Badge -->
                        @if($menu->category === 'snack')
                        <div class="absolute top-2 sm:top-3 left-2 sm:left-3 bg-orange-100 text-orange-600 font-bold py-0.5 sm:py-1 px-1.5 sm:px-2.5 rounded-md sm:rounded-lg text-[8px] sm:text-[9px] uppercase tracking-wide shadow-sm z-20">
                            <i class="fa-solid fa-cookie-bite mr-0.5 sm:mr-1"></i> <span class="hidden sm:inline">Snack</span>
                        </div>
                        @else
                        <div class="absolute top-2 sm:top-3 left-2 sm:left-3 bg-emerald-100 text-emerald-700 font-bold py-0.5 sm:py-1 px-1.5 sm:px-2.5 rounded-md sm:rounded-lg text-[8px] sm:text-[9px] uppercase tracking-wide shadow-sm z-20">
                            <i class="fa-solid fa-bowl-food mr-0.5 sm:mr-1"></i> <span class="hidden sm:inline">Makanan</span>
                        </div>
                        @endif

                        <!-- Tag Badge -->
                        @if($menu->tag)
                        <div class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-white/95 backdrop-blur text-primary font-bold py-0.5 sm:py-1 px-1.5 sm:px-3 rounded-full text-[8px] sm:text-[9px] lg:text-xs uppercase tracking-wide shadow-md z-20">
                            {{ $menu->tag }}
                        </div>
                        @endif
                    </div>
                    
                    <!-- Content -->
                    <div class="p-2.5 sm:p-4 lg:p-6 flex-grow flex flex-col justify-between z-10 bg-surface">
                        <div class="mb-2 sm:mb-4 lg:mb-6">
                            <h3 class="text-xs sm:text-base lg:text-xl font-serif font-bold text-dark mb-1 sm:mb-2 lg:mb-3 group-hover:text-primary transition-colors line-clamp-2 leading-tight">
                                {{ $menu->name }}
                            </h3>
                            <p class="text-gray-500 text-[9px] sm:text-xs lg:text-sm leading-relaxed line-clamp-2 hidden sm:block">
                                {{ $menu->description }}
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mt-auto pt-2 sm:pt-3 lg:pt-4 border-t border-gray-50 gap-2">
                            <div class="flex flex-col w-full sm:w-auto">
                                <span class="text-[8px] sm:text-[10px] lg:text-xs text-gray-400 font-medium">Harga</span>
                                <span class="text-sm sm:text-base lg:text-xl font-bold text-primary">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                            </div>
                            <button type="button"
                                onclick="event.preventDefault(); event.stopPropagation(); addToCartHandler(event, {{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})"
                                class="w-full sm:w-auto bg-primary hover:bg-accent text-white font-medium py-1.5 sm:py-2 lg:py-3 px-3 sm:px-4 lg:px-5 rounded-lg sm:rounded-xl lg:rounded-2xl transition-all duration-300 active:scale-95 shadow-sm text-[10px] sm:text-xs lg:text-base flex items-center justify-center gap-1 sm:gap-2">
                                <i class="fa-solid fa-cart-plus text-xs sm:text-sm"></i>
                                <span class="sm:inline">Tambah</span>
                            </button>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <!-- CTA -->
        <div class="mt-12 sm:mt-16 text-center px-4">
            <a href="{{ url('/checkout') }}" class="inline-flex items-center justify-center gap-2 sm:gap-3 bg-dark hover:bg-gray-800 text-white font-medium py-3 sm:py-4 px-6 sm:px-10 rounded-full transition-all transform hover:-translate-y-1 shadow-lg group text-sm sm:text-base">
                <span class="hidden sm:inline">Lanjut ke Keranjang &amp;</span> <span class="font-bold text-primary">Checkout</span>
                <i class="fa-solid fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
            </a>
        </div>

        @endif
    </div>
</section>

<script>
    function filterMenu(category, clickedButton) {
        // Reset semua button
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('bg-primary', 'text-white', 'shadow-md', 'active');
            btn.classList.add('bg-white', 'text-gray-600', 'hover:bg-primary/10', 'border', 'border-gray-200');
        });
        // Aktifkan tombol yang diklik
        if (clickedButton) {
            clickedButton.classList.add('bg-primary', 'text-white', 'shadow-md', 'active');
            clickedButton.classList.remove('bg-white', 'text-gray-600', 'hover:bg-primary/10', 'border-gray-200');
        }

        // Filter card
        document.querySelectorAll('.menu-card').forEach(card => {
            if (category === 'all' || card.dataset.category === category) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });

        // Cek apakah kosong setelah filter
        const visible = [...document.querySelectorAll('.menu-card')].filter(c => c.style.display !== 'none');
        const container = document.getElementById('menu-container');
        const emptyMsg = document.getElementById('empty-filter-msg');
        if (visible.length === 0) {
            if (!emptyMsg) {
                const msg = document.createElement('div');
                msg.id = 'empty-filter-msg';
                msg.className = 'col-span-full text-center text-gray-500 py-10 text-base';
                msg.innerHTML = '<i class="fa-solid fa-bowl-food text-3xl mb-3 text-gray-300 block"></i>Belum ada menu di kategori ini.';
                container.appendChild(msg);
            }
        } else {
            if (emptyMsg) emptyMsg.remove();
        }
    }

    function addToCartHandler(event, id, name, price) {
        let cart = getCart();
        const existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            existingItem.qty += 1;
        } else {
            cart.push({ id, name, price, qty: 1 });
        }
        saveCart(cart);

        // Feedback tombol
        const btn = event.currentTarget;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-check"></i>';
        btn.classList.add('bg-green-500');
        btn.classList.remove('bg-primary', 'hover:bg-accent');

        const badge = document.getElementById('nav-cart-badge');
        if (badge) badge.classList.add('animate-bounce');

        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('bg-green-500');
            btn.classList.add('bg-primary', 'hover:bg-accent');
            if (badge) badge.classList.remove('animate-bounce');
        }, 800);
    }
</script>
@endsection
