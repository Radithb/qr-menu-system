<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Events\NewOrderCreated;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'nomor_meja' => 'required|string',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|string|email|max:255',
            'payment_method' => 'nullable|string|max:100',
            'items' => 'required|array',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.subtotal' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            // Create Order
            $order = Order::create([
                'outlet_id' => $request->outlet_id,
                'nomor_meja' => $request->nomor_meja,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'status' => 'Menunggu Diproses',
                'payment_status' => 'Unpaid',
                'payment_method' => $request->payment_method ?? 'QRIS',
            ]);

            // Create Order Items
            $orderItems = [];
            foreach ($request->items as $item) {
                $orderItems[] = new OrderItem([
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                    'options' => isset($item['options']) ? $item['options'] : null,
                ]);
            }
            $order->items()->saveMany($orderItems);

            DB::commit();

            // Dispatch Event
            event(new NewOrderCreated($order->load(['items.menu', 'outlet'])));

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => $order
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Order Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index($outlet_id = null)
    {
        $query = Order::with(['items.menu', 'outlet'])
            ->where('status', '!=', 'Selesai')
            ->orderBy('created_at', 'desc');

        if ($outlet_id && $outlet_id !== 'all') {
            $query->where('outlet_id', $outlet_id);
        }

        $orders = $query->get();
            
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function history($outlet_id = null)
    {
        $query = Order::with(['items.menu', 'outlet'])
            ->where('status', 'Selesai')
            ->orderBy('updated_at', 'desc')
            ->limit(100);

        if ($outlet_id && $outlet_id !== 'all') {
            $query->where('outlet_id', $outlet_id);
        }

        $orders = $query->get();
            
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Riwayat Transaksi with filters (date_from, date_to, payment_method, search)
     */
    public function transactionHistory(Request $request)
    {
        $query = Order::with(['items.menu'])
            ->where('status', 'Selesai')
            ->orderBy('created_at', 'desc');

        // Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Payment method filter
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by customer name or order id
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhere('nomor_meja', 'like', "%{$search}%");
            });
        }

        $orders = $query->limit(500)->get();

        // Calculate summary stats
        $totalRevenue = $orders->sum(function($order) {
            return $order->items->sum('subtotal');
        });
        $totalOrders = $orders->count();
        $totalItems = $orders->sum(function($order) {
            return $order->items->sum('quantity');
        });

        return response()->json([
            'success' => true,
            'data' => $orders,
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'total_items' => $totalItems,
            ]
        ]);
    }

    public function markAsPaid($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
        }

        $order->status = 'Selesai';
        $order->payment_status = 'Paid';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil ditandai lunas',
            'data' => $order
        ]);
    }
}
