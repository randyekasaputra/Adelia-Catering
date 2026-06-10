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
        
        <a href="{{ url('/menu') }}" class="inline-flex items-center gap-2 sm:gap-3 bg-gradient-to-r from-primary to-accent hover:from-accent hover:to-orange-800 text-white font-semibold py-3 sm:py-4 px-8 sm:px-12 rounded-full transition-all duration-300 transform hover:scale-105 shadow-[0_10px_20px_rgba(217,119,6,0.3)] text-base sm:text-lg">
            <i class="fa-solid fa-bell-concierge"></i> <span>Lihat Daftar Menu</span>
        </a>
    </div>
    
    <!-- Animated Scroll Indicator -->
    <div class="absolute bottom-6 sm:bottom-8 left-1/2 transform -translate-x-1/2 animate-float z-10 text-white/50">
        <i class="fa-solid fa-chevron-down text-xl sm:text-2xl"></i>
    </div>
</div>
@endsection
