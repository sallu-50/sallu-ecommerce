<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function chart()
    {
        // Get the sales data (total sales per date)
        $salesData = Order::selectRaw('DATE(created_at) as date, SUM(total) as total_sales')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Calculate total sales and total number of orders
        $totalSales = Order::sum('total');
        $totalOrders = Order::count();

        // return compact('salesData', 'totalSales', 'totalOrders');
    }
}
