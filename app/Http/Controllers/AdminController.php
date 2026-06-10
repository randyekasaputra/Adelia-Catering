<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Menu;
use App\Models\Order;
use App\Models\BookingSetting;
use App\Models\BlockedDate;

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

        // FIX: Revenue = total dp_amount dari order yang statusnya paid/completed
        $revenue = Order::whereIn('status', ['paid', 'completed'])->sum('dp_amount');

        $recentOrders = Order::orderBy('created_at', 'desc')->take(5)->get();

        $now = \Carbon\Carbon::now();

        // 1. Mingguan (7 Hari Terakhir)
        $weeklyData = ['labels' => [], 'data' => []];
        for ($i = 6; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $sum  = Order::whereIn('status', ['paid', 'completed'])
                         ->whereDate('created_at', $date->format('Y-m-d'))
                         ->sum('dp_amount');
            $weeklyData['labels'][] = $date->translatedFormat('D, d M');
            $weeklyData['data'][]   = $sum;
        }

        // 2. Bulanan (12 Bulan Tahun Ini)
        $monthlyData = ['labels' => [], 'data' => []];
        for ($i = 1; $i <= 12; $i++) {
            $sum = Order::whereIn('status', ['paid', 'completed'])
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
            $sum  = Order::whereIn('status', ['paid', 'completed'])
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
            $monthlyRevenues[] = Order::whereIn('status', ['paid', 'completed'])
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
            ->whereIn('orders.status', ['paid', 'completed'])
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

        $busiestDaysRaw = Order::whereIn('status', ['paid', 'completed'])
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

        return view('admin.dashboard', compact(
            'totalOrders', 'pendingOrders', 'paidOrders', 'totalMenus',
            'revenue', 'recentOrders', 'weeklyData', 'monthlyData', 'yearlyData',
            'predictionData', 'topMenus', 'busiestDays', 'peakDay'
        ));
    }

    public function menus() {
        $menus = Menu::orderBy('created_at', 'desc')->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function createMenu() {
        return view('admin.menus.create');
    }

    public function storeMenu(Request $request) {
        $request->validate([
            'name'        => 'required|string',
            'description' => 'required|string',
            'price'       => 'required|numeric',
            'image'       => 'required|image|mimes:jpeg,png,webp|max:2048',
            'tag'         => 'nullable|string',
            'category'    => 'required|string',
        ]);

        // Simpan gambar ke storage/app/public/menus
        $imagePath = $request->file('image')->store('menus', 'public');

        Menu::create([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'image'       => Storage::url($imagePath),
            'tag'         => $request->tag,
            'category'    => $request->category,
        ]);

        return redirect('/admin/menus')->with('success', 'Menu berhasil ditambahkan!');
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
            'name'        => 'required|string',
            'description' => 'required|string',
            'price'       => 'required|numeric',
            'image'       => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'tag'         => 'nullable|string',
            'category'    => 'required|string',
        ];

        $request->validate($rules);

        $data = [
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'tag'         => $request->tag,
            'category'    => $request->category,
        ];

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
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusFilter', 'counts'));
    }

    /**
     * FIX: Status yang tersedia diperluas — pending / paid / completed / cancelled
     */
    public function updateOrderStatus(Request $request, $id) {
        $request->validate([
            'status' => 'required|in:pending,paid,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function printOrder($id) {
        $order = Order::with('items')->findOrFail($id);
        return view('admin.orders.print', compact('order'));
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
            ->whereIn('orders.status', ['pending', 'paid', 'completed'])
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
}
