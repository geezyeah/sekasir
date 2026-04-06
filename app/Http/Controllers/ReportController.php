<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $shopId = $request->input('shop_id');

        // Revenue Overview
        $revenueStats = $this->getRevenueStats($startDate, $endDate, $shopId);
        
        // Daily Revenue Chart Data
        $dailyRevenue = $this->getDailyRevenue($startDate, $endDate, $shopId);
        
        // Revenue by Payment Type
        $paymentBreakdown = $this->getPaymentBreakdown($startDate, $endDate, $shopId);
        
        // Revenue by Shop
        $revenueByShop = $this->getRevenueByShop($startDate, $endDate);
        
        // Top Products
        $topProducts = $this->getTopProducts($startDate, $endDate, $shopId);
        
        // Employee Performance
        $employeePerformance = $this->getEmployeePerformance($startDate, $endDate, $shopId);
        
        // Order Metrics
        $orderMetrics = $this->getOrderMetrics($startDate, $endDate, $shopId);
        
        // Shift Analytics
        $shiftAnalytics = $this->getShiftAnalytics($startDate, $endDate, $shopId);
        
        // Shops for filter
        $shops = DB::table('shops')->orderBy('name')->get();
        
        return view('admin.reports.index', compact(
            'revenueStats',
            'dailyRevenue',
            'paymentBreakdown',
            'revenueByShop',
            'topProducts',
            'employeePerformance',
            'orderMetrics',
            'shiftAnalytics',
            'shops',
            'startDate',
            'endDate',
            'shopId'
        ));
    }

    /**
     * Revenue Statistics
     */
    private function getRevenueStats($startDate, $endDate, $shopId = null)
    {
        $query = DB::table('orders')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->when($shopId, function ($q) use ($shopId) {
                return $q->where('shop_id', $shopId);
            });

        return [
            'total_revenue' => $query->clone()->sum('total_amount'),
            'total_orders' => $query->clone()->count(),
            'average_order_value' => $query->clone()->avg('total_amount'),
            'total_items' => DB::table('order_items')
                ->whereIn('order_id', $query->clone()->select('id'))
                ->sum('quantity'),
        ];
    }

    /**
     * Daily Revenue for Chart
     */
    private function getDailyRevenue($startDate, $endDate, $shopId = null)
    {
        return DB::table('orders')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->when($shopId, function ($q) use ($shopId) {
                return $q->where('shop_id', $shopId);
            })
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
    }

    /**
     * Payment Method Breakdown
     */
    private function getPaymentBreakdown($startDate, $endDate, $shopId = null)
    {
        return DB::table('orders')
            ->select(
                'payment_type',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('AVG(total_amount) as avg_value')
            )
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->when($shopId, function ($q) use ($shopId) {
                return $q->where('shop_id', $shopId);
            })
            ->groupBy('payment_type')
            ->get();
    }

    /**
     * Revenue by Shop
     */
    private function getRevenueByShop($startDate, $endDate)
    {
        return DB::table('orders')
            ->join('shops', 'orders.shop_id', '=', 'shops.id')
            ->select(
                'shops.id',
                'shops.name',
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(orders.total_amount) as revenue'),
                DB::raw('AVG(orders.total_amount) as avg_order_value')
            )
            ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('shops.id', 'shops.name')
            ->orderBy('revenue', 'desc')
            ->get();
    }

    /**
     * Top Performing Products
     */
    private function getTopProducts($startDate, $endDate, $shopId = null)
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
                DB::raw('AVG(order_items.price) as avg_price'),
                DB::raw('COUNT(DISTINCT order_items.order_id) as order_count')
            )
            ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->when($shopId, function ($q) use ($shopId) {
                return $q->where('orders.shop_id', $shopId);
            })
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Employee Performance Metrics
     */
    private function getEmployeePerformance($startDate, $endDate, $shopId = null)
    {
        return DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(orders.total_amount) as total_revenue'),
                DB::raw('AVG(orders.total_amount) as avg_order_value'),
                DB::raw('MAX(orders.created_at) as last_order_date')
            )
            ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->when($shopId, function ($q) use ($shopId) {
                return $q->where('orders.shop_id', $shopId);
            })
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_revenue', 'desc')
            ->get();
    }

    /**
     * Order Metrics
     */
    private function getOrderMetrics($startDate, $endDate, $shopId = null)
    {
        $allOrders = DB::table('orders')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->when($shopId, function ($q) use ($shopId) {
                return $q->where('shop_id', $shopId);
            });

        $totalOrders = $allOrders->clone()->count();
        $avgOrderValue = $allOrders->clone()->avg('total_amount');
        
        // Calculate peak hours (if applicable)
        $peakHour = DB::table('orders')
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->when($shopId, function ($q) use ($shopId) {
                return $q->where('shop_id', $shopId);
            })
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('count', 'desc')
            ->first();

        return [
            'total_orders' => $totalOrders,
            'avg_order_value' => round($avgOrderValue, 2),
            'peak_hour' => $peakHour->hour ?? null,
            'peak_hour_count' => $peakHour->count ?? 0,
        ];
    }

    /**
     * Shift Analytics
     */
    private function getShiftAnalytics($startDate, $endDate, $shopId = null)
    {
        $shifts = DB::table('shifts')
            ->join('users', 'shifts.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                DB::raw('COUNT(*) as shift_count'),
                DB::raw('SEC_TO_TIME(AVG(TIMESTAMPDIFF(SECOND, shifts.login_time, shifts.logout_time))) as avg_duration'),
                DB::raw('MIN(shifts.login_time) as first_login'),
                DB::raw('MAX(shifts.logout_time) as last_logout')
            )
            ->whereBetween('shifts.login_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->when($shopId, function ($q) use ($shopId) {
                return $q->where('shifts.shop_id', $shopId);
            })
            ->groupBy('users.id', 'users.name')
            ->orderBy('shift_count', 'desc')
            ->get();

        return $shifts;
    }
}
