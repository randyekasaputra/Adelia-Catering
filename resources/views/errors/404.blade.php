@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-16 bg-gradient-to-b from-orange-50/40 to-white">
    <div class="max-w-md w-full text-center">
        <div class="w-24 h-24 bg-orange-100 text-orange-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-sm border border-orange-200/60">
            <i class="fa-solid fa-utensils text-4xl"></i>
        </div>
        <span class="text-xs font-bold uppercase tracking-wider text-orange-600 bg-orange-50 px-3 py-1 rounded-full border border-orange-200/80">
            Error 404
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-850 mt-3 mb-2 tracking-tight font-serif">
            Halaman Tidak Ditemukan
        </h1>
        <p class="text-sm text-slate-500 font-medium mb-8 leading-relaxed">
            Maaf, hidangan atau halaman yang Anda cari mungkin telah dipindahkan atau tautan tidak valid.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="/" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold text-xs sm:text-sm px-6 py-3 rounded-xl shadow-sm transition active:scale-95">
                <i class="fa-solid fa-house"></i>
                <span>Kembali ke Beranda</span>
            </a>
            <a href="/packages" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs sm:text-sm px-6 py-3 rounded-xl border border-slate-200 transition active:scale-95">
                <i class="fa-solid fa-box-open text-orange-500"></i>
                <span>Lihat Paket Katering</span>
            </a>
        </div>
    </div>
</div>
@endsection
