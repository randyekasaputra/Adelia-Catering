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

<!-- Component Filter Pills (5 Komponen Paket Optimasi) -->
<div class="bg-white border border-slate-200/60 rounded-2xl p-3 sm:p-4 mb-6 shadow-sm">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-filter text-orange-500 text-xs"></i>
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Filter Komponen Paket</span>
        </div>
        <span class="text-[11px] text-slate-450 font-medium">Bahan optimasi menu Multi-Choice Knapsack</span>
    </div>
    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-thin">
        @php
            $tabs = [
                'all' => ['label' => 'Semua', 'icon' => 'fa-border-all', 'count' => $componentCounts['all'] ?? 0],
                'karbohidrat' => ['label' => 'Karbohidrat', 'icon' => 'fa-bowl-rice', 'count' => $componentCounts['karbohidrat'] ?? 0],
                'lauk_utama' => ['label' => 'Lauk Utama', 'icon' => 'fa-drumstick-bite', 'count' => $componentCounts['lauk_utama'] ?? 0],
                'lauk_pendamping' => ['label' => 'Lauk Pendamping', 'icon' => 'fa-egg', 'count' => $componentCounts['lauk_pendamping'] ?? 0],
                'sayur' => ['label' => 'Sayuran', 'icon' => 'fa-carrot', 'count' => $componentCounts['sayur'] ?? 0],
                'snack' => ['label' => 'Snack / Penutup', 'icon' => 'fa-cookie', 'count' => $componentCounts['snack'] ?? 0],
            ];
        @endphp

        @foreach($tabs as $key => $tab)
            @php $isActive = ($selectedComponent === $key); @endphp
            <a href="/admin/menus{{ $key === 'all' ? '' : '?component=' . $key }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $isActive ? 'bg-orange-500 text-white shadow-sm shadow-orange-200' : 'bg-slate-100 hover:bg-slate-200/70 text-slate-600' }}">
                <i class="fa-solid {{ $tab['icon'] }} text-[10px]"></i>
                <span>{{ $tab['label'] }}</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ $isActive ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $tab['count'] }}</span>
            </a>
        @endforeach
    </div>
</div>

@php
    $componentMeta = [
        'karbohidrat'     => ['label' => 'Karbohidrat', 'class' => 'bg-amber-50 text-amber-800 border-amber-200/80', 'icon' => 'fa-bowl-rice'],
        'lauk_utama'      => ['label' => 'Lauk Utama', 'class' => 'bg-rose-50 text-rose-800 border-rose-200/80', 'icon' => 'fa-drumstick-bite'],
        'lauk_pendamping' => ['label' => 'Lauk Pendamping', 'class' => 'bg-blue-50 text-blue-800 border-blue-200/80', 'icon' => 'fa-egg'],
        'sayur'           => ['label' => 'Sayuran', 'class' => 'bg-emerald-50 text-emerald-800 border-emerald-200/80', 'icon' => 'fa-carrot'],
        'snack'           => ['label' => 'Snack', 'class' => 'bg-purple-50 text-purple-800 border-purple-200/80', 'icon' => 'fa-cookie'],
    ];
@endphp

<!-- Mobile View (Card List) -->
<div class="md:hidden space-y-3 mb-6">
    @forelse($menus as $m)
    @php
        $cMeta = $componentMeta[$m->component_type] ?? ['label' => ucfirst(str_replace('_', ' ', $m->component_type ?? '-')), 'class' => 'bg-slate-100 text-slate-700 border-slate-200', 'icon' => 'fa-utensils'];
        $score = $m->popularity_score ?? 0;
    @endphp
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
                
                <!-- Component Badge & Score -->
                <div class="flex items-center gap-1.5 flex-wrap mt-1.5">
                    <span class="inline-flex items-center gap-1 {{ $cMeta['class'] }} border px-1.5 py-0.5 rounded text-[8px] font-bold">
                        <i class="fa-solid {{ $cMeta['icon'] }} text-[7px]"></i> {{ $cMeta['label'] }}
                    </span>
                    <span class="inline-flex items-center gap-0.5 bg-amber-50 text-amber-800 border border-amber-200/70 px-1.5 py-0.5 rounded text-[8px] font-bold">
                        <i class="fa-solid fa-star text-amber-500 text-[7px]"></i> {{ number_format($score, 1) }}
                    </span>
                    @if($m->calories)
                    <span class="text-[8px] text-slate-400 font-semibold">⚡ {{ $m->calories }} kkal</span>
                    @endif
                </div>
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
                    <th class="px-5 py-4 w-20">Visual</th>
                    <th class="px-5 py-4">Identitas Produk</th>
                    <th class="px-5 py-4">Komponen Paket</th>
                    <th class="px-5 py-4 text-center">Popularitas &amp; Utilitas</th>
                    <th class="px-5 py-4 text-right">Harga Jual &amp; HPP</th>
                    <th class="px-5 py-4 text-center w-32">Opsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-705 text-xs sm:text-sm">
                @forelse($menus as $m)
                @php
                    $cMeta = $componentMeta[$m->component_type] ?? ['label' => ucfirst(str_replace('_', ' ', $m->component_type ?? '-')), 'class' => 'bg-slate-100 text-slate-700 border-slate-200', 'icon' => 'fa-utensils'];
                    $score = $m->popularity_score ?? 0;
                @endphp
                <tr class="hover:bg-slate-50/40 transition duration-150">
                    <td class="px-5 py-4">
                        <div class="w-14 h-14 rounded-xl overflow-hidden border border-slate-200/60 shadow-sm shrink-0">
                            <img src="{{ $m->image }}" class="w-full h-full object-cover" alt="Foto">
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <div class="font-bold text-slate-800 text-sm mb-0.5">{{ $m->name }}</div>
                        <div class="text-xs text-slate-450 max-w-xs truncate font-medium">{{ $m->description }}</div>
                        <div class="flex items-center gap-1.5 mt-1.5">
                            <span class="{{ $m->category == 'makanan' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100/50' : 'bg-indigo-50 text-indigo-650 border border-indigo-100/50' }} px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider">
                                {{ $m->category }}
                            </span>
                            @if($m->tag)
                                <span class="inline-flex items-center gap-1 bg-orange-50 text-orange-655 font-bold px-1.5 py-0.5 rounded text-[9px] border border-orange-100/50">
                                    <i class="fa-solid fa-tag text-[7px]"></i> {{ $m->tag }}
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex flex-col gap-1">
                            <span class="inline-flex items-center gap-1.5 {{ $cMeta['class'] }} border px-2.5 py-1 rounded-lg text-xs font-bold w-fit">
                                <i class="fa-solid {{ $cMeta['icon'] }} text-[10px]"></i>
                                <span>{{ $cMeta['label'] }}</span>
                            </span>
                            @if($m->calories)
                                <span class="text-[11px] text-slate-450 font-medium pl-1">
                                    <i class="fa-solid fa-bolt text-amber-500 text-[10px]"></i> {{ $m->calories }} kkal
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="inline-flex flex-col items-center">
                            <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg font-bold text-xs {{ $score >= 70 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/70' : ($score >= 40 ? 'bg-amber-50 text-amber-700 border border-amber-200/70' : 'bg-slate-100 text-slate-650 border border-slate-200') }}">
                                <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                                <span>{{ number_format($score, 1) }}</span>
                                <span class="text-[10px] font-normal text-slate-400">/ 100</span>
                            </div>
                            <span class="text-[10px] text-slate-500 mt-1 font-semibold">Utilitas: {{ number_format($m->effective_utility, 1) }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="font-bold text-slate-800 text-sm">Rp {{ number_format($m->price,0,',','.') }}</div>
                        <div class="text-[11px] text-slate-400 font-medium">HPP: Rp {{ number_format($m->effective_hpp,0,',','.') }}</div>
                        <div class="text-[11px] text-emerald-600 font-semibold">Laba: +Rp {{ number_format($m->profit,0,',','.') }}</div>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="/admin/menus/{{ $m->id }}/edit"
                                class="w-8 h-8 flex items-center justify-center rounded-xl bg-white hover:bg-blue-50 text-slate-450 hover:text-blue-600 border border-slate-200 hover:border-blue-150 transition-all active:scale-95"
                                title="Edit">
                                <i class="fa-regular fa-pen-to-square text-xs"></i>
                            </a>
                            <form action="/admin/menus/{{ $m->id }}" method="POST" onsubmit="return confirm('Tindakan ini tidak bisa dibatalkan. Yakin hapus?');">
                                @csrf @method('DELETE')
                                <button class="w-8 h-8 flex items-center justify-center rounded-xl bg-white hover:bg-red-50 text-slate-450 hover:text-red-600 border border-slate-200 hover:border-red-150 transition-all active:scale-95" title="Hapus">
                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">
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
</div>

@endsection
