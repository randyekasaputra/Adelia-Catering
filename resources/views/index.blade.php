<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adelia Catering Jepara | Cita Rasa Istimewa</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="Pesan katering terbaik di Jepara untuk berbagai acara. Tersedia nasi kotak, prasmanan, tumpeng, dan snack box dengan cita rasa istimewa masakan nusantara.">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#d97706', // Amber 600 - Warna Kuliner Utama
                        secondary: '#fef3c7', // Amber 50 - Background Lembut
                        accent: '#b45309', // Amber 700 - Hover state
                        dark: '#1f2937', // Gray 800 - Teks utama
                        surface: '#ffffff'
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    animation: {
                        'float': 'float 3s ease-in-out infinite',
                        'fade-in-up': 'fadeInUp 1s ease-out forwards'
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Smooth scrolling */
        html { scroll-behavior: smooth; }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #fef3c7; }
        ::-webkit-scrollbar-thumb { background: #d97706; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #b45309; }

        /* Glassmorphism utility */
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* Hover Micro-animations */
        .hover-lift { 
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s ease; 
        }
        .hover-lift:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); 
        }

        /* Line clamping for descriptions */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;  
            overflow: hidden;
        }
    </style>
</head>
<body class="font-sans text-dark bg-gray-50 flex flex-col min-h-screen selection:bg-primary selection:text-white">

    <!-- 1. BAGIAN BERANDA (Hero Section) -->
    <header class="relative h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Image with Dark Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1555244162-803834f70033?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Suasana Catering Adelia" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/60 to-gray-900/40"></div>
        </div>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto glass p-8 md:p-14 rounded-[2.5rem] shadow-2xl animate-fade-in-up">
            <p class="text-primary font-bold tracking-widest uppercase mb-3 text-sm md:text-base">Katering Terbaik di Jepara</p>
            <div class="mb-4 flex justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Adelia Catering" class="h-48 md:h-64 object-contain drop-shadow-2xl filter transform hover:scale-105 transition-transform duration-500">
            </div>
            <h1 class="sr-only">Adelia Catering Jepara</h1>
            <p class="text-lg md:text-xl text-gray-700 mb-10 font-light max-w-2xl mx-auto">
                Menyajikan cita rasa istimewa dengan bahan segar pilihan untuk melengkapi setiap momen berharga Anda.
            </p>
            
            <a href="#katalog-menu" class="inline-flex items-center gap-2 bg-gradient-to-r from-primary to-accent hover:from-accent hover:to-orange-800 text-white font-semibold py-4 px-10 rounded-full transition-all duration-300 transform hover:scale-105 shadow-[0_10px_20px_rgba(217,119,6,0.3)]">
                <i class="fa-solid fa-bell-concierge"></i> Pesan Sekarang
            </a>
        </div>
        
        <!-- Animated Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-float z-10">
            <a href="#katalog-menu" class="flex flex-col items-center text-white/80 hover:text-white transition-colors gap-2">
                <span class="text-sm font-medium tracking-widest uppercase">Lihat Menu</span>
                <i class="fa-solid fa-chevron-down text-2xl drop-shadow-lg"></i>
            </a>
        </div>
    </header>

    <!-- 2. BAGIAN DAFTAR MENU (Katalog Produk) -->
    <section id="katalog-menu" class="py-24 bg-gradient-to-b from-secondary to-gray-50">
        <div class="container mx-auto px-4 md:px-8 max-w-7xl">
            <!-- Section Title -->
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-dark mb-4">Pilihan Menu Kami</h2>
                <div class="w-24 h-1.5 bg-primary mx-auto rounded-full mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Jelajahi ragam hidangan lezat kami mulai dari nasi kotak praktis hingga prasmanan mewah yang disiapkan khusus untuk Anda.</p>
            </div>

            <!-- Menu Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="menu-container">
                <!-- Data Menu akan dirender secara dinamis oleh JavaScript agar rapi -->
                <script>
                    const menus = [
                        { id: 1, name: "Nasi Kotak Ayam Bakar Madu", desc: "Nasi putih pulen, ayam bakar madu spesial, sambal terasi pedas, lalapan segar, dan tahu tempe.", price: 25000, img: "https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80", tag: "Terlaris" },
                        { id: 2, name: "Paket Prasmanan Executive", desc: "Cocok untuk pernikahan/acara resmi. Pilihan lauk pauk premium, sup, hidangan penutup, dan minuman.", price: 55000, img: "https://images.unsplash.com/photo-1530062845289-9109b2c9c868?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80", tag: "Premium" },
                        { id: 3, name: "Tumpeng Kuning Spesial", desc: "Tumpeng nasi kuning untuk syukuran lengkap dengan urap, ayam goreng kremes, telur iris, dan kering tempe.", price: 350000, img: "https://images.unsplash.com/photo-1621224403893-6f103b417bbd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80", tag: "Syukuran" },
                        { id: 4, name: "Snack Box Premium", desc: "Kotak cemilan eksklusif berisi 4 macam kue (manis & gurih basah) ditambah air mineral cup.", price: 15000, img: "https://images.unsplash.com/photo-1601000938259-9e92002320b2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80", tag: "Rapat / Event" },
                        { id: 5, name: "Nasi Besek Tradisional", desc: "Nasi langgi dengan kemasan besek bambu estetik yang unik dan ramah lingkungan.", price: 30000, img: "https://images.unsplash.com/photo-1548943487-a2e4142f360e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80", tag: "Estetik" },
                        { id: 6, name: "Gubukan Sate Ayam Blora", desc: "Stan makanan untuk acara berisi sate ayam khas Blora dengan bumbu kacang legit (Porsi 100 tusuk).", price: 200000, img: "https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80", tag: "Pondokan" }
                    ];

                    const menuContainer = document.getElementById('menu-container');
                    
                    menus.forEach(menu => {
                        const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(menu.price);
                        
                        const card = document.createElement('div');
                        card.className = "bg-surface rounded-3xl overflow-hidden shadow-lg border border-gray-100 hover-lift flex flex-col group relative";
                        card.innerHTML = `
                            <!-- Image Container -->
                            <div class="relative h-60 md:h-72 overflow-hidden">
                                <img src="${menu.img}" alt="${menu.name}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                
                                <!-- Label Tag -->
                                <div class="absolute top-4 right-4 bg-white/95 backdrop-blur text-primary font-bold py-1.5 px-4 rounded-full text-xs uppercase tracking-wide shadow-md">
                                    ${menu.tag}
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6 md:p-8 flex-grow flex flex-col justify-between relative z-10 bg-surface">
                                <div class="mb-6">
                                    <h3 class="text-xl md:text-2xl font-serif font-bold text-dark mb-3 group-hover:text-primary transition-colors">${menu.name}</h3>
                                    <p class="text-gray-500 text-sm leading-relaxed line-clamp-2">${menu.desc}</p>
                                </div>
                                <div class="flex items-center justify-between mt-auto">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-400 font-medium">Mulai dari</span>
                                        <span class="text-xl md:text-2xl font-bold text-primary">${formattedPrice}</span>
                                    </div>
                                    <button type="button" onclick="addToCart(${menu.id}, '${menu.name}', ${menu.price})" class="bg-gray-100 hover:bg-primary text-dark hover:text-white font-medium py-3 px-5 rounded-2xl transition-all duration-300 active:scale-95 shadow-sm group-hover:shadow-md">
                                        <i class="fa-solid fa-cart-plus md:mr-2"></i> <span class="hidden md:inline">Tambah</span>
                                    </button>
                                </div>
                            </div>
                        `;
                        menuContainer.appendChild(card);
                    });
                </script>
            </div>
        </div>
    </section>

    <!-- 3. BAGIAN KERANJANG & FORM CHECKOUT -->
    <section id="checkout-section" class="py-24 bg-white relative overflow-hidden">
        <!-- Dekorasi Background -->
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-secondary rounded-full mix-blend-multiply filter blur-3xl opacity-40 z-0 transform translate-x-1/3 -translate-y-1/3 pointer-events-none"></div>

        <div class="container mx-auto px-4 md:px-8 max-w-7xl relative z-10">
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-10">
                
                <!-- KOLOM KIRI: Form Detail Pengiriman -->
                <div class="xl:col-span-7 space-y-8">
                    <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 md:p-10 border border-gray-100">
                        <div class="mb-8 border-b border-gray-100 pb-6">
                            <h2 class="text-3xl font-serif font-bold text-dark flex items-center gap-4">
                                <span class="bg-primary/10 text-primary p-3 rounded-2xl"><i class="fa-solid fa-clipboard-list"></i></span>
                                Form Pemesanan
                            </h2>
                            <p class="text-gray-500 mt-2 ml-16">Lengkapi data di bawah ini untuk kelancaran pengiriman catering.</p>
                        </div>
                        
                        <form id="checkout-form" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="customerName">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" id="customerName" required class="w-full px-5 py-4 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none" placeholder="Cth: Budi Santoso">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="customerWa">Nomor WhatsApp <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                            <span class="text-gray-400"><i class="fa-brands fa-whatsapp text-lg"></i></span>
                                        </div>
                                        <input type="tel" id="customerWa" required class="w-full pl-12 pr-5 py-4 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none" placeholder="081234567890">
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="eventDate">Tanggal & Waktu Acara <span class="text-red-500">*</span></label>
                                <input type="datetime-local" id="eventDate" required class="w-full px-5 py-4 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none text-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="customerAddress">Alamat Pengiriman (Area Jepara) <span class="text-red-500">*</span></label>
                                <textarea id="customerAddress" rows="3" required class="w-full px-5 py-4 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none resize-none" placeholder="Sertakan detail alamat, patokan jalan, atau nama gedung..."></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" for="customerNotes">Catatan Tambahan (Opsional)</label>
                                <textarea id="customerNotes" rows="2" class="w-full px-5 py-4 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none resize-none" placeholder="Cth: Sambal dipisah, porsi VIP bungkus sendiri, dll."></textarea>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- KOLOM KANAN: Ringkasan Keranjang & Fitur Utama (Kalkulator DP) -->
                <div class="xl:col-span-5">
                    <div class="bg-gray-50 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 md:p-10 border border-gray-100 xl:sticky xl:top-10">
                        <h3 class="text-2xl font-serif font-bold mb-6 flex items-center justify-between pb-4 border-b border-gray-200">
                            <span class="flex items-center gap-3">
                                <i class="fa-solid fa-basket-shopping text-primary"></i> Keranjang
                            </span>
                            <span id="cart-badge" class="bg-primary text-white text-sm px-3 py-1 rounded-full font-sans">0 Item</span>
                        </h3>
                        
                        <!-- List Item Keranjang -->
                        <div id="cart-items" class="space-y-4 max-h-[280px] overflow-y-auto pr-2 mb-6 custom-scrollbar">
                            <div class="text-center bg-white py-10 rounded-2xl border border-dashed border-gray-300" id="empty-cart-msg">
                                <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-bag-shopping text-2xl"></i>
                                </div>
                                <p class="text-gray-500 font-medium">Keranjang masih kosong.</p>
                                <p class="text-sm text-gray-400 mt-1">Silakan pilih menu katering di atas.</p>
                            </div>
                            <!-- Items dinamis masuk ke sini -->
                        </div>

                        <!-- Subtotal -->
                        <div class="bg-white p-5 rounded-2xl mb-6 shadow-sm border border-gray-100">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Total Pesanan</span>
                                <span class="text-2xl font-bold text-dark" id="display-total">Rp 0</span>
                            </div>
                        </div>

                        <!-- FITUR UTAMA: Kalkulator DP (Radio Buttons) -->
                        <div class="mb-8">
                            <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-wallet text-gray-400"></i> Pilihan Pembayaran
                            </h4>
                            <div class="grid grid-cols-3 gap-3">
                                <!-- DP 30% -->
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="dp_scheme" value="0.3" class="peer sr-only" onchange="calculateDP()" checked>
                                    <div class="rounded-xl border border-gray-200 px-2 py-4 text-center peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:ring-1 peer-checked:ring-primary transition-all bg-white hover:bg-gray-50">
                                        <div class="font-bold text-gray-700 peer-checked:text-primary">DP 30%</div>
                                    </div>
                                </label>
                                <!-- DP 50% -->
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="dp_scheme" value="0.5" class="peer sr-only" onchange="calculateDP()">
                                    <div class="rounded-xl border border-gray-200 px-2 py-4 text-center peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:ring-1 peer-checked:ring-primary transition-all bg-white hover:bg-gray-50">
                                        <div class="font-bold text-gray-700 peer-checked:text-primary">DP 50%</div>
                                    </div>
                                </label>
                                <!-- Lunas -->
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="dp_scheme" value="1" class="peer sr-only" onchange="calculateDP()">
                                    <div class="rounded-xl border border-gray-200 px-2 py-4 text-center peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:ring-1 peer-checked:ring-primary transition-all bg-white hover:bg-gray-50">
                                        <div class="font-bold text-gray-700 peer-checked:text-primary">Lunas</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Kalkulasi Hasil Payment -->
                        <div class="bg-gradient-to-br from-primary to-accent p-6 rounded-2xl mb-8 text-white relative overflow-hidden shadow-lg">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
                            <div class="flex justify-between items-center relative z-10">
                                <span class="font-medium text-white/90">Nominal Transfer:</span>
                                <span class="text-3xl font-bold tracking-tight shadow-sm" id="display-dp">Rp 0</span>
                            </div>
                        </div>

                        <!-- 4. Tombol Aksi WhatsApp Redirect -->
                        <button onclick="processCheckout()" class="w-full bg-[#25D366] hover:bg-[#20bd5a] text-white font-bold py-5 px-6 rounded-2xl transition-all duration-300 transform active:scale-95 shadow-[0_8px_20px_rgba(37,211,102,0.3)] flex items-center justify-center text-lg gap-3">
                            <i class="fa-brands fa-whatsapp text-2xl"></i> Pesan via WhatsApp
                        </button>
                        <p class="text-center text-xs text-gray-500 mt-4"><i class="fa-solid fa-lock text-gray-400 mr-1"></i> Data Anda aman dan langsung terkirim ke WhatsApp Admin kami.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-16 pb-8">
        <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-12">
                <div>
                    <a href="{{ url('/') }}" class="inline-block mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Adelia" class="h-16 sm:h-20 lg:h-24 object-contain filter drop-shadow-lg hover:scale-105 transition-transform duration-300">
                    </a>
                    <p class="text-gray-400 leading-relaxed mb-6">Mitra kuliner terpercaya di Jepara. Kami melayani berbagai macam pesanan katering untuk acara pernikahan, syukuran, kantor, dan harian.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-secondary">Hubungi Kami</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-start gap-3"><i class="fa-solid fa-location-dot mt-1 text-primary"></i> Jl. Pemuda No. 123, Jepara, Jawa Tengah</li>
                        <li class="flex items-center gap-3"><i class="fa-brands fa-whatsapp text-primary"></i> 0812-3456-7890</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-envelope text-primary"></i> halo@adeliacatering.com</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-secondary">Jam Operasional</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex justify-between"><span>Senin - Jumat:</span> <span>08.00 - 17.00</span></li>
                        <li class="flex justify-between"><span>Sabtu:</span> <span>08.00 - 15.00</span></li>
                        <li class="flex justify-between"><span>Minggu:</span> <span class="text-primary font-medium">Libur (Kecuali ada pesanan)</span></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-gray-500 text-sm">
                <p>&copy; 2026 Adelia Catering Jepara. All rights reserved.</p>
                <div class="flex gap-4 mt-4 md:mt-0 text-lg">
                    <a href="#" class="hover:text-primary transition-colors"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="hover:text-primary transition-colors"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="hover:text-primary transition-colors"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Vanilla JavaScript DOM Manipulation & Logic -->
    <script>
        // State Management
        let cart = [];
        let totalAmount = 0;

        // Utility: Format IDR
        const formatIDR = (number) => {
            return new Intl.NumberFormat('id-ID', { 
                style: 'currency', 
                currency: 'IDR', 
                maximumFractionDigits: 0 
            }).format(number);
        };

        // DOM Elements setup
        const cartContainer = document.getElementById('cart-items');
        const emptyMsg = document.getElementById('empty-cart-msg');
        const displayTotal = document.getElementById('display-total');
        const displayDp = document.getElementById('display-dp');
        const cartBadge = document.getElementById('cart-badge');

        // Logic: Add to Cart
        function addToCart(id, name, price) {
            const existingItem = cart.find(item => item.id === id);
            
            if (existingItem) {
                existingItem.qty += 1;
            } else {
                cart.push({ id, name, price, qty: 1 });
            }
            
            updateCartUI();
            
            // Visual Feedback (Scroll to cart mobile subtly if needed, but a toast is better. 
            // We'll update the button temporarily as feedback)
            const eventTarget = event.currentTarget;
            const originalHTML = eventTarget.innerHTML;
            eventTarget.innerHTML = '<i class="fa-solid fa-check text-green-500"></i> Ditambahkan';
            setTimeout(() => {
                eventTarget.innerHTML = originalHTML;
            }, 1000);
        }

        // Logic: Update Quantity (+ / -)
        function updateQty(id, change) {
            const index = cart.findIndex(item => item.id === id);
            if (index !== -1) {
                cart[index].qty += change;
                // Remove item if qty becomes 0
                if (cart[index].qty <= 0) {
                    cart.splice(index, 1);
                }
                updateCartUI();
            }
        }

        // Logic: Re-render Cart UI
        function updateCartUI() {
            // Reset container EXCEPT empty message
            Array.from(cartContainer.children).forEach(child => {
                if(child.id !== 'empty-cart-msg') child.remove();
            });
            
            totalAmount = 0;
            let totalItems = 0;

            if (cart.length === 0) {
                emptyMsg.style.display = 'block';
                cartBadge.textContent = '0 Item';
            } else {
                emptyMsg.style.display = 'none';
                
                cart.forEach(item => {
                    const itemSubtotal = item.price * item.qty;
                    totalAmount += itemSubtotal;
                    totalItems += item.qty;

                    const cartRow = document.createElement('div');
                    cartRow.className = 'flex justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:border-primary/30 transition-colors';
                    cartRow.innerHTML = `
                        <div class="flex-1 pr-3">
                            <h5 class="font-semibold text-sm text-dark line-clamp-1">${item.name}</h5>
                            <div class="text-primary text-sm font-bold mt-1">${formatIDR(item.price)}</div>
                        </div>
                        <div class="flex items-center space-x-3 bg-gray-50 rounded-lg p-1.5 border border-gray-200">
                            <button onclick="updateQty(${item.id}, -1)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:text-white hover:bg-red-500 rounded-md transition-colors"><i class="fa-solid fa-minus text-xs"></i></button>
                            <span class="text-sm font-bold w-4 text-center text-dark">${item.qty}</span>
                            <button onclick="updateQty(${item.id}, 1)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:text-white hover:bg-green-500 rounded-md transition-colors"><i class="fa-solid fa-plus text-xs"></i></button>
                        </div>
                    `;
                    cartContainer.appendChild(cartRow);
                });
                
                cartBadge.textContent = `${totalItems} Item`;
            }

            displayTotal.innerText = formatIDR(totalAmount);
            calculateDP(); // Auto-calculate DP when cart updates
        }

        // FITUR UTAMA: Logic DP Calculator
        function calculateDP() {
            const schemeRadios = document.getElementsByName('dp_scheme');
            let selectedPercentage = 0.3; // Default 30%
            
            for (let radio of schemeRadios) {
                if (radio.checked) {
                    selectedPercentage = parseFloat(radio.value);
                    break;
                }
            }

            const dpAmount = totalAmount * selectedPercentage;
            
            // Animasi kecil saat angka berubah
            displayDp.parentElement.classList.add('scale-105');
            setTimeout(() => {
                displayDp.parentElement.classList.remove('scale-105');
            }, 200);

            displayDp.innerText = formatIDR(dpAmount);
        }

        // 4. LOGIKA WHATSAPP REDIRECT
        function processCheckout() {
            // 1. Validasi Keranjang
            if (cart.length === 0) {
                alert('Keranjang Anda masih kosong. Silakan pilih menu minimal 1 item.');
                document.getElementById('katalog-menu').scrollIntoView({behavior: 'smooth'});
                return;
            }

            // 2. Ambil Data Form
            const name = document.getElementById('customerName').value.trim();
            const wa = document.getElementById('customerWa').value.trim();
            const date = document.getElementById('eventDate').value; // datetime-local format
            const address = document.getElementById('customerAddress').value.trim();
            const notes = document.getElementById('customerNotes').value.trim();

            // 3. Validasi Form
            if (!name || !wa || !date || !address) {
                alert('Mohon lengkapi Form Pemesanan (Nama, WA, Tanggal Acara, dan Alamat).');
                // Scroll focus to form
                document.getElementById('customerName').focus();
                return;
            }

            // Mempercantik format tanggal jika memungkinkan (opsional)
            let formattedDate = date.replace('T', ' Jam ');

            // 4. Hitung DP fix
            const schemeRadios = document.getElementsByName('dp_scheme');
            let dpLabel = "DP 30%";
            let dpMultiplier = 0.3;
            
            for (let radio of schemeRadios) {
                if (radio.checked) {
                    dpMultiplier = parseFloat(radio.value);
                    if (dpMultiplier === 0.3) dpLabel = "DP 30%";
                    if (dpMultiplier === 0.5) dpLabel = "DP 50%";
                    if (dpMultiplier === 1) dpLabel = "Pelunasan Penuh (100%)";
                }
            }
            const dpAmountFixed = totalAmount * dpMultiplier;

            // 5. Build WhatsApp Text Message (URL Encoded later)
            // Menggunakan %0A untuk enter / new line di URL WhatsApp
            let waText = `Halo *Adelia Catering Jepara*, saya ingin memesan catering. Berikut detail pesanan saya:%0A%0A`;
            
            waText += `📋 *DATA PEMESAN*%0A`;
            waText += `Nama : ${name}%0A`;
            waText += `No. WA : ${wa}%0A`;
            waText += `Waktu Acara : ${formattedDate}%0A`;
            waText += `Alamat Pengiriman : %0A_${address}_%0A`;
            
            if (notes) {
                waText += `Catatan Khusus : ${notes}%0A`;
            }
            
            waText += `%0A🛍️ *DETAIL PESANAN*%0A`;
            cart.forEach((item, index) => {
                const sub = formatIDR(item.price * item.qty);
                waText += `${index + 1}. ${item.name} *(x${item.qty})*%0A   Subtotal: ${sub}%0A`;
            });

            waText += `%0A💳 *RINGKASAN BIAYA*%0A`;
            waText += `Total Keseluruhan : *${formatIDR(totalAmount)}*%0A`;
            waText += `Metode Pembayaran : *${dpLabel}*%0A`;
            waText += `Nominal Transfer : *${formatIDR(dpAmountFixed)}*%0A%0A`;
            
            waText += `Mohon segera diproses dan diinformasikan nomor rekening untuk pembayarannya. Terima kasih! 🙏`;

            // 6. Redirect ke WhatsApp
            const targetWhatsAppNumber = "6281234567890"; // Ganti ke nomor admin sebenarnya
            const waURL = `https://wa.me/${targetWhatsAppNumber}?text=${waText}`;
            
            // Buka tab baru menuju WA API
            window.open(waURL, '_blank');
        }
    </script>
</body>
</html>
