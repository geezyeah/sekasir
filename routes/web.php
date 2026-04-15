<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;

// Language Switching
Route::middleware(['auth'])->get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
        \Log::info('Language switched', ['locale' => $locale, 'session_locale' => session('locale')]);
    }
    return redirect()->back()->with('success', 'Language changed to ' . $locale);
})->name('language.switch');

// Debug: Test session
Route::middleware(['auth'])->get('/test-session', function () {
    $locale = session('locale');
    return response()->json([
        'session_locale' => $locale,
        'app_locale' => app()->getLocale(),
        'session_all' => session()->all(),
    ]);
});

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Shift Management
    Route::get('/shifts/select', [ShiftController::class, 'select'])->name('shifts.select');
    Route::post('/shifts/start', [ShiftController::class, 'start'])->middleware(['prevent-duplicate-shift'])->name('shifts.start');
    Route::post('/shifts/end', [ShiftController::class, 'end'])->name('shifts.end');
    Route::get('/shifts/history', [ShiftController::class, 'history'])->name('shifts.history');

    // POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/shift-report', [PosController::class, 'shiftReport'])->name('pos.shift-report');
    Route::post('/pos/cart/add', [PosController::class, 'addToCart'])->name('pos.cart.add');
    Route::post('/pos/cart/update', [PosController::class, 'updateCart'])->name('pos.cart.update');
    Route::post('/pos/cart/remove', [PosController::class, 'removeFromCart'])->name('pos.cart.remove');
    Route::post('/pos/cart/clear', [PosController::class, 'clearCart'])->name('pos.cart.clear');
    Route::get('/pos/cart', [PosController::class, 'getCart'])->name('pos.cart.get');

    // Orders
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // API: Shift Orders
    Route::get('/api/shifts/{shift}/orders', function (\App\Models\Shift $shift) {
        $orders = $shift->user->orders()
            ->where('shift_id', $shift->id)
            ->with(['items.product', 'shop'])
            ->get()
            ->map(function ($order) {
                return [
                    'id' => (int) $order->id,
                    'total_amount' => (int) $order->total_amount,
                    'payment_type' => $order->payment_type,
                    'created_at' => $order->created_at->format('d M Y H:i'),
                    'items' => $order->items->map(function ($item) {
                        return [
                            'id' => (int) $item->id,
                            'product_name' => $item->product?->name ?? '[Product Removed]',
                            'quantity' => (int) $item->quantity,
                            'subtotal' => (int) ($item->quantity * ($item->product?->price ?? $item->price)),
                        ];
                    })->toArray(),
                ];
            });

        return response()->json(['orders' => $orders]);
    });

    // Dashboard redirect based on role
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('shifts.select');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/employees', [AdminController::class, 'employees'])->name('employees');
    Route::get('/employees/create', [AdminController::class, 'createEmployee'])->name('employees.create');
    Route::post('/employees', [AdminController::class, 'storeEmployee'])->name('employees.store');
    Route::get('/employees/{user}/shops', [AdminController::class, 'editEmployeeShops'])->name('employees.shops');
    Route::patch('/employees/{user}/shops', [AdminController::class, 'updateEmployeeShops'])->name('employees.shops.update');
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::patch('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete');
    Route::patch('/products/{product}/toggle', [AdminController::class, 'toggleProduct'])->name('products.toggle');
    Route::get('/product-types', [AdminController::class, 'productTypes'])->name('product-types');
    Route::post('/product-types', [AdminController::class, 'storeProductType'])->name('product-types.store');
    Route::patch('/product-types/{productType}', [AdminController::class, 'updateProductType'])->name('product-types.update');
    Route::delete('/product-types/{productType}', [AdminController::class, 'deleteProductType'])->name('product-types.delete');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/shifts', [AdminController::class, 'shifts'])->name('shifts');
    Route::post('/shifts/{shift}/end', [AdminController::class, 'forceEndShift'])->name('shifts.force-end');
    Route::get('/shifts-summary', [AdminController::class, 'shiftsSummary'])->name('shifts.summary');
    Route::get('/shops', [AdminController::class, 'shops'])->name('shops');
    Route::get('/shops/{shop}/edit', [AdminController::class, 'editShop'])->name('shops.edit');
    Route::patch('/shops/{shop}', [AdminController::class, 'updateShop'])->name('shops.update');
    Route::get('/shops/{shop}/preview', [AdminController::class, 'previewShopStorefront'])->name('shops.preview');
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports');
});

require __DIR__.'/auth.php';
