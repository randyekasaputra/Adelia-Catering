<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use App\Models\Menu;
use App\Models\Order;
use App\Models\BookingSetting;
use App\Models\BlockedDate;
use App\Models\CateringPackage;
use App\Models\HistoricalSale;
use App\Models\MenuWeight;
use App\Models\VisitorLog;
use App\Services\PackageOptimizerService;
use App\Services\PopularityCalculatorService;

class AdminController extends Controller
{
    public function showLogin() {
        return view('admin.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()
            ->withErrors(['email' => 'Email atau password tidak sesuai.'])
            ->onlyInput('email');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }

    public function dashboard() {
        $totalOrders   = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $paidOrders    = Order::where('status', 'paid')->count();
        $totalMenus    = Menu::count();

        // FIX: Revenue = total dp_amount dari order yang statusnya valid (paid/cooking/shipping/completed)
        $validStatuses = ['paid', 'cooking', 'shipping', 'completed'];
        $revenue = Order::whereIn('status', $validStatuses)->sum('dp_amount');

        $recentOrders = Order::orderBy('created_at', 'desc')->take(5)->get();

        $now = \Carbon\Carbon::now();

        // 1. Mingguan (7 Hari Terakhir)
        $weeklyData = ['labels' => [], 'data' => []];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $sum  = Order::whereIn('status', $validStatuses)
                         ->whereDate('created_at', $date->format('Y-m-d'))
                         ->sum('dp_amount');
            $weeklyData['labels'][] = $date->translatedFormat('D, d M');
            $weeklyData['data'][]   = $sum;
        }

        // 2. Bulanan (12 Bulan Tahun Ini)
        $monthlyData = ['labels' => [], 'data' => []];
        for ($i = 1; $i <= 12; $i++) {
            $sum = Order::whereIn('status', $validStatuses)
                        ->whereYear('created_at', $now->year)
                        ->whereMonth('created_at', $i)
                        ->sum('dp_amount');
            $monthlyData['labels'][] = \Carbon\Carbon::create()->month($i)->translatedFormat('F');
            $monthlyData['data'][]   = $sum;
        }

        // 3. Tahunan (5 Tahun Terakhir)
        $yearlyData = ['labels' => [], 'data' => []];
        for ($i = 4; $i >= 0; $i--) {
            $year = $now->year - $i;
            $sum  = Order::whereIn('status', $validStatuses)
                         ->whereYear('created_at', $year)
                         ->sum('dp_amount');
            $yearlyData['labels'][] = (string) $year;
            $yearlyData['data'][]   = $sum;
        }

        // ============================================================
        // ANALITIK PREDIKTIF
        // ============================================================

        // --- A. PREDIKSI PENDAPATAN (Simple Moving Average, N=3) ---
        $smaWindow = 3;
        $monthlyRevenues = [];
        for ($i = 1; $i <= $smaWindow; $i++) {
            $targetDate = $now->copy()->subMonths($i);
            $monthlyRevenues[] = Order::whereIn('status', $validStatuses)
                ->whereYear('created_at', $targetDate->year)
                ->whereMonth('created_at', $targetDate->month)
                ->sum('dp_amount');
        }

        // SMA Calculation
        $nonZeroMonths = collect($monthlyRevenues)->filter(fn($v) => $v > 0);
        $prediction = $nonZeroMonths->count() > 0
            ? round($nonZeroMonths->sum() / $nonZeroMonths->count())
            : 0;

        // Growth Rate (bulan lalu vs 2 bulan lalu)
        $lastMonth = $monthlyRevenues[0] ?? 0;
        $twoMonthsAgo = $monthlyRevenues[1] ?? 0;
        $growthRate = $twoMonthsAgo > 0
            ? round((($lastMonth - $twoMonthsAgo) / $twoMonthsAgo) * 100, 1)
            : 0;

        // Confidence Level (berapa bulan yang punya data)
        $monthsWithData = $nonZeroMonths->count();
        $confidence = min(round(($monthsWithData / 6) * 100), 100);

        $nextMonthName = $now->copy()->addMonth()->translatedFormat('F Y');

        $predictionData = [
            'prediction'     => $prediction,
            'growth_rate'    => $growthRate,
            'confidence'     => $confidence,
            'next_month'     => $nextMonthName,
            'window'         => $smaWindow,
            'monthly_values' => $monthlyRevenues,
        ];

        // --- B. MENU TERLARIS (Ranking Aggregation) ---
        $topMenus = \App\Models\OrderItem::select(
                'order_items.menu_id',
                'order_items.menu_name',
                DB::raw('SUM(order_items.qty) as total_qty'),
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_items.order_id) as total_orders')
            )
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', $validStatuses)
            ->groupBy('order_items.menu_id', 'order_items.menu_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Hitung total qty untuk persentase
        $grandTotalQty = $topMenus->sum('total_qty');
        $topMenus->each(function ($menu) use ($grandTotalQty) {
            $menu->percentage = $grandTotalQty > 0
                ? round(($menu->total_qty / $grandTotalQty) * 100, 1)
                : 0;
        });

        // --- C. HARI TERSIBUK (Frequency Distribution) ---
        $hariIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $busiestDaysRaw = Order::whereIn('status', $validStatuses)
            ->selectRaw('DAYOFWEEK(created_at) as day_number, COUNT(*) as total_orders, SUM(dp_amount) as total_revenue')
            ->groupBy('day_number')
            ->orderByDesc('total_orders')
            ->get();

        // Build full 7-day distribution (isi 0 untuk hari tanpa order)
        $busiestDays = collect();
        $maxOrders = $busiestDaysRaw->max('total_orders') ?: 1;

        for ($d = 1; $d <= 7; $d++) {
            $found = $busiestDaysRaw->firstWhere('day_number', $d);
            $busiestDays->push((object)[
                'day_number'   => $d,
                'day_name'     => $hariIndo[$d - 1],
                'total_orders' => $found->total_orders ?? 0,
                'total_revenue'=> $found->total_revenue ?? 0,
                'percentage'   => $found ? round(($found->total_orders / $maxOrders) * 100, 1) : 0,
            ]);
        }

        // Peak day (hari dengan order terbanyak)
        $peakDay = $busiestDays->sortByDesc('total_orders')->first();

        // --- D. RINGKASAN PENGUNJUNG & DEVICE ---
        $visitorStats = [
            'today_visitors' => 0,
            'total_visitors' => 0,
            'mobile_pct'     => 0,
            'desktop_pct'    => 0,
        ];
        if (Schema::hasTable('visitor_logs')) {
            $todayVisitors = VisitorLog::whereDate('visited_date', $now->toDateString())
                ->distinct('session_id')
                ->count('session_id');
            $totalVisitors = VisitorLog::distinct('session_id')->count('session_id');
            $totalRows = max(1, VisitorLog::count());
            $mobileCount = VisitorLog::where('device_type', 'Mobile')->count();
            $desktopCount = VisitorLog::where('device_type', 'Desktop')->count();

            $visitorStats = [
                'today_visitors' => $todayVisitors,
                'total_visitors' => $totalVisitors,
                'mobile_pct'     => VisitorLog::count() > 0 ? round(($mobileCount / $totalRows) * 100, 1) : 0,
                'desktop_pct'    => VisitorLog::count() > 0 ? round(($desktopCount / $totalRows) * 100, 1) : 0,
            ];
        }

        return view('admin.dashboard', compact(
            'totalOrders', 'pendingOrders', 'paidOrders', 'totalMenus',
            'revenue', 'recentOrders', 'weeklyData', 'monthlyData', 'yearlyData',
            'predictionData', 'topMenus', 'busiestDays', 'peakDay', 'visitorStats'
        ));
    }

    /**
     * Halaman Analitik Pengunjung Website & Perangkat (Device, OS, Browser)
     */
    public function visitors(Request $request)
    {
        $days = (int) $request->input('days', 7);
        if (!in_array($days, [7, 14, 30], true)) {
            $days = 7;
        }

        $hasTable = Schema::hasTable('visitor_logs');
        $now = \Carbon\Carbon::now();

        if (!$hasTable) {
            return view('admin.visitors.index', [
                'hasTable'      => false,
                'days'          => $days,
                'todayVisitors' => 0,
                'totalVisitors' => 0,
                'totalViews'    => 0,
                'dominantDevice'=> '-',
                'dominantPct'   => 0,
                'trendData'     => ['labels' => [], 'visitors' => [], 'views' => []],
                'deviceStats'   => collect(),
                'osStats'       => collect(),
                'browserStats'  => collect(),
                'topPages'      => collect(),
                'recentLogs'    => collect(),
            ]);
        }

        $todayVisitors = VisitorLog::whereDate('visited_date', $now->toDateString())
            ->distinct('session_id')
            ->count('session_id');
        $totalVisitors = VisitorLog::distinct('session_id')->count('session_id');
        $totalViews = VisitorLog::count();

        // Tren Harian (7 / 14 / 30 hari terakhir)
        $trendData = ['labels' => [], 'visitors' => [], 'views' => []];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $trendData['labels'][] = $date->translatedFormat('d M');
            $trendData['visitors'][] = VisitorLog::whereDate('visited_date', $dateStr)->distinct('session_id')->count('session_id');
            $trendData['views'][] = VisitorLog::whereDate('visited_date', $dateStr)->count();
        }

        // Distribusi Tipe Perangkat (Mobile, Desktop, Tablet)
        $deviceRaw = VisitorLog::select('device_type', DB::raw('COUNT(DISTINCT session_id) as total_users'), DB::raw('COUNT(*) as total_views'))
            ->groupBy('device_type')
            ->orderByDesc('total_users')
            ->get();
        $totalDeviceUsers = max(1, $deviceRaw->sum('total_users'));
        $deviceStats = $deviceRaw->map(function ($row) use ($totalDeviceUsers) {
            $row->percentage = round(($row->total_users / $totalDeviceUsers) * 100, 1);
            return $row;
        });

        $topDev = $deviceStats->first();
        $dominantDevice = $topDev ? $topDev->device_type : '-';
        $dominantPct = $topDev ? $topDev->percentage : 0;

        // Distribusi Sistem Operasi (OS)
        $osRaw = VisitorLog::select('os', DB::raw('COUNT(DISTINCT session_id) as total_users'))
            ->groupBy('os')
            ->orderByDesc('total_users')
            ->get();
        $totalOsUsers = max(1, $osRaw->sum('total_users'));
        $osStats = $osRaw->map(function ($row) use ($totalOsUsers) {
            $row->percentage = round(($row->total_users / $totalOsUsers) * 100, 1);
            return $row;
        });

        // Distribusi Browser
        $browserRaw = VisitorLog::select('browser', DB::raw('COUNT(DISTINCT session_id) as total_users'))
            ->groupBy('browser')
            ->orderByDesc('total_users')
            ->get();
        $totalBrowserUsers = max(1, $browserRaw->sum('total_users'));
        $browserStats = $browserRaw->map(function ($row) use ($totalBrowserUsers) {
            $row->percentage = round(($row->total_users / $totalBrowserUsers) * 100, 1);
            return $row;
        });

        // Halaman Paling Sering Diakses
        $topPages = VisitorLog::select('url_path', DB::raw('COUNT(*) as total_views'), DB::raw('COUNT(DISTINCT session_id) as unique_visitors'))
            ->groupBy('url_path')
            ->orderByDesc('total_views')
            ->limit(8)
            ->get();

        // 20 Aktivitas Pengunjung Terbaru
        $recentLogs = VisitorLog::orderBy('created_at', 'desc')->limit(20)->get();

        return view('admin.visitors.index', compact(
            'hasTable', 'days', 'todayVisitors', 'totalVisitors', 'totalViews',
            'dominantDevice', 'dominantPct', 'trendData', 'deviceStats',
            'osStats', 'browserStats', 'topPages', 'recentLogs'
        ));
    }

    public function menus(Request $request) {
        $query = Menu::query();
        $selectedComponent = $request->input('component', 'all');

        if ($selectedComponent !== 'all' && in_array($selectedComponent, ['karbohidrat', 'lauk_utama', 'lauk_pendamping', 'sayur', 'snack'])) {
            $query->where('component_type', $selectedComponent);
        }

        $menus = $query->orderBy('created_at', 'desc')->get();

        $componentCounts = [
            'all'             => Menu::count(),
            'karbohidrat'     => Menu::where('component_type', 'karbohidrat')->count(),
            'lauk_utama'      => Menu::where('component_type', 'lauk_utama')->count(),
            'lauk_pendamping' => Menu::where('component_type', 'lauk_pendamping')->count(),
            'sayur'           => Menu::where('component_type', 'sayur')->count(),
            'snack'           => Menu::where('component_type', 'snack')->count(),
        ];

        return view('admin.menus.index', compact('menus', 'selectedComponent', 'componentCounts'));
    }

    public function createMenu() {
        return view('admin.menus.create');
    }

    public function storeMenu(Request $request) {
        $request->validate([
            'name'           => 'required|string',
            'description'    => 'required|string',
            'price'          => 'required|numeric|min:0',
            'hpp'            => 'nullable|numeric|min:0',
            'image'          => 'required|image|mimes:jpeg,png,webp|max:2048',
            'tag'            => 'nullable|string',
            'category'       => 'required|string',
            'component_type' => 'nullable|string',
            'calories'       => 'nullable|numeric',
        ]);

        // Simpan gambar ke storage/app/public/menus
        $imagePath = $request->file('image')->store('menus', 'public');

        $price = (int) $request->price;
        $hpp = $request->filled('hpp') ? (int) $request->hpp : (int) (round(($price * 0.68) / 100) * 100);

        $menuData = [
            'name'           => $request->name,
            'description'    => $request->description,
            'price'          => $price,
            'image'          => Storage::url($imagePath),
            'tag'            => $request->tag,
            'category'       => $request->category,
            'component_type' => $request->component_type ?: ($request->category === 'snack' ? 'snack' : 'lauk_utama'),
            'calories'       => $request->calories ?: 150,
        ];

        if (\Illuminate\Support\Facades\Schema::hasColumn('menus', 'hpp')) {
            $menuData['hpp'] = $hpp;
        }

        Menu::create($menuData);

        return redirect('/admin/menus')->with('success', 'Menu berhasil ditambahkan beserta perhitungan HPP!');
    }

    public function destroyMenu($id) {
        $menu = Menu::findOrFail($id);

        // Hapus file gambar dari storage jika bukan URL eksternal
        if ($menu->image && str_starts_with($menu->image, '/storage/')) {
            $path = str_replace('/storage/', '', $menu->image);
            Storage::disk('public')->delete($path);
        }

        $menu->delete();
        return back()->with('success', 'Menu berhasil dihapus!');
    }

    public function editMenu($id) {
        $menu = Menu::findOrFail($id);
        return view('admin.menus.edit', compact('menu'));
    }

    public function updateMenu(Request $request, $id) {
        $menu = Menu::findOrFail($id);

        $rules = [
            'name'           => 'required|string',
            'description'    => 'required|string',
            'price'          => 'required|numeric|min:0',
            'hpp'            => 'nullable|numeric|min:0',
            'image'          => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'tag'            => 'nullable|string',
            'category'       => 'required|string',
            'component_type' => 'nullable|string',
            'calories'       => 'nullable|numeric',
        ];

        $request->validate($rules);

        $price = (int) $request->price;
        $hpp = $request->filled('hpp') ? (int) $request->hpp : ($menu->hpp ?: (int) (round(($price * 0.68) / 100) * 100));

        $data = [
            'name'           => $request->name,
            'description'    => $request->description,
            'price'          => $price,
            'tag'            => $request->tag,
            'category'       => $request->category,
            'component_type' => $request->component_type ?: $menu->component_type,
            'calories'       => $request->calories !== null ? $request->calories : $menu->calories,
        ];

        if (\Illuminate\Support\Facades\Schema::hasColumn('menus', 'hpp')) {
            $data['hpp'] = $hpp;
        }

        // Jika ada gambar baru yang diupload
        if ($request->hasFile('image')) {
            // Hapus gambar lama dari storage (jika bukan URL eksternal)
            if ($menu->image && str_starts_with($menu->image, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $menu->image);
                Storage::disk('public')->delete($oldPath);
            }

            $imagePath = $request->file('image')->store('menus', 'public');
            $data['image'] = Storage::url($imagePath);
        }

        $menu->update($data);
        return redirect('/admin/menus')->with('success', 'Menu berhasil diperbarui!');
    }

    /**
     * FIX: Orders bisa difilter berdasarkan status
     */
    public function orders(Request $request) {
        $statusFilter = $request->query('status', 'all');

        $query = Order::with('items')->orderBy('created_at', 'desc');

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $orders = $query->get();

        // Hitung badge untuk setiap status tab
        $counts = [
            'all'       => Order::count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'paid'      => Order::where('status', 'paid')->count(),
            'cooking'   => Order::where('status', 'cooking')->count(),
            'shipping'  => Order::where('status', 'shipping')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusFilter', 'counts'));
    }

    /**
     * Update Status & Catatan Tracking Tahapan Pesanan
     */
    public function updateOrderStatus(Request $request, $id) {
        $request->validate([
            'status'        => 'required|in:pending,paid,cooking,shipping,completed,cancelled',
            'tracking_note' => 'nullable|string|max:500',
            'driver_name'   => 'nullable|string|max:100',
            'driver_phone'  => 'nullable|string|max:30',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        if ($request->has('tracking_note')) {
            $order->tracking_note = $request->tracking_note;
        }
        if ($request->has('driver_name')) {
            $order->driver_name = $request->driver_name;
        }
        if ($request->has('driver_phone')) {
            $order->driver_phone = $request->driver_phone;
        }
        $order->save();

        return back()->with('success', 'Status dan progres pesanan #' . $order->id . ' berhasil diperbarui!');
    }

    public function printOrder($id) {
        $order = Order::with('items.menu')->findOrFail($id);

        $packageBreakdowns = [];
        $kitchenChecklist = [
            'karbohidrat'     => [],
            'lauk_utama'      => [],
            'lauk_pendamping' => [],
            'sayur'           => [],
            'snack'           => [],
            'lainnya'         => [],
        ];

        foreach ($order->items as $item) {
            // Cek paket di tabel catering_packages
            $package = CateringPackage::with('items.menu')
                ->where('id', $item->menu_id)
                ->orWhere('name', $item->menu_name)
                ->first();

            if ($package && $package->items->isNotEmpty()) {
                $components = [];
                foreach ($package->items as $pItem) {
                    $cType = $pItem->component_type ?? 'lainnya';
                    $menuName = $pItem->menu ? $pItem->menu->name : 'Menu Hidangan';
                    $components[$cType] = [
                        'name'              => $menuName,
                        'qty_per_portion'   => $pItem->qty,
                        'total_kitchen_qty' => $pItem->qty * $item->qty,
                        'calories'          => $pItem->menu ? $pItem->menu->calories : null,
                    ];

                    if (!isset($kitchenChecklist[$cType][$menuName])) {
                        $kitchenChecklist[$cType][$menuName] = [
                            'qty'  => 0,
                            'unit' => ($cType === 'snack' ? 'pcs' : 'porsi'),
                        ];
                    }
                    $kitchenChecklist[$cType][$menuName]['qty'] += ($pItem->qty * $item->qty);
                }
                $packageBreakdowns[$item->id] = [
                    'package'    => $package,
                    'components' => $components,
                ];
            } else {
                $cType = $item->menu ? ($item->menu->component_type ?? 'lainnya') : 'lainnya';
                if (!isset($kitchenChecklist[$cType][$item->menu_name])) {
                    $kitchenChecklist[$cType][$item->menu_name] = [
                        'qty'  => 0,
                        'unit' => ($cType === 'snack' ? 'pcs' : 'porsi'),
                    ];
                }
                $kitchenChecklist[$cType][$item->menu_name]['qty'] += $item->qty;
            }
        }

        return view('admin.orders.print', compact('order', 'packageBreakdowns', 'kitchenChecklist'));
    }

    // ==============================
    // BOOKING CALENDAR MANAGEMENT
    // ==============================

    /**
     * Halaman manajemen kalender booking admin.
     */
    public function bookingCalendar() {
        $maxOrdersPerDay = BookingSetting::getValue('max_orders_per_day', 100);
        $minAdvanceDays  = BookingSetting::getValue('min_advance_days', 3);
        $blockedDates    = BlockedDate::orderBy('date', 'asc')->get();

        return view('admin.booking.calendar', compact(
            'maxOrdersPerDay', 'minAdvanceDays', 'blockedDates'
        ));
    }

    /**
     * Simpan pengaturan booking (max orders per day, min advance days).
     */
    public function updateBookingSettings(Request $request) {
        $request->validate([
            'max_orders_per_day' => 'required|integer|min:1|max:10000',
            'min_advance_days'   => 'required|integer|min:0|max:30',
        ]);

        BookingSetting::setValue('max_orders_per_day', $request->max_orders_per_day);
        BookingSetting::setValue('min_advance_days', $request->min_advance_days);

        return back()->with('success', 'Pengaturan kapasitas berhasil disimpan!');
    }

    /**
     * Blokir tanggal tertentu.
     */
    public function blockDate(Request $request) {
        $request->validate([
            'date'   => 'required|date|after_or_equal:today',
            'reason' => 'nullable|string|max:255',
        ]);

        BlockedDate::updateOrCreate(
            ['date' => $request->date],
            ['reason' => $request->reason ?? 'Diblokir oleh admin']
        );

        return back()->with('success', 'Tanggal ' . $request->date . ' berhasil diblokir!');
    }

    /**
     * Hapus blokir tanggal.
     */
    public function unblockDate($id) {
        BlockedDate::findOrFail($id)->delete();
        return back()->with('success', 'Tanggal berhasil dibuka kembali!');
    }

    /**
     * API: Mengembalikan data kalender untuk N bulan ke depan.
     * Dipakai oleh halaman checkout publik dan admin calendar.
     */
    public function calendarData(Request $request) {
        $months = $request->query('months', 3);
        $startDate = \Carbon\Carbon::today();
        $endDate   = \Carbon\Carbon::today()->addMonths($months)->endOfMonth();

        $maxPerDay = (int) BookingSetting::getValue('max_orders_per_day', 100);
        $minAdvanceDays = (int) BookingSetting::getValue('min_advance_days', 3);

        // Hitung jumlah porsi per tanggal acara
        $orderCounts = \App\Models\OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['pending', 'paid', 'cooking', 'shipping', 'completed'])
            ->whereBetween('orders.event_date', [$startDate, $endDate])
            ->selectRaw('DATE(orders.event_date) as date, SUM(order_items.qty) as total')
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Ambil tanggal yang diblokir
        $blockedDates = BlockedDate::whereBetween('date', [$startDate, $endDate])
            ->pluck('reason', 'date')
            ->mapWithKeys(function ($reason, $date) {
                return [\Carbon\Carbon::parse($date)->format('Y-m-d') => $reason];
            })
            ->toArray();

        // Build calendar data
        $calendarData = [];
        $current = $startDate->copy();

        while ($current <= $endDate) {
            $dateStr = $current->format('Y-m-d');
            $count = $orderCounts[$dateStr] ?? 0;
            $isBlocked = isset($blockedDates[$dateStr]);
            $isFull = $count >= $maxPerDay;
            $isPast = $current->lt(\Carbon\Carbon::today());
            $isTooSoon = $current->lt(\Carbon\Carbon::today()->addDays($minAdvanceDays));

            $status = 'available';
            if ($isPast) $status = 'past';
            elseif ($isBlocked) $status = 'blocked';
            elseif ($isFull) $status = 'full';
            elseif ($isTooSoon) $status = 'too_soon';
            elseif ($count > 0 && $count >= ($maxPerDay * 0.7)) $status = 'almost_full';

            $calendarData[$dateStr] = [
                'count'     => $count,
                'max'       => $maxPerDay,
                'status'    => $status,
                'reason'    => $isBlocked ? $blockedDates[$dateStr] : null,
            ];

            $current->addDay();
        }

        return response()->json([
            'calendar'        => $calendarData,
            'max_per_day'     => $maxPerDay,
            'min_advance_days' => $minAdvanceDays,
        ]);
    }

    /**
     * Tampilan Halaman Quick Budget Recommender untuk Penjual (Admin)
     */
    public function budgetRecommender()
    {
        return view('admin.recommender.index');
    }

    /**
     * API Perhitungan Cepat Rekomendasi Menu Berdasarkan Budget untuk Penjual (SMCKP + Weighted Sum + DP)
     */
    public function calculateBudgetRecommendation(Request $request, PackageOptimizerService $optimizer)
    {
        $request->validate([
            'budget'        => 'required|numeric|min:10000|max:100000',
            'portions'      => 'nullable|numeric|min:1|max:5000',
            'preference'    => 'nullable|string|in:all,ayam,daging,ikan',
            'customer_name' => 'nullable|string|max:100',
            'categories'    => 'nullable|array',
            'categories.*'  => 'string|in:karbohidrat,lauk_utama,lauk_pendamping,sayur,snack',
            'w_popularity'  => 'nullable|numeric|min:0|max:1',
            'w_profit'      => 'nullable|numeric|min:0|max:1',
        ]);

        $budget = (int) $request->input('budget', 25000);
        $portions = (int) $request->input('portions', 100);
        $preference = $request->input('preference', 'all');
        $categories = $request->input('categories', null);
        $wPopularity = (float) $request->input('w_popularity', 0.55);
        $wProfit = (float) $request->input('w_profit', 0.45);
        $customerName = trim($request->input('customer_name', 'Kak'));
        if (empty($customerName)) {
            $customerName = 'Kak';
        }

        $result = $optimizer->optimize($budget, $preference, $categories, $wPopularity, $wProfit);

        if (!$result['success']) {
            return response()->json($result, 422);
        }

        $items = $result['items'];
        $unitPrice = $result['total_price'];
        $totalCost = $unitPrice * $portions;
        $totalHppAll = ($result['total_hpp'] ?? 0) * $portions;
        $totalProfitAll = ($result['total_profit'] ?? 0) * $portions;
        $totalBudget = $budget * $portions;
        $totalSavings = max(0, $totalBudget - $totalCost);

        // Susun daftar komposisi menu yang terpilih (abaikan kategori opsional yang dilewati pada SMCKP)
        $catLabels = [
            'karbohidrat'     => '🍚 *Karbohidrat:*',
            'lauk_utama'      => '🍗 *Lauk Utama:*',
            'lauk_pendamping' => '🍳 *Pendamping:*',
            'sayur'           => '🥗 *Sayuran:*',
            'snack'           => '🥐 *Snack/Kue:*',
        ];
        $compositionLines = [];
        $idxNum = 1;
        foreach ($catLabels as $catKey => $label) {
            if (!empty($items[$catKey]) && empty($items[$catKey]['is_skipped'])) {
                $compositionLines[] = "{$idxNum}. {$label} {$items[$catKey]['name']}";
                $idxNum++;
            }
        }
        $selectedCount = count($compositionLines);

        // Buat Template Chat WhatsApp yang Rapi & Profesional
        $waTemplate = "Halo {$customerName}, terima kasih sudah menghubungi *Adelia Catering Jepara*! 🙏\n\n"
            . "Berikut rekomendasi paket menu katering spesial terbaik sesuai anggaran *Rp " . number_format($budget, 0, ',', '.') . " / porsi* (estimasi {$portions} porsi):\n\n"
            . "🍱 *KOMPOSISI PAKET ({$selectedCount} HIDANGAN TERPILIH):*\n"
            . implode("\n", $compositionLines) . "\n\n"
            . "💰 *RINCIAN HARGA:*\n"
            . "• Harga Satuan: *Rp " . number_format($unitPrice, 0, ',', '.') . " / porsi* (Sisa Anggaran Rp " . number_format($result['remaining_budget'], 0, ',', '.') . ")\n"
            . "• Total ({$portions} Porsi): *Rp " . number_format($totalCost, 0, ',', '.') . "*\n"
            . "⭐ *Nilai Utilitas:* {$result['average_utility']}% (Menu terpopuler berdasarkan data transaksi).\n\n"
            . "Apakah komposisi menu ini sudah cocok dengan rencana acara {$customerName}? Jika berkenan, bisa segera kami amankan slot tanggalnya ya! 😊";

        $result['portions'] = $portions;
        $result['total_cost'] = $totalCost;
        $result['total_hpp_all'] = $totalHppAll;
        $result['total_profit_all'] = $totalProfitAll;
        $result['total_budget'] = $totalBudget;
        $result['total_savings'] = $totalSavings;
        $result['customer_name'] = $customerName;
        $result['wa_template'] = $waTemplate;

        return response()->json($result);
    }

    /**
     * Tampilkan data penjualan historis 3 tahun dan ringkasan metrik
     */
    public function historicalSales(Request $request)
    {
        $query = HistoricalSale::with('menu');

        // Filter Tahun
        $selectedYear = $request->input('year', 'all');
        if ($selectedYear !== 'all' && is_numeric($selectedYear)) {
            $query->whereYear('transaction_date', $selectedYear);
        }

        // Filter Tipe Pelanggan
        $selectedCustomerType = $request->input('customer_type', 'all');
        if ($selectedCustomerType !== 'all' && in_array($selectedCustomerType, ['hajatan', 'instansi', 'kantor', 'perorangan'])) {
            $query->where('customer_type', $selectedCustomerType);
        }

        // Filter Pencarian (Nama menu / No Invoice)
        $search = trim($request->input('search', ''));
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('menu_name', 'like', "%{$search}%")
                  ->orWhere('invoice_no', 'like', "%{$search}%");
            });
        }

        $sales = $query->orderBy('transaction_date', 'desc')
                       ->paginate(20)
                       ->withQueryString();

        // Metrik Rangkuman
        $totalTransactions = HistoricalSale::count();
        $totalQtySold = HistoricalSale::sum('qty');
        $totalRevenue = HistoricalSale::sum('total_price');
        $uniqueMenusCount = HistoricalSale::distinct('menu_name')->count('menu_name');

        // Daftar tahun yang ada di basis data
        $years = HistoricalSale::selectRaw('YEAR(transaction_date) as yr')
            ->distinct()
            ->whereNotNull('transaction_date')
            ->orderBy('yr', 'desc')
            ->pluck('yr')
            ->toArray();

        $lastCalculated = MenuWeight::max('last_calculated_at');

        // Top 5 Menu Terlaris Secara Historis
        $topHistoricalMenus = HistoricalSale::select(
                'menu_name', 
                DB::raw('SUM(qty) as total_qty'), 
                DB::raw('COUNT(DISTINCT invoice_no) as trans_count'),
                DB::raw('SUM(total_price) as total_rev')
            )
            ->groupBy('menu_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('admin.historical_sales.index', compact(
            'sales', 'totalTransactions', 'totalQtySold', 'totalRevenue',
            'uniqueMenusCount', 'years', 'selectedYear', 'selectedCustomerType',
            'search', 'lastCalculated', 'topHistoricalMenus'
        ));
    }

    /**
     * Download template CSV standar untuk import data penjualan
     */
    public function downloadSalesTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_data_penjualan_adelia.csv"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function() {
            $handle = fopen('php://output', 'w');
            // Tulis BOM UTF-8 agar Excel membuka karakter dengan benar
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Baris Header CSV
            fputcsv($handle, ['invoice_no', 'menu_name', 'category', 'qty', 'unit_price', 'total_price', 'transaction_date', 'customer_type']);

            // Contoh Data Riil
            $samples = [
                ['INV-2023-001', 'Nasi Kuning Komplit', 'makanan', 150, 18000, 2700000, '2023-03-15', 'hajatan'],
                ['INV-2023-001', 'Ayam Bakar Madu', 'makanan', 150, 15000, 2250000, '2023-03-15', 'hajatan'],
                ['INV-2023-001', 'Sambal Goreng Ati Kentang', 'makanan', 150, 8000, 1200000, '2023-03-15', 'hajatan'],
                ['INV-2023-001', 'Capcay Goreng Spesial', 'makanan', 150, 6000, 900000, '2023-03-15', 'hajatan'],
                ['INV-2023-001', 'Pastel Sayur Telur', 'snack', 150, 3500, 525000, '2023-03-15', 'hajatan'],
                ['INV-2024-042', 'Rendang Daging Sapi', 'makanan', 80, 22000, 1760000, '2024-07-20', 'kantor'],
                ['INV-2024-042', 'Tumis Buncis Wortel Bakso', 'makanan', 80, 5000, 400000, '2024-07-20', 'kantor'],
                ['INV-2025-110', 'Nasi Liwet Solo Gurih', 'makanan', 100, 16000, 1600000, '2025-01-10', 'instansi'],
                ['INV-2025-110', 'Ayam Goreng Lengkuas', 'makanan', 100, 14000, 1400000, '2025-01-10', 'instansi'],
                ['INV-2025-110', 'Perkedel Kentang Daging', 'makanan', 100, 5000, 500000, '2025-01-10', 'instansi'],
                ['INV-2025-110', 'Urap Sayur Bumbu Kelapa', 'makanan', 100, 5000, 500000, '2025-01-10', 'instansi'],
                ['INV-2025-110', 'Risoles Mayo Smoked Beef', 'snack', 100, 4000, 400000, '2025-01-10', 'instansi'],
            ];

            foreach ($samples as $sample) {
                fputcsv($handle, $sample);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Proses Import File CSV Data Penjualan
     */
    public function importHistoricalSales(Request $request, PopularityCalculatorService $calculator)
    {
        $request->validate([
            'csv_file' => 'required|file|max:15360',
            'mode'     => 'required|in:append,replace',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        // Jika mode replace, bersihkan tabel terlebih dahulu
        if ($request->mode === 'replace') {
            HistoricalSale::truncate();
        }

        $handle = fopen($path, 'r');
        if (!$handle) {
            return back()->with('error', 'Gagal membuka file CSV yang diunggah.');
        }

        // Baca baris pertama untuk deteksi delimiter dan mapping header
        $firstLine = fgets($handle);
        if (!$firstLine) {
            fclose($handle);
            return back()->with('error', 'File CSV kosong.');
        }

        // Hapus BOM UTF-8 jika ada
        $firstLine = preg_replace('/^\xEF\xBB\xBF/', '', $firstLine);

        // Deteksi delimiter
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        $headers = str_getcsv(trim($firstLine), $delimiter);
        $headerMap = [];
        foreach ($headers as $idx => $hdr) {
            $cleanHdr = strtolower(trim($hdr, " \t\n\r\0\x0B\"'"));
            $headerMap[$cleanHdr] = $idx;
        }

        // Validasi kolom nama menu
        if (!isset($headerMap['menu_name']) && !isset($headerMap['nama_menu'])) {
            fclose($handle);
            return back()->with('error', 'Format CSV tidak valid! Kolom "menu_name" (nama menu) wajib ada pada baris pertama.');
        }

        $menuNameKey = isset($headerMap['menu_name']) ? 'menu_name' : 'nama_menu';
        $invoiceKey  = $headerMap['invoice_no'] ?? ($headerMap['no_invoice'] ?? null);
        $qtyKey      = $headerMap['qty'] ?? ($headerMap['jumlah'] ?? null);
        $priceKey    = $headerMap['unit_price'] ?? ($headerMap['harga_satuan'] ?? ($headerMap['price'] ?? null));
        $totalKey    = $headerMap['total_price'] ?? ($headerMap['total'] ?? null);
        $dateKey     = $headerMap['transaction_date'] ?? ($headerMap['tanggal'] ?? null);
        $custKey     = $headerMap['customer_type'] ?? ($headerMap['tipe_pelanggan'] ?? null);
        $categoryKey = $headerMap['category'] ?? ($headerMap['kategori'] ?? null);

        // Cache katalog menu saat ini untuk auto-matching menu_id
        $allMenus = Menu::all();
        $menuLookup = [];
        foreach ($allMenus as $m) {
            $menuLookup[strtolower(trim($m->name))] = $m;
        }

        $records = [];
        $rowCount = 0;
        $matchedMenuCount = 0;

        while (($row = fgetcsv($handle, 4096, $delimiter)) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }

            $menuName = trim($row[$headerMap[$menuNameKey]] ?? '');
            if ($menuName === '') {
                continue;
            }

            $qty = isset($qtyKey, $row[$qtyKey]) ? (int) $row[$qtyKey] : 1;
            if ($qty <= 0) $qty = 1;

            $unitPrice = isset($priceKey, $row[$priceKey]) ? (int) preg_replace('/[^0-9]/', '', $row[$priceKey]) : 0;
            $totalPrice = isset($totalKey, $row[$totalKey]) ? (int) preg_replace('/[^0-9]/', '', $row[$totalKey]) : ($unitPrice * $qty);

            $dateRaw = isset($dateKey, $row[$dateKey]) ? trim($row[$dateKey]) : null;
            $transactionDate = null;
            if ($dateRaw) {
                try {
                    $transactionDate = \Carbon\Carbon::parse($dateRaw)->format('Y-m-d');
                } catch (\Exception $e) {
                    $transactionDate = now()->format('Y-m-d');
                }
            } else {
                $transactionDate = now()->format('Y-m-d');
            }

            $customerType = isset($custKey, $row[$custKey]) ? strtolower(trim($row[$custKey])) : 'hajatan';
            $invoiceNo = isset($invoiceKey, $row[$invoiceKey]) && trim($row[$invoiceKey]) !== '' 
                ? trim($row[$invoiceKey]) 
                : 'INV-' . date('Ymd') . '-' . sprintf('%04d', $rowCount + 1);

            // Matching menu dengan katalog
            $lowerName = strtolower($menuName);
            $matchedMenu = $menuLookup[$lowerName] ?? null;
            if (!$matchedMenu) {
                foreach ($menuLookup as $knownName => $menuObj) {
                    if (str_contains($knownName, $lowerName) || str_contains($lowerName, $knownName)) {
                        $matchedMenu = $menuObj;
                        break;
                    }
                }
            }

            $menuId = $matchedMenu ? $matchedMenu->id : null;
            if ($matchedMenu) {
                $matchedMenuCount++;
            }

            $category = isset($categoryKey, $row[$categoryKey]) && trim($row[$categoryKey]) !== ''
                ? strtolower(trim($row[$categoryKey])) 
                : ($matchedMenu ? $matchedMenu->category : 'makanan');

            $records[] = [
                'invoice_no'       => $invoiceNo,
                'menu_id'          => $menuId,
                'menu_name'        => $menuName,
                'category'         => $category,
                'qty'              => $qty,
                'unit_price'       => $unitPrice ?: ($matchedMenu ? $matchedMenu->price : 0),
                'total_price'      => $totalPrice ?: (($matchedMenu ? $matchedMenu->price : 0) * $qty),
                'transaction_date' => $transactionDate,
                'customer_type'    => in_array($customerType, ['hajatan', 'instansi', 'kantor', 'perorangan']) ? $customerType : 'hajatan',
                'created_at'       => now(),
                'updated_at'       => now(),
            ];

            $rowCount++;

            if (count($records) >= 500) {
                HistoricalSale::insert($records);
                $records = [];
            }
        }

        fclose($handle);

        if (!empty($records)) {
            HistoricalSale::insert($records);
        }

        $message = "Sukses mengimpor {$rowCount} baris data penjualan ({$matchedMenuCount} item menu terhubung otomatis ke katalog).";

        // Hitung ulang bobot jika dicentang
        if ($request->boolean('recalculate', true)) {
            $calcResult = $calculator->calculateAll();
            $message .= " " . ($calcResult['message'] ?? 'Bobot popularitas berhasil disinkronkan.');
        }

        return redirect('/admin/historical-sales')->with('success', $message);
    }

    /**
     * Hitung ulang skor popularitas dan bobot empiris secara manual
     */
    public function recalculateWeights(PopularityCalculatorService $calculator)
    {
        $result = $calculator->calculateAll();
        if ($result['success']) {
            return back()->with('success', $result['message']);
        }
        return back()->with('error', $result['message']);
    }

    /**
     * Kosongkan tabel data penjualan historis
     */
    public function truncateHistoricalSales()
    {
        HistoricalSale::truncate();
        MenuWeight::truncate();
        Menu::query()->update(['popularity_score' => 50.0]);

        return back()->with('success', 'Seluruh data penjualan historis dan cache bobot berhasil dikosongkan.');
    }
}
