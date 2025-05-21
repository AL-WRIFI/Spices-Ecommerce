<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Enums\Order\OrderStatusEnum;
use Illuminate\Support\Facades\DB;

class EcommerceDashboard extends Controller
{

  public function index()
  {
     $currentMonthSales = Order::where('status', OrderStatusEnum::DELIVERED->value)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        // --- Statistics ---
        $totalSalesAmount = Order::where('status', OrderStatusEnum::DELIVERED->value)->sum('total_amount'); // Overall revenue
        $totalOrdersCount = Order::count(); // Total number of orders (any status)
        $totalCustomers = User::count(); // Assuming all users are customers
        $totalProducts = Product::count();

        // --- Profit last month ---
        $profitLastMonth = Order::where('status', OrderStatusEnum::DELIVERED->value)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('total_amount');

        $profitTwoMonthsAgo = Order::where('status', OrderStatusEnum::DELIVERED->value)
            ->whereMonth('created_at', Carbon::now()->subMonths(2)->month)
            ->whereYear('created_at', Carbon::now()->subMonths(2)->year)
            ->sum('total_amount');

        $profitPercentageChange = 0;
        if ($profitTwoMonthsAgo > 0) {
            $profitPercentageChange = (($profitLastMonth - $profitTwoMonthsAgo) / $profitTwoMonthsAgo) * 100;
        } elseif ($profitLastMonth > 0) {
            $profitPercentageChange = 100; // Infinite growth if previous was 0
        }

        // --- Expenses ---
        // The model doesn't have expenses. Let's simulate or use a placeholder.
        // For a real app, you'd fetch this from an expenses table or calculation.
        $expensesLastMonth = $profitLastMonth * 0.2; // Assuming expenses are 20% of profit/revenue for demo
        $expensesMoreThanLastMonth = $expensesLastMonth * 0.1; // Placeholder

        // --- Generated Leads ---
        $leadsThisMonth = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $leadsLastMonth = User::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        $leadsPercentageChange = 0;
        if ($leadsLastMonth > 0) {
            $leadsPercentageChange = (($leadsThisMonth - $leadsLastMonth) / $leadsLastMonth) * 100;
        } elseif ($leadsThisMonth > 0) {
            $leadsPercentageChange = 100;
        }

        // --- Revenue Report ---
        // Data for #totalRevenueChart would usually be fetched by its JS.
        // For the display values:
        $currentYearRevenue = Order::where('status', OrderStatusEnum::DELIVERED->value)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');
        $budgetForYear = 56800; // Placeholder or from config

        // --- Earning Reports ---
        // These are weekly. Let's simulate for current week.
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $weeklyNetProfit = Order::where('status', OrderStatusEnum::DELIVERED->value)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum('total_amount'); // Simplified: net profit = total amount
        $weeklySalesCount = Order::where('status', OrderStatusEnum::DELIVERED->value)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->count();
        // Placeholder percentages
        $weeklyNetProfitIncrease = 18.6;
        $weeklyTotalIncomeIncrease = 39.6;
        $weeklyExpensesIncrease = 52.8; // Assuming this implies expenses reduced, or it's a positive change in some metric.

        // --- Popular Products ---
        // Products with the most units sold from delivered orders.
        $popularProducts = Product::select('products.id', 'products.name', 'products.image', 'products.price', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->groupBy('products.id', 'products.name', 'products.image', 'products.price') // MySQL needs all selected non-aggregated columns
            ->orderByDesc('total_sold')
            ->take(6) // As per your template
            ->get();

        // --- Orders by Countries (Tabs) ---
        // The template uses "sender" and "receiver". For an e-commerce order:
        // Sender is typically the store/warehouse (could be a fixed address or dynamic if multi-warehouse).
        // Receiver is the customer.
        // For simplicity, we'll focus on receiver details (customer) from the order.
        // The "shipping_address" in Order model or "address" in User model can be used.
        // The model has `user_id` and `shipping_address`. We'll use `order->user` for name and `order->shipping_address` for address.

        $newOrders = Order::where('status', OrderStatusEnum::PENDING->value)
            ->with('user') // Eager load user
            ->latest()
            ->take(2) // As per template structure
            ->get();

        $preparingOrders = Order::where('status', OrderStatusEnum::PROCESSING->value)
            ->with('user')
            ->latest()
            ->take(2)
            ->get();

        $shippingOrders = Order::where('status', OrderStatusEnum::SHIPPED->value)
            ->with('user')
            ->latest()
            ->take(2)
            ->get();

        $deliveriesInProgressCount = Order::whereIn('status', [
            OrderStatusEnum::PROCESSING->value,
            OrderStatusEnum::SHIPPED->value,
            OrderStatusEnum::ON_WAY->value
        ])->count();


        // Data for charts (This is just sample data structure, your JS will handle the actual rendering)
        // You would typically pass more structured data for ApexCharts.
        // Example: $profitLastMonthChartData = ['series' => [...], 'categories' => [...]];
        // For now, we are mainly focusing on the visible text data.

        $viewData = [
            'greetingName' => auth()->user() ? auth()->user()->name : 'Guest', // For "Congratulations John!"
            'currentMonthSales' => $currentMonthSales,

            'totalSalesAmount' => $totalSalesAmount,
            'totalOrdersCount' => $totalOrdersCount,
            'totalCustomers' => $totalCustomers,
            'totalProducts' => $totalProducts,

            'profitLastMonth' => $profitLastMonth,
            'profitPercentageChange' => $profitPercentageChange,

            'expensesLastMonth' => $expensesLastMonth, // Simulated
            'expensesMoreThanLastMonth' => $expensesMoreThanLastMonth, // Simulated

            'leadsThisMonth' => $leadsThisMonth,
            'leadsPercentageChange' => $leadsPercentageChange,

            'currentYearRevenue' => $currentYearRevenue,
            'budgetForYear' => $budgetForYear, // Placeholder

            'weeklyNetProfit' => $weeklyNetProfit,
            'weeklySalesCount' => $weeklySalesCount,
            'weeklyNetProfitIncrease' => $weeklyNetProfitIncrease,
            'weeklyTotalIncome' => $weeklyNetProfit * 1.2, // Simulated Total Income
            'weeklyTotalIncomeIncrease' => $weeklyTotalIncomeIncrease,
            'weeklyTotalExpenses' => $weeklyNetProfit * 0.15, // Simulated Total Expenses
            'weeklyExpensesIncrease' => $weeklyExpensesIncrease,

            'popularProducts' => $popularProducts,

            'newOrders' => $newOrders,
            'preparingOrders' => $preparingOrders,
            'shippingOrders' => $shippingOrders,
            'deliveriesInProgressCount' => $deliveriesInProgressCount,
        ];

        // dd($viewData); // For debugging

        return view('content.apps.app-ecommerce-dashboard', $viewData); 
  }
}
