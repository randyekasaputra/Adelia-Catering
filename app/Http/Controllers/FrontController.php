<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\BookingSetting;
use App\Models\BlockedDate;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class FrontController extends Controller
{
    public function __construct() {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function home() {
        return view('home');
    }

    public function menu() {
        $menus = Menu::all();
        return view('menu', compact('menus'));
    }

    /**
     * FIX #1: Menu detail sekarang diambil dari database, bukan hardcoded.
     */
    public function menuDetail($id) {
        $menu = Menu::findOrFail($id);

        // Rekomendasi: kategori sama, exclude menu saat ini, ambil 3
        $recommendations = Menu::where('category', $menu->category)
            ->where('id', '!=', $menu->id)
            ->take(3)
            ->get();

        // Jika rekomendasi kurang dari 3, tambahkan dari kategori lain
        if ($recommendations->count() < 3) {
            $needed = 3 - $recommendations->count();
            $extra = Menu::where('id', '!=', $menu->id)
                ->whereNotIn('id', $recommendations->pluck('id'))
                ->take($needed)
                ->get();
            $recommendations = $recommendations->merge($extra);
        }

        return view('menu-detail', compact('menu', 'recommendations'));
    }

    public function checkout() {
        $clientKey = config('midtrans.client_key');
        // FIX #3: Kirim nomor WA admin ke view dari env
        $adminWa = env('ADMIN_WA_NUMBER', '6281234567890');
        return view('checkout', compact('clientKey', 'adminWa'));
    }

    public function processCheckout(Request $request) {
        $data = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_wa'    => 'required|string|max:20',
            'event_date'     => 'required|date',
            'address'        => 'required|string',
            'notes'          => 'nullable|string',
            'payment_scheme' => 'required|numeric',
            'total_amount'   => 'required|numeric',
            'dp_amount'      => 'required|numeric',
            'items'          => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            // === BOOKING CALENDAR VALIDATION ===
            $eventDateOnly = \Carbon\Carbon::parse($data['event_date'])->format('Y-m-d');
            $minAdvanceDays = (int) BookingSetting::getValue('min_advance_days', 3);
            $maxPerDay = (int) BookingSetting::getValue('max_orders_per_day', 100);

            // Check: tanggal terlalu mendadak
            $earliestAllowed = \Carbon\Carbon::today()->addDays($minAdvanceDays);
            if (\Carbon\Carbon::parse($eventDateOnly)->lt($earliestAllowed)) {
                return response()->json([
                    'success' => false,
                    'message' => "Pemesanan minimal {$minAdvanceDays} hari sebelum acara. Tanggal paling awal yang tersedia: " . $earliestAllowed->format('d M Y'),
                ], 422);
            }

            // Check: tanggal diblokir
            $isBlocked = BlockedDate::where('date', $eventDateOnly)->first();
            if ($isBlocked) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal ' . \Carbon\Carbon::parse($eventDateOnly)->format('d M Y') . ' tidak tersedia: ' . ($isBlocked->reason ?? 'Diblokir oleh admin'),
                ], 422);
            }

            // Hitung total porsi yang diminta
            $requestedPortions = 0;
            foreach ($data['items'] as $item) {
                $requestedPortions += $item['qty'];
            }

            // Check: kapasitas penuh
            $existingPortions = \App\Models\OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereIn('orders.status', ['pending', 'paid', 'completed'])
                ->whereDate('orders.event_date', $eventDateOnly)
                ->sum('order_items.qty');
                
            if ($existingPortions + $requestedPortions > $maxPerDay) {
                $sisa = max(0, $maxPerDay - $existingPortions);
                return response()->json([
                    'success' => false,
                    'message' => 'Kuota untuk tanggal ' . \Carbon\Carbon::parse($eventDateOnly)->format('d M Y') . ' sisa ' . $sisa . ' porsi (Batas ' . $maxPerDay . ' porsi/hari). Pesanan Anda: ' . $requestedPortions . ' porsi.',
                ], 422);
            }
            // === END BOOKING CALENDAR VALIDATION ===

            $schemeLabel = "DP 30%";
            if ($data['payment_scheme'] == 0.5) $schemeLabel = "DP 50%";
            if ($data['payment_scheme'] == 1)   $schemeLabel = "Lunas (100%)";

            $order = Order::create([
                'customer_name'  => $data['customer_name'],
                'customer_wa'    => $data['customer_wa'],
                'event_date'     => $data['event_date'],
                'address'        => $data['address'],
                'notes'          => $data['notes'] ?? '',
                'payment_scheme' => $schemeLabel,
                'total_amount'   => $data['total_amount'],
                'dp_amount'      => $data['dp_amount'],
                'status'         => 'pending',
            ]);

            $item_details = [];
            foreach ($data['items'] as $item) {
                OrderItem::create([
                    'order_id'  => $order->id,
                    'menu_id'   => $item['id'],
                    'menu_name' => $item['name'],
                    'qty'       => $item['qty'],
                    'price'     => $item['price'],
                    'subtotal'  => $item['price'] * $item['qty'],
                ]);

                $item_details[] = [
                    'id'       => $item['id'],
                    'price'    => $item['price'],
                    'quantity' => $item['qty'],
                    'name'     => mb_strimwidth($item['name'], 0, 47, '...'),
                ];
            }

            // Jika hanya DP, kirim 1 item DP ke Midtrans agar gross_amount cocok
            if ($data['dp_amount'] < $data['total_amount']) {
                $item_details = [[
                    'id'       => 'DP-' . $order->id,
                    'price'    => $data['dp_amount'],
                    'quantity' => 1,
                    'name'     => 'Deposit: ' . $schemeLabel,
                ]];
            }

            $params = [
                'transaction_details' => [
                    'order_id'     => 'ORD-' . $order->id . '-' . time(),
                    'gross_amount' => $data['dp_amount'],
                ],
                'customer_details' => [
                    'first_name'      => $data['customer_name'],
                    'phone'           => $data['customer_wa'],
                    'shipping_address' => [
                        'first_name' => $data['customer_name'],
                        'phone'      => $data['customer_wa'],
                        'address'    => $data['address'],
                    ],
                ],
                'item_details' => $item_details,
            ];

            $snapToken = Snap::getSnapToken($params);
            
            $order->snap_token = $snapToken;
            $order->save();

            DB::commit();

            return response()->json([
                'success'    => true,
                'order_id'   => $order->id,
                'snap_token' => $snapToken,
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * FIX #2: Sinkronkan status Midtrans → status order yang lengkap.
     * Midtrans status: capture / settlement → 'paid'
     *                  pending            → 'pending'
     *                  deny/expire/cancel → 'cancelled'
     */
    public function paymentNotification(Request $request) {
        $notif = new Notification();

        $transaction        = $notif->transaction_status;
        $type               = $notif->payment_type;
        $order_id_midtrans  = $notif->order_id; // format: ORD-{id}-{timestamp}
        $fraud              = $notif->fraud_status;

        // Ekstrak real order ID
        $parts        = explode('-', $order_id_midtrans);
        $real_order_id = $parts[1] ?? 0;

        $order = Order::find($real_order_id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($transaction == 'capture' || $transaction == 'settlement') {
            if ($type == 'credit_card' && $fraud == 'challenge') {
                $order->status = 'pending';
            } else {
                $order->status       = 'paid';
                $order->payment_type = $type;
            }
        } elseif ($transaction == 'pending') {
            $order->status = 'pending';
        } elseif (in_array($transaction, ['deny', 'expire', 'cancel'])) {
            $order->status = 'cancelled';
        }

        $order->save();

        return response()->json(['status' => 'OK']);
    }
}
