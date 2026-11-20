@extends('layouts.app')

@section('content')
<div class="relative min-h-[85vh] sm:min-h-[90vh] flex items-center justify-center overflow-hidden">
    <!-- Background Image with Dark Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1555244162-803834f70033?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Suasana Catering Adelia" class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/95 via-gray-900/70 to-gray-900/50"></div>
    </div>

    <div class="relative z-10 text-center px-4 sm:px-6 max-w-4xl mx-auto glass p-6 sm:p-10 md:p-16 rounded-2xl sm:rounded-[2.5rem] shadow-2xl animate-fade-in-up border border-white/20">
        <p class="text-primary font-bold tracking-widest uppercase mb-3 sm:mb-4 text-xs sm:text-sm md:text-base">Katering Terbaik di Jepara</p>
        <h1 class="text-3xl sm:text-4xl md:text-6xl lg:text-7xl font-serif text-gray-800 font-bold mb-4 sm:mb-6 drop-shadow-md leading-tight">
            Adelia Catering <br/>
            <span class="text-primary italic">Jepara</span>
        </h1>
        <p class="text-base sm:text-lg md:text-xl text-gray-700 mb-6 sm:mb-10 font-light max-w-2xl mx-auto leading-relaxed">
            Menyajikan cita rasa istimewa masakan nusantara dengan bahan segar pilihan untuk melengkapi setiap momen berharga Anda.
        </p>
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
            <a href="{{ url('/packages') }}" class="inline-flex items-center justify-center gap-2 sm:gap-3 bg-gradient-to-r from-primary to-accent hover:from-accent hover:to-orange-800 text-white font-semibold py-3 sm:py-4 px-8 sm:px-10 rounded-full transition-all duration-300 transform hover:scale-105 shadow-[0_10px_20px_rgba(217,119,6,0.3)] text-base sm:text-lg w-full sm:w-auto">
                <i class="fa-solid fa-box-open"></i> <span>Pilihan Paket Menu</span>
            </a>
            <a href="{{ url('/menu') }}" class="inline-flex items-center justify-center gap-2 sm:gap-3 bg-white/90 hover:bg-white text-gray-800 font-semibold py-3 sm:py-4 px-8 sm:px-10 rounded-full transition-all duration-300 transform hover:scale-105 shadow-md text-base sm:text-lg w-full sm:w-auto border border-gray-200">
                <i class="fa-solid fa-bell-concierge text-primary"></i> <span>Semua Menu</span>
            </a>
        </div>
    </div>
    
    <!-- Animated Scroll Indicator -->
    <div class="absolute bottom-6 sm:bottom-8 left-1/2 transform -translate-x-1/2 animate-float z-10 text-white/50">
        <i class="fa-solid fa-chevron-down text-xl sm:text-2xl"></i>
    </div>
</div>

<!-- Section Fitur Paket Cerdas (AI Recommendation Showcase) -->
<section class="py-16 sm:py-20 bg-white border-b border-gray-100">
    <div class="container mx-auto px-4 sm:px-6 md:px-8 max-w-7xl">
        <div class="bg-gradient-to-br from-amber-500/10 via-amber-50 to-orange-50 rounded-3xl p-6 sm:p-10 lg:p-12 border border-amber-200/60 shadow-sm relative overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-7 space-y-4">
                    <span class="inline-flex items-center gap-2 bg-amber-100 text-amber-900 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                        <i class="fa-solid fa-wand-magic-sparkles text-primary"></i> Rekomendasi Menu Cerdas
                    </span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-serif font-bold text-dark leading-tight">
                        Bingung Memilih Menu Sesuai Anggaran?
                    </h2>
                    <p class="text-gray-600 text-sm sm:text-base leading-relaxed">
                        Gunakan <strong>Smart Package Generator</strong> kami! Algoritma cerdas akan menyusun kombinasi paket 5 hidangan lengkap (karbohidrat, lauk utama, pendamping, sayur, dan snack) dengan skor kepuasan tertinggi berdasarkan data penjualan 3 tahun Adelia Catering, tanpa pernah melebihi budget Anda.
                    </p>
                    <div class="pt-2">
                        <a href="{{ url('/packages') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-amber-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-primary/20 transition-all text-sm sm:text-base">
                            <span>Coba Smart Package Generator</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5 grid grid-cols-2 gap-3 sm:gap-4 text-center">
                    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-amber-100">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-primary flex items-center justify-center mx-auto mb-3 text-lg">
                            <i class="fa-solid fa-calculator"></i>
                        </div>
                        <h4 class="font-bold text-xs sm:text-sm text-dark mb-1">Bebas Overbudget</h4>
                        <p class="text-[11px] text-gray-500">Harga paket dijamin sesuai plafon dana Anda.</p>
                    </div>

                    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-amber-100">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto mb-3 text-lg">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <h4 class="font-bold text-xs sm:text-sm text-dark mb-1">Data Riil 3 Tahun</h4>
                        <p class="text-[11px] text-gray-500">Menu terbukti paling disukai pelanggan.</p>
                    </div>

                    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-amber-100">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center mx-auto mb-3 text-lg">
                            <i class="fa-solid fa-utensils"></i>
                        </div>
                        <h4 class="font-bold text-xs sm:text-sm text-dark mb-1">5 Elemen Lengkap</h4>
                        <p class="text-[11px] text-gray-500">Nasi, lauk, pendamping, sayur &amp; kue.</p>
                    </div>

                    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-amber-100">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center mx-auto mb-3 text-lg">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <h4 class="font-bold text-xs sm:text-sm text-dark mb-1">Hasil Instan</h4>
                        <p class="text-[11px] text-gray-500">Optimasi Dynamic Programming dalam hitungan milidetik.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
