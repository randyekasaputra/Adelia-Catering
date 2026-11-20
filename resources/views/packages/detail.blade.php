@extends('layouts.app')

@section('content')
<section class="py-10 sm:py-16 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 md:px-8 max-w-6xl">
        
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-500 mb-6">
            <a href="{{ url('/') }}" class="hover:text-primary transition">Beranda</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <a href="{{ url('/packages') }}" class="hover:text-primary transition">Paket Katering</a>
            <i class="fa-solid fa-chevron-right text-[10px]"></i>
            <span class="text-gray-800 font-semibold truncate">{{ $package->name }}</span>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 p-6 sm:p-10">
                
                <!-- Gambar Utama Paket -->
                <div class="lg:col-span-5">
                    <div class="relative h-64 sm:h-80 lg:h-96 rounded-2xl overflow-hidden shadow-md">
                        <img src="{{ $package->image ?: '/images/menu_extracted/page35_img03_450x800.jpeg' }}" 
                            alt="{{ $package->name }}" 
                            class="w-full h-full object-cover">
                        <div class="absolute top-3 left-3 bg-primary text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            Paket Rekomendasi 3 Tahun
                        </div>
                    </div>
                </div>

                <!-- Info Paket & Rincian -->
                <div class="lg:col-span-7 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start gap-4 mb-3">
                            <h1 class="text-2xl sm:text-3xl font-serif font-bold text-dark">{{ $package->name }}</h1>
                            <span class="text-2xl sm:text-3xl font-serif font-bold text-primary flex-shrink-0">
                                Rp {{ number_format($package->price, 0, ',', '.') }}
                            </span>
                        </div>

                        <p class="text-sm sm:text-base text-gray-600 mb-6 leading-relaxed">
                            {{ $package->description }}
                        </p>

                        <!-- Metrik Ilmiah -->
                        <div class="grid grid-cols-3 gap-3 bg-amber-50/60 rounded-2xl p-4 border border-amber-100 mb-6">
                            <div class="text-center">
                                <span class="text-[10px] sm:text-xs text-gray-500 font-semibold block uppercase">Skor Kepuasan</span>
                                <span class="text-base sm:text-lg font-bold text-amber-700">⭐ {{ $package->popularity_score }}%</span>
                            </div>
                            <div class="text-center border-x border-amber-200/60">
                                <span class="text-[10px] sm:text-xs text-gray-500 font-semibold block uppercase">Batas Budget</span>
                                <span class="text-base sm:text-lg font-bold text-gray-700">Rp {{ number_format($package->target_budget, 0, ',', '.') }}</span>
                            </div>
                            <div class="text-center">
                                <span class="text-[10px] sm:text-xs text-gray-500 font-semibold block uppercase">Status Porsi</span>
                                <span class="text-base sm:text-lg font-bold text-emerald-600">5 Elemen</span>
                            </div>
                        </div>

                        <!-- Daftar Komponen Visual -->
                        <div class="mb-6">
                            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3">Rincian Komposisi Hidangan:</h3>
                            <div class="space-y-2.5">
                                @foreach($package->items as $item)
                                <div class="flex items-center justify-between p-2.5 rounded-xl bg-gray-50 border border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item->menu->image ?: '/images/menu_extracted/page37_img03_333x333.jpeg' }}" 
                                            class="w-10 h-10 rounded-lg object-cover" alt="{{ $item->menu->name }}">
                                        <div>
                                            <h5 class="text-xs sm:text-sm font-bold text-gray-800">{{ $item->menu->name }}</h5>
                                            <span class="text-[10px] text-gray-400 capitalize">{{ str_replace('_', ' ', $item->component_type) }}</span>
                                        </div>
                                    </div>
                                    <span class="text-xs sm:text-sm font-semibold text-primary">Rp {{ number_format($item->item_price, 0, ',', '.') }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Pesan -->
                    <div class="pt-4 border-t border-gray-100 flex gap-4">
                        <button type="button" onclick="addFixedPackageToCart({{ $package->id }}, '{{ addslashes($package->name) }}', {{ $package->price }})" 
                            class="flex-1 bg-primary hover:bg-amber-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-basket-shopping"></i>
                            <span>Tambahkan Paket ke Keranjang</span>
                        </button>
                        <a href="{{ url('/packages') }}" class="px-5 py-3.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-100 font-semibold text-sm flex items-center justify-center transition">
                            Kembali
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<script>
    function addFixedPackageToCart(pkgId, name, price) {
        const cart = getCart();
        const existing = cart.find(i => i.id === pkgId);
        if (existing) {
            existing.qty += 1;
        } else {
            cart.push({
                id: pkgId,
                name: name,
                price: price,
                qty: 1
            });
        }
        saveCart(cart);
        alert(`"${name}" telah ditambahkan ke keranjang belanja!`);
    }
</script>
@endsection

