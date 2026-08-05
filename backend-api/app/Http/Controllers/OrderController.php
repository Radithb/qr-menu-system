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
                'status' => 'Menunggu Diproses',
                'payment_status' => 'Unpaid',
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
            event(new NewOrderCreated($order->load('items.menu')));

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

    public function index($outlet_id)
    {
        $orders = Order::with('items.menu')
            ->where('outlet_id', $outlet_id)
            ->where('status', '!=', 'Selesai')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    public function history($outlet_id)
    {
        $orders = Order::with('items.menu')
            ->where('outlet_id', $outlet_id)
            ->where('status', 'Selesai')
            ->orderBy('updated_at', 'desc')
            ->limit(100)
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $orders
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
