@extends('admin.layout')
@section('content')

<!-- Page Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-slate-800 tracking-tight">Katalog Menu & Produk</h1>
        <p class="text-slate-500 mt-1 font-medium text-xs sm:text-sm">Atur etalase makanan dan snack yang dijual kepada pelanggan.</p>
    </div>
    <a href="/admin/menus/create" class="inline-flex items-center justify-center gap-2 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white px-4 py-2.5 rounded-xl font-bold transition active:scale-[0.98] text-xs sm:text-sm shadow-sm shrink-0 w-full sm:w-auto">
        <i class="fa-solid fa-plus text-[10px]"></i>
        <span>Tambah Menu Baru</span>
    </a>
</div>

<!-- Alert Success -->
@if(session('success'))
<div class="bg-emerald-50 border border-emerald-100 text-emerald-800 p-4 rounded-xl mb-6 flex items-start gap-2.5 text-xs sm:text-sm">
    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0 text-base"></i>
    <div>
        <span class="font-bold">Berhasil!</span> {{ session('success') }}
    </div>
</div>
@endif

<!-- Mobile View (Card List) -->
<div class="md:hidden space-y-3 mb-6">
    @forelse($menus as $m)
    <div class="bg-white border border-slate-200/60 rounded-xl p-3 shadow-sm hover:shadow-md transition flex gap-3">
        <!-- Image -->
        <div class="w-20 h-20 rounded-lg overflow-hidden shrink-0 border border-slate-150">
            <img src="{{ $m->image }}" class="w-full h-full object-cover" alt="{{ $m->name }}">
        </div>
        <!-- Info & Actions -->
        <div class="flex-1 min-w-0 flex flex-col justify-between">
            <div>
                <div class="flex items-start justify-between gap-2">
                    <h3 class="font-bold text-slate-800 text-xs sm:text-sm leading-snug truncate">{{ $m->name }}</h3>
                    <span class="text-slate-800 font-bold text-xs sm:text-sm shrink-0">Rp {{ number_format($m->price,0,',','.') }}</span>
                </div>
                <p class="text-[10px] sm:text-xs text-slate-500 font-medium line-clamp-2 mt-0.5">{{ $m->description }}</p>
            </div>
            
            <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-100">
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="{{ $m->category == 'makanan' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100/50' : 'bg-indigo-50 text-indigo-650 border border-indigo-100/50' }} px-2 py-0.5 rounded text-[8px] sm:text-[9px] font-bold uppercase tracking-wider">{{ $m->category }}</span>
                    @if($m->tag)
                    <span class="inline-flex items-center gap-1 bg-orange-50 text-orange-655 font-bold px-2 py-0.5 rounded text-[8px] sm:text-[9px] border border-orange-100/50">
                        <i class="fa-solid fa-tag text-[7px]"></i> {{ $m->tag }}
                    </span>
                    @endif
                </div>
                
                <div class="flex items-center gap-1.5">
                    <a href="/admin/menus/{{ $m->id }}/edit"
                        class="inline-flex items-center gap-1 text-[10px] font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100/50 border border-blue-100 px-2 py-1 rounded-lg transition active:scale-95">
                        <i class="fa-regular fa-pen-to-square text-[9px]"></i> Edit
                    </a>
                    <form action="/admin/menus/{{ $m->id }}" method="POST" onsubmit="return confirm('Tindakan ini tidak bisa dibatalkan. Yakin hapus?');">
                        @csrf @method('DELETE')
                        <button class="inline-flex items-center gap-1 text-[10px] font-semibold text-red-600 bg-red-50 hover:bg-red-100/50 border border-red-100 px-2 py-1 rounded-lg transition active:scale-95">
                            <i class="fa-regular fa-trash-can text-[9px]"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white border border-slate-200/60 rounded-2xl p-8 text-center">
        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400 border border-slate-100">
            <i class="fa-solid fa-utensils text-lg"></i>
        </div>
        <h3 class="text-slate-800 font-bold text-sm">Belum ada menu.</h3>
        <p class="text-slate-500 mt-1 text-xs">Tambahkan menu agar pelanggan bisa berbelanja.</p>
    </div>
    @endforelse
</div>

<!-- Desktop View (Clean Table) -->
<div class="hidden md:block bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead class="bg-slate-50 border-b border-slate-100 text-slate-450 text-[10px] sm:text-xs font-semibold uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4 w-24">Visual</th>
                    <th class="px-6 py-4">Identitas Produk</th>
                    <th class="px-6 py-4 text-center">Kategori</th>
                    <th class="px-6 py-4 text-right">Harga Jual</th>
                    <th class="px-6 py-4 text-center w-36">Opsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-705 text-xs sm:text-sm">
                @forelse($menus as $m)
                <tr class="hover:bg-slate-50/30 transition duration-150">
                    <td class="px-6 py-4">
                        <div class="w-16 h-16 rounded-xl overflow-hidden border border-slate-200/60 shadow-sm shrink-0">
                            <img src="{{ $m->image }}" class="w-full h-full object-cover" alt="Foto">
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800 text-base mb-0.5">{{ $m->name }}</div>
                        <div class="text-xs text-slate-450 max-w-sm truncate font-medium">{{ $m->description }}</div>
                        @if($m->tag)
                            <span class="inline-flex mt-1.5 items-center gap-1 bg-orange-50 text-orange-655 font-bold px-2 py-0.5 rounded text-[10px] border border-orange-100/50 shadow-sm">
                                <i class="fa-solid fa-tag text-[8px]"></i> {{ $m->tag }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="{{ $m->category == 'makanan' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100/50' : 'bg-indigo-50 text-indigo-650 border border-indigo-100/50' }} px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                            {{ $m->category }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-bold text-slate-800 text-base">
                        Rp {{ number_format($m->price,0,',','.') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="/admin/menus/{{ $m->id }}/edit"
                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-white hover:bg-blue-50 text-slate-450 hover:text-blue-600 border border-slate-200 hover:border-blue-150 transition-all active:scale-95"
                                title="Edit">
                                <i class="fa-regular fa-pen-to-square text-sm"></i>
                            </a>
                            <form action="/admin/menus/{{ $m->id }}" method="POST" onsubmit="return confirm('Tindakan ini tidak bisa dibatalkan. Yakin hapus?');">
                                @csrf @method('DELETE')
                                <button class="w-9 h-9 flex items-center justify-center rounded-xl bg-white hover:bg-red-50 text-slate-450 hover:text-red-600 border border-slate-200 hover:border-red-150 transition-all active:scale-95" title="Hapus">
                                    <i class="fa-regular fa-trash-can text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400 border border-slate-100">
                            <i class="fa-solid fa-utensils text-2xl"></i>
                        </div>
                        <h3 class="text-slate-850 font-bold text-lg">Belum ada menu terdaftar.</h3>
                        <p class="text-slate-500 mt-1 text-sm font-medium">Tambahkan menu agar pelanggan bisa berbelanja.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
