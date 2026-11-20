@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-16 bg-gradient-to-b from-orange-50/40 to-white">
    <div class="max-w-md w-full text-center">
        <div class="w-24 h-24 bg-rose-100 text-rose-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-sm border border-rose-200/60">
            <i class="fa-solid fa-triangle-exclamation text-4xl"></i>
        </div>
        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 bg-rose-50 px-3 py-1 rounded-full border border-rose-200/80">
            Error 500
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-850 mt-3 mb-2 tracking-tight font-serif">
            Terjadi Kendala Sistem
        </h1>
        <p class="text-sm text-slate-500 font-medium mb-8 leading-relaxed">
            Server kami sedang memproses permintaan Anda. Silakan muat ulang halaman atau hubungi kami langsung via WhatsApp.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="/" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold text-xs sm:text-sm px-6 py-3 rounded-xl shadow-sm transition active:scale-95">
                <i class="fa-solid fa-house"></i>
                <span>Kembali ke Beranda</span>
            </a>
            <a href="https://wa.me/6287731932494?text=Halo%20Admin%20Adelia%20Catering,%20saya%20mengalami%20kendala%20saat%20mengakses%20website" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs sm:text-sm px-6 py-3 rounded-xl shadow-sm transition active:scale-95">
                <i class="fa-brands fa-whatsapp"></i>
                <span>Hubungi Admin WA</span>
            </a>
        </div>
    </div>
</div>
@endsection
