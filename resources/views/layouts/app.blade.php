<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adelia Catering Jepara | Cita Rasa Istimewa</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="Pesan katering terbaik di Jepara untuk berbagai acara.">
    
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
                        primary: '#d97706', // Amber 600
                        secondary: '#fef3c7', // Amber 50
                        accent: '#b45309', // Amber 700
                        dark: '#1f2937', // Gray 800
                        surface: '#ffffff'
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    animation: {
                        'float': 'float 3s ease-in-out infinite',
                        'fade-in-up': 'fadeInUp 1s ease-out forwards',
                        'fade-in-down': 'fadeInDown 0.3s ease-out forwards'
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeInDown: {
                            '0%': { opacity: '0', transform: 'translateY(-10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        html { scroll-behavior: smooth; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #fef3c7; }
        ::-webkit-scrollbar-thumb { background: #d97706; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #b45309; }

        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .hover-lift { 
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s ease; 
        }
        
        /* Desktop hover effect */
        @media (hover: hover) and (pointer: fine) {
            .hover-lift:hover { 
                transform: translateY(-8px); 
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); 
            }
        }
        
        /* Mobile touch feedback */
        @media (hover: none) and (pointer: coarse) {
            .hover-lift:active { 
                transform: scale(0.98); 
            }
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;  
            overflow: hidden;
        }
        
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;  
            overflow: hidden;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1; 
            border-radius: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
        
        /* Better touch targets for mobile */
        @media (max-width: 640px) {
            button, a {
                min-height: 44px;
                min-width: 44px;
            }
        }
        
        /* Prevent text selection on buttons */
        button {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        /* Smooth transitions for mobile interactions */
        * {
            -webkit-tap-highlight-color: transparent;
        }
    </style>
    @yield('custom_scripts')
</head>
<body class="font-sans text-dark bg-gray-50 flex flex-col min-h-screen selection:bg-primary selection:text-white">

    <!-- Navbar -->
    <nav class="glass sticky top-0 z-50 px-4 sm:px-6 py-3 sm:py-4 transition-all duration-300">
        <div class="container mx-auto flex justify-between items-center max-w-7xl">
            <a href="{{ url('/') }}" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Adelia" class="h-12 sm:h-14 md:h-16 object-contain hover:scale-105 transition-transform">
            </a>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'text-primary font-bold' : 'text-gray-700 hover:text-primary font-medium' }} transition text-sm lg:text-base">Beranda</a>
                <a href="{{ url('/menu') }}" class="{{ request()->is('menu') ? 'text-primary font-bold' : 'text-gray-700 hover:text-primary font-medium' }} transition text-sm lg:text-base">Daftar Menu</a>
                
                <a href="{{ url('/checkout') }}" class="relative text-dark hover:text-primary bg-primary/10 hover:bg-primary/20 px-4 py-2 rounded-full transition-all flex items-center gap-2 group">
                    <i class="fa-solid fa-basket-shopping"></i>
                    <span class="font-semibold text-sm">Keranjang</span>
                    <span id="nav-cart-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold group-hover:scale-110 transition-transform hidden">0</span>
                </a>
            </div>
            
            <!-- Mobile Menu -->
            <div class="flex md:hidden items-center gap-2">
                <a href="{{ url('/checkout') }}" class="relative text-dark hover:text-primary bg-primary/10 hover:bg-primary/20 px-3 py-2 rounded-full transition-all flex items-center gap-1.5 group">
                    <i class="fa-solid fa-basket-shopping text-sm"></i>
                    <span id="nav-cart-badge-mobile" class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] px-1 py-0.5 rounded-full font-bold group-hover:scale-110 transition-transform hidden">0</span>
                </a>
                
                <button id="mobile-menu-button" class="text-dark hover:text-primary p-2 rounded-lg hover:bg-primary/10 transition-all">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu" class="hidden md:hidden absolute top-full left-0 right-0 bg-white shadow-lg border-t border-gray-100 animate-fade-in-down">
            <div class="container mx-auto px-4 py-4 space-y-2">
                <a href="{{ url('/') }}" class="block px-4 py-3 rounded-lg {{ request()->is('/') ? 'bg-primary/10 text-primary font-bold' : 'text-gray-700 hover:bg-gray-50' }} transition-all">
                    <i class="fa-solid fa-home mr-3"></i> Beranda
                </a>
                <a href="{{ url('/menu') }}" class="block px-4 py-3 rounded-lg {{ request()->is('menu') ? 'bg-primary/10 text-primary font-bold' : 'text-gray-700 hover:bg-gray-50' }} transition-all">
                    <i class="fa-solid fa-utensils mr-3"></i> Daftar Menu
                </a>
                <a href="{{ url('/checkout') }}" class="block px-4 py-3 rounded-lg {{ request()->is('checkout') ? 'bg-primary/10 text-primary font-bold' : 'text-gray-700 hover:bg-gray-50' }} transition-all">
                    <i class="fa-solid fa-shopping-cart mr-3"></i> Keranjang & Checkout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer (Hidden on Homepage) -->
    @if(!request()->is('/'))
    <footer class="relative overflow-hidden" style="background: #fdf6ee;">
        <!-- Subtle top border -->
        <div class="h-px" style="background: linear-gradient(to right, transparent, rgba(217,119,6,0.2), transparent);"></div>

        <div class="container mx-auto px-5 sm:px-6 lg:px-8 max-w-6xl">

            <!-- Main Footer -->
            <div class="py-10 sm:py-14 lg:py-16">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-10 lg:gap-16">

                    <!-- Brand Column -->
                    <div class="md:col-span-4">
                        <a href="{{ url('/') }}" class="inline-block mb-4">
                            <img src="{{ asset('images/logo.png') }}" alt="Adelia Catering" class="h-12 sm:h-14 object-contain hover:scale-105 transition-transform duration-300">
                        </a>
                        <p class="text-xs sm:text-sm leading-relaxed max-w-xs" style="color: #a0855e;">
                            Cita rasa istimewa untuk setiap momen berharga Anda. Melayani Jepara dan sekitarnya.
                        </p>

                        <!-- Social Icons -->
                        <div class="flex items-center gap-2 mt-5">
                            <a href="https://instagram.com" target="_blank" class="w-9 h-9 rounded-xl border flex items-center justify-center transition-all duration-300" style="background:rgba(180,130,70,0.06); border-color:rgba(180,130,70,0.12); color:#b8956a;" onmouseover="this.style.color='#ea580c'; this.style.borderColor='#ea580c'" onmouseout="this.style.color='#b8956a'; this.style.borderColor='rgba(180,130,70,0.12)'">
                                <i class="fa-brands fa-instagram text-sm"></i>
                            </a>
                            <a href="https://facebook.com" target="_blank" class="w-9 h-9 rounded-xl border flex items-center justify-center transition-all duration-300" style="background:rgba(180,130,70,0.06); border-color:rgba(180,130,70,0.12); color:#b8956a;" onmouseover="this.style.color='#ea580c'; this.style.borderColor='#ea580c'" onmouseout="this.style.color='#b8956a'; this.style.borderColor='rgba(180,130,70,0.12)'">
                                <i class="fa-brands fa-facebook text-sm"></i>
                            </a>
                            <a href="https://tiktok.com" target="_blank" class="w-9 h-9 rounded-xl border flex items-center justify-center transition-all duration-300" style="background:rgba(180,130,70,0.06); border-color:rgba(180,130,70,0.12); color:#b8956a;" onmouseover="this.style.color='#ea580c'; this.style.borderColor='#ea580c'" onmouseout="this.style.color='#b8956a'; this.style.borderColor='rgba(180,130,70,0.12)'">
                                <i class="fa-brands fa-tiktok text-sm"></i>
                            </a>
                            <a href="https://wa.me/6281234567890" target="_blank" class="w-9 h-9 rounded-xl border flex items-center justify-center transition-all duration-300" style="background:rgba(180,130,70,0.06); border-color:rgba(180,130,70,0.12); color:#b8956a;" onmouseover="this.style.color='#16a34a'; this.style.borderColor='#16a34a'" onmouseout="this.style.color='#b8956a'; this.style.borderColor='rgba(180,130,70,0.12)'">
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Links Columns -->
                    <div class="md:col-span-4">
                        <div class="grid grid-cols-2 gap-6 sm:gap-8">
                            <div>
                                <h4 class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider mb-3 sm:mb-4" style="color:#8b6d47;">Navigasi</h4>
                                <ul class="space-y-2 sm:space-y-2.5">
                                    <li><a href="{{ url('/') }}" class="text-xs sm:text-sm transition-colors duration-200" style="color:#a0855e;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#a0855e'">Beranda</a></li>
                                    <li><a href="{{ url('/menu') }}" class="text-xs sm:text-sm transition-colors duration-200" style="color:#a0855e;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#a0855e'">Daftar Menu</a></li>
                                    <li><a href="{{ url('/checkout') }}" class="text-xs sm:text-sm transition-colors duration-200" style="color:#a0855e;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#a0855e'">Keranjang</a></li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider mb-3 sm:mb-4" style="color:#8b6d47;">Layanan</h4>
                                <ul class="space-y-2 sm:space-y-2.5">
                                    <li><a href="{{ url('/menu') }}" class="text-xs sm:text-sm transition-colors duration-200" style="color:#a0855e;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#a0855e'">Nasi Kotak</a></li>
                                    <li><a href="{{ url('/menu') }}" class="text-xs sm:text-sm transition-colors duration-200" style="color:#a0855e;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#a0855e'">Prasmanan</a></li>
                                    <li><a href="{{ url('/menu') }}" class="text-xs sm:text-sm transition-colors duration-200" style="color:#a0855e;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#a0855e'">Snack Box</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Column -->
                    <div class="md:col-span-4">
                        <h4 class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider mb-3 sm:mb-4" style="color:#8b6d47;">Kontak</h4>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3">
                                <i class="fa-solid fa-location-dot text-xs mt-0.5 shrink-0" style="color:#d97706;"></i>
                                <span class="text-xs sm:text-sm leading-relaxed" style="color:#a0855e;">Jl. Pemuda No. 123, Jepara, Jawa Tengah</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fa-brands fa-whatsapp text-xs shrink-0" style="color:#d97706;"></i>
                                <a href="tel:081234567890" class="text-xs sm:text-sm transition-colors" style="color:#a0855e;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#a0855e'">0812-3456-7890</a>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fa-solid fa-envelope text-xs shrink-0" style="color:#d97706;"></i>
                                <a href="mailto:halo@adeliacatering.com" class="text-xs sm:text-sm transition-colors" style="color:#a0855e;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#a0855e'">halo@adeliacatering.com</a>
                            </li>
                            <li class="flex items-center gap-3 pt-2">
                                <i class="fa-solid fa-clock text-xs shrink-0" style="color:#d97706;"></i>
                                <span class="text-xs sm:text-sm" style="color:#a0855e;">Sen–Sab · 08.00–17.00</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="py-5 sm:py-6" style="border-top: 1px solid rgba(180,130,70,0.12);">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-[10px] sm:text-xs" style="color:#c4a67a;">
                    <p>&copy; {{ date('Y') }} Adelia Catering Jepara. All rights reserved.</p>
                    <p class="flex items-center gap-1.5">
                        Made with <span style="color:#ea580c;">♥</span> in Jepara
                    </p>
                </div>
            </div>

        </div>
    </footer>
    @endif

    <!-- Global Cart Script for LocalStorage -->
    <script>
        const CART_KEY = 'adelia_catering_cart';
        
        function getCart() {
            return JSON.parse(localStorage.getItem(CART_KEY)) || [];
        }
        
        function saveCart(cart) {
            localStorage.setItem(CART_KEY, JSON.stringify(cart));
            updateNavBadge();
        }
        
        function updateNavBadge() {
            const cart = getCart();
            const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);
            const badge = document.getElementById('nav-cart-badge');
            const badgeMobile = document.getElementById('nav-cart-badge-mobile');
            
            [badge, badgeMobile].forEach(el => {
                if (el) {
                    el.innerText = totalQty;
                    el.classList.toggle('hidden', totalQty === 0);
                }
            });
        }

        const formatIDR = (number) => {
            return new Intl.NumberFormat('id-ID', { 
                style: 'currency', 
                currency: 'IDR', 
                maximumFractionDigits: 0 
            }).format(number);
        };
        
        // Newsletter handler
        function handleNewsletter(event) {
            event.preventDefault();
            const email = document.getElementById('newsletter-email').value;
            const toast = document.getElementById('newsletter-toast');
            toast.classList.remove('translate-y-32');
            toast.classList.add('translate-y-0');
            document.getElementById('newsletter-email').value = '';
            setTimeout(() => {
                toast.classList.remove('translate-y-0');
                toast.classList.add('translate-y-32');
            }, 3000);
            console.log('Newsletter subscription:', email);
        }
        
        // Mobile Menu Toggle + Badge Init
        document.addEventListener('DOMContentLoaded', () => {
            updateNavBadge();
            
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                    const icon = mobileMenuButton.querySelector('i');
                    if (mobileMenu.classList.contains('hidden')) {
                        icon.classList.replace('fa-times', 'fa-bars');
                    } else {
                        icon.classList.replace('fa-bars', 'fa-times');
                    }
                });
                
                // Tutup saat klik di luar
                document.addEventListener('click', (e) => {
                    if (!mobileMenuButton.contains(e.target) && !mobileMenu.contains(e.target)) {
                        mobileMenu.classList.add('hidden');
                        const icon = mobileMenuButton.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
                
                // Tutup saat klik link
                mobileMenu.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', () => {
                        mobileMenu.classList.add('hidden');
                        const icon = mobileMenuButton.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    });
                });
            }
        });
    </script>

    <!-- ============================================================ -->
    <!-- AI CHATBOT WIDGET -->
    <!-- ============================================================ -->
    <style>
        .chatbot-bubble {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 32px rgba(249, 115, 22, 0.35);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            outline: none;
        }
        .chatbot-bubble:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 40px rgba(249, 115, 22, 0.5);
        }
        .chatbot-bubble .pulse-ring {
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px solid rgba(249, 115, 22, 0.4);
            animation: chatPulse 2s ease-out infinite;
        }
        @keyframes chatPulse {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        .chatbot-window {
            position: fixed;
            bottom: 92px;
            right: 24px;
            z-index: 9998;
            width: 380px;
            max-width: calc(100vw - 32px);
            height: 520px;
            max-height: calc(100vh - 120px);
            border-radius: 24px;
            background: #0f0f0f;
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transform: scale(0.9) translateY(20px);
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .chatbot-window.open {
            transform: scale(1) translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        .chat-header {
            padding: 16px 20px;
            background: linear-gradient(135deg, rgba(249,115,22,0.15), rgba(234,88,12,0.08));
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .chat-avatar {
            width: 40px; height: 40px;
            border-radius: 14px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }
        .chat-messages::-webkit-scrollbar { width: 4px; }
        .chat-messages::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

        .chat-msg { display: flex; gap: 8px; max-width: 88%; animation: msgIn 0.3s ease-out; }
        .chat-msg.bot { align-self: flex-start; }
        .chat-msg.user { align-self: flex-end; flex-direction: row-reverse; }

        .chat-msg .msg-content {
            padding: 10px 14px;
            border-radius: 16px;
            font-size: 13px;
            line-height: 1.6;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .chat-msg.bot .msg-content {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.8);
            border-bottom-left-radius: 4px;
        }
        .chat-msg.user .msg-content {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            border-bottom-right-radius: 4px;
        }
        .chat-msg .msg-content strong { color: #fff; font-weight: 700; }
        .chat-msg .msg-content a { color: #f97316; text-decoration: underline; }
        .chat-msg.user .msg-content a { color: #fff; }

        .chat-input-area {
            padding: 12px 16px;
            border-top: 1px solid rgba(255,255,255,0.06);
            display: flex;
            gap: 8px;
            background: rgba(255,255,255,0.02);
        }
        .chat-input-area input {
            flex: 1;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            padding: 10px 16px;
            color: white;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s;
        }
        .chat-input-area input::placeholder { color: rgba(255,255,255,0.25); }
        .chat-input-area input:focus { border-color: rgba(249,115,22,0.5); }
        .chat-input-area button {
            width: 40px; height: 40px;
            border-radius: 14px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            border: none;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .chat-input-area button:hover { transform: scale(1.05); }
        .chat-input-area button:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        .typing-indicator {
            display: flex; gap: 4px; padding: 12px 16px; align-self: flex-start;
        }
        .typing-indicator span {
            width: 6px; height: 6px; border-radius: 50%;
            background: rgba(255,255,255,0.3);
            animation: typing 1.4s infinite ease-in-out;
        }
        .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
        .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.3; }
            30% { transform: translateY(-8px); opacity: 1; }
        }
        @keyframes msgIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Quick suggestions */
        .chat-suggestions {
            display: flex; flex-wrap: wrap; gap: 6px; padding: 0 16px 12px;
        }
        .chat-suggestions button {
            padding: 6px 12px;
            border-radius: 20px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.5);
            font-size: 11px;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .chat-suggestions button:hover {
            background: rgba(249,115,22,0.15);
            border-color: rgba(249,115,22,0.3);
            color: #f97316;
        }
    </style>

    <!-- Chat Bubble -->
    <button class="chatbot-bubble" id="chatBubble" onclick="toggleChatbot()" aria-label="Buka chatbot">
        <div class="pulse-ring"></div>
        <i class="fa-solid fa-comment-dots text-xl" id="bubbleIcon"></i>
    </button>

    <!-- Chat Window -->
    <div class="chatbot-window" id="chatWindow">
        <!-- Header -->
        <div class="chat-header">
            <div class="chat-avatar">🤖</div>
            <div class="flex-1 min-w-0">
                <h4 class="text-white font-bold text-sm">Adelia Assistant</h4>
                <p class="text-white/40 text-[10px] flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full inline-block"></span>
                    Online — siap membantu
                </p>
            </div>
            <button onclick="toggleChatbot()" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center text-white/30 hover:text-white transition-all">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Messages -->
        <div class="chat-messages" id="chatMessages"></div>

        <!-- Quick Suggestions -->
        <div class="chat-suggestions" id="chatSuggestions">
            <button onclick="sendQuick('Lihat menu')">🍽️ Menu</button>
            <button onclick="sendQuick('Menu terlaris')">⭐ Terlaris</button>
            <button onclick="sendQuick('Cara pesan')">🛒 Cara Pesan</button>
            <button onclick="sendQuick('Kontak')">📞 Kontak</button>
            <button onclick="sendQuick('Jam buka')">⏰ Jam Buka</button>
        </div>

        <!-- Input -->
        <div class="chat-input-area">
            <input type="text" id="chatInput" placeholder="Ketik pesan..." autocomplete="off"
                onkeydown="if(event.key==='Enter') sendMessage()">
            <button onclick="sendMessage()" id="chatSendBtn">
                <i class="fa-solid fa-paper-plane text-sm"></i>
            </button>
        </div>
    </div>

    <script>
        let chatOpen = false;
        let chatInitialized = false;

        function toggleChatbot() {
            chatOpen = !chatOpen;
            const win = document.getElementById('chatWindow');
            const icon = document.getElementById('bubbleIcon');
            const pulse = document.querySelector('.pulse-ring');

            win.classList.toggle('open', chatOpen);
            icon.className = chatOpen ? 'fa-solid fa-xmark text-xl' : 'fa-solid fa-comment-dots text-xl';
            if (pulse) pulse.style.display = chatOpen ? 'none' : 'block';

            if (chatOpen && !chatInitialized) {
                chatInitialized = true;
                addBotMessage("Halo! 👋 Saya **Adelia Assistant**, siap membantu Anda.\n\nMau tanya tentang menu, harga, atau cara pesan? Ketik saja atau pilih topik di bawah! 😊");
            }

            if (chatOpen) {
                setTimeout(() => document.getElementById('chatInput').focus(), 300);
            }
        }

        function addBotMessage(text) {
            const container = document.getElementById('chatMessages');
            const div = document.createElement('div');
            div.className = 'chat-msg bot';
            div.innerHTML = `<div class="msg-content">${renderMarkdown(text)}</div>`;
            container.appendChild(div);
            scrollChat();
        }

        function addUserMessage(text) {
            const container = document.getElementById('chatMessages');
            const div = document.createElement('div');
            div.className = 'chat-msg user';
            div.innerHTML = `<div class="msg-content">${escapeHtml(text)}</div>`;
            container.appendChild(div);
            scrollChat();
        }

        function showTyping() {
            const container = document.getElementById('chatMessages');
            const div = document.createElement('div');
            div.className = 'typing-indicator';
            div.id = 'typingIndicator';
            div.innerHTML = '<span></span><span></span><span></span>';
            container.appendChild(div);
            scrollChat();
        }

        function hideTyping() {
            const el = document.getElementById('typingIndicator');
            if (el) el.remove();
        }

        function scrollChat() {
            const container = document.getElementById('chatMessages');
            setTimeout(() => container.scrollTop = container.scrollHeight, 50);
        }

        function renderMarkdown(text) {
            return text
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/_(.*?)_/g, '<em>$1</em>')
                .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank">$1</a>')
                .replace(/\n/g, '<br>');
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        async function sendMessage() {
            const input = document.getElementById('chatInput');
            const text = input.value.trim();
            if (!text) return;

            input.value = '';
            addUserMessage(text);

            // Hide suggestions after first message
            document.getElementById('chatSuggestions').style.display = 'none';

            showTyping();
            document.getElementById('chatSendBtn').disabled = true;

            try {
                const res = await fetch('/api/chatbot', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: text })
                });
                const data = await res.json();
                hideTyping();
                addBotMessage(data.reply || 'Maaf, terjadi kesalahan.');
            } catch (err) {
                hideTyping();
                addBotMessage('Maaf, terjadi kesalahan koneksi. Coba lagi nanti ya! 🙏');
            }

            document.getElementById('chatSendBtn').disabled = false;
            document.getElementById('chatInput').focus();
        }

        function sendQuick(text) {
            document.getElementById('chatInput').value = text;
            sendMessage();
        }
    </script>

</body>
</html>
