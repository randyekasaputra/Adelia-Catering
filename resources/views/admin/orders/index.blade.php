@extends('admin.layout')
@section('content')

<!-- Page Header -->
<div class="mb-6">
    <h1 class="text-2xl lg:text-3xl font-bold text-slate-800 tracking-tight">Kelola Pesanan</h1>
    <p class="text-slate-500 mt-1 font-medium text-xs sm:text-sm">Pantau status transaksi, konfirmasi pembayaran, dan cetak invoice dapur.</p>
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

<!-- ===== STATUS FILTER TABS ===== -->
<div class="bg-white border border-slate-200/60 p-1.5 rounded-2xl flex flex-wrap gap-1 shadow-sm mb-6 max-w-fit">
    @php
        $tabs = [
            'all'       => ['label' => 'Semua',     'icon' => 'fa-list',            'color' => 'slate'],
            'pending'   => ['label' => 'Pending',   'icon' => 'fa-clock',           'color' => 'amber'],
            'paid'      => ['label' => 'Dibayar',   'icon' => 'fa-credit-card',     'color' => 'sky'],
            'completed' => ['label' => 'Selesai',   'icon' => 'fa-circle-check',    'color' => 'emerald'],
            'cancelled' => ['label' => 'Dibatalkan','icon' => 'fa-ban',             'color' => 'red'],
        ];
    @endphp

    @foreach($tabs as $key => $tab)
    @php $isActive = $statusFilter === $key; @endphp
    <a href="/admin/orders?status={{ $key }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition active:scale-[0.98] border
              {{ $isActive
                 ? 'bg-slate-900 text-white border-transparent shadow-sm'
                 : 'bg-transparent text-slate-550 border-transparent hover:bg-slate-50 hover:text-slate-800' }}">
        <i class="fa-solid {{ $tab['icon'] }} text-[10px]"></i>
        <span>{{ $tab['label'] }}</span>
        <span class="ml-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold
                     {{ $isActive ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">
            {{ $counts[$key] }}
        </span>
    </a>
    @endforeach
</div>

<!-- ===== ORDER CARDS ===== -->
<div class="space-y-6">
    @forelse($orders as $o)
    @php
        $statusConfig = match($o->status) {
            'pending'   => ['border' => 'border-slate-200/60', 'bar' => 'bg-amber-500',   'badge' => 'text-amber-700 bg-amber-50 border-amber-100/50',   'icon' => 'fa-clock',       'label' => 'PENDING'],
            'paid'      => ['border' => 'border-slate-200/60', 'bar' => 'bg-sky-500',     'badge' => 'text-sky-700 bg-sky-50 border-sky-100/50',       'icon' => 'fa-credit-card', 'label' => 'DIBAYAR'],
            'completed' => ['border' => 'border-slate-200/60', 'bar' => 'bg-emerald-500', 'badge' => 'text-emerald-700 bg-emerald-50 border-emerald-100/50', 'icon' => 'fa-circle-check', 'label' => 'SELESAI'],
            'cancelled' => ['border' => 'border-slate-200/60', 'bar' => 'bg-red-500',     'badge' => 'text-red-700 bg-red-50 border-red-100/50',           'icon' => 'fa-ban',           'label' => 'DIBATALKAN'],
            default     => ['border' => 'border-slate-200/60', 'bar' => 'bg-slate-400',   'badge' => 'text-slate-700 bg-slate-50 border-slate-100',     'icon' => 'fa-question',    'label' => strtoupper($o->status)],
        };
    @endphp
    
    <div class="bg-white border {{ $statusConfig['border'] }} rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition duration-200">
        
        <!-- Header Banner -->
        <div class="bg-slate-50/50 border-b border-slate-100 px-5 py-4 flex flex-wrap items-center justify-between gap-3 relative">
            <!-- Left bar -->
            <div class="absolute left-0 top-0 bottom-0 w-1 {{ $statusConfig['bar'] }}"></div>
            
            <div>
                <div class="text-[9px] font-bold text-slate-400 tracking-wider uppercase mb-0.5">ID Transaksi</div>
                <div class="text-sm sm:text-base font-bold text-slate-800">#ORD-{{ str_pad($o->id, 5, '0', STR_PAD_LEFT) }}</div>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <div class="text-[9px] font-bold text-slate-400 tracking-wider uppercase mb-0.5">Waktu Masuk</div>
                    <div class="text-xs font-semibold text-slate-600">{{ $o->created_at->format('d M Y - H:i') }} WIB</div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold border {{ $statusConfig['badge'] }}">
                    <i class="fa-solid {{ $statusConfig['icon'] }} text-[9px]"></i>
                    {{ $statusConfig['label'] }}
                </span>
            </div>
        </div>
        
        <!-- Order Body -->
        <div class="p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Col 1: Pelanggan -->
            <div class="flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-user-circle"></i>
                        <span>Pelanggan</span>
                    </div>
                    <div class="font-bold text-slate-800 text-lg mb-2 truncate" title="{{ $o->customer_name }}">{{ $o->customer_name }}</div>
                    
                    <a href="https://wa.me/{{ $o->customer_wa }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100/70 border border-emerald-100 px-3 py-1.5 rounded-lg transition active:scale-[0.98] w-full mb-3">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>Kirim WhatsApp</span>
                    </a>
                </div>
                
                <div>
                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Alamat Kirim</div>
                    <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 border border-slate-100 p-2.5 rounded-lg font-medium">
                        {{ $o->address }}
                    </p>
                </div>
            </div>

            <!-- Col 2: Jadwal Acara -->
            <div class="flex flex-col gap-3">
                <div>
                    <div class="flex items-center gap-2 mb-2 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-calendar"></i>
                        <span>Jadwal Pengiriman</span>
                    </div>
                    
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 flex flex-col gap-1">
                        <span class="text-[9px] font-bold text-slate-400 uppercase">Hari & Tanggal</span>
                        <span class="font-bold text-slate-800 text-xs sm:text-sm">
                            {{ \Carbon\Carbon::parse($o->event_date)->translatedFormat('l, d F Y') }}
                        </span>
                    </div>
                </div>
                
                <div class="bg-orange-50/50 border border-orange-100/50 rounded-xl p-3 flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-bold text-orange-400 uppercase">Jam Target</span>
                        <span class="font-bold text-orange-655 text-base">{{ \Carbon\Carbon::parse($o->event_date)->format('H:i') }} WIB</span>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-500 flex items-center justify-center">
                        <i class="fa-regular fa-clock text-sm"></i>
                    </div>
                </div>
                
                @if($o->notes)
                <div class="bg-red-50 border border-red-100 rounded-xl p-3 border-l-4 border-l-red-500">
                    <span class="text-[9px] font-bold text-red-700 flex items-center gap-1 mb-0.5"><i class="fa-solid fa-circle-exclamation"></i> Catatan Khusus</span>
                    <p class="text-xs font-semibold text-red-800 leading-snug">{{ $o->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Col 3: Rincian Belanja (Items) -->
            <div class="flex flex-col">
                <div class="flex items-center gap-2 mb-2 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-utensils text-[9px]"></i>
                    <span>Item Dipesan</span>
                </div>
                
                <div class="bg-slate-50/70 border border-slate-100 rounded-xl flex-1 flex flex-col overflow-hidden max-h-[180px] md:max-h-none">
                    <div class="flex-1 overflow-y-auto p-2 space-y-1.5 custom-scrollbar">
                        @foreach($o->items as $item)
                        <div class="flex items-center gap-2 bg-white border border-slate-150 p-1.5 rounded-lg">
                            <span class="w-7 h-7 rounded bg-orange-50 text-orange-600 flex items-center justify-center font-bold text-xs shrink-0 border border-orange-100/50">
                                {{ $item->qty }}x
                            </span>
                            <span class="font-bold text-slate-700 text-xs truncate flex-1" title="{{ $item->menu_name }}">{{ $item->menu_name }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="bg-slate-100 p-2 text-center text-[9px] font-bold text-slate-550 border-t border-slate-200/50">
                        TOTAL: {{ $o->items->sum('qty') }} PORSI
                    </div>
                </div>
            </div>
            
            <!-- Col 4: Finansial & Aksi -->
            <div class="flex flex-col justify-between border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-4">
                <div class="bg-slate-900 text-white rounded-xl p-4">
                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Total Harga</div>
                    <div class="font-bold text-xl text-white tracking-tight">Rp {{ number_format($o->total_amount,0,',','.') }}</div>
                    
                    <div class="flex items-center justify-between border-t border-white/10 mt-3 pt-3 gap-2">
                        <div>
                            <span class="text-[9px] font-semibold text-slate-400 block">Metode DP</span>
                            <span class="text-[9px] font-bold bg-white/10 px-1.5 py-0.5 rounded border border-white/5 uppercase">{{ $o->payment_scheme }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[9px] font-semibold text-slate-400 block">Jumlah Uang</span>
                            <span class="font-bold text-emerald-450 text-xs sm:text-sm">Rp {{ number_format($o->dp_amount,0,',','.') }}</span>
                        </div>
                    </div>

                    @if($o->status === 'paid')
                    <div class="mt-2.5 pt-2 border-t border-white/5 flex items-center gap-1 text-[9px] text-sky-300 font-semibold">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Lunas via {{ $o->payment_type ?? 'online' }}</span>
                    </div>
                    @endif
                </div>
                
                <!-- Action Buttons -->
                <div class="space-y-2 mt-4">
                    <form action="/admin/orders/{{ $o->id }}/status" method="POST">
                        @csrf
                        <div class="relative">
                            <select name="status" onchange="this.form.submit()"
                                class="w-full text-xs font-bold py-2.5 pl-3 pr-8 rounded-xl border border-slate-200 outline-none cursor-pointer appearance-none transition focus:border-orange-500
                                @switch($o->status)
                                    @case('pending')   bg-amber-50 text-amber-700 @break
                                    @case('paid')      bg-sky-50 text-sky-700 @break
                                    @case('completed') bg-emerald-50 text-emerald-700 @break
                                    @case('cancelled') bg-red-50 text-red-700 @break
                                    @default           bg-slate-50 text-slate-700
                                @endswitch
                                ">
                                <option value="pending"   {{ $o->status == 'pending'   ? 'selected' : '' }}>⏳ PENDING</option>
                                <option value="paid"      {{ $o->status == 'paid'      ? 'selected' : '' }}>💳 DIBAYAR</option>
                                <option value="completed" {{ $o->status == 'completed' ? 'selected' : '' }}>✅ SELESAI</option>
                                <option value="cancelled" {{ $o->status == 'cancelled' ? 'selected' : '' }}>❌ DIBATALKAN</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                <i class="fa-solid fa-chevron-down text-[10px]"></i>
                            </div>
                        </div>
                    </form>
                    
                    <a href="/admin/orders/{{ $o->id }}/print" target="_blank"
                       class="w-full flex items-center justify-center gap-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 py-2.5 rounded-xl transition active:scale-[0.98]">
                        <i class="fa-solid fa-print text-slate-400"></i>
                        <span>Cetak Invoice</span>
                    </a>
                </div>
            </div>
            
        </div>
    </div>
    @empty
    <div class="bg-white border border-slate-200/60 p-10 text-center rounded-2xl">
        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100 text-slate-350">
            <i class="fa-solid fa-clipboard-list text-2xl"></i>
        </div>
        <h3 class="text-slate-850 font-bold text-base">Tidak ada transaksi ditemukan.</h3>
        <p class="text-slate-500 mt-1 text-xs">
            @if($statusFilter !== 'all') <a href="/admin/orders" class="text-orange-500 font-semibold hover:underline">Lihat semua pesanan →</a> @else Belum ada pesanan masuk. @endif
        </p>
    </div>
    @endforelse
</div>
@endsection
