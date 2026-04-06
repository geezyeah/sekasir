<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function select()
    {
        $user = Auth::user();
        $activeShift = $user->activeShift;

        if ($activeShift) {
            return redirect()->route('pos.index');
        }

        // Admins see all shops, employees see only authorized shops
        if ($user->isAdmin()) {
            $shops = Shop::all();
        } else {
            $shops = $user->authorizedShops;
        }

        return view('shifts.select', compact('shops'));
    }

    public function start(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
        ]);

        $user = Auth::user();

        // Check if user already has an active shift
        if ($user->activeShift) {
            return redirect()->route('pos.index');
        }

        Shift::create([
            'user_id' => $user->id,
            'shop_id' => $request->shop_id,
            'login_time' => now(),
            'status' => 'active',
        ]);

        return redirect()->route('pos.index');
    }

    public function end()
    {
        $user = Auth::user();
        $shift = $user->activeShift;

        if ($shift) {
            $shift->update([
                'logout_time' => now(),
                'status' => 'inactive',
            ]);

            // Clear cart from session and database
            session(['pos.cart' => []]);
            $user->carts()->delete();
        }

        return redirect()->route('shifts.select');
    }

    public function history()
    {
        $user = Auth::user();
        $shifts = Shift::query()
            ->select(['id', 'user_id', 'shop_id', 'login_time', 'logout_time', 'status', 'created_at', 'updated_at'])
            ->where('user_id', $user->id)
            ->with([
                'shop:id,name,properties',
                'user:id,name',
                'orders:id,shift_id,total_amount,created_at'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $isAdmin = $user->isAdmin();
        $activeShop = $user->activeShift?->shop;

        return view('shifts.history', compact('shifts', 'isAdmin', 'activeShop'));
    }
}
