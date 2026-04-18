<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'payment_type' => 'required|in:QRIS,CASH',
            'cash_received' => 'nullable|numeric|min:0',
            'idempotency_key' => 'nullable|string',
        ]);

        $user = Auth::user();
        $shift = $user->activeShift;

        if (!$shift) {
            return response()->json(['error' => 'No active shift'], 422);
        }

        $cartItems = session('pos.cart', []);

        // Fallback to database cart if session cart is empty
        if (empty($cartItems)) {
            $databaseCart = Cart::where('user_id', $user->id)->get();
            if ($databaseCart->isNotEmpty()) {
                $cartItems = $databaseCart->map(function ($item) {
                    return [
                        'id' => 'cart_' . $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name ?? 'Product',
                        'product_type' => $item->product->type ?? null,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price ?? 0,
                        'subtotal' => $item->subtotal,
                    ];
                })->toArray();
                // Restore session cart from database
                session(['pos.cart' => $cartItems]);
            }
        }

        if (empty($cartItems)) {
            return response()->json(['error' => 'Cart is empty'], 422);
        }

        // Implement idempotency: check if we've already processed this request
        $idempotencyKey = $request->input('idempotency_key');
        if ($idempotencyKey) {
            $cacheKey = "order_submission_{$idempotencyKey}";
            
            // Check if we've already processed this key
            if (Cache::has($cacheKey)) {
                $existingOrder = Cache::get($cacheKey);
                return response()->json([
                    'success' => true,
                    'order' => Order::with('items.product')->find($existingOrder['id']),
                    'message' => 'Order placed successfully! (Duplicate request prevented)',
                    'duplicate' => true,
                ]);
            }
        }

        $totalAmount = collect($cartItems)->sum('subtotal');

        if ($request->payment_type === 'CASH') {
            if (!$request->cash_received || $request->cash_received < $totalAmount) {
                return response()->json(['error' => 'Insufficient cash amount'], 422);
            }
        }

        $order = DB::transaction(function () use ($user, $shift, $cartItems, $totalAmount, $request) {
            $changeAmount = null;
            $cashReceived = null;

            if ($request->payment_type === 'CASH') {
                $cashReceived = $request->cash_received;
                $changeAmount = $cashReceived - $totalAmount;
            }

            $order = Order::create([
                'user_id' => $user->id,
                'shop_id' => $shift->shop_id,
                'shift_id' => $shift->id,
                'total_amount' => $totalAmount,
                'payment_type' => $request->payment_type,
                'cash_received' => $cashReceived,
                'change_amount' => $changeAmount,
            ]);

            // Generate formatted_id
            $date = $order->created_at->format('Ymd');
            $randomChars = strtoupper(substr(md5($order->id . 'order'), 0, 5));
            $orderNumber = str_pad($order->id % 10000, 4, '0', STR_PAD_LEFT);
            $formattedId = $date . $randomChars . $orderNumber;
            $order->update(['formatted_id' => $formattedId]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            // Clear cart from session and database
            session(['pos.cart' => []]);
            Cart::where('user_id', $user->id)->delete();

            return $order;
        });

        // Cache the order result for 24 hours to prevent duplicate submissions
        if ($idempotencyKey) {
            $cacheKey = "order_submission_{$idempotencyKey}";
            Cache::put($cacheKey, ['id' => $order->id], now()->addHours(24));
        }

        $order->load('items.product');

        return response()->json([
            'success' => true,
            'order' => $order,
            'message' => 'Order placed successfully!',
        ]);
    }

    public function history()
    {
        $user = Auth::user();
        $shift = $user->activeShift;

        if (!$shift) {
            return response()->json(['orders' => []]);
        }

        $orders = Order::where('shift_id', $shift->id)
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['orders' => $orders]);
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user', 'shop');
        
        if (request()->ajax()) {
            return response()->json(['order' => $order]);
        }
        
        return view('orders.show', compact('order'));
    }
}
