<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function chart()
    {
        // Example data for the sales chart (can be dynamic based on your database)
        $salesData = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total_sales')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Passing the data to the view
        return view('admin.dashboard', compact('salesData'));
    }
}
