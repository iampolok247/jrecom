<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 100% Real Database Queries with all status categories included
        $totalRevenue = (float) Order::where('payment_status', 'paid')->sum('total_amount');
        $totalOrders = Order::count();
        
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $processingOrders = Order::whereIn('order_status', ['confirmed', 'processing', 'packing', 'shipped'])->count();
        $completedOrders = Order::whereIn('order_status', ['delivered', 'completed'])->count();
        $cancelledOrders = Order::whereIn('order_status', ['cancelled', 'returned', 'refunded'])->count();

        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();

        $lowStockProducts = Product::where('stock', '<=', 5)->orderBy('stock', 'asc')->get();
        $latestOrders = Order::with('user')->latest()->take(6)->get();
        $latestCustomers = User::where('role', 'customer')->latest()->take(5)->get();

        // 6-Month Timeline Data directly from Database
        $chartMonths = [];
        $chartRevenue = [];
        $chartOrders = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('M Y');
            $chartMonths[] = $monthName;

            $rev = Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('payment_status', 'paid')
                ->sum('total_amount');

            $ordCount = Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $chartRevenue[] = (float) $rev;
            $chartOrders[] = (int) $ordCount;
        }

        // Real Month-over-Month Revenue Growth Percentage
        $currentMonthRevenue = $chartRevenue[5] ?? 0;
        $prevMonthRevenue = $chartRevenue[4] ?? 0;
        if ($prevMonthRevenue > 0) {
            $revenueGrowthPercent = round((($currentMonthRevenue - $prevMonthRevenue) / $prevMonthRevenue) * 100, 1);
        } else if ($currentMonthRevenue > 0) {
            $revenueGrowthPercent = 100;
        } else {
            $revenueGrowthPercent = 0;
        }

        // Real Order Status Breakdown from Database (Includes ALL 38 orders)
        $orderStatusStats = [
            'Pending' => (int) $pendingOrders,
            'Processing' => (int) $processingOrders,
            'Completed' => (int) $completedOrders,
            'Cancelled' => (int) $cancelledOrders,
        ];

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'cancelledOrders',
            'totalCustomers',
            'totalProducts',
            'lowStockProducts',
            'latestOrders',
            'latestCustomers',
            'chartMonths',
            'chartRevenue',
            'chartOrders',
            'revenueGrowthPercent',
            'orderStatusStats'
        ));
    }
}
