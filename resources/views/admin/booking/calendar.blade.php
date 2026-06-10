@extends('admin.layout')
@section('content')

<!-- Page Header -->
<div class="mb-6">
    <h1 class="text-2xl lg:text-3xl font-bold text-slate-800 tracking-tight">Kalender Booking & Kuota</h1>
    <p class="text-slate-500 mt-1 font-medium text-xs sm:text-sm">Kelola kapasitas produksi harian, jarak minimal order, dan pemblokiran tanggal operasional.</p>
</div>

<!-- Success Alert -->
@if(session('success'))
<div class="bg-emerald-50 border border-emerald-100 text-emerald-800 p-4 rounded-xl mb-6 flex items-start justify-between gap-2.5 text-xs sm:text-sm" id="success-alert">
    <div class="flex items-start gap-2">
        <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0 text-base"></i>
        <div>
            <span class="font-bold">Berhasil!</span> {{ session('success') }}
        </div>
    </div>
    <button onclick="document.getElementById('success-alert').remove()" class="text-emerald-400 hover:text-emerald-600 shrink-0">
        <i class="fa-solid fa-xmark"></i>
    </button>
</div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <!-- ========== KOLOM KIRI: Kalender Visual + Legenda ========== -->
    <div class="xl:col-span-2 space-y-6">

        <!-- Kalender Interaktif -->
        <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <h2 class="text-sm sm:text-base font-bold text-slate-800 tracking-tight flex items-center gap-2">
                        <i class="fa-regular fa-calendar-days text-orange-500"></i>
                        Peta Ketersediaan Slot
                    </h2>
                    <p class="text-[10px] sm:text-xs text-slate-505 font-medium mt-1 truncate">Klik tanggal untuk memblokir secara instan.</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button onclick="changeMonth(-1)" class="w-8 h-8 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-650 transition flex items-center justify-center shadow-sm active:scale-95">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </button>
                    <span id="calendar-month-label" class="text-xs font-bold text-slate-700 min-w-[90px] sm:min-w-[120px] text-center"></span>
                    <button onclick="changeMonth(1)" class="w-8 h-8 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-655 transition flex items-center justify-center shadow-sm active:scale-95">
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </button>
                </div>
            </div>

            <div class="p-4 sm:p-6">
                <!-- Days Header -->
                <div class="grid grid-cols-7 gap-1.5 mb-3">
                    <div class="text-center text-[9px] sm:text-[10px] font-bold text-red-500 uppercase tracking-wider">Min</div>
                    <div class="text-center text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sen</div>
                    <div class="text-center text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sel</div>
                    <div class="text-center text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Rab</div>
                    <div class="text-center text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kam</div>
                    <div class="text-center text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jum</div>
                    <div class="text-center text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sab</div>
                </div>
                <!-- Calendar Grid -->
                <div class="grid grid-cols-7 gap-1.5" id="admin-calendar-grid">
                    <!-- Populated by JS -->
                </div>
            </div>
        </div>

        <!-- Legenda -->
        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 shadow-sm">
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Keterangan Warna Kalender</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                <div class="flex items-center gap-2 text-[10px] sm:text-xs text-slate-600 font-medium">
                    <span class="w-4 h-4 rounded-lg bg-emerald-50 border border-emerald-300 shrink-0"></span>
                    <span>Tersedia</span>
                </div>
                <div class="flex items-center gap-2 text-[10px] sm:text-xs text-slate-600 font-medium">
                    <span class="w-4 h-4 rounded-lg bg-amber-50 border border-amber-300/65 shrink-0"></span>
                    <span>Hampir Penuh</span>
                </div>
                <div class="flex items-center gap-2 text-[10px] sm:text-xs text-slate-600 font-medium">
                    <span class="w-4 h-4 rounded-lg bg-red-50 border border-red-300 shrink-0"></span>
                    <span>Kuota Penuh</span>
                </div>
                <div class="flex items-center gap-2 text-[10px] sm:text-xs text-slate-600 font-medium">
                    <span class="w-4 h-4 rounded-lg bg-slate-900 border border-slate-900 shrink-0"></span>
                    <span>Diblokir Manual</span>
                </div>
                <div class="flex items-center gap-2 text-[10px] sm:text-xs text-slate-600 font-medium">
                    <span class="w-4 h-4 rounded-lg bg-slate-50 border border-slate-200/60 shrink-0"></span>
                    <span>Jadwal Terlewat</span>
                </div>
            </div>
        </div>

        <!-- Daftar Tanggal Diblokir -->
        <div class="bg-white border border-slate-200/60 rounded-2xl overflow-hidden shadow-sm">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm font-bold text-slate-800 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-ban text-red-500 text-xs"></i>
                    Daftar Tanggal Diblokir
                </h2>
            </div>
            <div class="p-5">
                @if($blockedDates->isEmpty())
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center mx-auto mb-3 text-slate-400 border border-slate-100">
                        <i class="fa-regular fa-calendar-xmark text-lg"></i>
                    </div>
                    <p class="text-slate-505 font-bold text-xs">Tidak ada tanggal diblokir saat ini.</p>
                    <p class="text-slate-400 text-[10px] sm:text-xs mt-0.5">Semua tanggal dapat dipesan pelanggan sesuai kapasitas harian.</p>
                </div>
                @else
                <div class="space-y-2.5 max-h-[300px] overflow-y-auto custom-scrollbar pr-1">
                    @foreach($blockedDates as $bd)
                    <div class="flex items-center justify-between bg-slate-50 border border-slate-150 p-3 rounded-xl hover:bg-slate-100/50 transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 bg-red-50 text-red-655 rounded-lg flex items-center justify-center shrink-0 border border-red-100/50">
                                <i class="fa-solid fa-calendar-xmark text-xs"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-slate-800 text-xs">
                                    {{ \Carbon\Carbon::parse($bd->date)->translatedFormat('l, d F Y') }}
                                </div>
                                <div class="text-[10px] text-slate-450 truncate font-semibold">{{ $bd->reason ?? 'Tanpa alasan spesifik' }}</div>
                            </div>
                        </div>
                        <form action="/admin/booking/unblock/{{ $bd->id }}" method="POST" onsubmit="return confirm('Yakin ingin membuka blokir tanggal ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-8 h-8 bg-white hover:bg-red-50 text-red-550 hover:text-red-655 rounded-lg border border-slate-200 hover:border-red-150 flex items-center justify-center transition active:scale-95 shadow-sm shrink-0">
                                <i class="fa-regular fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ========== KOLOM KANAN: Settings & Form Blokir ========== -->
    <div class="space-y-6">

        <!-- Pengaturan Kapasitas -->
        <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm font-bold text-slate-800 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-orange-500"></i>
                    Batas Kuota Kapasitas
                </h2>
                <p class="text-[10px] sm:text-xs text-slate-500 font-medium mt-1">Konfigurasi batasan pesanan masuk otomatis.</p>
            </div>
            <form action="/admin/booking/settings" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] sm:text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                        Maks. Porsi / Hari <span class="text-red-500">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <input type="number" name="max_orders_per_day" value="{{ $maxOrdersPerDay }}" min="1" max="10000" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-550/30 focus:bg-white focus:border-orange-500 outline-none text-xs sm:text-sm font-bold text-slate-800 transition">
                        <span class="absolute right-3.5 text-[9px] sm:text-xs text-slate-400 font-bold">porsi / hari</span>
                    </div>
                    <p class="text-[9px] text-slate-400 mt-1">Batas porsi maksimum per hari sebelum tanggal terkunci otomatis.</p>
                </div>
                <div>
                    <label class="block text-[10px] sm:text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                        Jarak Minimal Order <span class="text-red-500">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <input type="number" name="min_advance_days" value="{{ $minAdvanceDays }}" min="0" max="30" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-550/30 focus:bg-white focus:border-orange-500 outline-none text-xs sm:text-sm font-bold text-slate-800 transition">
                        <span class="absolute right-3.5 text-[9px] sm:text-xs text-slate-400 font-bold">hari ke depan</span>
                    </div>
                    <p class="text-[9px] text-slate-400 mt-1">Jarak waktu minimum pemesanan bagi konsumen (H-Jarak).</p>
                </div>
                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-bold py-2.5 rounded-xl transition active:scale-[0.98] flex items-center justify-center gap-1.5 text-xs sm:text-sm shadow-sm">
                    <i class="fa-regular fa-floppy-disk"></i>
                    <span>Simpan Aturan</span>
                </button>
            </form>
        </div>

        <!-- Form Blokir Tanggal Manual -->
        <div class="bg-white border border-slate-200/60 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm font-bold text-slate-800 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-lock text-red-500 text-xs"></i>
                    Blokir Tanggal Baru
                </h2>
                <p class="text-[10px] sm:text-xs text-slate-500 font-medium mt-1">Kunci tanggal tertentu agar tidak bisa dipilih pelanggan.</p>
            </div>
            <form action="/admin/booking/block" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] sm:text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                        Pilih Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date" required min="{{ date('Y-m-d') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-550/30 focus:bg-white focus:border-red-500 outline-none text-xs sm:text-sm font-bold text-slate-800 transition">
                </div>
                <div>
                    <label class="block text-[10px] sm:text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                        Alasan Pemblokiran <span class="text-slate-400 font-medium lowercase">(opsional)</span>
                    </label>
                    <input type="text" name="reason" placeholder="Cth: Libur Karyawan, Reservasi Khusus"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-550/30 focus:bg-white focus:border-red-500 outline-none text-xs sm:text-sm font-medium text-slate-800 transition">
                </div>
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 active:bg-red-800 text-white font-bold py-2.5 rounded-xl transition active:scale-[0.98] flex items-center justify-center gap-1.5 text-xs sm:text-sm shadow-sm">
                    <i class="fa-solid fa-ban text-[10px]"></i>
                    <span>Kunci Tanggal</span>
                </button>
            </form>
        </div>

        <!-- Quick Stats Widget -->
        <div class="bg-slate-900 text-white rounded-2xl p-5 shadow-sm border border-slate-850">
            <h3 class="text-xs font-bold text-white uppercase tracking-wider mb-4 flex items-center gap-1.5">
                <i class="fa-solid fa-chart-simple text-orange-400 text-[10px]"></i>
                <span>Statistik Operasional</span>
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center text-xs border-b border-white/10 pb-2">
                    <span class="text-slate-400 font-medium">Batas Maks. Harian</span>
                    <span class="text-white font-bold">{{ $maxOrdersPerDay }} porsi</span>
                </div>
                <div class="flex justify-between items-center text-xs border-b border-white/10 pb-2">
                    <span class="text-slate-400 font-medium">Jarak Min. Order</span>
                    <span class="text-white font-bold">H-{{ $minAdvanceDays }} hari</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-400 font-medium">Total Terblokir</span>
                    <span class="text-orange-450 font-bold">{{ $blockedDates->count() }} tanggal</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admin Calendar JS -->
<script>
    let calendarData = {};
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    const bulanIndo = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

    async function loadCalendarData() {
        try {
            const res = await fetch('/api/calendar?months=6');
            const json = await res.json();
            calendarData = json.calendar;
            renderCalendar();
        } catch(e) {
            console.error('Gagal memuat data kalender:', e);
        }
    }

    function changeMonth(dir) {
        currentMonth += dir;
        if (currentMonth > 11) { currentMonth = 0; currentYear++; }
        if (currentMonth < 0) { currentMonth = 11; currentYear--; }
        renderCalendar();
    }

    function renderCalendar() {
        const grid = document.getElementById('admin-calendar-grid');
        const label = document.getElementById('calendar-month-label');
        grid.innerHTML = '';
        label.textContent = bulanIndo[currentMonth] + ' ' + currentYear;

        const firstDay = new Date(currentYear, currentMonth, 1).getDay(); // 0=Sun
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

        // Empty cells
        for (let i = 0; i < firstDay; i++) {
            const empty = document.createElement('div');
            empty.className = 'aspect-square';
            grid.appendChild(empty);
        }

        // Day cells
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${currentYear}-${String(currentMonth+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
            const info = calendarData[dateStr];
            const cell = document.createElement('div');
            
            let bgClass = 'bg-white border-slate-200 hover:border-orange-300';
            let textClass = 'text-slate-700';
            let badge = '';
            let statusIcon = '';
            let cursor = 'cursor-default';
            let tooltip = '';

            if (info) {
                switch(info.status) {
                    case 'available':
                        bgClass = 'bg-emerald-50/60 border-emerald-200 hover:border-emerald-400 hover:bg-emerald-100/50';
                        textClass = 'text-emerald-800';
                        if (info.count > 0) badge = `<span class="text-[7px] sm:text-[8px] lg:text-[9px] font-bold text-emerald-600">${info.count}/${info.max}</span>`;
                        cursor = 'cursor-pointer';
                        break;
                    case 'almost_full':
                        bgClass = 'bg-amber-50 border-amber-300 hover:border-amber-400 hover:bg-amber-100/50';
                        textClass = 'text-amber-800';
                        badge = `<span class="text-[7px] sm:text-[8px] lg:text-[9px] font-bold text-amber-600">${info.count}/${info.max}</span>`;
                        statusIcon = '<i class="fa-solid fa-triangle-exclamation text-amber-500 text-[8px]"></i>';
                        cursor = 'cursor-pointer';
                        break;
                    case 'full':
                        bgClass = 'bg-red-50 border-red-250';
                        textClass = 'text-red-700';
                        badge = `<span class="text-[7px] sm:text-[8px] lg:text-[9px] font-bold text-red-500">PENUH</span>`;
                        cursor = 'cursor-pointer';
                        break;
                    case 'blocked':
                        bgClass = 'bg-slate-900 border-transparent';
                        textClass = 'text-white';
                        statusIcon = '<i class="fa-solid fa-lock text-slate-400 text-[8px]"></i>';
                        tooltip = info.reason || 'Diblokir';
                        cursor = 'cursor-pointer';
                        break;
                    case 'past':
                    case 'too_soon':
                        bgClass = 'bg-slate-50 border-slate-200/50';
                        textClass = 'text-slate-350';
                        break;
                }
            }

            cell.className = `aspect-square rounded-xl border ${bgClass} ${cursor} flex flex-col items-center justify-center gap-0.5 transition relative group`;
            cell.innerHTML = `
                <span class="text-[10px] sm:text-xs font-bold ${textClass} leading-none">${day}</span>
                ${badge}
                ${statusIcon}
            `;

            if (tooltip) {
                cell.title = tooltip;
            }

            if (info && ['available','almost_full','full'].includes(info.status)) {
                cell.addEventListener('click', () => {
                    if (confirm(`Blokir tanggal ${day} ${bulanIndo[currentMonth]} ${currentYear}?`)) {
                        blockDateFromCalendar(dateStr);
                    }
                });
            }

            grid.appendChild(cell);
        }
    }

    function blockDateFromCalendar(dateStr) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/booking/block';
        form.innerHTML = `
            @csrf
            <input type="hidden" name="date" value="${dateStr}">
            <input type="hidden" name="reason" value="Diblokir via kalender admin">
        `;
        document.body.appendChild(form);
        form.submit();
    }

    loadCalendarData();
</script>
@endsection
