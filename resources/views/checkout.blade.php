@extends('layouts.app')

@section('custom_scripts')
<script type="text/javascript"
    src="{{ $snapUrl ?? 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
    data-client-key="{{ $clientKey }}"></script>
@endsection

@section('content')
<section class="py-8 sm:py-12 bg-white relative overflow-hidden min-h-screen">
    <div class="absolute top-0 right-0 w-[400px] sm:w-[600px] h-[400px] sm:h-[600px] bg-secondary rounded-full mix-blend-multiply filter blur-3xl opacity-40 z-0 transform translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>

    <div class="container mx-auto px-4 sm:px-6 md:px-8 max-w-7xl relative z-10">
        <div class="mb-6 sm:mb-10 text-center md:text-left px-2">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-serif font-bold text-dark">Selesaikan Pesanan Anda</h1>
            <p class="text-gray-500 mt-2 text-sm sm:text-base">Pastikan keranjang terisi dan lengkapi data pengambilan/pengiriman.</p>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 sm:gap-10 items-start">
            
            <!-- KIRI: Form Pengiriman -->
            <div class="xl:col-span-7 space-y-4 sm:space-y-6 lg:space-y-8">
                <div class="bg-white rounded-xl sm:rounded-2xl lg:rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-4 sm:p-6 lg:p-8 xl:p-10 border border-gray-100">
                    <div class="mb-4 sm:mb-6 lg:mb-8 border-b border-gray-100 pb-3 sm:pb-4 lg:pb-6 flex items-center gap-2 sm:gap-3 lg:gap-4">
                        <span class="bg-primary/10 text-primary p-2 sm:p-2.5 lg:p-3 rounded-lg sm:rounded-xl lg:rounded-2xl text-xs sm:text-sm lg:text-base flex-shrink-0">
                            <i class="fa-solid fa-clipboard-list"></i>
                        </span>
                        <h2 class="text-base sm:text-lg lg:text-xl xl:text-2xl font-serif font-bold text-dark">Form Detail Pemesan</h2>
                    </div>
                    
                    <form id="checkout-form" class="space-y-3 sm:space-y-4 lg:space-y-6">
                        <!-- Nama & WhatsApp -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 lg:gap-6">
                            <div>
                                <label class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="customerName" required 
                                    class="w-full px-3 sm:px-4 lg:px-5 py-2.5 sm:py-3 lg:py-4 rounded-lg sm:rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none text-xs sm:text-sm lg:text-base" 
                                    placeholder="Cth: Budi Santoso">
                            </div>
                            <div>
                                <label class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">
                                    Nomor WhatsApp <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 lg:pl-5 flex items-center pointer-events-none">
                                        <span class="text-gray-400"><i class="fa-brands fa-whatsapp text-sm sm:text-base lg:text-lg"></i></span>
                                    </div>
                                    <input type="tel" id="customerWa" required 
                                        class="w-full pl-9 sm:pl-10 lg:pl-12 pr-3 sm:pr-4 lg:pr-5 py-2.5 sm:py-3 lg:py-4 rounded-lg sm:rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none text-xs sm:text-sm lg:text-base" 
                                        placeholder="081234567890">
                                </div>
                            </div>
                        </div>
                        
                        
                        <!-- ===== KALENDER BOOKING INTERAKTIF ===== -->
                        <div>
                            <label class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">
                                Tanggal Acara <span class="text-red-500">*</span>
                            </label>
                            <!-- Selected Date Display -->
                            <div id="selected-date-display" class="hidden mb-3 bg-primary/5 border border-primary/20 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-calendar-check text-primary text-sm"></i>
                                    <span id="selected-date-text" class="font-bold text-primary text-xs sm:text-sm"></span>
                                </div>
                                <button type="button" onclick="clearSelectedDate()" class="text-gray-400 hover:text-red-500 transition text-xs">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <input type="hidden" id="pickupDate" required>
                            
                            <!-- Calendar Widget -->
                            <div class="bg-gray-50 rounded-xl sm:rounded-2xl border border-gray-200 overflow-hidden">
                                <!-- Calendar Header -->
                                <div class="flex items-center justify-between px-3 sm:px-4 py-2.5 sm:py-3 bg-white border-b border-gray-100">
                                    <button type="button" onclick="calChangeMonth(-1)" class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-gray-100 hover:bg-primary/10 text-gray-500 hover:text-primary flex items-center justify-center transition active:scale-90">
                                        <i class="fa-solid fa-chevron-left text-[10px] sm:text-xs"></i>
                                    </button>
                                    <span id="cal-month-label" class="text-xs sm:text-sm font-bold text-gray-700"></span>
                                    <button type="button" onclick="calChangeMonth(1)" class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-gray-100 hover:bg-primary/10 text-gray-500 hover:text-primary flex items-center justify-center transition active:scale-90">
                                        <i class="fa-solid fa-chevron-right text-[10px] sm:text-xs"></i>
                                    </button>
                                </div>
                                <!-- Day Headers -->
                                <div class="grid grid-cols-7 gap-0.5 sm:gap-1 px-2 sm:px-3 pt-2 sm:pt-3">
                                    <div class="text-center text-[8px] sm:text-[9px] font-bold text-red-400 py-1">Min</div>
                                    <div class="text-center text-[8px] sm:text-[9px] font-bold text-gray-400 py-1">Sen</div>
                                    <div class="text-center text-[8px] sm:text-[9px] font-bold text-gray-400 py-1">Sel</div>
                                    <div class="text-center text-[8px] sm:text-[9px] font-bold text-gray-400 py-1">Rab</div>
                                    <div class="text-center text-[8px] sm:text-[9px] font-bold text-gray-400 py-1">Kam</div>
                                    <div class="text-center text-[8px] sm:text-[9px] font-bold text-gray-400 py-1">Jum</div>
                                    <div class="text-center text-[8px] sm:text-[9px] font-bold text-gray-400 py-1">Sab</div>
                                </div>
                                <!-- Calendar Grid -->
                                <div class="grid grid-cols-7 gap-0.5 sm:gap-1 p-2 sm:p-3" id="checkout-calendar-grid">
                                    <!-- JS fills this -->
                                </div>
                                <!-- Legenda Mini -->
                                <div class="flex flex-wrap items-center gap-x-3 sm:gap-x-4 gap-y-1 px-3 sm:px-4 py-2 sm:py-2.5 bg-white border-t border-gray-100">
                                    <span class="flex items-center gap-1 text-[8px] sm:text-[9px] text-gray-500"><span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded bg-emerald-400"></span>Tersedia</span>
                                    <span class="flex items-center gap-1 text-[8px] sm:text-[9px] text-gray-500"><span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded bg-amber-400"></span>Hampir Penuh</span>
                                    <span class="flex items-center gap-1 text-[8px] sm:text-[9px] text-gray-500"><span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded bg-red-400"></span>Penuh</span>
                                    <span class="flex items-center gap-1 text-[8px] sm:text-[9px] text-gray-500"><span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded bg-gray-700"></span>Tidak Tersedia</span>
                                </div>
                            </div>
                            <!-- Date Validation Message -->
                            <p id="date-validation-msg" class="hidden text-red-500 text-[10px] sm:text-xs font-medium mt-2 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span id="date-validation-text"></span>
                            </p>
                        </div>
                        <!-- ===== END KALENDER BOOKING ===== -->

                        <!-- Jam Acara -->
                        <div>
                            <label class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">
                                Jam Acara <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="pickupTime" required 
                                class="w-full px-3 sm:px-4 lg:px-5 py-2.5 sm:py-3 lg:py-4 rounded-lg sm:rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none text-gray-700 text-[11px] sm:text-xs lg:text-sm">
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">
                                Alamat Pengiriman <span class="text-red-500">*</span>
                            </label>
                            <textarea id="customerAddress" rows="3" required 
                                class="w-full px-3 sm:px-4 lg:px-5 py-2.5 sm:py-3 lg:py-4 rounded-lg sm:rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none resize-none text-xs sm:text-sm lg:text-base" 
                                placeholder="Isi alamat lengkap dengan RT/RW... (Tulis 'Ambil di tempat' bila tidak dikirim)"></textarea>
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">
                                Catatan Tambahan (Opsional)
                            </label>
                            <textarea id="customerNotes" rows="2" 
                                class="w-full px-3 sm:px-4 lg:px-5 py-2.5 sm:py-3 lg:py-4 rounded-lg sm:rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none resize-none text-xs sm:text-sm lg:text-base" 
                                placeholder="Pesan untuk dapur..."></textarea>
                        </div>
                    </form>
                </div>
            </div>

            <!-- KANAN: Ringkasan Keranjang -->
            <div class="xl:col-span-5">
                <div class="bg-gray-50 rounded-2xl sm:rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 sm:p-8 md:p-10 border border-gray-100 xl:sticky xl:top-24">
                    <h3 class="text-lg sm:text-xl font-serif font-bold mb-4 sm:mb-6 flex items-center justify-between pb-3 sm:pb-4 border-b border-gray-200 flex-wrap gap-2">
                        <span class="flex items-center gap-2 sm:gap-3">
                            <i class="fa-solid fa-basket-shopping text-primary text-sm sm:text-base"></i> <span>Pesanan Saya</span>
                        </span>
                        <a href="{{ url('/menu') }}" class="text-xs sm:text-sm font-sans text-primary hover:text-accent underline decoration-primary/30 underline-offset-4">Kembali ke Menu</a>
                    </h3>
                    
                    <div id="cart-items" class="space-y-3 sm:space-y-4 max-h-[250px] sm:max-h-[300px] overflow-y-auto pr-2 sm:pr-3 mb-4 sm:mb-6 custom-scrollbar"></div>

                    <div class="bg-white p-4 sm:p-5 rounded-xl sm:rounded-2xl mb-4 sm:mb-6 shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 font-medium text-sm sm:text-base">Total Pesanan</span>
                            <span class="text-xl sm:text-2xl font-bold text-dark" id="display-total">Rp 0</span>
                        </div>
                    </div>

                    <!-- Kalkulator DP -->
                    <div class="mb-6 sm:mb-8">
                        <h4 class="font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2 text-sm sm:text-base">
                            <i class="fa-solid fa-wallet text-gray-400"></i> Pilihan Pembayaran
                        </h4>
                        <div class="grid grid-cols-3 gap-2 sm:gap-3">
                            <label class="cursor-pointer relative">
                                <input type="radio" name="dp_scheme" value="0.3" class="peer sr-only" onchange="calculateDP()" checked>
                                <div class="rounded-lg sm:rounded-xl border border-gray-200 px-2 py-3 sm:py-4 text-center peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:ring-1 peer-checked:ring-primary transition-all bg-white hover:bg-gray-50">
                                    <div class="font-bold text-gray-700 peer-checked:text-primary text-xs sm:text-sm md:text-base">DP 30%</div>
                                </div>
                            </label>
                            <label class="cursor-pointer relative">
                                <input type="radio" name="dp_scheme" value="0.5" class="peer sr-only" onchange="calculateDP()">
                                <div class="rounded-lg sm:rounded-xl border border-gray-200 px-2 py-3 sm:py-4 text-center peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:ring-1 peer-checked:ring-primary transition-all bg-white hover:bg-gray-50">
                                    <div class="font-bold text-gray-700 peer-checked:text-primary text-xs sm:text-sm md:text-base">DP 50%</div>
                                </div>
                            </label>
                            <label class="cursor-pointer relative">
                                <input type="radio" name="dp_scheme" value="1" class="peer sr-only" onchange="calculateDP()">
                                <div class="rounded-lg sm:rounded-xl border border-gray-200 px-2 py-3 sm:py-4 text-center peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:ring-1 peer-checked:ring-primary transition-all bg-white hover:bg-gray-50">
                                    <div class="font-bold text-gray-700 peer-checked:text-primary text-xs sm:text-sm md:text-base">Lunas</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Nominal DP -->
                    <div class="bg-gradient-to-br from-primary to-amber-700 p-4 sm:p-6 rounded-xl sm:rounded-2xl mb-6 sm:mb-8 text-white relative overflow-hidden shadow-lg">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-20 sm:w-24 h-20 sm:h-24 bg-white opacity-10 rounded-full blur-xl"></div>
                        <div class="flex justify-between items-center relative z-10">
                            <span class="font-medium text-white/90 text-sm sm:text-base">Nominal Transfer:</span>
                            <span class="text-xl sm:text-2xl md:text-3xl font-bold tracking-tight shadow-sm" id="display-dp">Rp 0</span>
                        </div>
                    </div>

                    <button onclick="processCheckout(this)" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 sm:py-4 px-5 sm:px-6 rounded-xl sm:rounded-2xl transition-all duration-300 transform active:scale-[0.98] shadow-[0_8px_20px_rgba(0,0,0,0.15)] flex items-center justify-center text-base sm:text-lg gap-2 sm:gap-3">
                        <i class="fa-regular fa-credit-card text-xl sm:text-2xl"></i> <span id="btn-text">Bayar Sekarang</span>
                    </button>
                    
                    <p class="text-center mt-3 sm:mt-4">
                        <button onclick="clearCart()" class="text-gray-400 hover:text-red-500 underline text-xs transition-colors">Kosongkan Keranjang</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Form bayangan untuk CSRF Token jika dibutuhkan oleh fetch -->
<form id="csrf-form">@csrf</form>

<script>
    let totalAmount = 0;

    function renderCartPage() {
        const cart = getCart(); // Fungsi dari global app layouts
        const cartContainer = document.getElementById('cart-items');
        cartContainer.innerHTML = '';
        totalAmount = 0;

        if (cart.length === 0) {
            cartContainer.innerHTML = `
                <div class="text-center bg-white py-12 rounded-2xl border border-dashed border-gray-300">
                    <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-bag-shopping text-2xl"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Keranjang masih kosong.</p>
                    <a href="{{ url('/menu') }}" class="text-primary text-sm font-medium mt-3 inline-block hover:underline">Belanja Dulu</a>
                </div>
            `;
        } else {
            cart.forEach(item => {
                totalAmount += (item.price * item.qty);
                const itemDiv = document.createElement('div');
                itemDiv.className = 'flex justify-between items-center bg-white p-3 sm:p-4 rounded-lg sm:rounded-xl shadow-sm border border-gray-100';
                itemDiv.innerHTML = `
                    <div class="flex-1 pr-2 sm:pr-3 min-w-0">
                        <h5 class="font-semibold text-xs sm:text-sm text-dark line-clamp-1">${item.name}</h5>
                        <div class="text-primary text-xs sm:text-sm font-bold mt-1">${formatIDR(item.price)}</div>
                    </div>
                    <div class="flex items-center space-x-2 sm:space-x-3 bg-gray-50 rounded-lg p-1 sm:p-1.5 border border-gray-200 flex-shrink-0">
                        <button onclick="updateQtyPage(${item.id}, -1)" class="w-6 h-6 sm:w-7 sm:h-7 flex items-center justify-center text-gray-500 hover:text-white hover:bg-red-500 rounded-md transition-colors"><i class="fa-solid fa-minus text-[10px] sm:text-xs"></i></button>
                        <input type="number" min="1" value="${item.qty}" onchange="setQtyPage(${item.id}, this.value)" class="text-xs sm:text-sm font-bold w-10 sm:w-12 text-center text-dark bg-transparent border-none p-0 m-0 focus:ring-0 outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        <button onclick="updateQtyPage(${item.id}, 1)" class="w-6 h-6 sm:w-7 sm:h-7 flex items-center justify-center text-gray-500 hover:text-white hover:bg-green-500 rounded-md transition-colors"><i class="fa-solid fa-plus text-[10px] sm:text-xs"></i></button>
                    </div>
                `;
                cartContainer.appendChild(itemDiv);
            });
        }
        
        document.getElementById('display-total').innerText = formatIDR(totalAmount);
        calculateDP();
    }

    function updateQtyPage(id, change) {
        let cart = getCart();
        let index = cart.findIndex(item => item.id === id);
        if (index !== -1) {
            cart[index].qty += change;
            if (cart[index].qty <= 0) cart.splice(index, 1);
            saveCart(cart);
            renderCartPage();
        }
    }

    function setQtyPage(id, value) {
        let cart = getCart();
        let index = cart.findIndex(item => item.id === id);
        if (index !== -1) {
            let newQty = parseInt(value);
            if (isNaN(newQty) || newQty <= 0) {
                cart.splice(index, 1);
            } else {
                cart[index].qty = newQty;
            }
            saveCart(cart);
            renderCartPage();
        }
    }

    function clearCart() {
        if(confirm('Anda yakin ingin membatalkan semua pesanan?')) {
            saveCart([]);
            renderCartPage();
        }
    }

    function calculateDP() {
        const schemeRadios = document.getElementsByName('dp_scheme');
        let percentage = 0.3; 
        for (let radio of schemeRadios) {
            if (radio.checked) percentage = parseFloat(radio.value);
        }
        document.getElementById('display-dp').innerText = formatIDR(totalAmount * percentage);
    }

    // ================================
    // BOOKING CALENDAR LOGIC
    // ================================
    let bookingCalendarData = {};
    let calMonth = new Date().getMonth();
    let calYear = new Date().getFullYear();
    let selectedDate = null;
    const bulanIndo = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const hariIndoFull = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

    async function loadBookingCalendar() {
        try {
            const res = await fetch('/api/calendar?months=6');
            const json = await res.json();
            bookingCalendarData = json.calendar;
            renderCheckoutCalendar();
        } catch(e) {
            console.error('Gagal memuat kalender:', e);
        }
    }

    function calChangeMonth(dir) {
        calMonth += dir;
        if (calMonth > 11) { calMonth = 0; calYear++; }
        if (calMonth < 0) { calMonth = 11; calYear--; }
        renderCheckoutCalendar();
    }

    function selectDate(dateStr) {
        selectedDate = dateStr;
        document.getElementById('pickupDate').value = dateStr;

        // Show selected date display
        const dateObj = new Date(dateStr + 'T00:00:00');
        const dayName = hariIndoFull[dateObj.getDay()];
        const day = dateObj.getDate();
        const month = bulanIndo[dateObj.getMonth()];
        const year = dateObj.getFullYear();
        
        document.getElementById('selected-date-text').textContent = `${dayName}, ${day} ${month} ${year}`;
        document.getElementById('selected-date-display').classList.remove('hidden');

        // Hide validation message
        document.getElementById('date-validation-msg').classList.add('hidden');

        // Re-render to show selected state
        renderCheckoutCalendar();
    }

    function clearSelectedDate() {
        selectedDate = null;
        document.getElementById('pickupDate').value = '';
        document.getElementById('selected-date-display').classList.add('hidden');
        renderCheckoutCalendar();
    }

    function renderCheckoutCalendar() {
        const grid = document.getElementById('checkout-calendar-grid');
        const label = document.getElementById('cal-month-label');
        grid.innerHTML = '';
        label.textContent = bulanIndo[calMonth] + ' ' + calYear;

        const firstDay = new Date(calYear, calMonth, 1).getDay();
        const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();

        // Empty cells for offset
        for (let i = 0; i < firstDay; i++) {
            const empty = document.createElement('div');
            empty.className = 'aspect-square';
            grid.appendChild(empty);
        }

        // Day cells
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${calYear}-${String(calMonth+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
            const info = bookingCalendarData[dateStr];
            const cell = document.createElement('button');
            cell.type = 'button';
            
            let bgClass = 'bg-white border-gray-200';
            let textClass = 'text-gray-400';
            let isClickable = false;
            let statusDot = '';
            const isSelected = selectedDate === dateStr;

            if (info) {
                switch(info.status) {
                    case 'available':
                        bgClass = 'bg-emerald-50 border-emerald-200 hover:border-emerald-400 hover:bg-emerald-100 hover:shadow-sm';
                        textClass = 'text-emerald-800';
                        isClickable = true;
                        if (info.count > 0) statusDot = `<span class="w-1 h-1 sm:w-1.5 sm:h-1.5 rounded-full bg-emerald-400 absolute bottom-1 left-1/2 -translate-x-1/2"></span>`;
                        break;
                    case 'almost_full':
                        bgClass = 'bg-amber-50 border-amber-300 hover:border-amber-400 hover:bg-amber-100';
                        textClass = 'text-amber-700';
                        isClickable = true;
                        statusDot = `<span class="w-1 h-1 sm:w-1.5 sm:h-1.5 rounded-full bg-amber-400 absolute bottom-1 left-1/2 -translate-x-1/2"></span>`;
                        break;
                    case 'full':
                        bgClass = 'bg-red-50 border-red-200';
                        textClass = 'text-red-400 line-through';
                        statusDot = `<span class="w-1 h-1 sm:w-1.5 sm:h-1.5 rounded-full bg-red-400 absolute bottom-1 left-1/2 -translate-x-1/2"></span>`;
                        break;
                    case 'blocked':
                        bgClass = 'bg-gray-100 border-gray-300';
                        textClass = 'text-gray-300';
                        break;
                    case 'past':
                    case 'too_soon':
                        bgClass = 'bg-gray-50 border-gray-100';
                        textClass = 'text-gray-300';
                        break;
                }
            }

            // Selected state
            if (isSelected) {
                bgClass = 'bg-primary border-primary ring-2 ring-primary/30 shadow-md shadow-primary/20';
                textClass = 'text-white';
                statusDot = '';
            }

            cell.className = `aspect-square rounded-lg sm:rounded-xl border ${bgClass} ${isClickable ? 'cursor-pointer active:scale-90' : 'cursor-not-allowed opacity-60'} flex items-center justify-center relative transition-all duration-200`;
            cell.innerHTML = `<span class="text-[10px] sm:text-xs font-bold ${textClass} leading-none">${day}</span>${statusDot}`;
            cell.disabled = !isClickable;

            if (isClickable) {
                cell.addEventListener('click', () => selectDate(dateStr));
            } else if (info) {
                cell.addEventListener('click', () => {
                    const msgEl = document.getElementById('date-validation-msg');
                    const textEl = document.getElementById('date-validation-text');
                    let msg = '';
                    switch(info.status) {
                        case 'full': msg = `Tanggal ${day} ${bulanIndo[calMonth]} sudah penuh (${info.count}/${info.max} porsi).`; break;
                        case 'blocked': msg = `Tanggal ${day} ${bulanIndo[calMonth]} tidak tersedia${info.reason ? ': ' + info.reason : '.'}`; break;
                        case 'too_soon': msg = 'Pemesanan terlalu mendadak. Silakan pilih tanggal yang lebih jauh.'; break;
                        case 'past': msg = 'Tidak bisa memesan untuk tanggal yang sudah lewat.'; break;
                    }
                    if (msg) {
                        textEl.textContent = msg;
                        msgEl.classList.remove('hidden');
                        setTimeout(() => msgEl.classList.add('hidden'), 5000);
                    }
                });
            }

            grid.appendChild(cell);
        }
    }

    // UPDATE: INTEGRASI BACKEND (with calendar validation)
    async function processCheckout(btn) {
        const cart = getCart();
        if (cart.length === 0) {
            alert('Tidak bisa memproses checkout. Keranjang kosong.');
            return;
        }

        const name = document.getElementById('customerName').value.trim();
        const wa = document.getElementById('customerWa').value.trim();
        const pickupDate = document.getElementById('pickupDate').value;
        const pickupTime = document.getElementById('pickupTime').value;
        const address = document.getElementById('customerAddress').value.trim();
        const notes = document.getElementById('customerNotes').value.trim();

        if (!name || !wa || !pickupDate || !pickupTime || !address) {
            if (!pickupDate) {
                const msgEl = document.getElementById('date-validation-msg');
                const textEl = document.getElementById('date-validation-text');
                textEl.textContent = 'Silakan pilih tanggal acara pada kalender di atas.';
                msgEl.classList.remove('hidden');
                document.getElementById('checkout-calendar-grid').scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            alert('Mohon lengkapi seluruh field wajib di Form Pengiriman.');
            return;
        }

        // Kalkulasi nama Hari
        const dateObj = new Date(pickupDate + 'T00:00:00');
        const dayName = hariIndoFull[dateObj.getDay()];

        // Satukan untuk format backend database yyyy-mm-dd hh:ii
        let dbEventDate = `${pickupDate} ${pickupTime}:00`; 

        const schemeRadios = document.getElementsByName('dp_scheme');
        let dpLabel = "DP 30%";
        let dpMultiplier = 0.3;
        
        for (let radio of schemeRadios) {
            if (radio.checked) {
                dpMultiplier = parseFloat(radio.value);
                if (dpMultiplier === 0.3) dpLabel = "DP 30%";
                if (dpMultiplier === 0.5) dpLabel = "DP 50%";
                if (dpMultiplier === 1) dpLabel = "Pelunasan (100%)";
            }
        }

        let dpAmountFixed = totalAmount * dpMultiplier;

        // UI Loading State
        btn.disabled = true;
        document.getElementById('btn-text').innerText = 'Memproses...';

        try {
            // Ambil token CSRF Laravel
            const csrfToken = document.querySelector('form#csrf-form input[name="_token"]').value;

            // Kirim data ke backend untuk disimpan di database Admin
            const response = await fetch('/checkout/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    customer_name: name,
                    customer_wa: wa,
                    event_date: dbEventDate,
                    address: address,
                    notes: notes,
                    payment_scheme: dpMultiplier,
                    total_amount: totalAmount,
                    dp_amount: dpAmountFixed,
                    items: cart
                })
            });

            const result = await response.json();

            // Handle booking calendar validation errors (422)
            if (response.status === 422 && !result.success) {
                const msgEl = document.getElementById('date-validation-msg');
                const textEl = document.getElementById('date-validation-text');
                textEl.textContent = result.message;
                msgEl.classList.remove('hidden');
                document.getElementById('checkout-calendar-grid').scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Reload calendar data in case availability changed
                loadBookingCalendar();
                
                btn.disabled = false;
                document.getElementById('btn-text').innerText = 'Bayar Sekarang';
                return;
            }

            if(result.success) {
                // Tampilkan Snap Modal Midtrans
                window.snap.pay(result.snap_token, {
                    onSuccess: function(paymentResult) {
                        // Racik link WA jika pembayaran sukses
                        let waText = `Halo *Adelia Catering Jepara*, saya baru saja membayar pesanan catering:%0A%0A`;
                        waText += `📋 *DATA PEMESAN*%0A`;
                        waText += `No. Order : #ORD-${result.order_id}%0A`;
                        waText += `Nama : ${name}%0A`;
                        waText += `No. WA : ${wa}%0A`;
                        waText += `Hari/Tgl Diambil : ${dayName}, ${pickupDate}%0A`;
                        waText += `Jam Diambil : Pukul ${pickupTime}%0A`;
                        waText += `Alamat : _${address}_%0A`;
                        if (notes) waText += `Catatan : ${notes}%0A`;
                        
                        waText += `%0A🛍️ *DETAIL PESANAN*%0A`;
                        cart.forEach((item, index) => {
                            waText += `${index + 1}. ${item.name} *(x${item.qty})*%0A   Subtotal: ${formatIDR(item.price * item.qty)}%0A`;
                        });

                        waText += `%0A💳 *TELAH DIBAYAR LUNAS/DP:* *${formatIDR(dpAmountFixed)}*%0A%0A`;
                        waText += `Nomor Tanda Terima Midtrans: ${paymentResult.order_id}%0A`;
                        waText += `Pembayaran berhasil via sistem otomatis. Mohon order segera disiapkan. Terima kasih! 🙏`;

                        saveCart([]);
                        window.open(`https://wa.me/{{ $adminWa }}?text=${waText}`, '_blank');
                        // Arahkan langsung ke halaman lacak pesanan
                        window.location.href = '/tracking?search=' + result.order_id;
                    },
                    onPending: function(paymentResult) {
                        alert('Silakan selesaikan instruksi pembayaran Anda.');
                        window.location.href = '/tracking?search=' + result.order_id;
                    },
                    onError: function(paymentResult) {
                        alert('Pembayaran gagal, silakan coba lagi.');
                        btn.disabled = false;
                        document.getElementById('btn-text').innerText = 'Bayar Sekarang';
                    },
                    onClose: function() {
                        alert('Anda menutup layar pembayaran, Anda bisa membayar nanti.');
                        btn.disabled = false;
                        document.getElementById('btn-text').innerText = 'Bayar Sekarang';
                    }
                });

            } else {
                alert('Gagal memproses pesanan: ' + result.message);
                btn.disabled = false;
                document.getElementById('btn-text').innerText = 'Bayar Sekarang';
            }

        } catch (error) {
            console.error(error);
            alert('Terjadi kesalahan jaringan.');
            btn.disabled = false;
            document.getElementById('btn-text').innerText = 'Bayar Sekarang';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderCartPage();
        loadBookingCalendar();
    });
</script>
@endsection

