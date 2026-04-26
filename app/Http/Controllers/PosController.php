<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PosController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $shift = $user->activeShift;

        if (!$shift) {
            return redirect()->route('shifts.select');
        }

        $shop = $shift->shop;
        $products = Product::with('productType')
            ->where('shop_id', $shop->id)
            ->where('is_active', true)
            ->get();

        $cartItems = $this->getSessionCart();
        $cartTotal = collect($cartItems)->sum('subtotal');

        return view('pos.index', compact('shop', 'products', 'cartItems', 'cartTotal', 'shift'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::with('productType')->findOrFail($request->product_id);
        $user = Auth::user();
        $cart = $this->getSessionCart();
        $productTypeName = strtoupper($product->productType?->name ?? 'UNKNOWN');

        $productIndex = array_search($product->id, array_column($cart, 'product_id'));

        if ($productIndex !== false) {
            $cart[$productIndex]['quantity'] += $request->quantity;
            $cart[$productIndex]['subtotal'] = $cart[$productIndex]['quantity'] * $product->price;
            
            // Update database cart
            Cart::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->update([
                    'quantity' => $cart[$productIndex]['quantity'],
                    'subtotal' => $cart[$productIndex]['subtotal'],
                ]);
        } else {
            $cart[] = [
                'id' => uniqid('cart_'),
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_type' => $productTypeName,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'subtotal' => $request->quantity * $product->price,
            ];
            
            // Add to database cart
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'subtotal' => $request->quantity * $product->price,
            ]);
        }

        session(['pos.cart' => $cart]);

        if ($request->ajax()) {
            return $this->cartData();
        }

        return redirect()->route('pos.index');
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|string',
            'quantity' => 'required|integer|min:0',
        ]);

        $user = Auth::user();
        $cart = $this->getSessionCart();
        $cartIndex = array_search($request->cart_id, array_column($cart, 'id'));

        if ($cartIndex === false) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        if ($request->quantity == 0) {
            $productId = $cart[$cartIndex]['product_id'];
            unset($cart[$cartIndex]);
            $cart = array_values($cart);
            
            // Remove from database cart
            Cart::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->delete();
        } else {
            $cart[$cartIndex]['quantity'] = $request->quantity;
            $cart[$cartIndex]['subtotal'] = $request->quantity * $cart[$cartIndex]['price'];
            
            // Update database cart
            Cart::where('user_id', $user->id)
                ->where('product_id', $cart[$cartIndex]['product_id'])
                ->update([
                    'quantity' => $request->quantity,
                    'subtotal' => $cart[$cartIndex]['subtotal'],
                ]);
        }

        session(['pos.cart' => $cart]);

        if ($request->ajax()) {
            return $this->cartData();
        }

        return redirect()->route('pos.index');
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|string',
        ]);

        $user = Auth::user();
        $cart = $this->getSessionCart();
        $cartIndex = array_search($request->cart_id, array_column($cart, 'id'));

        if ($cartIndex !== false) {
            $productId = $cart[$cartIndex]['product_id'];
            unset($cart[$cartIndex]);
            $cart = array_values($cart);
            
            // Remove from database cart
            Cart::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->delete();
        }

        session(['pos.cart' => $cart]);

        if ($request->ajax()) {
            return $this->cartData();
        }

        return redirect()->route('pos.index');
    }

    public function clearCart()
    {
        $user = Auth::user();
        session(['pos.cart' => []]);
        
        // Clear database cart
        Cart::where('user_id', $user->id)->delete();

        if (request()->ajax()) {
            return $this->cartData();
        }

        return redirect()->route('pos.index');
    }

    private function cartData()
    {
        $cart = $this->getSessionCart();
        $total = collect($cart)->sum('subtotal');

        return response()->json([
            'items' => $cart,
            'total' => $total,
            'count' => collect($cart)->sum('quantity'),
        ]);
    }

    private function getSessionCart()
    {
        return session('pos.cart', []);
    }

    public function getCart()
    {
        return $this->cartData();
    }

    public function shiftReport()
    {
        $user = Auth::user();
        $shift = $user->activeShift;

        if (!$shift) {
            return redirect()->route('shifts.select');
        }

        $shop = $shift->shop;
        $shiftOrders = $shift->orders()->with('items.product.productType')->orderBy('created_at', 'desc')->get();
        $shiftTotalAmount = $shiftOrders->sum('total_amount');
        $totalItems = $shiftOrders->sum(function ($order) {
            return $order->items->sum('quantity');
        });

        // Organize product details by payment type
        $qrisProductDetails = $this->getProductDetailsByPayment($shiftOrders, 'QRIS');
        $cashProductDetails = $this->getProductDetailsByPayment($shiftOrders, 'CASH');

        return view('pos.shift-report', compact('shift', 'shop', 'shiftOrders', 'shiftTotalAmount', 'totalItems', 'qrisProductDetails', 'cashProductDetails'));
    }

    private function getProductDetailsByPayment($orders, $paymentType)
    {
        $filteredOrders = $orders->where('payment_type', $paymentType);
        $productDetails = collect();

        foreach ($filteredOrders as $order) {
            foreach ($order->items as $item) {
                $productName = $item->product?->name ?? '[Product Removed]';
                // Get product type from ProductType relationship
                $productType = strtoupper($item->product?->productType?->name ?? 'UNKNOWN');
                $key = "{$productName} - {$productType}";

                if ($productDetails->has($key)) {
                    $productDetails[$key] = [
                        'product_name' => $productName,
                        'product_type' => $productType,
                        'quantity' => $productDetails[$key]['quantity'] + $item->quantity,
                        'total' => $productDetails[$key]['total'] + ($item->price * $item->quantity),
                    ];
                } else {
                    $productDetails[$key] = [
                        'product_name' => $productName,
                        'product_type' => $productType,
                        'quantity' => $item->quantity,
                        'total' => $item->price * $item->quantity,
                    ];
                }
            }
        }

        return $productDetails->sortByDesc('quantity');
    }
}
