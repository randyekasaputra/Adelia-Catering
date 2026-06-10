<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adelia Catering | Ruang Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script>
        if (localStorage.getItem('admin-sidebar-collapsed') === 'true') {
            document.documentElement.classList.add('sidebar-collapsed');
        }
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }

        .sidebar-transition {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @media (min-width: 1024px) {
            .sidebar-collapsed .sidebar-el {
                width: 80px;
            }
            .sidebar-collapsed .sidebar-label-el {
                opacity: 0;
                pointer-events: none;
                width: 0;
                display: none;
            }
            .sidebar-collapsed .sidebar-logo-el {
                width: 40px;
                height: 40px;
            }
            .sidebar-collapsed .sidebar-info-el {
                display: none;
            }
            .sidebar-collapsed .sidebar-logout-text {
                display: none;
            }
            .sidebar-collapsed .sidebar-logout-btn {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }
            .sidebar-collapsed .sidebar-user-avatar {
                margin: 0 auto;
            }
            .sidebar-collapsed .sidebar-nav-link {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }
            .sidebar-collapsed .sidebar-nav-link i {
                margin: 0;
            }
            .sidebar-collapsed .sidebar-badge {
                position: absolute;
                top: 4px;
                right: 4px;
                margin: 0;
                font-size: 8px;
                padding: 1px 4px;
            }
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-850 font-sans h-full overflow-hidden flex flex-row">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-40 hidden opacity-0 transition-opacity duration-300"></div>

    <!-- ===== SIDEBAR ===== -->
    <aside id="admin-sidebar" class="sidebar-el sidebar-transition fixed inset-y-0 left-0 z-50 w-72 lg:static flex flex-col bg-white border-r border-slate-200/60 shadow-sm -translate-x-full lg:translate-x-0 shrink-0">
        
        <!-- Logo Header -->
        <div class="h-16 lg:h-20 flex items-center justify-between px-6 border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="sidebar-logo-el sidebar-transition w-9 h-9 object-contain drop-shadow-sm">
                <span class="sidebar-label-el sidebar-transition text-base font-bold text-slate-800 tracking-tight">Adelia Catering</span>
            </div>
            <!-- Mobile Close Button -->
            <button onclick="toggleMobileSidebar()" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <!-- User Profile -->
        <div class="p-4 border-b border-slate-100 shrink-0 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="relative shrink-0 sidebar-user-avatar sidebar-transition">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=f97316&color=fff&bold=true" alt="Admin" class="w-10 h-10 rounded-xl border border-white shadow-sm">
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-white"></span>
                </div>
                <div class="sidebar-info-el min-w-0">
                    <div class="text-xs font-bold text-slate-800 truncate">Administrator</div>
                    <div class="text-[10px] text-slate-500 font-medium">Divisi Pusat</div>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto custom-scrollbar">
            <a href="/admin/dashboard" class="sidebar-nav-link flex items-center px-4 py-3 rounded-xl font-medium text-sm sidebar-transition {{ request()->is('admin/dashboard') ? 'bg-orange-50 text-orange-650 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fa-solid fa-chart-pie w-5 text-center text-base shrink-0 mr-3 {{ request()->is('admin/dashboard') ? 'text-orange-500' : 'text-slate-400' }}"></i>
                <span class="sidebar-label-el sidebar-transition truncate">Dashboard</span>
            </a>
            
            <a href="/admin/orders" class="sidebar-nav-link flex items-center px-4 py-3 rounded-xl font-medium text-sm sidebar-transition relative {{ request()->is('admin/orders*') ? 'bg-orange-50 text-orange-655 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fa-solid fa-boxes-packing w-5 text-center text-base shrink-0 mr-3 {{ request()->is('admin/orders*') ? 'text-orange-500' : 'text-slate-400' }}"></i>
                <span class="sidebar-label-el sidebar-transition truncate">Pesanan</span>
                @php
                    $pendingOrdersCount = \App\Models\Order::where('status', 'pending')->count();
                @endphp
                @if($pendingOrdersCount > 0)
                    <span class="sidebar-badge ml-auto bg-orange-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full shrink-0 shadow-sm">{{ $pendingOrdersCount }}</span>
                @endif
            </a>

            <a href="/admin/menus" class="sidebar-nav-link flex items-center px-4 py-3 rounded-xl font-medium text-sm sidebar-transition {{ request()->is('admin/menus*') ? 'bg-orange-50 text-orange-650 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fa-solid fa-fire-burner w-5 text-center text-base shrink-0 mr-3 {{ request()->is('admin/menus*') ? 'text-orange-500' : 'text-slate-400' }}"></i>
                <span class="sidebar-label-el sidebar-transition truncate">Katalog Menu</span>
            </a>

            <a href="/admin/booking" class="sidebar-nav-link flex items-center px-4 py-3 rounded-xl font-medium text-sm sidebar-transition {{ request()->is('admin/booking*') ? 'bg-orange-50 text-orange-650 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <i class="fa-solid fa-calendar-check w-5 text-center text-base shrink-0 mr-3 {{ request()->is('admin/booking*') ? 'text-orange-500' : 'text-slate-400' }}"></i>
                <span class="sidebar-label-el sidebar-transition truncate">Kalender Booking</span>
            </a>
        </nav>
        
        <!-- Footer / Logout -->
        <div class="p-4 border-t border-slate-100 shrink-0 bg-slate-50/20">
            <form action="/admin/logout" method="POST" id="logout-form">
                @csrf
                <button type="submit" class="sidebar-logout-btn w-full flex items-center px-4 py-2.5 rounded-xl font-medium text-sm text-slate-550 hover:bg-red-50 hover:text-red-650 transition duration-200">
                    <i class="fa-solid fa-power-off w-5 text-center text-base shrink-0 mr-3 text-slate-400"></i>
                    <span class="sidebar-logout-text truncate">Keluar Sesi</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ===== MAIN CONTENT CONTAINER ===== -->
    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        
        <!-- Header -->
        <header class="h-16 lg:h-20 bg-white border-b border-slate-200/60 px-4 sm:px-6 lg:px-8 flex items-center justify-between shrink-0">
            
            <div class="flex items-center gap-3">
                <!-- Hamburger Menu -->
                <button onclick="toggleMobileSidebar()" class="lg:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-50 active:scale-95 transition">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                
                <!-- Sidebar Toggle -->
                <button onclick="toggleDesktopSidebar()" class="hidden lg:flex p-2 rounded-xl text-slate-500 hover:bg-slate-50 active:scale-95 transition">
                    <i class="fa-solid fa-indent text-lg sidebar-toggle-icon"></i>
                </button>

                <!-- Date Info -->
                <div class="hidden sm:flex items-center gap-2 bg-slate-50 text-slate-600 text-xs font-semibold px-3 py-1.5 rounded-lg border border-slate-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                    <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-3">
                <a href="/" target="_blank" class="hidden md:flex text-xs font-bold text-slate-550 hover:text-orange-500 transition items-center gap-1.5 px-3 py-1.5 hover:bg-slate-50 rounded-xl">
                    <span>Kunjungi Situs</span> 
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
                
                <!-- Notifications -->
                <button class="relative w-9 h-9 sm:w-10 sm:h-10 rounded-xl border border-slate-200/80 hover:bg-slate-50 flex items-center justify-center text-slate-500 hover:text-slate-800 transition active:scale-95">
                    <i class="fa-regular fa-bell text-base"></i>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-orange-500 rounded-full border-2 border-white"></span>
                </button>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 custom-scrollbar">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

    </div>

    <!-- JS Logic for Sidebar -->
    <script>
        const sidebar = document.getElementById('admin-sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const toggleIcon = document.querySelector('.sidebar-toggle-icon');

        function updateToggleIcon() {
            if (document.documentElement.classList.contains('sidebar-collapsed')) {
                toggleIcon?.classList.replace('fa-indent', 'fa-outdent');
            } else {
                toggleIcon?.classList.replace('fa-outdent', 'fa-indent');
            }
        }
        updateToggleIcon();

        function toggleDesktopSidebar() {
            const isCollapsed = document.documentElement.classList.toggle('sidebar-collapsed');
            localStorage.setItem('admin-sidebar-collapsed', isCollapsed ? 'true' : 'false');
            updateToggleIcon();
        }

        function toggleMobileSidebar() {
            const isHidden = sidebar.classList.contains('-translate-x-full');
            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.add('opacity-100');
                }, 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.remove('opacity-100');
                setTimeout(() => {
                    backdrop.classList.add('hidden');
                }, 300);
            }
        }

        backdrop.addEventListener('click', toggleMobileSidebar);
    </script>

    @stack('scripts')
</body>
</html>
