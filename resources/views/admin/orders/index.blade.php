@extends('admin.layout')

@section('title', 'Daftar Pesanan & Pelacakan Progres')
@section('header', 'Kelola Pesanan & Progres Katering')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight">Daftar Pesanan Masuk</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Pantau & perbarui tahapan pesanan (Milestone Tracking) dari dapur hingga pengiriman.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="/tracking" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold bg-white hover:bg-orange-50 text-orange-600 border border-orange-200 px-3.5 py-2.5 rounded-xl shadow-sm transition active:scale-95">
            <i class="fa-solid fa-magnifying-glass-location"></i>
            <span>Preview Lacak Publik</span>
        </a>
    </div>
</div>

<!-- ===== STATUS FILTER TABS ===== -->
<div class="bg-white border border-slate-200/60 p-1.5 rounded-2xl flex flex-wrap gap-1 shadow-sm mb-6 max-w-fit">
    @php
        $tabs = [
            'all'       => ['label' => 'Semua',       'icon' => 'fa-list',            'color' => 'slate'],
            'pending'   => ['label' => 'Pending',     'icon' => 'fa-clock',           'color' => 'amber'],
            'paid'      => ['label' => 'Dibayar',     'icon' => 'fa-credit-card',     'color' => 'sky'],
            'cooking'   => ['label' => 'Dimasak',     'icon' => 'fa-fire-burner',     'color' => 'orange'],
            'shipping'  => ['label' => 'Dikirim',     'icon' => 'fa-truck-fast',      'color' => 'indigo'],
            'completed' => ['label' => 'Selesai',     'icon' => 'fa-circle-check',    'color' => 'emerald'],
            'cancelled' => ['label' => 'Dibatalkan',  'icon' => 'fa-ban',             'color' => 'red'],
        ];
    @endphp

    @foreach($tabs as $key => $tab)
    @php $isActive = $statusFilter === $key; @endphp
    <a href="/admin/orders?status={{ $key }}"
       class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl text-xs font-bold transition active:scale-[0.98] border
              {{ $isActive
                 ? 'bg-slate-900 text-white border-transparent shadow-sm'
                 : 'bg-transparent text-slate-550 border-transparent hover:bg-slate-50 hover:text-slate-800' }}">
        <i class="fa-solid {{ $tab['icon'] }} text-[10px]"></i>
        <span>{{ $tab['label'] }}</span>
        <span class="ml-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold
                     {{ $isActive ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">
            {{ $counts[$key] ?? 0 }}
        </span>
    </a>
    @endforeach
</div>

<!-- ===== ORDER CARDS ===== -->
<div class="space-y-6">
    @forelse($orders as $o)
    @php
        $statusConfig = match($o->status) {
            'pending'   => ['border' => 'border-slate-200/60', 'bar' => 'bg-amber-500',   'badge' => 'text-amber-700 bg-amber-50 border-amber-200/60',   'icon' => 'fa-clock',       'label' => 'MENUNGGU DP'],
            'paid'      => ['border' => 'border-slate-200/60', 'bar' => 'bg-sky-500',     'badge' => 'text-sky-700 bg-sky-50 border-sky-200/60',         'icon' => 'fa-credit-card', 'label' => 'DIBAYAR (TERKUNCI)'],
            'cooking'   => ['border' => 'border-orange-200/60', 'bar' => 'bg-orange-500', 'badge' => 'text-orange-700 bg-orange-50 border-orange-200/60', 'icon' => 'fa-fire-burner', 'label' => 'SEDANG DIMASAK'],
            'shipping'  => ['border' => 'border-indigo-200/60', 'bar' => 'bg-indigo-500', 'badge' => 'text-indigo-700 bg-indigo-50 border-indigo-200/60', 'icon' => 'fa-truck-fast',  'label' => 'DALAM PENGIRIMAN'],
            'completed' => ['border' => 'border-slate-200/60', 'bar' => 'bg-emerald-500', 'badge' => 'text-emerald-700 bg-emerald-50 border-emerald-200/60', 'icon' => 'fa-circle-check', 'label' => 'SELESAI'],
            'cancelled' => ['border' => 'border-slate-200/60', 'bar' => 'bg-red-500',     'badge' => 'text-red-700 bg-red-50 border-red-200/60',         'icon' => 'fa-ban',           'label' => 'DIBATALKAN'],
            default     => ['border' => 'border-slate-200/60', 'bar' => 'bg-slate-400',   'badge' => 'text-slate-700 bg-slate-50 border-slate-100',       'icon' => 'fa-question',    'label' => strtoupper($o->status)],
        };
    @endphp
    
    <div class="bg-white border {{ $statusConfig['border'] }} rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition duration-200">
        
        <!-- Header Banner -->
        <div class="bg-slate-50/50 border-b border-slate-100 px-5 py-4 flex flex-wrap items-center justify-between gap-3 relative">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $statusConfig['bar'] }}"></div>
            
            <div class="pl-2">
                <div class="text-[9px] font-bold text-slate-400 tracking-wider uppercase mb-0.5">ID Transaksi Pesanan</div>
                <div class="text-sm sm:text-base font-bold text-slate-800 flex items-center gap-2">
                    <span>#ORD-{{ str_pad($o->id, 5, '0', STR_PAD_LEFT) }}</span>
                    <a href="/tracking?search={{ $o->id }}" target="_blank" title="Buka Halaman Lacak" class="text-slate-400 hover:text-orange-500 text-xs">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <div class="text-[9px] font-bold text-slate-400 tracking-wider uppercase mb-0.5">Waktu Masuk</div>
                    <div class="text-xs font-semibold text-slate-600">{{ $o->created_at->format('d M Y - H:i') }} WIB</div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-bold border {{ $statusConfig['badge'] }}">
                    <i class="fa-solid {{ $statusConfig['icon'] }} text-[10px]"></i>
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
                    
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $o->customer_wa) }}?text=Halo%20{{ urlencode($o->customer_name) }},%20kami%20dari%20Adelia%20Catering%20terkait%20pesanan%20%23{{ $o->id }}..." target="_blank" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100/70 border border-emerald-200 px-3 py-1.5 rounded-lg transition active:scale-[0.98] w-full mb-3">
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

            <!-- Col 2: Jadwal Acara & Progres -->
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
                
                @if($o->tracking_note)
                <div class="bg-indigo-50/70 border border-indigo-100 rounded-xl p-3 border-l-4 border-l-indigo-500">
                    <span class="text-[9px] font-bold text-indigo-700 flex items-center gap-1 mb-0.5"><i class="fa-solid fa-truck-ramp-box"></i> Catatan Pelacakan</span>
                    <p class="text-xs font-semibold text-indigo-900 leading-snug">{{ $o->tracking_note }}</p>
                </div>
                @endif

                @if($o->notes)
                <div class="bg-red-50 border border-red-100 rounded-xl p-3 border-l-4 border-l-red-500">
                    <span class="text-[9px] font-bold text-red-700 flex items-center gap-1 mb-0.5"><i class="fa-solid fa-circle-exclamation"></i> Catatan Pelanggan</span>
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
            
            <!-- Col 4: Finansial & Aksi Tahapan -->
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

                    @if(in_array($o->status, ['paid', 'cooking', 'shipping', 'completed']))
                    <div class="mt-2.5 pt-2 border-t border-white/5 flex items-center gap-1 text-[9px] text-sky-300 font-semibold">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Terverifikasi via {{ $o->payment_type ?? 'Midtrans' }}</span>
                    </div>
                    @endif
                </div>
                
                <!-- Action Buttons: Update Tahapan Progres -->
                <div class="space-y-2 mt-4">
                    <form action="/admin/orders/{{ $o->id }}/status" method="POST" class="space-y-2">
                        @csrf
                        <div class="relative">
                            <select name="status" onchange="this.form.submit()"
                                class="w-full text-xs font-bold py-2.5 pl-3 pr-8 rounded-xl border border-slate-200 outline-none cursor-pointer appearance-none transition focus:border-orange-500
                                @switch($o->status)
                                    @case('pending')   bg-amber-50 text-amber-700 @break
                                    @case('paid')      bg-sky-50 text-sky-700 @break
                                    @case('cooking')   bg-orange-50 text-orange-700 @break
                                    @case('shipping')  bg-indigo-50 text-indigo-700 @break
                                    @case('completed') bg-emerald-50 text-emerald-700 @break
                                    @case('cancelled') bg-red-50 text-red-700 @break
                                    @default           bg-slate-50 text-slate-700
                                @endswitch
                                ">
                                <option value="pending"   {{ $o->status == 'pending'   ? 'selected' : '' }}>⏳ 1. MENUNGGU DP</option>
                                <option value="paid"      {{ $o->status == 'paid'      ? 'selected' : '' }}>💳 2. DIBAYAR (TERKUNCI)</option>
                                <option value="cooking"   {{ $o->status == 'cooking'   ? 'selected' : '' }}>🍳 3. SEDANG DIMASAK</option>
                                <option value="shipping"  {{ $o->status == 'shipping'  ? 'selected' : '' }}>🚚 4. DALAM PENGIRIMAN</option>
                                <option value="completed" {{ $o->status == 'completed' ? 'selected' : '' }}>✅ 5. SELESAI & TIBA</option>
                                <option value="cancelled" {{ $o->status == 'cancelled' ? 'selected' : '' }}>❌ DIBATALKAN</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                <i class="fa-solid fa-chevron-down text-[10px]"></i>
                            </div>
                        </div>

                        <!-- Tombol Tambah Catatan Pelacakan Cepat -->
                        <button type="button" onclick="openTrackingModal({{ $o->id }}, '{{ addslashes($o->tracking_note ?? '') }}', '{{ addslashes($o->status) }}')"
                            class="w-full flex items-center justify-center gap-1.5 text-[11px] font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 py-1.5 rounded-lg transition active:scale-[0.98]">
                            <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                            <span>{{ $o->tracking_note ? 'Edit Catatan Kurir/Dapur' : '+ Catatan Kurir/Dapur' }}</span>
                        </button>
                    </form>
                    
                    <a href="/admin/orders/{{ $o->id }}/print" target="_blank"
                       class="w-full flex items-center justify-center gap-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 py-2 rounded-xl transition active:scale-[0.98]">
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

<!-- Modal Catatan Pelacakan -->
<div id="trackingModal" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-150 p-6 animate-fade-in-up">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-truck-fast text-orange-500"></i>
                <span>Catatan Pelacakan Pesanan <span id="modalOrderId" class="text-orange-600"></span></span>
            </h3>
            <button onclick="closeTrackingModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="trackingNoteForm" method="POST" action="">
            @csrf
            <input type="hidden" name="status" id="modalOrderStatus" value="cooking">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Catatan Progres untuk Pelanggan (Muncul di Web Lacak)</label>
                    <textarea name="tracking_note" id="modalTrackingNote" rows="3" 
                        placeholder="Contoh: Pesanan sedang dipacking oleh chef / Driver Mas Budi meluncur ke lokasi..."
                        class="w-full text-xs p-3 rounded-xl border border-slate-200 outline-none focus:border-orange-500"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeTrackingModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-orange-600 hover:bg-orange-700 shadow-sm transition">Simpan Catatan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function openTrackingModal(orderId, note, status) {
    document.getElementById('modalOrderId').innerText = '#' + orderId;
    document.getElementById('modalTrackingNote').value = note;
    document.getElementById('modalOrderStatus').value = status;
    document.getElementById('trackingNoteForm').action = '/admin/orders/' + orderId + '/status';
    document.getElementById('trackingModal').classList.remove('hidden');
}
function closeTrackingModal() {
    document.getElementById('trackingModal').classList.add('hidden');
}
</script>
@endsection
