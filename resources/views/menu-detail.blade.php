@extends('layouts.app')

@section('content')
<section class="py-8 sm:py-12 bg-gradient-to-b from-secondary to-gray-50 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 md:px-8 max-w-6xl">
        
        <!-- Breadcrumb -->
        <div class="mb-6 sm:mb-8">
            <nav class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ url('/') }}" class="hover:text-primary transition">Beranda</a>
                <i class="fa-solid fa-chevron-right text-xs"></i>
                <a href="{{ url('/menu') }}" class="hover:text-primary transition">Menu</a>
                <i class="fa-solid fa-chevron-right text-xs"></i>
                <span class="text-primary font-semibold truncate max-w-[180px] sm:max-w-none">{{ $menu->name }}</span>
            </nav>
        </div>

        <!-- Detail Menu -->
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl overflow-hidden mb-12 sm:mb-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                
                <!-- Gambar Menu -->
                <div class="relative h-64 sm:h-80 lg:h-full min-h-[360px] lg:min-h-[440px]">
                    <img src="{{ $menu->image }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                    
                    <!-- Badge Kategori -->
                    @if($menu->category === 'snack')
                        <div class="absolute top-4 left-4 bg-orange-100 text-orange-600 font-bold py-2 px-4 rounded-xl text-xs uppercase tracking-wide shadow-lg">
                            <i class="fa-solid fa-cookie-bite mr-2"></i> Snack
                        </div>
                    @else
                        <div class="absolute top-4 left-4 bg-emerald-100 text-emerald-700 font-bold py-2 px-4 rounded-xl text-xs uppercase tracking-wide shadow-lg">
                            <i class="fa-solid fa-bowl-food mr-2"></i> Makanan
                        </div>
                    @endif
                    
                    <!-- Badge Tag -->
                    @if($menu->tag)
                    <div class="absolute top-4 right-4 bg-white/95 backdrop-blur text-primary font-bold py-2 px-4 rounded-full text-xs uppercase tracking-wide shadow-lg">
                        {{ $menu->tag }}
                    </div>
                    @endif
                </div>

                <!-- Informasi Menu -->
                <div class="p-6 sm:p-8 lg:p-10 flex flex-col justify-between">
                    <div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-serif font-bold text-dark mb-4">{{ $menu->name }}</h1>
                        
                        <!-- Rating (Static) -->
                        <div class="flex items-center gap-2 mb-6">
                            <div class="flex text-yellow-400 text-lg">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="text-gray-600 text-sm">(4.5/5 - 128 ulasan)</span>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-dark mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-circle-info text-primary"></i> Deskripsi
                            </h3>
                            <p class="text-gray-600 leading-relaxed">{{ $menu->description }}</p>
                        </div>

                        <!-- Info Tambahan -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-clock text-primary"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Waktu Persiapan</p>
                                    <p class="font-semibold text-dark">2-3 Hari</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-users text-primary"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Min. Pemesanan</p>
                                    <p class="font-semibold text-dark">10 Porsi</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Harga & Action -->
                    <div class="border-t border-gray-100 pt-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Harga per Porsi</p>
                                <p class="text-3xl sm:text-4xl font-bold text-primary">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <!-- Quantity & Add to Cart -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-2 border border-gray-200">
                                <button onclick="decreaseQty()" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:text-white hover:bg-red-500 rounded-lg transition-colors">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <input type="number" id="qty-input" value="1" min="1" class="w-16 text-center font-bold text-lg bg-transparent border-0 outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" onchange="setCustomQty(this.value)">
                                <button onclick="increaseQty()" class="w-10 h-10 flex items-center justify-center text-gray-600 hover:text-white hover:bg-green-500 rounded-lg transition-colors">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                            
                            <button onclick="addToCartFromDetail(event)" class="flex-1 bg-gradient-to-r from-primary to-accent hover:from-accent hover:to-orange-800 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 transform active:scale-95 shadow-lg flex items-center justify-center gap-3">
                                <i class="fa-solid fa-cart-plus text-xl"></i>
                                <span>Tambah ke Keranjang</span>
                            </button>
                        </div>

                        <!-- Back to Menu -->
                        <a href="{{ url('/menu') }}" class="block text-center mt-4 text-gray-600 hover:text-primary transition-colors text-sm">
                            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar Menu
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rekomendasi Menu -->
        @if($recommendations->count() > 0)
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6 sm:mb-8">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-serif font-bold text-dark mb-2">Menu Rekomendasi Lainnya</h2>
                    <p class="text-gray-600 text-sm sm:text-base">Menu pilihan yang mungkin Anda suka</p>
                </div>
                <a href="{{ url('/menu') }}" class="hidden sm:inline-flex items-center gap-2 text-primary hover:text-accent font-semibold transition-colors">
                    Lihat Semua <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($recommendations as $rec)
                <a href="{{ url('/menu/' . $rec->id) }}" class="bg-white rounded-xl sm:rounded-2xl overflow-hidden shadow-lg border border-gray-100 hover-lift transition-all group">
                    <!-- Image -->
                    <div class="relative h-32 sm:h-40 lg:h-48 overflow-hidden">
                        <img src="{{ $rec->image }}" alt="{{ $rec->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        @if($rec->tag)
                        <div class="absolute top-2 sm:top-3 right-2 sm:right-3 bg-white/95 backdrop-blur text-primary font-bold py-0.5 sm:py-1 px-2 sm:px-3 rounded-full text-[8px] sm:text-xs uppercase tracking-wide shadow-md">
                            {{ $rec->tag }}
                        </div>
                        @endif
                    </div>
                    
                    <!-- Content -->
                    <div class="p-3 sm:p-4 lg:p-5">
                        <h3 class="text-xs sm:text-base lg:text-lg font-serif font-bold text-dark mb-1 sm:mb-2 group-hover:text-primary transition-colors line-clamp-2 leading-tight">{{ $rec->name }}</h3>
                        <p class="text-gray-500 text-[9px] sm:text-xs lg:text-sm leading-relaxed line-clamp-2 mb-2 sm:mb-4 hidden sm:block">{{ $rec->description }}</p>
                        
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pt-2 sm:pt-3 border-t border-gray-100 gap-2">
                            <div>
                                <span class="text-[8px] sm:text-xs text-gray-400 font-medium">Harga</span>
                                <p class="text-sm sm:text-base lg:text-lg font-bold text-primary">Rp {{ number_format($rec->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-full sm:w-auto bg-primary/10 text-primary px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg font-semibold text-[10px] sm:text-sm group-hover:bg-primary group-hover:text-white transition-colors text-center">
                                Lihat Detail
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Mobile: Lihat Semua -->
            <div class="sm:hidden text-center mt-6">
                <a href="{{ url('/menu') }}" class="inline-flex items-center gap-2 text-primary hover:text-accent font-semibold transition-colors">
                    Lihat Semua Menu <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
        @endif
    </div>
</section>

<script>
    // Data menu dari Eloquent model — dikirim dari controller
    const menuData = @json($menu);
    let currentQty = 1;

    function increaseQty() {
        currentQty++;
        document.getElementById('qty-input').value = currentQty;
    }

    function decreaseQty() {
        if (currentQty > 1) {
            currentQty--;
            document.getElementById('qty-input').value = currentQty;
        }
    }

    function setCustomQty(val) {
        let parsed = parseInt(val);
        if (isNaN(parsed) || parsed < 1) {
            parsed = 1;
        }
        currentQty = parsed;
        document.getElementById('qty-input').value = currentQty;
    }

    function addToCartFromDetail(event) {
        let cart = getCart();
        const existingItem = cart.find(item => item.id === menuData.id);
        
        if (existingItem) {
            existingItem.qty += currentQty;
        } else {
            cart.push({
                id: menuData.id,
                name: menuData.name,
                price: menuData.price,
                qty: currentQty
            });
        }
        
        saveCart(cart);
        
        // Feedback visual
        const btn = event.currentTarget;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-check text-xl"></i> <span>Berhasil Ditambahkan!</span>';
        btn.classList.add('bg-green-500');
        btn.classList.remove('from-primary', 'to-accent');
        
        const badge = document.getElementById('nav-cart-badge');
        if (badge) badge.classList.add('animate-bounce');
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('bg-green-500');
            btn.classList.add('from-primary', 'to-accent');
            if (badge) badge.classList.remove('animate-bounce');
        }, 1500);
        
        currentQty = 1;
        document.getElementById('qty-input').value = 1;
    }
</script>
@endsection
