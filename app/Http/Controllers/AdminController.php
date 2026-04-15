<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Shift;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    public function dashboard()
    {
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::whereDate('created_at', today())->sum('total_amount');
        $activeShifts = Shift::where('status', 'active')->with(['user', 'shop'])->get();
        $totalEmployees = User::where('role', 'employee')->count();

        $shops = Shop::withCount([
            'orders' => fn($q) => $q->whereDate('created_at', today())
        ])->get();

        $recentOrders = Order::with(['user', 'shop', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Payment breakdown by shop
        $paymentBreakdownByShop = [];
        foreach ($shops as $shop) {
            $qrisTotal = Order::whereDate('created_at', today())
                ->where('shop_id', $shop->id)
                ->where('payment_type', 'QRIS')
                ->sum('total_amount');
            
            $cashTotal = Order::whereDate('created_at', today())
                ->where('shop_id', $shop->id)
                ->where('payment_type', 'CASH')
                ->sum('total_amount');
            
            $paymentBreakdownByShop[$shop->name] = [
                'QRIS' => $qrisTotal,
                'CASH' => $cashTotal,
                'total' => $qrisTotal + $cashTotal,
            ];
        }

        $paymentBreakdown = [
            'QRIS' => Order::whereDate('created_at', today())->where('payment_type', 'QRIS')->sum('total_amount'),
            'CASH' => Order::whereDate('created_at', today())->where('payment_type', 'CASH')->sum('total_amount'),
        ];

        return view('admin.dashboard', compact(
            'todayOrders', 'todayRevenue', 'activeShifts',
            'totalEmployees', 'shops', 'recentOrders', 'paymentBreakdown', 'paymentBreakdownByShop'
        ));
    }

    public function employees()
    {
        $employees = User::with('authorizedShops')
            ->withCount('shifts')
            ->orderBy('name')
            ->get();

        return view('admin.employees', compact('employees'));
    }

    public function createEmployee()
    {
        return view('admin.employee-form');
    }

    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,employee',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.employees')->with('success', 'Employee created successfully.');
    }

    public function products()
    {
        $products = Product::with('shop', 'productType')->orderBy('shop_id')->get();
        $shops = Shop::all();
        $productTypes = ProductType::all();
        return view('admin.products', compact('products', 'shops', 'productTypes'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'product_type_id' => 'nullable|exists:product_types,id',
            'is_seasonal' => 'boolean',
            'idempotency_key' => 'nullable|string',
        ]);

        // Check if we've already processed this idempotency key
        $idempotencyKey = $request->input('idempotency_key');
        if ($idempotencyKey) {
            $cacheKey = "product_submission_{$idempotencyKey}";
            
            if (Cache::has($cacheKey)) {
                // Already processed, redirect with success message
                return redirect()->route('admin.products')->with('success', 'Product created successfully! (Duplicate request prevented)');
            }
        }

        $product = Product::create($request->only(['shop_id', 'name', 'price', 'product_type_id', 'is_seasonal']));

        // Cache the submission result for 24 hours
        if ($idempotencyKey) {
            $cacheKey = "product_submission_{$idempotencyKey}";
            Cache::put($cacheKey, ['id' => $product->id], now()->addHours(24));
        }

        return redirect()->route('admin.products')->with('success', 'Product created successfully.');
    }

    public function editProduct(Product $product)
    {
        $shops = Shop::all();
        $productTypes = ProductType::all();
        return view('admin.product-edit', compact('product', 'shops', 'productTypes'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'product_type_id' => 'nullable|exists:product_types,id',
            'is_seasonal' => 'boolean',
        ]);

        $product->update($request->only(['shop_id', 'name', 'price', 'product_type_id', 'is_seasonal']));

        return redirect()->route('admin.products')->with('success', 'Product updated successfully.');
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully.');
    }

    public function toggleProduct(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return redirect()->route('admin.products')->with('success', 'Product status updated.');
    }

    // Product Types CRUD
    public function productTypes()
    {
        $productTypes = ProductType::withCount('products')->get();
        return view('admin.product-types', compact('productTypes'));
    }

    public function storeProductType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_types,name',
        ]);

        ProductType::create($request->only(['name']));

        return redirect()->route('admin.product-types')->with('success', 'Product type created successfully.');
    }

    public function updateProductType(Request $request, ProductType $productType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_types,name,' . $productType->id,
        ]);

        $productType->update($request->only(['name']));

        return redirect()->route('admin.product-types')->with('success', 'Product type updated successfully.');
    }

    public function deleteProductType(ProductType $productType)
    {
        // Only delete if no products use this type
        if ($productType->products()->count() > 0) {
            return redirect()->route('admin.product-types')->with('error', 'Cannot delete product type that has products.');
        }

        $productType->delete();
        return redirect()->route('admin.product-types')->with('success', 'Product type deleted successfully.');
    }

    public function orders(Request $request)
    {
        $query = Order::with(['user', 'shop', 'items.product']);

        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        $shops = Shop::all();

        return view('admin.orders', compact('orders', 'shops'));
    }

    public function shifts()
    {
        $shifts = Shift::with(['user', 'shop'])
            ->withCount('orders')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.shifts', compact('shifts'));
    }

    public function shiftsSummary()
    {
        // Get all shifts for statistics calculation
        $allShifts = Shift::with(['user', 'shop', 'orders'])
            ->withCount('orders')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $totalOrders = $allShifts->flatMap->orders->count();
        $totalRevenue = $allShifts->flatMap->orders->sum('total_amount');
        $activeShifts = $allShifts->where('status', 'active')->count();

        // Paginate for display
        $shifts = Shift::with(['user', 'shop', 'orders'])
            ->withCount('orders')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.shifts-summary', compact('shifts', 'totalOrders', 'totalRevenue', 'activeShifts'));
    }

    public function forceEndShift(Shift $shift)
    {
        try {
            // Check if shift is active
            if ($shift->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'This shift is not active.'
                ], 400);
            }

            // Update shift to ended
            $shift->update([
                'status' => 'inactive',
                'logout_time' => now(),
            ]);

            // Log the action
            \Log::info('Admin force ended shift', [
                'shift_id' => $shift->id,
                'employee_id' => $shift->user_id,
                'admin_id' => auth()->id(),
                'logout_time' => $shift->logout_time,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Shift ended successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error ending shift: ' . $e->getMessage()
            ], 500);
        }
    }

    public function shops()
    {
        $shops = Shop::all();
        return view('admin.shops', compact('shops'));
    }

    public function editShop(Shop $shop)
    {
        return view('admin.shop-form', compact('shop'));
    }

    public function updateShop(Request $request, Shop $shop)
    {
        $request->validate([
            'bg_color' => 'required|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'text_color' => 'required|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'primary_color' => 'required|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'logo_path' => 'nullable|string|max:255',
        ]);

        $properties = [
            'bg_color' => $request->bg_color,
            'text_color' => $request->text_color,
            'primary_color' => $request->primary_color,
            'logo_path' => $request->logo_path ?? $shop->getProperty('logo_path'),
        ];

        $shop->update(['properties' => $properties]);

        return redirect()->route('admin.shops')->with('success', 'Shop settings updated successfully.');
    }

    public function previewShopStorefront(Shop $shop)
    {
        $products = Product::where('shop_id', $shop->id)
            ->where('is_active', true)
            ->get();

        return view('pos.preview', compact('shop', 'products'));
    }

    public function editEmployeeShops(User $user)
    {
        $allShops = Shop::all();
        $authorizedShops = $user->authorizedShops->pluck('id')->toArray();

        return view('admin.employee-shops', compact('user', 'allShops', 'authorizedShops'));
    }

    public function updateEmployeeShops(Request $request, User $user)
    {
        $request->validate([
            'shops' => 'required|array',
            'shops.*' => 'exists:shops,id',
        ]);

        $user->authorizedShops()->sync($request->shops);

        return redirect()->route('admin.employees')->with('success', 'Shop authorizations updated successfully.');
    }
}
