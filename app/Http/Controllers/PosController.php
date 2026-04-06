<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $products = Product::where('shop_id', $shop->id)
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

        $product = Product::findOrFail($request->product_id);
        $cart = $this->getSessionCart();

        $productIndex = array_search($product->id, array_column($cart, 'product_id'));

        if ($productIndex !== false) {
            $cart[$productIndex]['quantity'] += $request->quantity;
            $cart[$productIndex]['subtotal'] = $cart[$productIndex]['quantity'] * $product->price;
        } else {
            $cart[] = [
                'id' => uniqid('cart_'),
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_type' => $product->type,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'subtotal' => $request->quantity * $product->price,
            ];
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

        $cart = $this->getSessionCart();
        $cartIndex = array_search($request->cart_id, array_column($cart, 'id'));

        if ($cartIndex === false) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        if ($request->quantity == 0) {
            unset($cart[$cartIndex]);
            $cart = array_values($cart);
        } else {
            $cart[$cartIndex]['quantity'] = $request->quantity;
            $cart[$cartIndex]['subtotal'] = $request->quantity * $cart[$cartIndex]['price'];
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

        $cart = $this->getSessionCart();
        $cartIndex = array_search($request->cart_id, array_column($cart, 'id'));

        if ($cartIndex !== false) {
            unset($cart[$cartIndex]);
            $cart = array_values($cart);
        }

        session(['pos.cart' => $cart]);

        if ($request->ajax()) {
            return $this->cartData();
        }

        return redirect()->route('pos.index');
    }

    public function clearCart()
    {
        session(['pos.cart' => []]);

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
        $shiftOrders = $shift->orders()->with('items.product')->orderBy('created_at', 'desc')->limit(50)->get();
        $shiftTotalAmount = $shiftOrders->sum('total_amount');
        $totalItems = $shiftOrders->sum(function ($order) {
            return $order->items->sum('quantity');
        });

        return view('pos.shift-report', compact('shift', 'shop', 'shiftOrders', 'shiftTotalAmount', 'totalItems'));
    }
}
